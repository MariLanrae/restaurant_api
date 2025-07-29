<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;



/**
 * @method static create(array $all)
 * @method static where(string $string, mixed $role_id)
 * @method static findOrFail(Dish $dish)
 */
class Dish extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'dishes';

    protected $fillable = [
        'title',
        'file_id',
        'compound',
        'calories',
        'price',
        'category_id',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }
}
