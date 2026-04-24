<?php

namespace Tests\Unit;

use App\Http\Requests\FormRequestStoreProfile;
use App\Http\Requests\FormRequestUpdateProfile;
use PHPUnit\Framework\TestCase;

class ProfileFormRequestTest extends TestCase
{
    public function test_store_profile_request_has_expected_rules(): void
    {
        $request = new FormRequestStoreProfile();

        $this->assertSame([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:50'],
            'image' => ['required', 'file', 'image', 'max:2048'],
            'statut' => ['required', 'in:inactif,en_attente,actif'],
        ], $request->rules());
    }

    public function test_update_profile_request_has_expected_rules(): void
    {
        $request = new FormRequestUpdateProfile();

        $this->assertSame([
            'nom' => ['sometimes', 'string', 'max:100'],
            'prenom' => ['sometimes', 'string', 'max:50'],
            'image' => ['sometimes', 'file', 'image', 'max:2048'],
            'statut' => ['sometimes', 'in:inactif,en_attente,actif'],
        ], $request->rules());
    }

    public function test_profile_form_requests_are_authorized(): void
    {
        $this->assertTrue((new FormRequestStoreProfile())->authorize());
        $this->assertTrue((new FormRequestUpdateProfile())->authorize());
    }
}
