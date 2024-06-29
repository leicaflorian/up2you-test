<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	
	/**
	 * @property int    $id
	 * @property string $firstname
	 * @property string $lastname
	 *
	 * @mixin Builder
	 */
	class Author extends Model {
		use HasFactory;
		
		public $fillable = [
			'firstname',
			'lastname',
		];
		
		function books(): BelongsToMany {
			return $this->belongsToMany(Book::class);
		}
	}
