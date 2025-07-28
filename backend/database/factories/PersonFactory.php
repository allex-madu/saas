<?php

namespace Database\Factories;

use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'nickname' => $this->faker->userName,
            'nif' => $this->faker->numerify('#########'),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->streetAddress,
            'reference' => $this->faker->sentence(3),
            'number' => $this->faker->buildingNumber,
            'zip_code' => $this->faker->postcode,
            'city_id' => 3531, // ou aleatório se tiver cidades
        ];
    }
}
