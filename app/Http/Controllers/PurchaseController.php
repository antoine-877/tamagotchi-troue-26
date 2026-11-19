<?php

namespace App\Http\Controllers;

use App\Models\Creature;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    public function store(Request $request, Item $item): RedirectResponse
    {
        DB::transaction(function () use ($request, $item) {
            $user = $request->user();
            $validated = $request->validate([
                'quantity' => ['required', 'integer', 'min:1', 'max:10'],
                'creature_id' => ['required', 'integer', Rule::exists('creatures', 'id')->where('user_id', $request->user()->id)],
            ]);
            $creature = Creature::findOrFail($request->integer('creature_id'));

            $item = Item::lockForUpdate()->findOrFail($item->id);
            $user = User::lockForUpdate()->findOrFail($request->user()->id);
            $creature = Creature::lockForUpdate()->findOrFail($creature->id);

            $quantity = $validated['quantity'];

            $total = $item->price_cents * $quantity;

            if ($item->stock < $quantity) {
                throw ValidationException::withMessages(['quantity' => 'Il n\'en reste pas assez en stock.']);
            }

            if ($user->balance_cents < $total) {
                throw ValidationException::withMessages(['quantity' => 'Votre solde est insuffisant.']);
            }

            $item->update(['stock' => $item->stock - $quantity]);

            $user->balance_cents -= $total;
            $user->save();

            Purchase::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'creature_id' => $creature->id,
                'quantity' => $quantity,
                'total_cents' => $total,
            ]);

            $gauge = $item->effect;

            $creature->update([
                $gauge => min(100, $creature->getAttribute($gauge) + $item->effect_value * $quantity),
            ]);

            Inertia::flash('toast', ['type' => 'success', 'message' => "Achat effectué pour {$creature->name}."]);
        });
        return to_route('shop.index');
    }
}
