<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    // j'ai rencontrer quelques problèmes avec le refresh qui ne fonctionnais pas correctement  entre les differents test.
    // d'ou l'ajout du .env.testing qui vide la base entre chaque lancement des tests.
    use RefreshDatabase;

    public function test_public_route_returns_only_active_profiles_without_status(): void
    {
        Profile::factory()->create([
            'statut' => 'actif',
        ]);

        Profile::factory()->create([
            'statut' => 'inactif',
        ]);

        $response = $this->getJson('/api/profiles');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonMissing([
                'statut' => 'actif',
            ]);
    }

    public function test_admin_profiles_route_requires_authentication(): void
    {
        $response = $this->getJson('/api/profiles/list');

        $response->assertUnauthorized();
    }

    public function test_admin_can_see_all_profiles_with_status(): void
    {
        $admin = User::factory()->create();

        Sanctum::actingAs($admin);

        Profile::factory()->create([
            'statut' => 'actif',
        ]);

        Profile::factory()->create([
            'statut' => 'inactif',
        ]);

        $response = $this->getJson('/api/profiles/list');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'statut' => 'actif',
            ])
            ->assertJsonFragment([
                'statut' => 'inactif',
            ]);
    }

    public function test_authenticated_admin_can_create_profile(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();

        Sanctum::actingAs($admin);

        $image = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/profile', [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'statut' => 'actif',
            'image' => $image,
        ]);

        $response->assertCreated()
            ->assertJsonFragment([
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'statut' => 'actif',
            ]);

        $this->assertDatabaseHas('profiles', [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'statut' => 'actif',
        ]);

        $profile = Profile::first();

        Storage::disk('public')->assertExists($profile->image);
    }

    public function test_authenticated_admin_can_update_profile(): void
    {
        $admin = User::factory()->create();

        Sanctum::actingAs($admin);

        $profile = Profile::factory()->create([
            'nom' => 'Ancien',
            'statut' => 'en_attente',
        ]);

        $response = $this->putJson("/api/profile/{$profile->id}", [
            'nom' => 'Nouveau',
            'statut' => 'actif',
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'nom' => 'Nouveau',
                'statut' => 'actif',
            ]);

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'nom' => 'Nouveau',
            'statut' => 'actif',
        ]);
    }

    public function test_authenticated_admin_can_delete_profile(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create();

        Sanctum::actingAs($admin);

        $image = UploadedFile::fake()->image('avatar.jpg')->store('profiles', 'public');

        $profile = Profile::factory()->create([
            'image' => $image,
        ]);

        $response = $this->deleteJson("/api/profile/{$profile->id}");

        $response->assertOk();

        $this->assertDatabaseMissing('profiles', [
            'id' => $profile->id,
        ]);

        Storage::disk('public')->assertMissing($image);
    }

}
