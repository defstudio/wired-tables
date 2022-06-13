<?php

use Illuminate\Database\Eloquent\Factories\Factory;

class RoulotteFactory extends Factory
{
    protected $model = Roulotte::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
