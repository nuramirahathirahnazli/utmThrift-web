<!-- views/welcome.blade.php -->
@extends('shared.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Statistics Cards -->
    <div class="bg-[var(--primary-color)] text-white p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm">Total Users</p>
                <p class="text-3xl font-bold">1,234</p>
            </div>
            <i class="fas fa-users text-3xl"></i>
        </div>
    </div>

    <div class="bg-[var(--secondary-color)] text-gray-800 p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm">Active Sellers</p>
                <p class="text-3xl font-bold">89</p>
            </div>
            <i class="fas fa-store text-3xl"></i>
        </div>
    </div>

    <div class="bg-green-500 text-white p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm">Ongoing Events</p>
                <p class="text-3xl font-bold">5</p>
            </div>
            <i class="fas fa-calendar-alt text-3xl"></i>
        </div>
    </div>

    <div class="bg-purple-500 text-white p-6 rounded-lg">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm">Total Sales</p>
                <p class="text-3xl font-bold">RM 12,345</p>
            </div>
            <i class="fas fa-wallet text-3xl"></i>
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
                <!-- Sample Data -->
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">John Doe</td>
                    <td class="py-3 px-4">New registration</td>
                    <td class="py-3 px-4">2023-08-15</td>
                    <td class="py-3 px-4">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Completed</span>
                    </td>
                </tr>
                <!-- Add more rows -->
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
    // Sample Chart.js implementation
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Monthly Sales',
                data: [12, 19, 3, 5, 2, 3],
                borderColor: 'rgb(0, 35, 102)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection
