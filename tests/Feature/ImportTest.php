<?php

use App\Models\Contact;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = makeUser(['role' => \App\Support\Roles::SUPER_ADMIN]);
    $this->actingAs($this->user);
    Storage::fake('local');
    putenv('EXPORT_PIN=123456');
    $_ENV['EXPORT_PIN'] = '123456';
    $_SERVER['EXPORT_PIN'] = '123456';
});

it('previews a CSV with auto-detected columns', function () {
    $csv = "Name,Email,Phone,Company,Category,Tags\nAda Lovelace,ada@ex.com,555-1111,Analytical,VIP Customers,\"vip, founder\"\n";
    $file = UploadedFile::fake()->createWithContent('contacts.csv', $csv);

    $this->post(route('imports.preview'), ['csv' => $file])
        ->assertOk()
        ->assertSee('Map columns')
        ->assertSee('Category')
        ->assertSee('Tags');
});

it('imports rows with category and tags after mapping', function () {
    $csv = "Name,Email,Phone,Category,Tags\nAda Lovelace,ada@ex.com,555-1111,VIP Customers,\"vip, founder\"\n";
    $file = UploadedFile::fake()->createWithContent('contacts.csv', $csv);

    $this->post(route('imports.preview'), ['csv' => $file]);
    $path = Storage::disk('local')->files('imports')[0];

    $response = $this->post(route('imports.store'), [
        'file' => $path,
        'pin' => '123456',
        'has_header' => '1',
        'mapping' => [0 => 'name', 1 => 'email', 2 => 'phone', 3 => 'category', 4 => 'tags'],
    ])->assertRedirect();

    preg_match('#/imports/([A-Za-z0-9]{24})/progress#', $response->headers->get('Location'), $matches);
    $importId = $matches[1];

    $this->post(route('imports.process', ['import' => $importId]))->assertOk();

    $contact = Contact::where('email', 'ada@ex.com')->firstOrFail();

    expect($contact->group?->name)->toBe('VIP Customers')
        ->and($contact->tags()->pluck('name')->sort()->values()->all())->toBe(['founder', 'vip'])
        ->and(Group::where('team_id', $this->user->current_team_id)->where('name', 'VIP Customers')->exists())->toBeTrue()
        ->and(Tag::where('team_id', $this->user->current_team_id)->where('name', 'vip')->exists())->toBeTrue();
});

it('requires a name column mapping', function () {
    $csv = "Email\nfoo@bar.com\n";
    $file = UploadedFile::fake()->createWithContent('c.csv', $csv);
    $this->post(route('imports.preview'), ['csv' => $file]);
    $path = Storage::disk('local')->files('imports')[0];

    $this->post(route('imports.store'), [
        'file' => $path,
        'pin' => '123456',
        'has_header' => '1',
        'mapping' => [0 => 'email'],
    ])->assertSessionHasErrors('mapping');
});
