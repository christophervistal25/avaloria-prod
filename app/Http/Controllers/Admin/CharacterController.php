<?php

namespace App\Http\Controllers\Admin;

use App\Models\Character;
use App\Http\Controllers\Controller;

class CharacterController extends Controller
{
    public function index()
    {
        $search = request('search');

        $characters = Character::when(request('search') != '', function () {
            return Character::where('m_szName', 'like', '%' . request('search') . '%');
        })->paginate(10, [ 'm_idPlayer',
            'm_szName',
            'm_nLevel',
            'm_nJob',
            'TotalPlayTime',
            'CreateTime']);

        return inertia('Admin/Characters', [
            'characters' => $characters,
            'search' => $search,
            'currentPage' => $characters->currentPage(),
        ]);
    }
}
