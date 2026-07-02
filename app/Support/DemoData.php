<?php

namespace App\Support;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Reminder;
use App\Models\Tag;
use App\Models\Team;
use App\Models\User;

class DemoData
{
    /**
     * Seed a freshly-installed workspace with sample contacts, groups, tags,
     * and reminders so the buyer can click around immediately. Idempotent —
     * skips if the team already has contacts.
     */
    public static function seed(Team $team, User $user): array
    {
        $created = ['groups' => 0, 'tags' => 0, 'contacts' => 0, 'reminders' => 0];

        // Don't seed if the workspace already has contacts.
        // (Contact has no team global scope — plain team_id filter.)
        $existing = Contact::where('team_id', $team->id)->count();
        if ($existing > 0) {
            return $created;
        }

        // Groups
        $groups = collect([
            ['name' => 'Customers', 'color' => '#a855f7'],
            ['name' => 'Leads',     'color' => '#3b82f6'],
            ['name' => 'Partners',  'color' => '#10b981'],
            ['name' => 'Vendors',   'color' => '#f59e0b'],
        ])->map(function ($g) use ($team, &$created) {
            $row = Group::firstOrCreate(
                ['team_id' => $team->id, 'name' => $g['name']],
                ['color' => $g['color']]
            );
            if ($row->wasRecentlyCreated) $created['groups']++;
            return $row;
        });

        // Tags
        $tagNames = ['vip', 'follow-up', 'cold', 'hot', 'enterprise', 'self-serve', 'referral'];
        $tags = collect($tagNames)->map(function ($name) use ($team, &$created) {
            $row = Tag::firstOrCreate(
                ['team_id' => $team->id, 'slug' => $name],
                ['name' => ucfirst($name)]
            );
            if ($row->wasRecentlyCreated) $created['tags']++;
            return $row;
        });

        // Contacts — recognizable names so it feels real
        $sampleContacts = [
            ['Ada Lovelace',       'ada@analyticalengine.test',   '+15551112201', 'Analytical Engines Ltd', 'Founder & CEO',         'customer'],
            ['Grace Hopper',       'grace@nbcompiler.test',       '+15551112202', 'NBcompiler',             'CTO',                   'customer'],
            ['Alan Turing',        'alan@bombe.test',             '+15551112203', 'Bombe Cryptanalysis',    'Chief Cryptographer',   'lead'],
            ['Katherine Johnson',  'katherine@orbital.test',      '+15551112204', 'Orbital Mechanics Inc',  'Lead Mathematician',    'customer'],
            ['Margaret Hamilton',  'margaret@apollo.test',        '+15551112205', 'Apollo Guidance',        'Director of Software',  'customer'],
            ['Linus Torvalds',     'linus@kernel.test',           '+15551112206', 'Kernel Co.',             'Maintainer',            'partner'],
            ['Tim Berners-Lee',    'tim@cern.test',               '+15551112207', 'CERN Web Project',       'Inventor',              'partner'],
            ['Marie Curie',        'marie@radium.test',           '+15551112208', 'Radium Research',        'Director',              'vendor'],
            ['Nikola Tesla',       'nikola@alternating.test',     '+15551112209', 'Alternating Current Co', 'Chief Engineer',        'lead'],
            ['Hedy Lamarr',        'hedy@frequency.test',         '+15551112210', 'Frequency Hopping Labs', 'Inventor',              'lead'],
            ['Donald Knuth',       'don@taocp.test',              '+15551112211', 'TAOCP Press',            'Author',                'customer'],
            ['Barbara Liskov',     'barbara@substitution.test',   '+15551112212', 'Substitution Inc',       'Distinguished Engineer','customer'],
        ];

        $stages = ['lead', 'prospect', 'customer', 'partner', 'vendor'];
        foreach ($sampleContacts as $i => $row) {
            [$name, $email, $phone, $company, $title, $hint] = $row;
            $stage = in_array($hint, $stages, true) ? $hint : 'lead';

            $contact = Contact::create([
                'team_id' => $team->id,
                'owner_id' => $user->id,
                'group_id' => $groups->firstWhere('name', match ($stage) {
                    'lead', 'prospect' => 'Leads',
                    'customer'         => 'Customers',
                    'partner'          => 'Partners',
                    'vendor'           => 'Vendors',
                    default            => 'Leads',
                })?->id,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company' => $company,
                'job_title' => $title,
                'lifecycle_stage' => $stage,
                'notes' => 'Sample demo contact — feel free to delete.',
                'last_contacted_at' => now()->subDays(rand(1, 30)),
            ]);

            // Random 1-3 tags per contact
            $contact->tags()->sync($tags->random(rand(1, 3))->pluck('id')->all());
            $created['contacts']++;
        }

        // Reminders — a couple due today, one overdue, a couple upcoming
        $reminderContacts = Contact::where('team_id', $team->id)->inRandomOrder()->take(5)->get();
        $reminderTemplates = [
            ['title' => 'Follow up on intro call',        'offset' => -2],
            ['title' => 'Send proposal',                  'offset' => 0],
            ['title' => 'Check in after demo',            'offset' => 0],
            ['title' => 'Quarterly business review',      'offset' => 3],
            ['title' => 'Renewal conversation',           'offset' => 14],
        ];
        foreach ($reminderContacts as $i => $c) {
            $tpl = $reminderTemplates[$i] ?? $reminderTemplates[0];
            Reminder::create([
                'team_id' => $team->id,
                'contact_id' => $c->id,
                'user_id' => $user->id,
                'title' => $tpl['title'],
                'remind_at' => now()->addDays($tpl['offset'])->setTime(10, 0),
            ]);
            $created['reminders']++;
        }

        return $created;
    }
}
