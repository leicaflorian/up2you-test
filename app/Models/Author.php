<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	
	/**
	 * @property int    $id
	 * @property string $firstname
	 * @property string $lastname
	 */
	class Author extends Model {
		use HasFactory;
		
		function books(): HasMany {
			return $this->hasMany(Book::class);
		}
	}
