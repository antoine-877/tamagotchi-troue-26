<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreatureRequest;
use App\Http\Requests\UpdateCreatureRequest;
use App\Models\Creature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CreatureController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('creatures/Index', [
            'creatures' => $request->user()->creatures()
                ->orderBy('name')
                ->get(['id', 'name', 'species', 'food', 'mood', 'energy']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('creatures/Create', [
            'species' => Creature::SPECIES,
        ]);
    }

    public function store(StoreCreatureRequest $request): RedirectResponse
    {
        $request->user()->creatures()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Créature adoptée.']);

        return to_route('dashboard');
    }

    public function edit(Creature $creature): Response
    {
        return Inertia::render('creatures/Edit', [
            'creature' => $creature->only('id', 'name', 'species', 'food', 'mood', 'energy'),
        ]);
    }

    public function update(UpdateCreatureRequest $request, Creature $creature): RedirectResponse
    {
        $creature->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Créature renommée.']);

        return to_route('creatures.edit', $creature);
    }

    public function destroy(Creature $creature): RedirectResponse
    {
        $creature->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Créature relâchée.']);

        return to_route('dashboard');
    }

    public function play(Creature $creature): RedirectResponse
    {
        $creature->update([
            'mood' => min(100, $creature->mood + 15),
            'food' => max(0, $creature->food - 10),
            'energy' => max(0, $creature->energy - 10),
        ]);

        return back();
    }
}
