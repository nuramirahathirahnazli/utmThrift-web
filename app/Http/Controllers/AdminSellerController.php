<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use App\Notifications\SellerApplicationStatusNotification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSellerController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'all');

        $counts = [
            'total' => Seller::count(), // Get total sellers from the sellers table
            'unverified' => Seller::where('verification_status', 'pending')->count(), // Get unverified sellers from the sellers table
        ];

        $allSellers = Seller::with('user')  
            ->latest()
            ->paginate(10)
            ->appends(['tab' => 'all']);

        $unverifiedSellers = Seller::with('user') 
            ->where('verification_status', 'pending')
            ->latest()
            ->paginate(10)
            ->appends(['tab' => 'unverified']);


        return view('users.admin.sellers.index', [
            'activeTab' => $activeTab,
            'allSellers' => $allSellers,
            'unverifiedSellers' => $unverifiedSellers,
            'totalSellers' => $counts['total'],
            'unverifiedCount' => $counts['unverified']
        ]);
    }

    public function show($id)
    {
        $seller = Seller::with('user')->findOrFail($id); 
        return view('users.admin.sellers.seller_details', compact('seller'));
    }


    public function edit($id) {
        $seller = Seller::with('user')->findOrFail($id);
        return view('users.admin.sellers.seller_edit', compact('seller'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'matric_number' => 'required',
            'contact_number' => 'required',
            'user_type' => 'required|in:Buyer,Seller',
            'verification_status' => 'required|in:pending,approved,rejected',
        ]);

        $seller = User::findOrFail($id);
        $seller->update($request->all());

        return redirect()->route('admin.sellers.index')->with('success', 'Seller info updated.');
    }


    public function verifySeller(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $status = $request->action === 'approve' ? 'approved' : 'rejected';

        // Update seller verification status
        $seller->verification_status = $status;
        $seller->save();

        // Update user_type
        $user = $seller->user;
        if ($user) {
            $user->user_type = $status === 'approved' ? 'Seller' : 'Buyer';
            $user->save();

            // Send email notification
            $user->notify(new SellerApplicationStatusNotification($status));
        }

        session()->flash('success', 'Seller status updated!');
        return redirect()->route('admin.sellers.index');
    }


}
