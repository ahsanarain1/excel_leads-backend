<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Lead;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Location;
use App\Models\LeadDetail;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Faker\Factory as Faker; // Add this to import Faker


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);
        $manager = User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@manager.com',
            'password' => bcrypt('manager'),
        ]);
        $seller = User::factory()->create([
            'name' => 'Seller',
            'email' => 'seller@seller.com',
            'password' => bcrypt('seller'),
        ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            // LeadTableSeeder::class,
        ]);

        // Assign admin role to the test user
        $admin->assignRole('admin');
        $manager->assignRole('manager');
        $seller->assignRole('seller');

        // Create campaigns
        Campaign::factory()->create([
            'name' => 'Excel',
            'domain' => 'https://excel.com',
        ]);
        Campaign::factory()->create([
            'name' => 'Divine',
            'domain' => 'https://www.divine.com',
        ]);




        // Create leads
        Lead::factory()->count(10)->create()->each(function ($lead) {
            // Create Faker instance
            $faker = Faker::create();

            // Create a few locations
            Location::create([
                'lead_id' => $lead->id,
                'country' => $faker->country(),
                'city' => $faker->city(),
                'state' => $faker->state(),
                'ip_address' => $faker->ipv4(),
            ]);
            // Create multiple lead details for each lead with different keys

            // Access the campaign's domain
            $campaignDomain = $lead->campaign->domain;
            $leadDetails = [
                ['key' => 'form_name', 'value' => $faker->randomElement(['coupon form', 'banner form'])],
                ['key' => 'lead_from', 'value' => $campaignDomain . '/landing-page'],
                ['key' => 'referring_page', 'value' => 'www.google.com'],
                ['key' => 'gad_source', 'value' => $faker->word()],
                ['key' => 'gclid', 'value' => $faker->word()],
                ['key' => 'keyword', 'value' => $faker->word()],
            ];

            // Insert all details for this lead
            foreach ($leadDetails as $detail) {
                LeadDetail::create([
                    'lead_id' => $lead->id, // Set the lead_id dynamically
                    'key' => $detail['key'],
                    'value' => $detail['value'],
                ]);
            }
        });
    }
}
