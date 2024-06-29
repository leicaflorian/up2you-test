<?php
	
	namespace Tests\Feature;
	
	use Tests\TestCase;
	
	class AuthorTest extends TestCase {
		
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
		
		private array $basic_author_structure = [
			'id',
			'firstname',
			'lastname',
			'created_at',
			'updated_at',
		];
		
		
		public function test_index(): void {
			$response = $this->get('/api/authors');
			
			$response->assertStatus(200)
				->assertJsonStructure([
					'current_page',
					'data' => [
						'*' => $this->basic_author_structure,
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
			$response = $this->withHeaders($this->basic_headers)->get('/api/authors/1');
			
			$response->assertStatus(200)
				->assertJsonStructure($this->basic_author_structure);
		}
		
		public function test_show_fail(): void {
			$response = $this->withHeaders($this->basic_headers)->get('/api/authors/99999999');
			
			$response->assertStatus(404)
				->assertJsonStructure($this->basic_error_structure);
		}
		
		public function test_store(): void {
			$response = $this->withHeaders($this->basic_headers)->post('/api/authors', [
				'firstname' => 'John',
				'lastname'  => 'Doe',
			]);
			
			$response->assertCreated()
				->assertJsonStructure($this->basic_author_structure);
		}
		
		public function test_store_fail(): void {
			$response = $this->withHeaders($this->basic_headers)->post('/api/authors', [
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
	}
