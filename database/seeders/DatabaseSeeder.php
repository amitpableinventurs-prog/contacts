<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Tag;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('password'),
                'role'     => \App\Support\Roles::SUPER_ADMIN,
                'is_active' => true,
            ]
        );

        // Ensure first user is always super admin
        if ($user->role !== \App\Support\Roles::SUPER_ADMIN) {
            $user->update(['role' => \App\Support\Roles::SUPER_ADMIN]);
        }

        $team = $user->currentTeam;

        $groups = collect([
            ['name' => 'Customers', 'color' => '#a855f7'],
            ['name' => 'Leads',     'color' => '#3b82f6'],
            ['name' => 'Partners',  'color' => '#10b981'],
            ['name' => 'Vendors',   'color' => '#f59e0b'],
        ])->map(fn ($g) => Group::firstOrCreate(
            ['team_id' => $team->id, 'name' => $g['name']],
            ['color' => $g['color']]
        ));

        $tagNames = ['vip', 'follow-up', 'cold', 'hot', 'enterprise', 'self-serve', 'referral'];
        $tags = collect($tagNames)->map(fn ($name) => Tag::firstOrCreate(
            ['team_id' => $team->id, 'slug' => $name],
            ['name' => ucfirst($name)]
        ));

        if (Contact::where('team_id', $team->id)->count() < 25) {
            Contact::factory()->count(30)->make()->each(function (Contact $contact) use ($team, $groups, $tags, $user) {
                $contact->team_id = $team->id;
                $contact->owner_id = $user->id;
                $contact->group_id = $groups->random()->id;
                $contact->save();

                $contact->tags()->sync(
                    $tags->random(rand(0, 3))->pluck('id')->all()
                );
            });
        }
    }
}
