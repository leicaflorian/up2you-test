<?php
	
	namespace Database\Seeders;
	
	use App\Models\Author;
	use App\Models\Book;
	use Illuminate\Database\Console\Seeds\WithoutModelEvents;
	use Illuminate\Database\Seeder;
	
	class BooksSeeder extends Seeder {
		/**
		 * Run the database seeds.
		 */
		public function run(): void {
			for ($i = 0; $i < 100; $i++) {
				Book::factory()
					->count(1)
					->hasAttached(Author::inRandomOrder()->limit(rand(1, 3))->get())
					->create();
			}
		}
	}
