<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Services\AuditLogService;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->latest()
            ->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function history()
    {
        $query = Announcement::with('creator')->ordered();

        $filter = request('filter');

        if ($filter === 'active') {
            $query->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('publish_at')
                      ->orWhere('publish_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
                });
        }

        if ($filter === 'scheduled') {
            $query->where('is_active', true)
                ->whereNotNull('publish_at')
                ->where('publish_at', '>', now());
        }

        if ($filter === 'expired') {
            $query->whereNotNull('expires_at')
                ->where('expires_at', '<', now());
        }

        if ($filter === 'disabled') {
            $query->where('is_active', false);
        }

        if ($filter === 'pinned') {
            $query->where('is_pinned', true);
        }

        if ($audience = request('audience')) {
            $query->where('audience', $audience);
        }

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $announcements = $query->paginate(10)->withQueryString();

        $analytics = [
            'total'     => Announcement::count(),
            'active'    => Announcement::published()->count(),
            'scheduled' => Announcement::where('is_active', true)
                ->whereNotNull('publish_at')
                ->where('publish_at', '>', now())
                ->count(),
            'expired'   => Announcement::whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->count(),
            'pinned'    => Announcement::where('is_pinned', true)->count(),
        ];

        return view('admin.announcements.history', compact(
            'announcements',
            'analytics'
        ));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();
        $data['is_pinned']  = $request->boolean('is_pinned');
        $data['is_active']  = $request->boolean('is_active');

        $announcement = Announcement::create($data);

        AuditLogService::log(
            event: 'created',
            description: 'Created announcement: ' . $announcement->title,
            category: 'announcement',
            subject: $announcement,
            newValues: $this->auditPayload($announcement)
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        $data = $request->validated();

        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['is_active'] = $request->boolean('is_active');

        $oldValues = $this->auditPayload($announcement);

        $announcement->update($data);
        $announcement->refresh();

        AuditLogService::log(
            event: 'updated',
            description: 'Updated announcement: ' . $announcement->title,
            category: 'announcement',
            subject: $announcement,
            oldValues: $oldValues,
            newValues: $this->auditPayload($announcement)
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        AuditLogService::log(
            event: 'deleted',
            description: 'Deleted announcement: ' . $announcement->title,
            category: 'announcement',
            subject: $announcement,
            oldValues: $this->auditPayload($announcement)
        );

        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    protected function auditPayload(Announcement $announcement): array
    {
        return [
            'title'      => $announcement->title,
            'body'       => $announcement->body,
            'audience'   => $announcement->audience,
            'is_active'  => $announcement->is_active,
            'is_pinned'  => $announcement->is_pinned,
            'publish_at' => $announcement->publish_at,
            'expires_at' => $announcement->expires_at,
        ];
    }
}