<?php
	
	namespace Database\Factories;
	
	use App\Models\Book;
	use Illuminate\Database\Eloquent\Factories\Factory;
	
	/**
	 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
	 */
	class BookFactory extends Factory {
		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition(): array {
			return [
				"title"       => fake()->sentence(),
				"subtitle"    => fake()->sentence(12),
				"publisher"   => fake()->company(),
				"description" => fake()->realText(520),
			];
		}
	}
