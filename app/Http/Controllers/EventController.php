<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 4);

        $events = Event::orderBy('event_date', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($event) {
                $event->poster = url('event-image/' . $event->poster);  
                return $event;
            });

        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'event_date' => $event->event_date,
            'start_time' => $event->start_time,
            'end_time' => $event->end_time,
            'location' => $event->location,
            'poster' => url('event-image/' . $event->poster), 
        ]);
    }

}
