<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\HasMany;
	
	/**
	 * @property int    $id
	 * @property string $title
	 * @property string $subtitle
	 * @property string $publisher
	 * @property string $description
	 */
	class Book extends Model {
		use HasFactory;
		
		function authors(): HasMany {
			return $this->hasMany(Author::class);
		}
	}
