<?php

use App\Models\Contact;

beforeEach(function () {
    $this->user = makeUser();
});

it('rejects unauthenticated API requests', function () {
    $this->getJson('/api/contacts')->assertStatus(401);
});

it('returns contacts JSON with a valid Sanctum token', function () {
    Contact::factory()->count(2)->create(['team_id' => $this->user->current_team_id]);

    $token = $this->user->createToken('test')->plainTextToken;
    $res = $this->withHeader('Authorization', 'Bearer '.$token)
        ->withHeader('Accept', 'application/json')
        ->getJson('/api/contacts');

    $res->assertOk();
    expect($res->json('data'))->toHaveCount(2);
});

it('isolates contacts by team in the API', function () {
    Contact::factory()->count(2)->create(['team_id' => $this->user->current_team_id]);
    Contact::factory()->count(3)->create(['team_id' => makeUser()->current_team_id]);

    $token = $this->user->createToken('test')->plainTextToken;
    $res = $this->withHeader('Authorization', 'Bearer '.$token)
        ->withHeader('Accept', 'application/json')
        ->getJson('/api/contacts');

    expect($res->json('data'))->toHaveCount(2);
});

it('creates a contact via the API', function () {
    $token = $this->user->createToken('test')->plainTextToken;

    $res = $this->withHeader('Authorization', 'Bearer '.$token)
        ->withHeader('Accept', 'application/json')
        ->postJson('/api/contacts', [
            'name' => 'API Contact',
            'email' => 'api@example.com',
        ]);

    $res->assertStatus(201);
    expect(Contact::where('email', 'api@example.com')->exists())->toBeTrue();
});
