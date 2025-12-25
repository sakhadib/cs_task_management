<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'position' => 'required|string|max:191',
            'level' => 'required|integer|min:0',
        ]);

        $position->update($validated);

        return redirect()->back()->with('success', 'Position updated.');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->back()->with('success', 'Position removed.');
    }
}
