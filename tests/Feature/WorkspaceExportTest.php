<?php

use App\Models\Contact;
use App\Models\Reminder;
use App\Models\Tag;

beforeEach(function () {
    $this->user = makeUser();
    $this->actingAs($this->user);
});

it('redirects unauthenticated users', function () {
    auth()->logout();
    $this->get('/workspace/export')->assertRedirect('/login');
});

it('streams a zip containing CSVs of the workspace', function () {
    Contact::factory()->count(3)->create(['team_id' => $this->user->current_team_id, 'name' => 'Ada Lovelace', 'email' => 'ada@example.test']);
    Tag::create(['team_id' => $this->user->current_team_id, 'name' => 'VIP', 'slug' => 'vip']);

    $res = $this->get('/workspace/export');
    $res->assertOk();
    expect($res->headers->get('content-type'))->toContain('application/zip');
    expect($res->headers->get('content-disposition'))->toContain('laracontact-export-');

    // Capture the streamed bytes and unzip into memory.
    ob_start();
    $res->sendContent();
    $bytes = ob_get_clean();

    $tmp = tempnam(sys_get_temp_dir(), 't').'.zip';
    file_put_contents($tmp, $bytes);

    $zip = new ZipArchive();
    expect($zip->open($tmp))->toBeTrue();

    $files = collect(range(0, $zip->numFiles - 1))->map(fn ($i) => $zip->getNameIndex($i));
    expect($files)->toContain('contacts.csv', 'messages.csv', 'emails.csv', 'reminders.csv', 'groups.csv', 'tags.csv', 'README.txt');

    $contactsCsv = $zip->getFromName('contacts.csv');
    expect($contactsCsv)->toContain('Ada Lovelace');
    expect($contactsCsv)->toContain('ada@example.test');

    $tagsCsv = $zip->getFromName('tags.csv');
    expect($tagsCsv)->toContain('VIP');

    $zip->close();
    @unlink($tmp);
});

it('only exports the current team\'s data', function () {
    Contact::factory()->create(['team_id' => $this->user->current_team_id, 'name' => 'Mine']);
    Contact::factory()->create(['team_id' => makeUser()->current_team_id, 'name' => 'Theirs']);

    $res = $this->get('/workspace/export');

    ob_start();
    $res->sendContent();
    $bytes = ob_get_clean();
    $tmp = tempnam(sys_get_temp_dir(), 't').'.zip';
    file_put_contents($tmp, $bytes);

    $zip = new ZipArchive();
    $zip->open($tmp);
    $csv = $zip->getFromName('contacts.csv');
    expect($csv)->toContain('Mine');
    expect($csv)->not->toContain('Theirs');
    $zip->close();
    @unlink($tmp);
});
