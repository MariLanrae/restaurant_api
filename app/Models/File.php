<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @method static create(array $array)
 */
class File extends Model
{
    use HasFactory;

    public $table = 'files';

    protected $fillable = ['path'];

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }

    public function dish(): HasOne
    {
        return $this->hasOne(Dish::class);
    }
}
