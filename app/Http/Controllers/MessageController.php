<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function store(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        $user->receivedMessages()->create([
            'author_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Message envoyé.']);

        return to_route('enclosures.show', $user);
    }
}
