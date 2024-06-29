<?php
	
	namespace Tests\Feature;
	
	use App\Models\Author;
	use Tests\TestCase;
	
	class AuthorTest extends TestCase {
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
		
		private array $basic_author_structure = [
			'id',
			'firstname',
			'lastname',
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
			$response = $this->withHeaders($this->get_correct_headers())->get('/api/authors');
			
			$response->assertStatus(200)
				->assertJsonStructure([
					'current_page',
					'data' => [
						'*' => array_merge($this->basic_author_structure, [
							"books_count"
						]),
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
			$response = $this->withHeaders($this->get_correct_headers())->get('/api/authors/1');
			
			$response->assertStatus(200)
				->assertJsonStructure(array_merge($this->basic_author_structure,
					[
						"books" => [
							"*" => [
								"id",
								"title",
								"subtitle",
								"publisher",
								"description",
								"created_at",
								"updated_at",
							]
						]
					]
				));
		}
		
		public function test_show_fail(): void {
			$response = $this->withHeaders($this->get_correct_headers())->get('/api/authors/99999999');
			
			$response->assertStatus(404)
				->assertJsonStructure($this->basic_error_structure);
		}
		
		public function test_store(): void {
			$response = $this->withHeaders($this->get_correct_headers())->post('/api/authors', [
				'firstname' => '[test] - John',
				'lastname'  => 'Doe',
			]);
			
			$response->assertCreated()
				->assertJsonStructure($this->basic_author_structure);
		}
		
		public function test_store_fail(): void {
			$response = $this->withHeaders($this->get_correct_headers())->post('/api/authors', [
				'firstname' => 'John',
			]);
			
			$response->assertStatus(422)
				->assertJson([
					"message" => "The lastname field is required.",
					"errors"  => [
						"lastname" => [
							"The lastname field is required."
						]
					]
				]);
		}
		
		public function test_update(): void {
			$last_author = Author::where("firstname", "[test] - John")->latest()->first();
			
			$response = $this->withHeaders($this->get_correct_headers())->put("/api/authors/$last_author->id", [
				'lastname' => 'Verdi',
			]);
			
			$response->assertStatus(200)
				->assertJsonStructure($this->basic_author_structure)
				->assertJson([
					'firstname' => '[test] - John',
					'lastname'  => 'Verdi',
				]);
		}
		
		public function test_update_fail(): void {
			$last_author = Author::where("firstname", "[test] - John")->latest()->first();
			
			$response = $this->withHeaders($this->get_correct_headers())->put("/api/authors/$last_author->id", [
				'firstname' => 22,
			]);
			
			$response->assertStatus(422)
				->assertJson([
					"message" => "The firstname field must be a string.",
					"errors"  => [
						"firstname" => [
							"The firstname field must be a string."
						]
					]
				]);
		}
		
		public function test_destroy(): void {
			$last_author = Author::where("firstname", "[test] - John")->latest()->first();
			
			$response = $this->withHeaders($this->get_correct_headers())->delete("/api/authors/$last_author->id");
			
			$response->assertNoContent();
		}
	}
