<?php

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;

beforeEach(function () {
    $this->seed(RoleAndPermissionSeeder::class);
});

test('admin can access settings and perform actions', function () {
    $admin = User::where('email', 'admin@email.com')->first();
    expect($admin)->not->toBeNull();
    expect($admin->hasRole('Admin'))->toBeTrue();

    $response = $this->actingAs($admin)->get(route('settings.index'));
    $response->assertStatus(200);
});

test('guest can access dashboards but is forbidden from mutation endpoints and settings', function () {
    $guest = User::where('email', 'guest@email.com')->first();
    expect($guest)->not->toBeNull();
    expect($guest->hasRole('Guest'))->toBeTrue();

    // Can access read dashboards
    $response = $this->actingAs($guest)->get(route('dashboard'));
    $response->assertStatus(200);

    $response = $this->actingAs($guest)->get(route('stunting.dashboard'));
    $response->assertStatus(200);

    $response = $this->actingAs($guest)->get(route('anc.dashboard'));
    $response->assertStatus(200);

    // Forbidden from settings
    $response = $this->actingAs($guest)->get(route('settings.index'));
    $response->assertStatus(403);

    // Forbidden from clearing massive data
    $response = $this->actingAs($guest)->delete(route('stunting.clear-massive'));
    $response->assertStatus(403);

    $response = $this->actingAs($guest)->delete(route('tb.patients.clear-massive'));
    $response->assertStatus(403);
});
