<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Book::class;

    public function definition()
    {
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->words(3, true),
            'desc' => $this->faker->text(200),
            'author' => $this->faker->name,
            'availability' => $this->faker->numberBetween(0, 1),
            'edition' => $this->faker->words(3, true),
            'count' => $this->faker->numberBetween(1, 100),
        ];
    }
}
