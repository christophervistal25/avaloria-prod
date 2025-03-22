<?php

namespace App\Http\Controllers\Admin;

use App\Models\Download;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = Download::orderBy('created_at', 'DESC')->get();
        return inertia('Admin/Downloads', [
            'downloads' => $downloads
        ]);
    }

    public function create()
    {
        return inertia('Admin/DownloadsCreate');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'link' => ['required', 'url', 'unique:downloads'],
        ]);

        Download::create([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'link' => $request->link,
            'created_at' => now(),
        ]);


        return redirect()->route('admin.downloads.index')->with('success', 'Download created successfully');
    }

    public function edit(int $id)
    {
        $download = Download::find($id);
        return inertia('Admin/DownloadsEdit', [
            'download' => $download
        ]);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'link' => ['required', 'url', 'unique:downloads,link,' . $id],
        ]);

        $download = Download::findOrFail($id);

        $download->update([
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.downloads.index')->with('success', 'Download updated successfully');
    }

    public function destroy(int $id)
    {
        $download = Download::findOrFail($id);
        $download->delete();
        return redirect()->route('admin.downloads.index')->with('success', 'Download deleted successfully');
    }

}
