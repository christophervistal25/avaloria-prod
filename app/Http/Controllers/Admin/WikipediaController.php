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
            'items.*.description' => 'required',
            'items.*.newImages.*' => 'nullable|image|max:2048' // Validate multiple images per item
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('wikipedia', 'public');
            $validated['image'] = $path;
        }

        $validated['created_by'] = auth()->id();

        return \DB::transaction(function () use ($validated, $request) {
            $wikipedia = Wikipedia::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? null,
                'created_by' => $validated['created_by'],
                'is_published' => $validated['is_published']
            ]);

            foreach ($validated['items'] as $index => $item) {
                // Process multiple images for this item
                $imagePaths = [];
                
                // Handle new image uploads
                if ($request->hasFile("items.{$index}.newImages")) {
                    $files = $request->file("items.{$index}.newImages");
                    foreach ($files as $file) {
                        $imagePath = $file->store('wikipedia/items', 'public');
                        $imagePaths[] = $imagePath;
                    }
                }
                
                // Combine into a pipe-delimited string
                $imageString = !empty($imagePaths) ? implode('|', $imagePaths) : null;

                $wikipedia->items()->create([
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'image' => $imageString,
                    'order' => $index,
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
            'items.*.description' => 'required',
            'items.*.newImages.*' => 'nullable|image|max:2048', // Validate multiple new images
        ]);
        
        $wikipedia = $wikipedium;

        if ($request->hasFile('image')) {
            if ($wikipedia->image) {
                Storage::disk('public')->delete($wikipedia->image);
            }
            $path = $request->file('image')->store('wikipedia', 'public');
            $validated['image'] = $path;
        }

        return \DB::transaction(function () use ($validated, $wikipedia, $request) {
            $wikipedia->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $validated['image'] ?? $wikipedia->image,
                'is_published' => $validated['is_published']
            ]);

            // Get existing items for reference
            $existingItems = $wikipedia->items()->get();
            
            // Delete existing items (to be recreated)
            $wikipedia->items()->delete();

            // Create new items
            foreach ($validated['items'] as $index => $item) {
                // Process images for this item
                $finalImagePaths = [];
                
                // Keep existing images that should not be removed
                if (!empty($item['existingImages'])) {
                    $existingImages = explode('|', $item['existingImages']);
                    $imagesToRemove = !empty($item['imagesToRemove']) 
                        ? explode('|', $item['imagesToRemove']) 
                        : [];
                    
                    // Filter out images marked for removal
                    foreach ($existingImages as $existingImage) {
                        if (!in_array($existingImage, $imagesToRemove) && !empty($existingImage)) {
                            $finalImagePaths[] = $existingImage;
                        }
                    }
                }
                
                // Add newly uploaded images
                if ($request->hasFile("items.{$index}.newImages")) {
                    $files = $request->file("items.{$index}.newImages");
                    foreach ($files as $file) {
                        $imagePath = $file->store('wikipedia/items', 'public');
                        $finalImagePaths[] = $imagePath;
                    }
                }
                
                // Combine into a pipe-delimited string
                $imageString = !empty($finalImagePaths) ? implode('|', $finalImagePaths) : null;

                $wikipedia->items()->create([
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'image' => $imageString,
                    'order' => $index
                ]);
            }

            // Clean up removed images
            foreach ($validated['items'] as $item) {
                if (!empty($item['imagesToRemove'])) {
                    $imagesToRemove = explode('|', $item['imagesToRemove']);
                    foreach ($imagesToRemove as $imagePath) {
                        if (!empty($imagePath)) {
                            Storage::disk('public')->delete($imagePath);
                        }
                    }
                }
            }

            return redirect()->route('admin.wikipedia.index')
                ->with('success', 'Wikipedia entry updated successfully.');
        });
    }

    public function destroy(Wikipedia $wikipedium)
    {
        // Delete the main image if it exists
        if ($wikipedium->image) {
            Storage::disk('public')->delete($wikipedium->image);
        }

        // Delete all item images
        foreach ($wikipedium->items as $item) {
            if ($item->image) {
                $images = explode('|', $item->image);
                foreach ($images as $imagePath) {
                    if (!empty($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }
        }

        $wikipedium->delete();

        return redirect()->route('admin.wikipedia.index')
            ->with('success', 'Wikipedia entry deleted successfully.');
    }
}