<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;

/**
 * Seed a large number of realistic contacts for load testing the app at
 * 1–5 lakh scale. Rows are tagged in `notes` so they can be removed with
 * --clean without touching real data.
 */
class GenerateTestContacts extends Command
{
    protected $signature = 'contacts:generate
        {count=100000 : Number of contacts to create}
        {--team= : Team id (defaults to the first team)}
        {--clean : Delete previously generated test contacts instead}';

    protected $description = 'Bulk-generate fake contacts for load testing (or --clean to remove them)';

    protected const MARKER = 'seeded-by:contacts:generate';

    public function handle(): int
    {
        if ($this->option('clean')) {
            $deleted = Contact::withTrashed()->where('notes', self::MARKER)->forceDelete();
            $this->info("Removed {$deleted} generated test contacts.");
            return self::SUCCESS;
        }

        $team = $this->option('team')
            ? Team::find($this->option('team'))
            : Team::first();

        if (! $team) {
            $this->error('No team found. Pass --team=<id>.');
            return self::FAILURE;
        }

        $ownerId = User::where('current_team_id', $team->id)->value('id') ?? User::value('id');
        $count   = max(1, (int) $this->argument('count'));

        $first  = ['Aarav', 'Ananya', 'Arjun', 'Diya', 'Ishaan', 'Kavya', 'Rohan', 'Priya', 'Vikram', 'Meera', 'Aditya', 'Sneha', 'Karthik', 'Nandini', 'Rahul', 'Pooja', 'Suresh', 'Lakshmi', 'Manoj', 'Divya'];
        $last   = ['Sharma', 'Patel', 'Reddy', 'Iyer', 'Khan', 'Nair', 'Gupta', 'Das', 'Mehta', 'Rao', 'Singh', 'Joshi', 'Kumar', 'Menon', 'Verma', 'Pillai', 'Chopra', 'Bose', 'Naidu', 'Mishra'];
        $cities = ['Chennai', 'Mumbai', 'Delhi', 'Bengaluru', 'Hyderabad', 'Pune', 'Kolkata', 'Coimbatore', 'Madurai', 'Kochi'];
        $firms  = ['Acme Corp', 'Globex', 'Initech', 'Umbrella Ltd', 'Wayne Enterprises', 'Stark Industries', 'Pied Piper', 'Hooli', 'Vandelay', 'Wonka Industries'];

        $this->info("Generating {$count} contacts for team #{$team->id} ({$team->name})…");
        $bar = $this->output->createProgressBar($count);
        $start = microtime(true);
        $now = now();

        for ($done = 0; $done < $count; $done += count($batch ?? [])) {
            $batch = [];
            $size = min(1000, $count - $done);
            for ($i = 0; $i < $size; $i++) {
                $n = $done + $i;
                $name  = $first[array_rand($first)] . ' ' . $last[array_rand($last)];
                $phone = '+91' . str_pad((string) random_int(6000000000, 9999999999), 10, '0', STR_PAD_LEFT);
                $batch[] = [
                    'team_id'         => $team->id,
                    'owner_id'        => $ownerId,
                    'group_id'        => null,
                    'name'            => $name,
                    'email'           => 'test' . $n . '.' . random_int(1000, 9999) . '@example.com',
                    'phone'           => $phone,
                    'phone_digits'    => substr($phone, 1),
                    'company'         => $firms[array_rand($firms)],
                    'city'            => $cities[array_rand($cities)],
                    'approval_status' => 'approved',
                    'notes'           => self::MARKER,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
            Contact::insert($batch);
            $bar->advance($size);
        }

        $bar->finish();
        $secs = round(microtime(true) - $start, 1);
        $this->newLine();
        $this->info("Done in {$secs}s. Remove later with: php artisan contacts:generate --clean");

        return self::SUCCESS;
    }
}
