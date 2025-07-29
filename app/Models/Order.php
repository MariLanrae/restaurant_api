<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $all)
 */
class Order extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'orders';

    protected $fillable = [
        'user_id',
        'status',
        'number',
        'creation_date',
        'closing_date',
        'total_price',
        'dishes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class)->withPivot('quantity');
    }
}
