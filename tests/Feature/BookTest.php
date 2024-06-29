<?php
	
	namespace Tests\Feature;
	
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class BookTest extends TestCase {
		private array $basic_headers = [
			'Accept' => 'application/json',
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
		
		
		public function test_index(): void {
			$response = $this->get('/api/books');
			
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
			$response = $this->withHeaders($this->basic_headers)->get('/api/books/1');
			
			$response->assertStatus(200)
				->assertJsonStructure($this->basic_book_structure);
		}
		
		public function test_show_fail(): void {
			$response = $this->withHeaders($this->basic_headers)->get('/api/books/99999999');
			
			$response->assertStatus(404)
				->assertJsonStructure($this->basic_error_structure);
		}
		
		public function test_store(): void {
			$response = $this->withHeaders($this->basic_headers)->post('/api/books', [
				'title'       => 'My book',
				'subtitle'    => 'Some random subtitle',
				'publisher'   => 'Some publisher',
				'description' => 'Some description',
				'authors'     => [1, 2]
			]);
			
			$response->assertCreated()
				->assertJsonStructure($this->basic_book_structure);
		}
		
		public function test_store_fail(): void {
			$response = $this->withHeaders($this->basic_headers)->post('/api/books', [
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
	}
