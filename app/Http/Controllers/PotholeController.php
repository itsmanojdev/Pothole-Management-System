<?php

namespace App\Http\Controllers;

use App\AdminFilter;
use App\Http\Requests\PotholeRequest;
use App\Models\Pothole;
use App\PotholeStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Str;

class PotholeController extends Controller
{
    /**
     * Show Pothole List
     *
     * @param  Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status') ? titleCase($request->input('status')) : '';
        $status = ($status == PotholeStatus::All->value) ? '' : $status;
        $query = Pothole::query();

        if (Auth::user()->isCitizen()) {
            $query->where('created_by', Auth::id());
        } elseif (Auth::user()->isAdmin()) {
            $adminFilter = titleCase($request->input('admin-filter'));
            if (!$status && $adminFilter == AdminFilter::OPEN_POTHOLES->value) {
                $status = PotholeStatus::OPEN->value;
            } else {
                $query->where('assigned_to', Auth::id());
            }
        }

        $potholes = $query
            ->when($search, function (Builder $query, string $search) {
                $query->whereAny(['title', 'address'], 'like', "%$search%");
            })
            ->when($status, function (Builder $query, string $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('pothole.index', compact('potholes'));
    }

    /**
     * Pothole Create View
     *
     * @return View
     */
    public function create(): View
    {
        return view('pothole.create');
    }

    /**
     * Store Pothole Record
     *
     * @param  PotholeRequest $request
     * @return RedirectResponse
     */
    public function store(PotholeRequest $request): RedirectResponse
    {
        $attributes = $request->validated();

        $image_path = request()->hasFile('pothole-image')
            ? request()->file('pothole-image')->store("potholes", "public")
            : null;

        $attributes = array_merge($attributes, [
            "created_by" => Auth::id(),
            "status" => PotholeStatus::OPEN,
            "image_path" => $image_path
        ]);

        Pothole::create($attributes);

        return redirect()->route('citizen.pothole.index')->with('success', "Pothole Create Successfully.");
    }

    /**
     * Pothole Details Page View
     *
     * @param  Pothole $pothole
     * @return View
     */
    public function show(Pothole $pothole): View
    {
        return view('pothole.show', compact('pothole'));
    }

    /**
     * Pothole Edit Page View - Only Citizen
     *
     * @param  Pothole $pothole
     * @return View
     */
    public function edit(Pothole $pothole): View
    {
        return view('pothole.edit', compact('pothole'));
    }

    /**
     * Update Pothole Details - Only Citizen
     *
     * @param  PotholeRequest $request
     * @param  Pothole $pothole
     * @return RedirectResponse
     */
    public function update(PotholeRequest $request, Pothole $pothole): RedirectResponse
    {
        $validated = $request->validated();
        $pothole->fill($validated);

        if (request()->hasFile('pothole-image')) {
            Storage::disk('public')->delete($pothole->image_path ?? '');
            $pothole->image_path = request()->file('pothole-image')->store("potholes", "public");
        }

        // Save only if fields are dirty
        if ($pothole->isDirty()) {
            $pothole->save();

            return redirect()->route('citizen.pothole.show', $pothole->id)->with('success', 'Pothole Details Updated Successfully!');
        }
        return back()->with('info', 'No changes detected.');
    }

    /**
     * Update Pothole Status - Only Admin & Super Admin
     *
     * @param  Request $request
     * @param  Pothole $pothole
     * @return RedirectResponse
     */
    public function statusUpdate(Request $request, Pothole $pothole): RedirectResponse
    {
        $status = PotholeStatus::tryFrom(titleCase($request->status));
        if ($status == $pothole->status) {
            return back()->with('info', 'No changes in status detected!');
        }

        // Assign To Me Form Handling
        if ($status == PotholeStatus::ASSIGNED) {
            $pothole->status = $status;
            $pothole->assigned_to = Auth::id();
        }

        // Status Update Form Handling
        if (verifyStatus($pothole->status, $status)) {
            $pothole->status = $status;
        } else {
            return back()->with('error', 'Invalid Status Update!!');
        }

        // Updating Pothole Status
        $pothole->save();
        return redirect()->route('admin.pothole.show', $pothole->id)->with('success', 'Pothole Status Updated Successfully');
    }

    /**
     * Verify The Pothole - Only Citizen & Super Admin
     *
     * @param  Request $request
     * @param  Pothole $pothole
     * @return RedirectResponse
     */
    public function verify(Request $request, Pothole $pothole): RedirectResponse
    {
        if ($request->verify === "VERIFY") {
            $pothole->status = PotholeStatus::VERIFIED;
            $pothole->save();
            return redirect(userRoute('pothole.show', $pothole->id))->with('success', "Pothole Verified Successfully!!");
        }
        return back()->with('error', "Type VERIFY to verify the pothole.");
    }

    /**
     * Delete Pothole Record - Only Citizen
     *
     * @param  Pothole $pothole
     * @return RedirectResponse
     */
    public function destroy(Request $request, Pothole $pothole): RedirectResponse
    {
        if ($request->delete === "DELETE") {
            $pothole->delete();
            return redirect()->route('citizen.pothole.index')->with('success', "Pothole Deleted Successfully!!");
        }
        return back()->with('error', "Type DELETE to delete the pothole.");
    }
}
