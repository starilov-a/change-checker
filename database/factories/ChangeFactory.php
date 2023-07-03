<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChangeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'site_id' => rand(1,2),
            'url' => '/',
            'checked' => 0
        ];
    }
}
