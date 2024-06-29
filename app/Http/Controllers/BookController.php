<?php
	
	namespace App\Http\Controllers;
	
	use App\Models\Book;
	use Illuminate\Contracts\Pagination\LengthAwarePaginator;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class BookController extends Controller {
		/**
		 * Display a listing of the resource.
		 */
		public function index(): LengthAwarePaginator {
			return Book::with("authors")
				->paginate(10);
		}
		
		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request): Model|Book {
			$payload = $request->validate([
				'title'       => 'required|string|max:255',
				'subtitle'    => 'required|string|max:255',
				'publisher'   => 'required|string|max:255',
				'description' => 'required|string',
				'authors'     => 'required|array|exists:authors,id',
			]);
			
			$book = Book::create($payload);
			$book->authors()->attach($payload['authors']);
			
			return $book->load("authors");
		}
		
		/**
		 * Display the specified resource.
		 */
		public function show(Book $book): Book {
			return $book->load("authors");
		}
		
		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, Book $book): Book {
			$payload = $request->validate([
				'title'       => 'string|max:255',
				'subtitle'    => 'string|max:255',
				'publisher'   => 'string|max:255',
				'description' => 'string',
				'authors'     => 'array|exists:authors,id',
			]);
			
			$book->update($payload);
			
			if (array_key_exists('authors', $payload)) {
				$book->authors()->sync($payload['authors']);
			}
			
			return $book->load("authors");
		}
		
		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(Book $book): Response {
			// first remove all authors from this book
			$book->authors()->detach();
			
			// then delete the book
			$book->delete();
			
			return response()->noContent();
		}
	}
