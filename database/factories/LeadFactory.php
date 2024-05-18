<?php

namespace Database\Factories;

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
    public function definition(): array
    {

        $slug = $this->faker->randomElement(array('ghost-writing-offers', 'book-publishing-offers', 'book-writing-offers'));
        $ref = $this->faker->randomElement(array('', 'https://www.google.com'));
        $email = $this->faker->unique()->safeEmail;
        $phone = $this->faker->phoneNumber;
        $name = $this->faker->name;
        $site = 'https://www.excelbookwriting.com';
        $page = $site . '/' . $slug;
        $details = [
            'formname' => $this->faker->sentence(1),
            'current_page' =>  $page,
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
                'params' => [
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
            'created_at' => Carbon::now()->subMinutes(rand(0, 1440)),
            'updated_at' => Carbon::now()->subMinutes(rand(0, 1440)),
        ];
    }
}
