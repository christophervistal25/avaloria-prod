<?php

namespace App\Http\Controllers\Admin;

use App\Models\Wikipedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class WikipediaController extends Controller
{
    public function index()
    {
        $wikipedias = Wikipedia::with(['user', 'items'])
            ->latest()
            ->paginate(10);

        return inertia('Admin/Wikipedia/Index', [
            'wikipedias' => $wikipedias
        ]);
    }

    public function create()
    {
        return inertia('Admin/Wikipedia/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|max:255',
            'items.*.description' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('wikipedia', 'public');
            $validated['image'] = $path;
        }

        $validated['created_by'] = auth()->id();

        return \DB::transaction(function () use ($validated) {
            $wikipedia = Wikipedia::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? null,
                'created_by' => $validated['created_by'],
                'is_published' => $validated['is_published']
            ]);

            foreach ($validated['items'] as $index => $item) {
                $wikipedia->items()->create([
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'order' => $index
                ]);
            }

            return redirect()->route('admin.wikipedia.index')
                ->with('success', 'Wikipedia entry created successfully.');
        });
    }

    public function edit(Wikipedia $wikipedium)
    {
        return inertia('Admin/Wikipedia/Edit', [
            'wikipedia' => $wikipedium->load('items')
        ]);
    }

    public function update(Request $request, Wikipedia $wikipedium)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.title' => 'required|max:255',
            'items.*.description' => 'required'
        ]);
        $wikipedia = $wikipedium;

        if ($request->hasFile('image')) {
            if ($wikipedia->image) {
                Storage::disk('public')->delete($wikipedia->image);
            }
            $path = $request->file('image')->store('wikipedia', 'public');
            $validated['image'] = $path;
        }

        return \DB::transaction(function () use ($validated, $wikipedia) {
            $wikipedia->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? $wikipedia->image,
                'is_published' => $validated['is_published']
            ]);

            // Delete existing items
            $wikipedia->items()->delete();

            // Create new items
            foreach ($validated['items'] as $index => $item) {
                $wikipedia->items()->create([
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'order' => $index
                ]);
            }

            return redirect()->route('admin.wikipedia.index')
                ->with('success', 'Wikipedia entry updated successfully.');
        });
    }

    public function destroy(Wikipedia $wikipedium)
    {
        if ($wikipedium->image) {
            Storage::disk('public')->delete($wikipedium->image);
        }

        $wikipedium->delete();

        return redirect()->route('admin.wikipedia.index')
            ->with('success', 'Wikipedia entry deleted successfully.');
    }
}