<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormRequestStoreProfile;
use App\Models\Profile;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function store(FormRequestStoreProfile $request)
    {
        $path = $request->file('image')->store('profiles', 'public');
        
        $profile = Profile::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'image' => $path,
            'statut' => $request->statut,
        ]);

        return response()->json($profile, 201);
    }
}
