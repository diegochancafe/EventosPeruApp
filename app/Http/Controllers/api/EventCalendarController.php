<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventCalendarController extends Controller
{
    /**
     * Return events formatted for FullCalendar
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Event::with(['services.category', 'client']);

        // If provider, limit to events that include services from that provider
        if ($user && $user->role === 'provider') {
            $query->whereHas('services', function ($q) use ($user) {
                $q->where('services.user_id', $user->id);
            });
        }

        $events = $query->get();

        $formatted = $events->map(function ($event) {
            $isAllDay = empty($event->start_time) && empty($event->end_time);

            // Build start as ISO8601 when time present, otherwise as date string (YYYY-MM-DD)
            if (!empty($event->start_time)) {
                $startDt = Carbon::parse($event->event_date . ' ' . $event->start_time);
                $start = $startDt->toIso8601String();
            } else {
                $start = Carbon::parse($event->event_date)->toDateString();
            }

            // Build end similarly; if no end_time leave null
            $end = null;
            if (!empty($event->end_time)) {
                $endDt = Carbon::parse($event->event_date . ' ' . $event->end_time);
                $end = $endDt->toIso8601String();
            }

            $services = $event->services->map(function ($s) {
                return [
                    'title' => $s->title,
                    'category' => $s->category ? $s->category->title : null,
                ];
            })->values()->all();

            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $start,
                'end' => $end,
                'allDay' => $isAllDay,
                'extendedProps' => [
                    'description' => $event->description,
                    'status' => $event->status,
                    'client' => $event->client ? $event->client->name : null,
                    'services' => $services,
                ],
            ];
        })->values();

        return response()->json($formatted, 200);
    }
}
