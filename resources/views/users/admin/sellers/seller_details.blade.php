@extends('shared.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $seller->user->name }}'s Profile</h1>
            <div class="inline-flex items-center text-gray-500">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Seller Management Portal</span>
            </div>
        </div>

        <!-- Profile Picture Section -->
        <div class="mb-10 text-center">
            @if($seller->profile_picture)
                <img src="{{ asset('storage/' . $seller->profile_picture) }}" 
                     alt="Profile Picture" 
                     class="w-40 h-40 rounded-full mx-auto border-4 border-white shadow-xl object-cover hover:shadow-lg transition-shadow duration-300">
            @else
                <div class="w-40 h-40 rounded-full bg-gray-100 mx-auto flex items-center justify-center 
                            border-4 border-white shadow-xl hover:shadow-lg transition-shadow duration-300">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            @endif
        </div>

        <!-- Information Sections -->
        <div class="space-y-8">
            <!-- Personal Information Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h2 class="ml-3 text-xl font-semibold text-gray-800">Personal Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-gray-800">{{ $seller->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email Address</label>
                            <p class="text-gray-800 break-all">{{ $seller->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Contact Number</label>
                            <p class="text-gray-800">{{ $seller->user->contact ?? 'Not Provided' }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Faculty</label>
                            <p class="text-gray-800">{{ $seller->user->faculty ?? 'Not Provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Location</label>
                            <p class="text-gray-800">{{ $seller->user->location ?? 'Not Provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Gender</label>
                            <p class="text-gray-800">{{ ucfirst($seller->user->gender) ?? 'Not Provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Information Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="ml-3 text-xl font-semibold text-gray-800">Verification Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Matric Number</label>
                            <p class="text-gray-800">{{ $seller->user->matric ?? 'Not Provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Matric Card Image</label>
                            @if ($seller->matric_card_image)
                                <button 
                                    onclick="document.getElementById('pdfModal').classList.remove('hidden')" 
                                    class="flex items-center text-blue-600 hover:text-blue-800 transition-colors mt-1">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    View Document
                                </button>
                            @else
                                <p class="text-gray-500">No file uploaded</p>
                            @endif
                        </div>

                        <!-- PDF Modal -->
                        <div id="pdfModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white w-full max-w-3xl h-[80vh] p-4 rounded-lg relative">
                                <button 
                                    onclick="document.getElementById('pdfModal').classList.add('hidden')" 
                                    class="absolute top-3 right-3 text-gray-600 hover:text-black text-xl font-bold">
                                    ✕
                                </button>
                                <iframe 
                                    src="{{ $seller->matric_card_image }}"
                                    alt="Matric Card PDF"
                                    class="w-full h-full border rounded" 
                                    frameborder="0">
                                </iframe>
                            </div>
                        </div>

                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Verification Status</label>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                            ];
                            $statusColor = $statusColors[$seller->verification_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <div class="mt-1 inline-flex items-center px-3 py-1 rounded-full {{ $statusColor }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ ucfirst($seller->verification_status) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Information Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="ml-3 text-xl font-semibold text-gray-800">Store Information</h2>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Store Name</label>
                        <p class="text-gray-800">{{ $seller->store_name ?? 'No store registered' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        @if($seller->verification_status === 'pending')
        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" name="action" value="approve" 
                    class="w-full flex items-center justify-center bg-green-500 hover:bg-green-600 text-white 
                           px-6 py-3 rounded-xl transition-all duration-200 hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve Seller
                </button>
            </form>

            <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" name="action" value="reject" 
                    class="w-full flex items-center justify-center bg-red-500 hover:bg-red-600 text-white 
                           px-6 py-3 rounded-xl transition-all duration-200 hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Application
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
