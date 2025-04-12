@extends('shared.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Seller Management</h1>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-8 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px">
            <li class="mr-2">
                <a href="{{ route('admin.sellers.index', ['tab' => 'all']) }}" 
                   class="inline-block py-4 px-4 text-sm font-medium text-center rounded-t-lg border-b-2 
                          @if($activeTab === 'all') border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-600 @endif">
                    All Sellers ({{ $totalSellers }})
                </a>
            </li>
            <li class="mr-2">
                <a href="{{ route('admin.sellers.index', ['tab' => 'unverified']) }}" 
                   class="inline-block py-4 px-4 text-sm font-medium text-center rounded-t-lg border-b-2 
                          @if($activeTab === 'unverified') border-primary text-primary @else border-transparent text-gray-500 hover:text-gray-600 @endif">
                    Unverified Sellers ({{ $unverifiedCount }})
                </a>
            </li>
        </ul>
    </div>

    <!-- Display Success Message -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            <strong>Success!</strong> {{ session('success') }}
        </div>
    @endif

    <!-- Tab Content -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($activeTab === 'all')
            @include('users.admin.sellers.seller_all_list')
        @else
            @include('users.admin.sellers.seller_unverified_list')
        @endif
    </div>
</div>
@endsection
