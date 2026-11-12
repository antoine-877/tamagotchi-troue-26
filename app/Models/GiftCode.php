<?php

namespace App\Models;

use Database\Factories\GiftCodeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property float $amount
 * @property int|null $redeemed_by
 * @property Carbon|null $redeemed_at
 */
#[Fillable(['code', 'amount', 'redeemed_by', 'redeemed_at'])]
class GiftCode extends Model
{
    /** @use HasFactory<GiftCodeFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'redeemed_at' => 'datetime',
        ];
    }
}
