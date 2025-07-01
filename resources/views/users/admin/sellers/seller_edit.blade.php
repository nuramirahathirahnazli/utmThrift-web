@extends('shared.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8 border-b border-gray-200 pb-4">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
            <svg class="w-6 h-6 mr-2 text-[var(--primary-color)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Edit Seller Profile
        </h2>
        <p class="text-gray-600 mt-1">Manage seller account details and verification status</p>
    </div>

    <!-- Success Notification -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center text-green-700">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Form Section -->
    <form action="{{ route('admin.sellers.update', $seller->id) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @csrf
        @method('PUT')

        <!-- Account Details -->
        <div class="space-y-6">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Account Information</h3>
                <p class="text-gray-600 text-sm mt-1">Basic seller account details</p>
            </div>

            <!-- Grid Layout for Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Read-only Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md border border-gray-200 text-gray-600">
                        {{ $seller->user->name }}
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md border border-gray-200 text-gray-600">
                        {{ $seller->user->contact ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md border border-gray-200 text-gray-600">
                        {{ ucfirst($seller->user->gender) ?? 'N/A' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md border border-gray-200 text-gray-600">
                        {{ $seller->user->location ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Editable Fields Section -->
        <div class="space-y-6 mt-8">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Editable Information</h3>
                <p class="text-gray-600 text-sm mt-1">Modifiable account settings</p>
            </div>

            <!-- Editable Fields Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $seller->user->email) }}"
                           class="w-full px-4 py-2.5 rounded-lg border focus:border-[var(--primary-color)] focus:ring-2 focus:ring-[var(--primary-color)]/20 @error('email') border-red-300 @else border-gray-300 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Matric Number -->
                <div>
                    <label for="matric" class="block text-sm font-medium text-gray-700 mb-2">Matric Number</label>
                    <input type="text" name="matric" id="matric" value="{{ old('matric', $seller->user->matric) }}"
                           class="w-full px-4 py-2.5 rounded-lg border focus:border-[var(--primary-color)] focus:ring-2 focus:ring-[var(--primary-color)]/20 @error('matric') border-red-300 @else border-gray-300 @enderror">
                    @error('matric')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Type -->
                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                    <select name="user_type" id="user_type" 
                            class="w-full px-4 py-2.5 rounded-lg border focus:border-[var(--primary-color)] focus:ring-2 focus:ring-[var(--primary-color)]/20 @error('user_type') border-red-300 @else border-gray-300 @enderror">
                        <option value="Buyer" {{ $seller->user_type === 'Buyer' ? 'selected' : '' }}>Buyer</option>
                        <option value="Seller" {{ $seller->user_type === 'Seller' ? 'selected' : '' }}>Seller</option>
                    </select>
                    @error('user_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Verification Status -->
                <div>
                    <label for="verification_status" class="block text-sm font-medium text-gray-700 mb-2">Verification Status</label>
                    <select name="verification_status" id="verification_status" 
                            class="w-full px-4 py-2.5 rounded-lg border focus:border-[var(--primary-color)] focus:ring-2 focus:ring-[var(--primary-color)]/20 @error('verification_status') border-red-300 @else border-gray-300 @enderror">
                        <option value="pending" {{ $seller->verification_status === 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="approved" {{ $seller->verification_status === 'approved' ? 'selected' : '' }}>Verified Seller</option>
                        <option value="rejected" {{ $seller->verification_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('verification_status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
            <a href="{{ route('admin.sellers.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[var(--primary-color)] text-white rounded-lg hover:bg-[var(--primary-color)]/90 focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                Save Changes
            </button>
        </div>

    </form>
</div>
@endsection
