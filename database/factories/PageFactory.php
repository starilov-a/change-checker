<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'site_id' => rand(1,5),
            'url' => $this->faker->url(),
            'size' => rand(15, 100000)
        ];
    }
}
