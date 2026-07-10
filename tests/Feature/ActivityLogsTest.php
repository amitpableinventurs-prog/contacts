<?php

use App\Models\ActivityLog;
use App\Models\User;
use App\Support\Roles;

function makeLog(User $user, string $action = 'user.login'): ActivityLog
{
    return ActivityLog::create([
        'team_id'  => $user->current_team_id,
        'user_id'  => $user->id,
        'action'   => $action,
        'metadata' => ['ip' => '1.2.3.4', 'browser' => 'Chrome', 'device' => 'Desktop'],
    ]);
}

it('exports activity logs as csv', function () {
    $superAdmin = makeUser(['role' => Roles::SUPER_ADMIN]);
    makeLog($superAdmin);

    $this->actingAs($superAdmin);
    $res = $this->get('/activity-logs/export');

    $res->assertOk();
    expect($res->headers->get('content-type'))->toContain('text/csv')
        ->and($res->streamedContent())->toContain('user.login')->toContain('1.2.3.4');
});

it('lets only super admin delete logs', function () {
    $admin = makeUser();
    $log = makeLog($admin);

    $this->actingAs($admin); // admin, not super admin
    $this->post('/activity-logs/delete', ['log_ids' => [$log->id]])->assertForbidden();
    expect(ActivityLog::find($log->id))->not->toBeNull();

    $this->actingAs(makeUser(['role' => Roles::SUPER_ADMIN]));
    $this->post('/activity-logs/delete', ['log_ids' => [$log->id]])->assertRedirect();
    expect(ActivityLog::find($log->id))->toBeNull();
});

it('shows a user profile with details, activity, and search usage', function () {
    $admin = makeUser();
    $clerk = User::factory()->create([
        'role' => Roles::CLERK,
        'searches_used' => 7,
        'search_quota' => 100,
        'current_team_id' => $admin->current_team_id,
    ])->fresh();
    makeLog($clerk, 'contact.searched');

    $this->actingAs($admin);
    $this->get('/users/'.$clerk->id)
        ->assertOk()
        ->assertSee($clerk->name)
        ->assertSee('Searches used')
        ->assertSee('Contact Searched');
});

it('blocks admins from viewing a super admin profile', function () {
    $superAdmin = makeUser(['role' => Roles::SUPER_ADMIN]);

    $this->actingAs(makeUser()); // admin
    $this->get('/users/'.$superAdmin->id)->assertForbidden();
});
