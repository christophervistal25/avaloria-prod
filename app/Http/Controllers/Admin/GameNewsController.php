<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NewsType;
use App\Models\GameNews;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameNewsController extends Controller
{
    public function index()
    {
        $news = GameNews::orderBy('created_at', 'DESC')->paginate(10);

        return inertia('Admin/News', [
            'news' => $news,
            'currentPage' => request()->query('page', 1),
        ]);
    }

    public function create()
    {
        return inertia('Admin/CreateNews');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required',
            'Content' => 'required',
            'IMGLink' => 'required',
            'type' => 'required',
            'display' => 'required',
        ]);

        $fileName = md5(time() . $request->file('IMGLink')->getClientOriginalName()) . '.' . $request->file('IMGLink')->getClientOriginalExtension();
        if (move_uploaded_file($request->file('IMGLink')->getPathname(), public_path().'/images/uploaded/'.$fileName)) {
            $type = NewsType::tryFrom($request->type + 1);
            GameNews::create([
                'title' => $request->Title,
                'description' => $request->Content,
                'image_link' => '/images/uploaded/' . $fileName,
                'created_by' => auth()->id(),
                'type' => $type->value,
                'display' => $request->display,
            ]);
        }

        return response()->json(['message' => 'News created successfully']);
    }

    public function edit(int $id)
    {
        $news = GameNews::find($id);
        return inertia('Admin/EditNews', [
            'news' => $news,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
          'title' => 'required',
          'description' => 'required',
          'type' => 'required',
          'display' => 'required',
        ]);

        $news = GameNews::find($id);

        $type = NewsType::tryFrom($request->type + 1);
        $data = [
            'Auteur' => $request->user()->ID,
            'Titre' => $request->Title,
            'Texte_news' => $request->Content,
            'type' => $type->value,
            'display' => $request->display,
            'Preview' => $request->Preview,
        ];

        if (request()->has('IMGLink')) {
            $fileName = time().'.'.$request->file('IMGLink')->getClientOriginalExtension();
            $data['image_link'] = '/images/uploaded/'.$fileName;
            move_uploaded_file($request->file('IMGLink')->getPathname(), public_path().'/images/uploaded/'.$fileName);
        }

        $news->update($data);

        return response()->json(['message' => 'News updated successfully']);
    }

    public function destroy(int $id)
    {
        GameNews::destroy($id);
        return response()->json(['message' => 'News deleted successfully']);
    }
}
