<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FavoriteProduct
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\User|null $user
 */
class FavoriteProduct extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Returns the number of products added to favorites from the authenticated user
    public function numberOfFavorites(): int
    {
        $count = auth()->user()?->favorites->count() ?? 0;

        return ($count);
    }
}
