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


        $details = [
            'source' => $this->faker->url,
            'form_name' => $this->faker->sentence(1),
            'from_page' =>  $this->faker->url,
            'from_website' =>  $this->faker->url,
            'user_ip' => $this->faker->ipv4,
            'user_city' => $this->faker->city,
            'user_country' => $this->faker->country,
            'user_region' => $this->faker->country,
            'nested_array' => [
                $this->faker->word,
                $this->faker->numberBetween(1, 100),
                // Add more key-value pairs as needed
            ],
        ];
        return [
            'lead_from' => $this->faker->url,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'details' => $details,
            'is_read' => $this->faker->boolean,
            'is_hidden' => $this->faker->boolean,
            'created_at' => Carbon::now()->subMinutes(rand(0, 1440)),
            'updated_at' => Carbon::now()->subMinutes(rand(0, 1440)),
        ];
    }
}
