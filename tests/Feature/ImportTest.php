<?php

use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = makeUser();
    $this->actingAs($this->user);
    Storage::fake('local');
});

it('previews a CSV with auto-detected columns', function () {
    $csv = "Name,Email,Phone,Company\nAda Lovelace,ada@ex.com,555-1111,Analytical\n";
    $file = UploadedFile::fake()->createWithContent('contacts.csv', $csv);

    $this->post('/contacts/import/preview', ['csv' => $file])
        ->assertOk()
        ->assertSee('Map columns');
});

it('imports rows after mapping', function () {
    $csv = "Name,Email,Tags\nAda Lovelace,ada@ex.com,vip,founder\n";
    $file = UploadedFile::fake()->createWithContent('contacts.csv', $csv);

    $this->post('/contacts/import/preview', ['csv' => $file]);
    $path = Storage::disk('local')->files('imports')[0];

    $this->post('/contacts/import', [
        'file' => $path,
        'has_header' => '1',
        'mapping' => [0 => 'name', 1 => 'email', 2 => 'tags'],
    ])->assertRedirect('/contacts');

    expect(Contact::where('email', 'ada@ex.com')->exists())->toBeTrue();
});

it('requires a name column mapping', function () {
    $csv = "Email\nfoo@bar.com\n";
    $file = UploadedFile::fake()->createWithContent('c.csv', $csv);
    $this->post('/contacts/import/preview', ['csv' => $file]);
    $path = Storage::disk('local')->files('imports')[0];

    $this->post('/contacts/import', [
        'file' => $path,
        'has_header' => '1',
        'mapping' => [0 => 'email'],
    ])->assertSessionHasErrors('mapping');
});
