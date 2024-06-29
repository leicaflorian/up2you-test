<?php
	
	namespace App\Http\Controllers;
	
	use App\Models\Author;
	use Illuminate\Contracts\Pagination\LengthAwarePaginator;
	use Illuminate\Database\Eloquent\Collection;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Http\Request;
	
	class AuthorController extends Controller {
		/**
		 * Display a listing of the resource.
		 */
		public function index(): LengthAwarePaginator {
			return Author::paginate(10);
		}
		
		/**
		 * Store a newly created resource in storage.
		 */
		public function store(Request $request): Model|Author {
			$payload = $request->validate([
				'firstname' => 'required|string|max:255',
				'lastname'  => 'required|string|max:255',
			]);
			
			return Author::create($payload);
		}
		
		/**
		 * Display the specified resource.
		 */
		public function show(Author $author): Model|Collection|Author|array|null {
			return $author;
		}
		
		/**
		 * Update the specified resource in storage.
		 */
		public function update(Request $request, Author $author): Author {
			$payload = $request->validate([
				'firstname' => 'string|max:255',
				'lastname'  => 'string|max:255',
			]);
			
			$author->update($payload);
			
			return $author;
		}
		
		/**
		 * Remove the specified resource from storage.
		 */
		public function destroy(Author $author): void {
			// detach all books from this author
			$author->books()->detach();
			
			// delete the author
			$author->delete();
		}
	}
