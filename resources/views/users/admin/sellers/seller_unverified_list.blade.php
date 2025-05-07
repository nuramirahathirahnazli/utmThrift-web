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
                <td class="px-6 py-4 whitespace-nowrap">{{ $seller->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $seller->user->email ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        {{ ucfirst($seller->verification_status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                    <a href="{{ route('admin.sellers.show', $seller->id) }}"
                       class="group flex items-center gap-2 text-yellow-600 hover:bg-yellow-100 px-3 py-2 rounded-lg transition duration-150 ease-in-out">
                        <!-- Eye icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:text-yellow-700" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057 -5.064 7 -9.542 7
                                     -4.477 0 -8.268 -2.943 -9.542 -7z" />
                        </svg>
                        <span class="text-sm group-hover:text-yellow-700">View</span>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
