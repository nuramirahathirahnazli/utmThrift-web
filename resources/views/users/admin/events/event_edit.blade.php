@extends('shared.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Event: {{ $event->title }}</h1>
            <div class="inline-flex items-center text-gray-500">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Event Management Portal</span>
            </div>
        </div>

       <form action="{{ route('admin.events.update', ['event' => $event->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Event Poster Section (New dedicated section) -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-purple-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="ml-3 text-xl font-semibold text-gray-800">Event Poster</h2>
                </div>

                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Current Poster Display -->
                    <div class="w-full md:w-1/2">
                        <h3 class="text-lg font-medium text-gray-700 mb-3">Current Poster</h3>
                        @if($event->poster)
                            <div class="border-2 border-dashed border-gray-200 rounded-xl overflow-hidden">
                                <img src="{{ asset('storage/events/' . $event->poster) }}" 
                                     alt="Current Event Poster" 
                                     class="w-full h-auto max-h-96 object-contain mx-auto">
                            </div>
                            <p class="text-sm text-gray-500 mt-2 text-center">Current uploaded poster</p>
                        @else
                            <div class="bg-gray-100 rounded-xl h-64 flex items-center justify-center">
                                <p class="text-gray-500">No poster uploaded yet</p>
                            </div>
                        @endif
                    </div>

                    <!-- Poster Upload Section -->
                    <div class="w-full md:w-1/2">
                        <h3 class="text-lg font-medium text-gray-700 mb-3">Upload New Poster</h3>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-400 transition-colors duration-200">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <div class="text-sm text-gray-600">
                                    <p class="font-medium">Drag and drop your poster here</p>
                                    <p>or</p>
                                </div>
                                <label class="cursor-pointer">
                                    <span class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Select File
                                        <input id="file-input" type="file" name="poster" accept="image/*" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'No file selected'">
                                    </span>
                                </label>
                                <p id="file-name" class="text-sm text-gray-700 mt-2">No file selected</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                            </div>
                        </div>
                        @error('poster')
                            <p class="text-red-500 text-sm mt-3 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Event Details Card -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="ml-3 text-xl font-semibold text-gray-800">Event Details</h2>
                </div>

                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 px-4 py-2"
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="6"
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 px-4 py-2"
                                  required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Time & Location Card -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h2 class="ml-3 text-xl font-semibold text-gray-800">Time & Location</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event Date <span class="text-red-500">*</span></label>
                        <input type="date" name="event_date" value="{{ old('event_date', $event->event_date) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 px-4 py-2"
                               required>
                        @error('event_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 px-4 py-2"
                               required>
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time"
                               value="{{ old('start_time', \Carbon\Carbon::createFromFormat('H:i:s', $event->start_time)->format('H:i')) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 px-4 py-2"
                               required>
                        @error('start_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time"
                               value="{{ old('end_time', \Carbon\Carbon::createFromFormat('H:i:s', $event->end_time)->format('H:i')) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-150 px-4 py-2"
                               required>
                        @error('end_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.events.index') }}" 
                   class="flex items-center justify-center text-gray-600 hover:text-gray-900 px-6 py-3 rounded-lg transition-all duration-200 border border-gray-300 hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelector('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Updating...
    `;
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Success - show message and redirect
            alert(data.message);
            window.location.href = "{{ route('admin.events.index') }}";
        } else {
            // Error - show message
            alert(data.message);
            console.error('Update failed:', data);
        }
    } catch (error) {
        console.error('Network error:', error);
        alert('Network error occurred. Please check console for details.');
    } finally {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Update Event
        `;
    }
});
</script>
@endsection
