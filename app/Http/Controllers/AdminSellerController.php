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

        $allSellers = Seller::whereHas('user', function ($query) {
            $query->where('user_type', 'Seller');
        })->with('user')->latest()->paginate(10);


        $unverifiedSellers = Seller::where('verification_status', 'pending')
        ->whereHas('user', function ($query) {
            $query->where('user_type', 'Seller');
        })
        ->with('user')
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
            'email' => 'required|email',
            'matric' => 'required|string|max:255',
            'user_type' => 'required|in:Buyer,Seller',
        ]);

        $seller = Seller::with('user')->findOrFail($id);
        $user = $seller->user;

        // Update user
        $user->update([
            'email' => $request->email,
            'matric' => $request->matric,
            'user_type' => $request->user_type,
        ]);

        // Only update verification status if user type is Seller
        if ($request->user_type === 'Seller') {
            $request->validate([
                'verification_status' => 'required|in:pending,approved,rejected',
            ]);
            $seller->verification_status = $request->verification_status;
        }


        $seller->save();

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
