<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use App\Models\User;
use App\Models\Investor;
use App\Models\InvestorNote;
use App\Models\InvestorFile;
use App\Models\InvestorActivity;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateInvestorRequest;
use App\Http\Requests\StoreInvestorRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\InvestorsImport;
use App\Exports\InvestorsExport;
use App\Services\AuditLogService;

class InvestorController extends Controller
{
    public function index(Request $request)
    {
        $view = $request->get('view', 'active');

        $query = User::query()
            ->where('role', 'Investor')
            ->with([
                'investor' => function ($q) {
                    $q->withTrashed()
                      ->with([
                          'investmentRequests' => function ($q2) {
                              $q2->with('project')
                                 ->select('id', 'investor_id', 'project_id', 'status', 'created_at');
                          }
                      ]);
                }
            ]);

        if ($view === 'archived') {
            $query->whereIn('id', Investor::onlyTrashed()->pluck('user_id'));
        } elseif ($view === 'active') {
            $query->whereIn('id', Investor::query()->whereNull('deleted_at')->pluck('user_id'));
        } elseif ($view === 'all') {
            $query->whereIn('id', Investor::withTrashed()->pluck('user_id'));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sortBy  = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $perPage = (int) $request->get('per_page', 10);

        $allowedSorts = ['created_at', 'name', 'email', 'status'];
        if (! in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        $sortDir = strtolower($sortDir) === 'asc' ? 'asc' : 'desc';

        $investors = $query->orderBy($sortBy, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        $activeInvestorUserIds = Investor::query()
            ->whereNull('deleted_at')
            ->pluck('user_id');

        $archivedInvestorUserIds = Investor::onlyTrashed()
            ->pluck('user_id');

        $topCompany = Investor::query()
            ->whereNull('deleted_at')
            ->select('company', DB::raw('COUNT(*) as total'))
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->groupBy('company')
            ->orderByDesc('total')
            ->first();

        $stats = [
            'total' => Investor::count(),

            'active' => User::where('role', 'Investor')
                ->where('status', 'active')
                ->whereIn('id', $activeInvestorUserIds)
                ->count(),

            'inactive' => User::where('role', 'Investor')
                ->where('status', 'inactive')
                ->whereIn('id', $activeInvestorUserIds)
                ->count(),

            'budget' => Investor::whereNull('deleted_at')->sum('budget'),

            'archived' => $archivedInvestorUserIds->count(),

            'top_company' => $topCompany,
        ];

        return view('investors.index', compact('investors', 'stats', 'view'));
    }

    public function create()
    {
        return view('investors.create');
    }

    public function store(StoreInvestorRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $request->username,
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
                'role'     => 'Investor',
                'status'   => $request->status ?? 'active',
                'gender'   => $request->gender,
                'city'     => $request->city,
                'state'    => $request->state,
            ]);

            $investor = $user->investor()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone'           => $request->phone,
                    'company'         => $request->company,
                    'position'        => $request->position,
                    'investment_type' => $request->investment_type,
                    'budget'          => $request->budget,
                    'source'          => $request->source,
                    'notes'           => $request->notes,
                    'status'          => $request->status ?? 'active',
                ]
            );

            InvestorActivity::create([
                'investor_id' => $investor->id,
                'user_id'     => auth('admin')->id(),
                'action'      => 'created',
                'meta'        => ['name' => $user->name],
            ]);

            AuditLogService::log(
                event: 'created',
                description: 'Created investor: ' . ($user->name ?? $user->username),
                category: 'investor',
                subject: $investor,
                newValues: $this->auditInvestorPayload($investor->fresh('user')),
                properties: [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'company' => $investor->company,
                ]
            );

            DB::commit();

