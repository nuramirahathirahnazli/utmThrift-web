<!-- views/welcome.blade.php -->
@extends('shared.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-[var(--primary-color)] text-white p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                <p class="text-sm mt-1">Total Users</p>
            </div>
            <i class="fas fa-users text-3xl"></i>
        </div>
    </div>

    <!-- Total Sellers -->
    <div class="bg-[var(--secondary-color)] text-gray-800 p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold">{{ $totalSellers }}</p>
                <p class="text-sm mt-1">Total Sellers</p>
            </div>
            <i class="fas fa-store text-3xl"></i>
        </div>
    </div>

    <!-- Total Events -->
    <div class="bg-green-500 text-white p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold">{{ $totalEvents }}</p>
                <p class="text-sm mt-1">Total Events</p>
            </div>
            <i class="fas fa-calendar text-3xl"></i>
        </div>
    </div>

    <!-- Today's Events -->
    <div class="bg-purple-500 text-white p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-3xl font-bold">{{ $todayEvents }}</p>
                <p class="text-sm mt-1">Events Today</p>
            </div>
            <i class="fas fa-calendar-day text-3xl"></i>
        </div>
    </div>
</div>


<!-- Recent Activities -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-xl font-semibold mb-4">Recent Activities</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-3 px-4">User</th>
                    <th class="text-left py-3 px-4">Action</th>
                    <th class="text-left py-3 px-4">Date</th>
                    <th class="text-left py-3 px-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingSellers as $seller)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $seller->user->name }}</td>
                        <td class="py-3 px-4">Applied as Seller</td>
                        <td class="py-3 px-4">{{ $seller->created_at->format('Y-m-d') }}</td>
                        <td class="py-3 px-4">
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">Pending</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No unverified sellers at the moment.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>


<!-- Statistics Chart -->
<div class="mt-8 bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-xl font-semibold mb-4">Sales Overview</h3>
    <div class="h-64"> <!-- Chart container -->
        <canvas id="salesChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_map(fn($m) => date('F', mktime(0, 0, 0, $m, 10)), array_keys($eventStats))) !!},
            datasets: [{
                label: 'Monthly Event Count',
                data: {!! json_encode(array_values($eventStats)) !!},
                backgroundColor: 'rgba(0, 102, 204, 0.6)',
                borderColor: 'rgb(0, 102, 204)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });

</script>
@endsection
