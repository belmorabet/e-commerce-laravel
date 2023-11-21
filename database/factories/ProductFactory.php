<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'slug' => $this->faker->slug,
            'title' => $this->faker->sentence,
            'description' => '<p>' . implode('</p><p>', $this->faker->paragraphs(6)) . '</p>',
            'price' => $this->faker->randomFloat(2,0,99),
            'dimensions' => $this->faker->randomElement(['6.5 X 9.6 X 1.6 inches | 1.64 pounds',
                '8.2 X 9.2 X 1.1 inches | 2.5 pounds',
                '7.0 X 9.0 X 1.0 inches | 1.62 pounds',
                '6.1 X 9.2 X 0.8 inches | 1.32 pounds'
                ]),
            'type' => $this->faker->randomElement(['Hardcover', 'Paperback']),
        ];
    }
}

