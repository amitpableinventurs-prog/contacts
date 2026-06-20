<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    public function definition(): array
    {
        $stages = ['lead', 'customer', 'partner', 'vendor', 'prospect'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->e164PhoneNumber(),
            'company' => fake()->company(),
            'job_title' => fake()->jobTitle(),
            'website' => fake()->url(),
            'address' => fake()->address(),
            'birthday' => fake()->optional(0.4)->dateTimeBetween('-60 years', '-20 years'),
            'lifecycle_stage' => fake()->randomElement($stages),
            'facebook' => fake()->optional(0.3)->userName(),
            'twitter' => fake()->optional(0.3)->userName(),
            'linkedin' => fake()->optional(0.5)->userName(),
            'notes' => fake()->optional(0.6)->paragraph(),
            'last_contacted_at' => fake()->optional(0.7)->dateTimeBetween('-90 days', 'now'),
        ];
    }
}
