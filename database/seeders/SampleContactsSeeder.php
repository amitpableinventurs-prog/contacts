<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Group;
use App\Models\Tag;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleContactsSeeder extends Seeder
{
    public function run(string $email = 'admin@example.com'): void
    {
        $targetEmail = $this->command?->option('email') ?? $email;

        $user = User::where('email', $targetEmail)->first();
        if (! $user) {
            $this->command->warn("User [{$targetEmail}] not found.");
            return;
        }

        $team = $user->currentTeam;

        foreach ([
            ['name' => 'Customers', 'color' => '#a855f7'],
            ['name' => 'Leads',     'color' => '#3b82f6'],
            ['name' => 'Partners',  'color' => '#10b981'],
            ['name' => 'Vendors',   'color' => '#f59e0b'],
        ] as $g) {
            Group::firstOrCreate(['team_id' => $team->id, 'name' => $g['name']], ['color' => $g['color']]);
        }

        foreach (['vip', 'follow-up', 'cold', 'hot', 'enterprise', 'self-serve', 'referral'] as $slug) {
            Tag::firstOrCreate(['team_id' => $team->id, 'slug' => $slug], ['name' => ucfirst($slug)]);
        }

        $groups = Group::where('team_id', $team->id)->pluck('id', 'name');
        $tags   = Tag::where('team_id', $team->id)->pluck('id', 'slug');

        $contacts = [
            [
                'name'            => 'Amit Joshi',
                'email'           => 'amit.joshi@outlook.com',
                'phone'           => '+919876543210',
                'company'         => 'Tata Consultancy Services',
                'job_title'       => 'Senior Software Engineer',
                'website'         => 'https://amitjoshi.dev',
                'address'         => '12 MG Road, Bengaluru, Karnataka 560001',
                'birthday'        => '1990-03-15',
                'lifecycle_stage' => 'customer',
                'linkedin'        => 'amitjoshi',
                'twitter'         => 'amit_joshi90',
                'notes'           => 'Key client contact for TCS account. Prefers email communication.',
                'rating'          => 5,
                'tag_slugs'       => ['vip', 'enterprise'],
                'group'           => 'Customers',
            ],
            [
                'name'            => 'Karan Mehta',
                'email'           => 'karan.mehta@gmail.com',
                'phone'           => '+919812345678',
                'company'         => 'Infosys Ltd',
                'job_title'       => 'Product Manager',
                'website'         => null,
                'address'         => '45 Hiranandani Gardens, Powai, Mumbai 400076',
                'birthday'        => '1988-07-22',
                'lifecycle_stage' => 'lead',
                'linkedin'        => 'karanmehta',
                'facebook'        => 'karan.mehta.88',
                'notes'           => 'Interested in enterprise plan. Follow up after Q3.',
                'rating'          => 2,
                'tag_slugs'       => ['follow-up', 'hot'],
                'group'           => 'Leads',
            ],
            [
                'name'            => 'Priya Verma',
                'email'           => 'priya.verma@yahoo.com',
                'phone'           => '+919923456789',
                'company'         => 'Wipro Technologies',
                'job_title'       => 'HR Business Partner',
                'website'         => null,
                'address'         => 'B-7 Sector 62, Noida, Uttar Pradesh 201309',
                'birthday'        => '1992-11-05',
                'lifecycle_stage' => 'customer',
                'linkedin'        => 'priya-verma-hr',
                'notes'           => 'Primary HR contact for Wipro partnership.',
                'rating'          => 4,
                'tag_slugs'       => ['vip', 'referral'],
                'group'           => 'Partners',
            ],
            [
                'name'            => 'Rahul Sharma',
                'email'           => 'rahul.sharma@gmail.com',
                'phone'           => '+919734567890',
                'company'         => 'HCL Technologies',
                'job_title'       => 'Business Development Manager',
                'website'         => 'https://hcltech.com',
                'address'         => 'Plot 3C, Sector 126, Noida, Uttar Pradesh 201304',
                'birthday'        => '1985-01-30',
                'lifecycle_stage' => 'customer',
                'linkedin'        => 'rahulsharma-hcl',
                'twitter'         => 'rahul_hcl',
                'notes'           => 'Strong upsell opportunity in Q4. Referred by Priya Verma.',
                'rating'          => 4,
                'tag_slugs'       => ['enterprise', 'hot'],
                'group'           => 'Customers',
            ],
            [
                'name'            => 'Sneha Patel',
                'email'           => 'sneha.patel@hotmail.com',
                'phone'           => '+919645678901',
                'company'         => 'Reliance Jio',
                'job_title'       => 'Marketing Manager',
                'website'         => 'https://jio.com',
                'address'         => 'Maker Chambers IV, Nariman Point, Mumbai 400021',
                'birthday'        => '1994-06-18',
                'lifecycle_stage' => 'prospect',
                'facebook'        => 'sneha.patel.official',
                'linkedin'        => 'snehapatel-jio',
                'notes'           => 'Met at MarTech Summit 2026. Very interested in bulk email features.',
                'rating'          => 3,
                'tag_slugs'       => ['follow-up', 'self-serve'],
                'group'           => 'Leads',
            ],
            [
                'name'            => 'Vikram Singh',
                'email'           => 'vikram.singh@rediffmail.com',
                'phone'           => '+919556789012',
                'company'         => 'Mahindra & Mahindra',
                'job_title'       => 'VP Operations',
                'website'         => 'https://mahindra.com',
                'address'         => 'Gateway Building, Apollo Bunder, Mumbai 400001',
                'birthday'        => '1979-09-12',
                'lifecycle_stage' => 'partner',
                'linkedin'        => 'vikramsingh-mahindra',
                'twitter'         => 'vikram_m_singh',
                'notes'           => 'Long-term partner. Quarterly business review scheduled.',
                'rating'          => 5,
                'tag_slugs'       => ['vip', 'enterprise', 'referral'],
                'group'           => 'Partners',
            ],
            [
                'name'            => 'Ananya Krishnan',
                'email'           => 'ananya.krishnan@gmail.com',
                'phone'           => '+919467890123',
                'company'         => 'Zoho Corporation',
                'job_title'       => 'Customer Success Lead',
                'website'         => 'https://zoho.com',
                'address'         => 'Estancia IT Park, GST Road, Chennai 603202',
                'birthday'        => '1996-04-25',
                'lifecycle_stage' => 'customer',
                'linkedin'        => 'ananyakrishnan',
                'notes'           => 'Champions our product internally at Zoho. Great advocate.',
                'rating'          => 5,
                'tag_slugs'       => ['vip', 'hot'],
                'group'           => 'Customers',
            ],
            [
                'name'            => 'Rohit Agarwal',
                'email'           => 'rohit.agarwal@outlook.com',
                'phone'           => '+919378901234',
                'company'         => 'Byju\'s',
                'job_title'       => 'Sales Director',
                'website'         => null,
                'address'         => 'IBC Knowledge Park, Bannerghatta Rd, Bengaluru 560029',
                'birthday'        => '1987-12-03',
                'lifecycle_stage' => 'lead',
                'linkedin'        => 'rohitagarwal-sales',
                'notes'           => 'Cold lead. Reached out via LinkedIn. Schedule demo call.',
                'rating'          => 1,
                'tag_slugs'       => ['cold', 'follow-up'],
                'group'           => 'Leads',
            ],
            [
                'name'            => 'Meera Nair',
                'email'           => 'meera.nair@gmail.com',
                'phone'           => '+919289012345',
                'company'         => 'Flipkart',
                'job_title'       => 'Procurement Head',
                'website'         => 'https://flipkart.com',
                'address'         => 'Embassy Tech Village, Outer Ring Rd, Bengaluru 560103',
                'birthday'        => '1991-08-14',
                'lifecycle_stage' => 'vendor',
                'linkedin'        => 'meeranair-flipkart',
                'twitter'         => 'meera_nair',
                'notes'           => 'Manages vendor onboarding at Flipkart. Good referral source.',
                'rating'          => 3,
                'tag_slugs'       => ['referral', 'self-serve'],
                'group'           => 'Vendors',
            ],
            [
                'name'            => 'Arjun Kapoor',
                'email'           => 'arjun.kapoor@ymail.com',
                'phone'           => '+919190123456',
                'company'         => 'Paytm',
                'job_title'       => 'CTO',
                'website'         => 'https://paytm.com',
                'address'         => 'B-121 Sector 5, Noida, Uttar Pradesh 201301',
                'birthday'        => '1982-02-28',
                'lifecycle_stage' => 'customer',
                'linkedin'        => 'arjunkapoor-cto',
                'twitter'         => 'arjun_k_tech',
                'facebook'        => 'arjun.kapoor.cto',
                'notes'           => 'Decision maker. Signed enterprise contract last year. Renews in October.',
                'rating'          => 5,
                'tag_slugs'       => ['vip', 'enterprise'],
                'group'           => 'Customers',
            ],
            [
                'name'            => 'Divya Reddy',
                'email'           => 'divya.reddy@gmail.com',
                'phone'           => '+919001234567',
                'company'         => 'Tech Mahindra',
                'job_title'       => 'Project Coordinator',
                'website'         => null,
                'address'         => 'Plot No 1, Software Units Layout, Hyderabad 500081',
                'birthday'        => '1995-10-09',
                'lifecycle_stage' => 'prospect',
                'linkedin'        => 'divyareddy',
                'notes'           => 'Interested in team collaboration features. Needs approval from VP.',
                'rating'          => 2,
                'tag_slugs'       => ['cold'],
                'group'           => 'Leads',
            ],
            [
                'name'            => 'Sanjay Gupta',
                'email'           => 'sanjay.gupta@businessmail.in',
                'phone'           => '+919912345601',
                'company'         => 'Asian Paints',
                'job_title'       => 'Regional Sales Manager',
                'website'         => 'https://asianpaints.com',
                'address'         => '6A Shantinagar, Santacruz East, Mumbai 400055',
                'birthday'        => '1976-05-20',
                'lifecycle_stage' => 'vendor',
                'linkedin'        => 'sanjaygupta-sales',
                'notes'           => 'Supply chain vendor. Annual contract due in February.',
                'rating'          => 3,
                'tag_slugs'       => ['self-serve'],
                'group'           => 'Vendors',
            ],
        ];

        foreach ($contacts as $data) {
            $tagSlugs = $data['tag_slugs'] ?? [];
            $groupName = $data['group'] ?? null;
            unset($data['tag_slugs'], $data['group']);

            if (Contact::where('team_id', $team->id)->where('email', $data['email'])->exists()) {
                continue;
            }

            $contact = Contact::create(array_merge($data, [
                'team_id'         => $team->id,
                'owner_id'        => $user->id,
                'group_id'        => $groupName && isset($groups[$groupName]) ? $groups[$groupName] : null,
                'approval_status' => 'approved',
                'approved_by'     => $user->id,
                'approved_at'     => now(),
                'last_contacted_at' => now()->subDays(rand(1, 60)),
            ]));

            $tagIds = collect($tagSlugs)
                ->filter(fn ($s) => isset($tags[$s]))
                ->map(fn ($s) => $tags[$s])
                ->all();

            if ($tagIds) {
                $contact->tags()->sync($tagIds);
            }
        }

        if ($this->command) {
            $this->command->info('12 sample contacts inserted successfully.');
        }
    }
}
