<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestorContractRequest;
use App\Http\Requests\UpdateInvestorContractRequest;
use App\Models\Investor;
use App\Models\InvestorContract;
use App\Models\InvestorActivity;
use Illuminate\Support\Facades\Storage;

class InvestorContractController extends Controller
{
    public function index(Investor $investor)
    {
        $investor->load(['user', 'contracts.creator']);

        $contracts = $investor->contracts()
            ->with('creator')
            ->latest()
            ->paginate(10);

        return view('investors.contracts.index', compact('investor', 'contracts'));
    }

    public function create(Investor $investor)
    {
        $investor->load('user');

        return view('investors.contracts.create', compact('investor'));
    }

    public function store(StoreInvestorContractRequest $request, Investor $investor)
    {
        $data = $request->validated();

        $filePath = null;
        $fileName = null;
        $fileSize = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('investor-contracts', 'public');
            $fileName = $request->file('file')->getClientOriginalName();
            $fileSize = $request->file('file')->getSize();
        }

        $contract = $investor->contracts()->create([
            'created_by' => auth('admin')->id(),
            'title'      => $data['title'],
            'type'       => $data['type'] ?? null,
            'status'     => $data['status'],
            'start_date' => $data['start_date'] ?? null,
            'end_date'   => $data['end_date'] ?? null,
            'file_path'  => $filePath,
            'file_name'  => $fileName,
            'file_size'  => $fileSize,
            'notes'      => $data['notes'] ?? null,
        ]);

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'contract_created',
            'meta'        => [
                'contract_id' => $contract->id,
                'title'       => $contract->title,
                'status'      => $contract->status,
            ],
        ]);

        return redirect()
            ->route('investors.contracts.index', $investor->id)
            ->with('success', 'Contract created successfully.');
    }

    public function edit(Investor $investor, InvestorContract $contract)
    {
        return view('investors.contracts.edit', compact('investor', 'contract'));
    }

    public function update(UpdateInvestorContractRequest $request, Investor $investor, InvestorContract $contract)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($contract->file_path && Storage::disk('public')->exists($contract->file_path)) {
                Storage::disk('public')->delete($contract->file_path);
            }

            $contract->file_path = $request->file('file')->store('investor-contracts', 'public');
            $contract->file_name = $request->file('file')->getClientOriginalName();
            $contract->file_size = $request->file('file')->getSize();
        }

        $contract->title = $data['title'];
        $contract->type = $data['type'] ?? null;
        $contract->status = $data['status'];
        $contract->start_date = $data['start_date'] ?? null;
        $contract->end_date = $data['end_date'] ?? null;
        $contract->notes = $data['notes'] ?? null;
        $contract->save();

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'contract_updated',
            'meta'        => [
                'contract_id' => $contract->id,
                'title'       => $contract->title,
                'status'      => $contract->status,
            ],
        ]);

        return redirect()
            ->route('investors.contracts.index', $investor->id)
            ->with('success', 'Contract updated successfully.');
    }

    public function destroy(Investor $investor, InvestorContract $contract)
    {
        if ($contract->file_path && Storage::disk('public')->exists($contract->file_path)) {
            Storage::disk('public')->delete($contract->file_path);
        }

        InvestorActivity::create([
            'investor_id' => $investor->id,
            'user_id'     => auth('admin')->id(),
            'action'      => 'contract_deleted',
            'meta'        => [
                'contract_id' => $contract->id,
                'title'       => $contract->title,
            ],
        ]);

        $contract->delete();

        return redirect()
            ->route('investors.contracts.index', $investor->id)
            ->with('success', 'Contract deleted successfully.');
    }
}