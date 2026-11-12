<?php

namespace App\Models;

use Database\Factories\CreatureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $species
 * @property int $food
 * @property int $mood
 * @property int $energy
 */
#[Fillable(['name', 'species', 'food', 'mood', 'energy'])]
class Creature extends Model
{
    /** @use HasFactory<CreatureFactory> */
    use HasFactory;

    /**
     * The species a player can adopt.
     *
     * @var list<string>
     */
    public const array SPECIES = ['dragon', 'cat', 'octopus', 'fox', 'rabbit'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
