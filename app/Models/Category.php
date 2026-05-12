<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $all)
 * @method static where(string $string, mixed $category_id)
 */
class Category extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'categories';

    protected $fillable = [
        'title',
        'file_id',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function dish(): HasMany
    {
        return $this->hasMany(Dish::class);
    }

    public static function getRelationshipsForEagerLoading(): array
    {
        return ['dish', 'file'];
    }
}
