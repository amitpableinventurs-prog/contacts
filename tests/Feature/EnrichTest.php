<?php

beforeEach(function () {
    $this->actingAs(makeUser());
});

it('extracts contact fields from pasted text in fake mode', function () {
    $text = "Ada Lovelace\nMathematician at Analytical Inc\nada@example.com · +1 415 555 0142\nhttps://linkedin.com/in/ada";

    $res = $this->postJson('/contacts/enrich', ['text' => $text]);
    $res->assertOk();

    expect($res->json('fake'))->toBeTrue();
    expect($res->json('fields'))
        ->name->toBe('Ada Lovelace')
        ->email->toBe('ada@example.com')
        ->company->toBe('Analytical Inc')
        ->job_title->toBe('Mathematician');
});
