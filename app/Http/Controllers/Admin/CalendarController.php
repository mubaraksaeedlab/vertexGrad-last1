<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    // عرض صفحة التقويم
    public function index()
    {
        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed calendar page',
            category: 'calendar'
        );

        return view('manager.calendar');
    }

    // إضافة حدث جديد
    public function addEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:50'],
            'icon' => ['nullable', 'string', 'max:100'],
            'event_date' => ['required', 'date'],
        ]);

        try {
            $event = CalendarEvent::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'color' => $validated['color'] ?? null,
                'icon' => $validated['icon'] ?? null,
                'event_date' => $validated['event_date'],
            ]);

            AuditLogService::log(
                event: 'created',
                description: 'Created calendar event: ' . $event->title,
                category: 'calendar_event',
                subject: $event,
                newValues: $this->auditCalendarPayload($event)
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleteEvents(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:calendar_events,id'],
        ]);

        $ids = $validated['ids'];

        try {
            $events = CalendarEvent::whereIn('id', $ids)->get();

            if ($events->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No matching events found',
                ]);
            }

            $oldValues = $events->map(function (CalendarEvent $event) {
                return $this->auditCalendarPayload($event);
            })->values()->toArray();

            CalendarEvent::whereIn('id', $ids)->delete();

            AuditLogService::log(
                event: 'deleted',
                description: 'Deleted ' . count($ids) . ' calendar event(s)',
                category: 'calendar_event',
                oldValues: [
                    'events' => $oldValues,
                ],
                properties: [
                    'deleted_ids' => $ids,
                    'deleted_count' => count($ids),
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // جلب جميع الأحداث
    public function getEvents()
    {
        $events = CalendarEvent::all()->groupBy('event_date');

        AuditLogService::log(
            event: 'viewed',
            description: 'Fetched calendar events list',
            category: 'calendar_event',
            properties: [
                'events_count' => CalendarEvent::count(),
                'grouped_dates_count' => $events->count(),
            ]
        );

        return response()->json($events);
    }

    protected function auditCalendarPayload(CalendarEvent $event): array
    {
        return [
            'id' => $event->getKey(),
            'title' => $event->title,
            'description' => $event->description,
            'color' => $event->color,
            'icon' => $event->icon,
            'event_date' => $event->event_date,
            'created_at' => $event->created_at ?? null,
            'updated_at' => $event->updated_at ?? null,
        ];
    }
}