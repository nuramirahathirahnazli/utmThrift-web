<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
 use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Event;

class AdminEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return view('users.admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.admin.events.event_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $data = $request->all();

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');

            // Clean the original filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName); // Replace symbols
            $filename = time() . '_' . $cleanName . '.' . $file->getClientOriginalExtension();

            // Save the file
            $file->storeAs('public/events', $filename);

            // Save filename into the $data array
            $data['poster'] = $filename;
        }
        //dd($data);
        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('users.admin.events.show', compact('event'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('users.admin.events.event_edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
   

public function update(Request $request, Event $event)
{
    Log::info('Update request received', ['request_data' => $request->except('poster')]);
    
    try {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        Log::info('Validation passed', ['validated_data' => $validatedData]);

        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            Log::info('File uploaded', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . $cleanName . '.' . $extension;

            $path = $file->storeAs('public/events', $filename);
            Log::info('File stored', ['path' => $path, 'filename' => $filename]);

            if ($event->poster && Storage::exists('public/events/' . $event->poster)) {
                Storage::delete('public/events/' . $event->poster);
                Log::info('Old file deleted', ['old_poster' => $event->poster]);
            }

            $validatedData['poster'] = $filename;
        }

        $event->update($validatedData);
        Log::info('Event updated successfully', ['event_id' => $event->id]);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);

    } catch (\Exception $e) {
        Log::error('Update failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Update failed: ' . $e->getMessage()
        ], 500);
    }
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
