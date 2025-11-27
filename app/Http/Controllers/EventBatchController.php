<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventBatch;
use App\Models\PermissionRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventBatchController extends Controller
{
    public function list(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Event Batch', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['PermissionAdd']    = PermissionRole::getPermission('Add Event Batch', Auth::user()->role_id);
        $data['PermissionEdit']   = PermissionRole::getPermission('Edit Event Batch', Auth::user()->role_id);
        $data['PermissionDelete'] = PermissionRole::getPermission('Delete Event Batch', Auth::user()->role_id);

        // Query dasar
        $query = EventBatch::query();

        // SEARCH: tahun / tahap / nama lengkap
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('event_year', 'like', '%' . $keyword . '%')
                    ->orWhere('event_phase', 'like', '%' . $keyword . '%')
                    ->orWhere('full_batch_name', 'like', '%' . $keyword . '%');
            });
        }

        // FILTER: tahun
        if (!empty($request->event_year)) {
            $query->where('event_year', $request->event_year);
        }

        // SORTING (whitelist)
        $allowedSortBy    = ['event_year', 'event_phase', 'created_at'];
        $sortBy           = $request->get('sort_by');
        $sortDirectionRaw = $request->get('sort_direction');

        $sortDirection = $sortDirectionRaw === 'asc' ? 'asc' : 'desc';
        if (!in_array($sortBy, $allowedSortBy)) {
            $sortBy = 'event_year'; // default
        }

        $query->orderBy($sortBy, $sortDirection)
            ->orderBy('event_phase', 'asc'); // supaya tahap tetap urut dalam tahun yang sama

        // PAGINATION
        $data['getRecord'] = $query->paginate(10)->withQueryString();

        // Data tahun unik untuk dropdown filter
        $data['years'] = EventBatch::select('event_year')
            ->distinct()
            ->orderBy('event_year', 'desc')
            ->pluck('event_year');

        // simpan nilai filter & sort ke view
        $data['filter_keyword'] = $request->keyword;
        $data['filter_year']    = $request->event_year;
        $data['sort_by']        = $sortBy;
        $data['sort_direction'] = $sortDirection;

        return view('panel.event_batch.list', $data);
    }

    public function add()
    {
        $PermissionRole = PermissionRole::getPermission('Add Event Batch', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        return view('panel.event_batch.add');
    }

    public function insert(Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Add Event Batch', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'event_year' => 'required|string|max:4',
            'event_phase' => 'required|string|max:50',
        ]);

        EventBatch::create([
            'event_year' => trim($request->event_year),
            'event_phase' => trim($request->event_phase),
        ]);

        return redirect('event-batch')->with('success', 'Event Batch berhasil dibuat');
    }

    public function edit($id)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Event Batch', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $data['getRecord'] = EventBatch::findOrFail($id);
        return view('panel.event_batch.edit', $data);
    }

    public function update($id, Request $request)
    {
        $PermissionRole = PermissionRole::getPermission('Edit Event Batch', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        $request->validate([
            'event_year' => 'required|string|max:4',
            'event_phase' => 'required|string|max:50',
        ]);

        $batch = EventBatch::findOrFail($id);
        $batch->event_year = trim($request->event_year);
        $batch->event_phase = trim($request->event_phase);
        $batch->save();

        return redirect('event-batch')->with('success', 'Event Batch berhasil diperbarui');
    }

    public function delete($id)
    {
        $PermissionRole = PermissionRole::getPermission('Delete Event Batch', Auth::user()->role_id);
        if (empty($PermissionRole)) abort(404);

        EventBatch::findOrFail($id)->delete();
        return redirect('event-batch')->with('success', 'Event Batch berhasil dihapus');
    }
}
