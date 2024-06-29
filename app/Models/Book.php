<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\Relations\BelongsToMany;
	
	/**
	 * @property int    $id
	 * @property string $title
	 * @property string $subtitle
	 * @property string $publisher
	 * @property string $description
	 *
	 * @mixin Builder
	 */
	class Book extends Model {
		use HasFactory;
		
		function authors(): BelongsToMany {
			return $this->belongsToMany(Author::class);
		}
	}
