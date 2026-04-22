<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormRequestStoreProfile;
use App\Http\Requests\FormRequestUpdateProfile;
use App\Http\Resources\AdminProfileResource;
use App\Http\Resources\PublicProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{

    public function indexPublic(): AnonymousResourceCollection
    {
        $profiles = Profile::where('statut', 'actif')->latest()->get();

        return PublicProfileResource::collection($profiles);
    }

    public function indexAdmin(): AnonymousResourceCollection
    {
        $profiles = Profile::latest()->get();

        return AdminProfileResource::collection($profiles);
    }

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

    public function update(FormRequestUpdateProfile $request, Profile $profile): JsonResponse
    {
        //j'ai eu des soucis avec postman sur la methode PUT et le form-data je ne comprend pas pourquoi cela envoi souvent rien, mais en raw ça fonctionne parfaitement
        $data = $request->validated();

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($profile->image);

            $data['image'] = $request->file('image')->store('profiles', 'public');
        }

        $profile->update($data);

        return response()->json($profile->fresh(), 200);
    }

    public function delete(Profile $profile): JsonResponse
    {
        Storage::disk('public')->delete($profile->image);

        $profile->delete();

        return response()->json(200);
    }
}
