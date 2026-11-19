<?php

namespace App\Support;

class Money
{
    /**
     * Convertit un montant en centimes en euros.
     */
    public static function format(int $cents): string
    {
        return number_format($cents / 100, 2, ',', ' ') . ' €';
    }
}
