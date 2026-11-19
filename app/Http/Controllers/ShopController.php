<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Support\Money;

class ShopController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('q')->toString();

        $items = Item::query();

        if ($search !== '') {
            $items->where('name', 'like', "%{$search}%");
        }

        return Inertia::render('shop/Index', [
            'items' => $items->orderBy('name')->get()->map(fn(Item $item) => [
                ...$item->only('id', 'name', 'description', 'effect', 'effect_value', 'stock', 'price_cents'),
                'price' => Money::format($item->price_cents),
            ]),
            'search' => $search,
            'creatures' => $request->user()->creatures()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }
}
