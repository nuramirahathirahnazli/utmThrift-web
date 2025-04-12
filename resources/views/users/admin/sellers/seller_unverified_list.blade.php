@if($unverifiedSellers->isEmpty())
<div class="p-6 text-gray-500">
    No sellers pending verification.
</div>
@else
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($unverifiedSellers as $seller)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">{{ $seller->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $seller->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        {{ ucfirst($seller->verification_status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                    <!-- Approve Button -->
                    <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST">
                        @csrf
                        <button type="submit" name="action" value="approve"
                            class="group flex items-center gap-2 text-green-600 hover:bg-green-100 px-3 py-2 rounded-lg transition duration-150 ease-in-out">
                            <!-- Check icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:text-green-700" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-sm group-hover:text-green-700">Approve</span>
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST">
                        @csrf
                        <button type="submit" name="action" value="reject"
                            class="group flex items-center gap-2 text-red-600 hover:bg-red-100 px-3 py-2 rounded-lg transition duration-150 ease-in-out">
                            <!-- X icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:text-red-700" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span class="text-sm group-hover:text-red-700">Reject</span>
                        </button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
