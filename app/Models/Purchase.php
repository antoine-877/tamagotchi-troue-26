<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property int $creature_id
 * @property int $quantity
 * @property float $total
 */
#[Fillable(['user_id', 'item_id', 'creature_id', 'quantity', 'total_cents'])]

class Purchase extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_cents' => 'integer',
        ];
    }
}
