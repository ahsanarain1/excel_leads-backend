<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Location;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $campaign = Campaign::inRandomOrder()->first(); // or you can use `where('domain', 'excel.com')` for specific campaigns
        return [
            'campaign_id' => $campaign->id, // References the Campaign factory
            'assigned_to' => null, // Can be set later or you can add a user factory if needed
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'description' => $this->faker->text(),
            'is_read' => $this->faker->boolean(),
            'is_hidden' => $this->faker->boolean(),
        ];
    }
    public function desfinition(): array
    {

        $slug = $this->faker->randomElement(array('ghost-writing-offers', 'book-publishing-offers', 'book-writing-offers'));
        $ref = $this->faker->randomElement(array('', 'https://www.google.com'));
        $email = $this->faker->unique()->safeEmail;
        $phone = $this->faker->phoneNumber;
        $name = $this->faker->name;
        $site = 'https://www.excelbookwriting.com';
        $page = $site . '/' . $slug;
        $details = [
            'lead_from' => $page,
            'formname' => $this->faker->sentence(1),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'u_ip' => $this->faker->ipv4,
            'u_city' => $this->faker->city,
            'u_region' => $this->faker->state,
            'u_country' => $this->faker->country,
            'nested_array' => [
                $this->faker->word,
                $this->faker->numberBetween(1, 100),
                // Add more key-value pairs as needed
            ],
            'params' => [
                'page' => $slug,
                'google' => [
                    'gad_source' => "1",
                    'gclid' => "Cj0KCQjw3ZayBhD",
                    'keyword' => $slug,
                ],
                'sitename' => "excelbookwriting.com",
            ]
        ];
        if (!empty($ref)) {
            $details['referring_page'] = $ref;
        }
        return [
            'lead_from' => $page,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'details' => $details,
            'is_read' => $this->faker->boolean,
            'is_hidden' => $this->faker->boolean,
            'created_at' =>  $this->faker->dateTimeBetween('-12 month', 'now'),

        ];
    }
}
