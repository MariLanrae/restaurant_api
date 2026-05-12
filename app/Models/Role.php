<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @method static findOrFail($id)
 * @method static where(string $string, mixed $role_id)
 * @method static create(array $all)
 */
class Role extends Model
{
    use HasFactory;

    public $table = 'roles';

    protected $fillable = ['name'];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function getRelationshipsForEagerLoading(): array
    {
        return ['user'];
    }
}
