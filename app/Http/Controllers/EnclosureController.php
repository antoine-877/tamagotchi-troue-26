<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEnclosureRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EnclosureController extends Controller
{
    public function show(Request $request, User $user): Response
    {
        return Inertia::render('enclosures/Show', [
            'owner' => $user->only('id', 'name', 'motto'),
            'isOwner' => $request->user()->is($user),
            'creatures' => $user->creatures()
                ->orderBy('name')
                ->get(['id', 'name', 'species', 'food', 'mood', 'energy']),
            'messages' => $user->receivedMessages()
                ->with('author:id,name')
                ->latest()
                ->get()
                ->map(fn (Message $message) => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'author' => $message->author->name,
                    'date' => $message->created_at->format('d/m/Y H:i'),
                ]),
        ]);
    }

    public function update(UpdateEnclosureRequest $request): RedirectResponse
    {
        $request->user()->update($request->all());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Enclos mis à jour.']);

        return to_route('enclosures.show', $request->user());
    }
}
