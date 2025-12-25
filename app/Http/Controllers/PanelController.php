<?php

namespace App\Http\Controllers;

use App\Models\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function index()
    {
        $panels = Panel::orderBy('name')->get();
        return view('panels.index', compact('panels'));
    }

    public function create()
    {
        return view('panels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Panel::create($validated);

        return redirect()->route('panels.index')->with('success', 'Panel created successfully.');
    }

    public function edit(Panel $panel)
    {
        return view('panels.edit', compact('panel'));
    }

    public function update(Request $request, Panel $panel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $panel->update($validated);

        return redirect()->route('panels.index')->with('success', 'Panel updated successfully.');
    }

    public function destroy(Panel $panel)
    {
        $panel->delete();
        return redirect()->route('panels.index')->with('success', 'Panel deleted successfully.');
    }

    // Mark the given panel as the current one (only one current panel allowed)
    public function makeCurrent(Panel $panel)
    {
        DB::transaction(function () use ($panel) {
            // Set all panels to not current
            Panel::query()->update(['is_current' => false]);

            // Set selected panel as current
            $panel->update(['is_current' => true]);
        });

        return redirect()->route('panels.index')->with('success', "Panel '{$panel->name}' is now current.");
    }

    // Show positions for a specific panel
    public function positions(Panel $panel)
    {
        // Load positions with user data
        $positions = \App\Models\Position::with('user')
            ->where('panel_id', $panel->id)
            ->orderBy('level')
            ->get();

        return view('panels.positions', compact('panel', 'positions'));
    }

    // Store a new position for the given panel
    public function storePosition(Request $request, Panel $panel)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'position' => 'required|string|max:191',
            'level' => 'required|integer|min:0',
        ]);

        $validated['panel_id'] = $panel->id;

        \App\Models\Position::create($validated);

        return redirect()->route('panels.positions', $panel->id)->with('success', 'Position added.');
    }
}