            return redirect()->route('admin.investors.index')
                ->with('success', 'Investor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Investor $investor)
    {
        $investor->load([
            'user',
            'investorNotes.user',
            'files',
            'activities.user',
        ]);

        $projectInvestments = $investor->user
            ? $investor->user->investments()
                ->with('student')
                ->orderByPivot('created_at', 'desc')
                ->get()
            : collect();

        return view('investors.show', compact('investor', 'projectInvestments'));
    }

    public function edit(Investor $investor)
    {
        $investor->load('user');

        return view('investors.edit', compact('investor'));
    }

    public function update(UpdateInvestorRequest $request, Investor $investor)
    {
        $data = $request->validate([
            'username'        => 'nullable|string|max:150|unique:users,username,' . $investor->user_id,
            'name'            => 'required|string|max:150',
            'email'           => 'required|email|unique:users,email,' . $investor->user_id,
            'status'          => 'required|string|in:active,inactive',
            'gender'          => 'nullable|string|in:male,female',
            'city'            => 'nullable|string|max:150',
            'state'           => 'nullable|string|max:150',
            'phone'           => 'nullable|string|max:50',
            'company'         => 'nullable|string|max:150',
            'position'        => 'nullable|string|max:150',
            'investment_type' => 'nullable|string|max:100',
            'budget'          => 'nullable|numeric|min:0',
            'source'          => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
        ]);

        $investor->load('user');
        $oldValues = $this->auditInvestorPayload($investor);

        DB::transaction(function () use ($investor, $data) {
            $investor->user()->update([
                'username' => $data['username'] ?? $investor->user->username,
                'name'     => $data['name'],
                'email'    => $data['email'],
                'status'   => $data['status'],
                'gender'   => $data['gender'] ?? null,
                'city'     => $data['city'] ?? null,
                'state'    => $data['state'] ?? null,
            ]);

            $investor->update([
                'phone'           => $data['phone'] ?? null,
                'company'         => $data['company'] ?? null,
                'position'        => $data['position'] ?? null,
                'investment_type' => $data['investment_type'] ?? null,
                'budget'          => $data['budget'] ?? null,
                'source'          => $data['source'] ?? null,
                'notes'           => $data['notes'] ?? null,
                'status'          => $data['status'],
            ]);

            $investor->activities()->create([
                'user_id' => auth('admin')->id(),
                'action'  => 'updated',
            ]);
        });

        $investor->refresh()->load('user');

        AuditLogService::log(
            event: 'updated',
            description: 'Updated investor: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor',
            subject: $investor,
            oldValues: $oldValues,
            newValues: $this->auditInvestorPayload($investor)
        );

        return redirect()->route('admin.investors.show', $investor->user_id)
            ->with('success', 'Investor updated successfully.');
    }

    public function destroy(Investor $investor)
    {
        $investor->load('user');

        AuditLogService::log(
            event: 'archived',
            description: 'Archived investor: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor',
            subject: $investor,
            oldValues: $this->auditInvestorPayload($investor)
        );

        $investor->activities()->create([
            'user_id' => auth('admin')->id(),
            'action'  => 'archived',
        ]);

        $investor->delete();

        return redirect()->route('admin.investors.index', ['view' => 'active'])
            ->with('success', 'Investor archived successfully.');
    }

    public function restore(Investor $investor)
    {
        $investor->load('user');

        $investor->restore();

        $investor->activities()->create([
            'user_id' => auth('admin')->id(),
            'action'  => 'restored',
        ]);

        $investor->refresh()->load('user');

        AuditLogService::log(
            event: 'restored',
            description: 'Restored investor: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor',
            subject: $investor,
            newValues: $this->auditInvestorPayload($investor)
        );

        return redirect()->route('admin.investors.index', ['view' => 'archived'])
            ->with('success', 'Investor restored successfully.');
    }

    public function forceDelete(Investor $investor)
    {
        $investor->load(['files', 'user']);

        $oldValues = $this->auditInvestorPayload($investor);

        foreach ($investor->files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        AuditLogService::log(
            event: 'deleted',
            description: 'Permanently deleted investor: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor',
            subject: $investor,
            oldValues: $oldValues,
            properties: [
                'delete_mode' => 'force',
                'files_count' => $investor->files->count(),
            ]
        );

        $investor->forceDelete();

        return redirect()->route('admin.investors.index', ['view' => 'archived'])
            ->with('success', 'Investor permanently deleted.');
    }

    public function storeNote(Request $request, Investor $investor)
    {
        $request->validate([
            'note' => 'required|string',
        ]);

        $investor->load('user');

        $note = $investor->investorNotes()->create([
            'user_id' => auth('admin')->id(),
            'note'    => $request->note,
        ]);

        $investor->activities()->create([
            'user_id' => auth('admin')->id(),
            'action'  => 'note_added',
            'meta'    => ['note_id' => $note->id],
        ]);

        AuditLogService::log(
            event: 'note_added',
            description: 'Added investor note for: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor_note',
            subject: $investor,
            properties: [
                'note_id'      => $note->id,
                'note_preview' => mb_substr($note->note, 0, 120),
            ]
        );

        return redirect()
            ->route('admin.investors.show', $investor->user_id)
            ->with('success', 'Note added successfully.');
    }

    public function deleteNote(Investor $investor, InvestorNote $note)
    {
        $investor->load('user');

        AuditLogService::log(
            event: 'note_deleted',
            description: 'Deleted investor note for: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor_note',
            subject: $investor,
            properties: [
                'note_id'      => $note->id,
                'note_preview' => mb_substr($note->note ?? '', 0, 120),
            ]
        );

        $note->delete();

        $investor->activities()->create([
            'user_id' => auth('admin')->id(),
            'action'  => 'note_deleted',
        ]);

        return redirect()
            ->route('admin.investors.show', $investor->user_id)
            ->with('success', 'Note deleted successfully.');
    }

    public function uploadFile(Request $request, Investor $investor)
    {
        $request->validate([
            'file' => 'required|file|max:51200',
        ]);

        $investor->load('user');

        $file = $request->file('file');
        $path = $file->store('investors/' . $investor->id, 'public');

        $record = $investor->files()->create([
            'filename'    => $file->getClientOriginalName(),
            'path'        => $path,
            'mime'        => $file->getClientMimeType(),
            'size'        => $file->getSize(),
            'uploaded_by' => auth('admin')->id(),
        ]);

        $investor->activities()->create([
            'user_id' => auth('admin')->id(),
            'action'  => 'file_uploaded',
            'meta'    => ['file_id' => $record->id],
        ]);

        AuditLogService::log(
            event: 'file_uploaded',
            description: 'Uploaded investor file for: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor_file',
            subject: $investor,
            properties: [
                'file_id'   => $record->id,
                'filename'  => $record->filename,
                'mime'      => $record->mime,
                'size'      => $record->size,
            ]
        );

        return redirect()
            ->route('admin.investors.show', $investor->user_id)
            ->with('success', 'File uploaded successfully.');
    }

    public function deleteFile(Investor $investor, InvestorFile $file)
    {
        $investor->load('user');

        AuditLogService::log(
            event: 'file_deleted',
            description: 'Deleted investor file for: ' . ($investor->user->name ?? $investor->user->username ?? 'Investor'),
            category: 'investor_file',
            subject: $investor,
            properties: [
                'file_id'  => $file->id,
                'filename' => $file->filename,
                'mime'     => $file->mime,
                'size'     => $file->size,
            ]
        );

        Storage::disk('public')->delete($file->path);
        $file->delete();

        $investor->activities()->create([
            'user_id' => auth('admin')->id(),
            'action'  => 'file_deleted',
        ]);

        return redirect()
            ->route('admin.investors.show', $investor->user_id)
            ->with('success', 'File deleted successfully.');
    }

    public function export($format = 'xlsx')
    {
        AuditLogService::log(
            event: 'exported',
            description: 'Exported investors list',
            category: 'investor_export',
            properties: [
                'format' => $format,
            ]
        );

        $fileName = 'investors_' . now()->format('Ymd_His') . '.' . $format;
        return Excel::download(new InvestorsExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        AuditLogService::log(
            event: 'imported',
            description: 'Imported investors file',
            category: 'investor_import',
            properties: [
                'original_name' => $request->file('file')->getClientOriginalName(),
                'mime'          => $request->file('file')->getClientMimeType(),
                'size'          => $request->file('file')->getSize(),
            ]
        );

        Excel::import(new InvestorsImport, $request->file('file'));

        return back()->with('success', 'Investors imported successfully.');
    }

    protected function auditInvestorPayload(Investor $investor): array
    {
        $investor->loadMissing('user');

        return [
            'investor_id'      => $investor->id,
            'user_id'          => $investor->user_id,
            'username'         => $investor->user->username ?? null,
            'name'             => $investor->user->name ?? null,
            'email'            => $investor->user->email ?? null,
            'role'             => $investor->user->role ?? null,
            'status'           => $investor->status,
            'user_status'      => $investor->user->status ?? null,
            'gender'           => $investor->user->gender ?? null,
            'city'             => $investor->user->city ?? null,
            'state'            => $investor->user->state ?? null,
            'phone'            => $investor->phone,
            'company'          => $investor->company,
            'position'         => $investor->position,
            'investment_type'  => $investor->investment_type,
            'budget'           => $investor->budget,
            'source'           => $investor->source,
            'notes'            => $investor->notes,
            'deleted_at'       => $investor->deleted_at,
        ];
    }
}