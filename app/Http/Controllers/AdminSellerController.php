<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSellerController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'all');

        $counts = [
            'total' => User::where('user_type', 'Seller')->count(),
            'unverified' => User::where('user_type', 'Seller')
                                ->where('verification_status', 'pending')
                                ->count()
        ];

        $allSellers = User::where('user_type', 'Seller')
            ->latest()
            ->paginate(10)
            ->appends(['tab' => 'all']);

        $unverifiedSellers = User::where('user_type', 'Seller')
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
        $seller = User::with('items')->findOrFail($id);

        return view('users.admin.sellers.show', compact('seller'));
    }

    public function edit($id) {
        $seller = User::findOrFail($id);
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
        $seller = User::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject'
        ]);

        $seller->verification_status = $request->action === 'approve' ? 'approved' : 'rejected';
        $seller->save();

        session()->flash('success', 'Seller added successfully!');
            return redirect()->route('admin.sellers.index');
    }
}
