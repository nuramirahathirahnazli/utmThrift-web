@if($allSellers->isEmpty())
    <div class="p-6 text-gray-500">
        No sellers found.
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($allSellers as $index => $seller)
                    @php
                        // Accessing the 'verification_status' from the 'Seller' model
                        $status = strtolower($seller->verification_status);
                        $statusClasses = match($status) {
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'pending' => 'bg-orange-100 text-orange-800',
                             default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $seller->user->name }}</td> <!-- Access user data -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                            <!-- Edit Action -->
                            <a href="{{ route('admin.sellers.edit', $seller->id) }}"
                               class="group flex items-center gap-2 text-blue-600 hover:bg-blue-100 px-3 py-2 rounded-lg transition duration-150 ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:text-blue-700" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.232 5.232l3.536 3.536M9 13l6-6
                                             m2 2l-6 6M5 20h14" />
                                </svg>
                                <span class="text-sm group-hover:text-blue-700">Edit</span>
                            </a>
                            <!-- View Action -->
                            <a href="{{ route('admin.sellers.show', $seller->id) }}"
                               class="group flex items-center gap-2 text-yellow-600 hover:bg-yellow-100 px-3 py-2 rounded-lg transition duration-150 ease-in-out">
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
