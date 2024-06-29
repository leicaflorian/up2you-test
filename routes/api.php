<?php
	
	use App\Http\Middleware\ApiKeyMiddleware;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Route;
	
/*	Route::get('/user', function (Request $request) {
		return $request->user();
	})->middleware('auth:sanctum');*/
	
	Route::middleware(ApiKeyMiddleware::class)
		->group(function () {
		Route::resource('authors', \App\Http\Controllers\AuthorController::class)->except(['create', 'edit']);
		Route::resource('books', \App\Http\Controllers\BookController::class)->except(['create', 'edit']);
	});
	
