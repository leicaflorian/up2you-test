<?php
	
	namespace Tests\Feature;
	
	use App\Models\Book;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class BookTest extends TestCase {
		private array $basic_headers = [
			'Accept' => 'application/json',
		];
		
		private array $basic_auth_headers = [
			'X-API-KEY' => 'c3e9f6e2-6b14-4a4e-b2d1-5ef5bb1cfc6a',
		];
		
		private array $basic_error_structure = [
			'message',
			"exception",
			"file",
			"line",
			"trace"
		];
		
		private array $basic_book_structure = [
			'id',
			'title',
			'subtitle',
			'publisher',
			'description',
			'created_at',
			'updated_at',
		];
		
		private function get_correct_headers(): array {
			return array_merge($this->basic_headers, $this->basic_auth_headers);
		}
		
		public function test_authorization(): void {
			$response = $this->withHeaders($this->basic_headers)->get('/api/authors');
			
			$response->assertStatus(403)
				->assertJsonStructure($this->basic_error_structure)
				->assertJson([
					"message" => "You are unauthorized. Please check your API key."
				]);
		}
		
		public function test_index(): void {
			$response = $this->withHeaders($this->get_correct_headers())->get('/api/books');
			
			$response->assertStatus(200)
				->assertJsonStructure([
					'current_page',
					'data' => [
						'*' => $this->basic_book_structure,
					],
					"next_page_url",
					"path",
					"per_page",
					"prev_page_url",
					"to",
					"total",
				]);
		}
		
		public function test_show(): void {
			$response = $this->withHeaders($this->get_correct_headers())->get('/api/books/1');
			
			$response->assertStatus(200)
				->assertJsonStructure($this->basic_book_structure);
		}
		
		public function test_show_fail(): void {
			$response = $this->withHeaders($this->get_correct_headers())->get('/api/books/99999999');
			
			$response->assertStatus(404)
				->assertJsonStructure($this->basic_error_structure);
		}
		
		public function test_store(): void {
			$response = $this->withHeaders($this->get_correct_headers())->post('/api/books', [
				'title' => '[test] - My book',
				'subtitle'    => 'Some random subtitle',
				'publisher'   => 'Some publisher',
				'description' => 'Some description',
				'authors'     => [1, 2]
			]);
			
			$response->assertCreated()
				->assertJsonStructure($this->basic_book_structure)
				->assertJson([
					'title'       => '[test] - My book',
					'subtitle'    => 'Some random subtitle',
					'publisher'   => 'Some publisher',
					'description' => 'Some description',
				]);
		}
		
		public function test_store_fail(): void {
			$response = $this->withHeaders($this->get_correct_headers())->post('/api/books', [
				'subtitle'    => 'Some random subtitle',
				'publisher'   => 'Some publisher',
				'description' => 'Some description',
			]);
			
			$response->assertStatus(422)
				->assertJson([
					"message" => "The title field is required. (and 1 more error)",
					"errors"  => [
						"title"   => [
							"The title field is required."
						],
						"authors" => [
							"The authors field is required."
						]
					]
				]);
		}
		
		public function test_update(): void {
			$last_book = Book::where("title", "[test] - My book")->latest()->first();
			
			$response = $this->withHeaders($this->get_correct_headers())->put("/api/books/$last_book->id", [
				'subtitle'  => 'This subtitle has been changed',
				'publisher' => 'Also a new publisher',
			]);
			
			$response->assertStatus(200)
				->assertJsonStructure($this->basic_book_structure)
				->assertJson([
					'title'       => '[test] - My book',
					'subtitle'    => 'This subtitle has been changed',
					'publisher'   => 'Also a new publisher',
					'description' => 'Some description',
				]);
		}
		
		public function test_update_fail(): void {
			$last_book = Book::where("title", "[test] - My book")->latest()->first();
			
			$response = $this->withHeaders($this->get_correct_headers())->put("/api/books/$last_book->id", [
				'title' => 22,
			]);
			
			$response->assertStatus(422)
				->assertJson([
					"message" => "The title field must be a string.",
					"errors"  => [
						"title" => [
							"The title field must be a string."
						]
					]
				]);
		}
		
		public function test_destroy(): void {
			$last_book = Book::where("title", "[test] - My book")->latest()->first();
			
			$response = $this->withHeaders($this->get_correct_headers())->delete("/api/books/$last_book->id");
			
			$response->assertNoContent();
		}
	}
