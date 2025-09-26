<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    // Retorna todos os eventos
    public function index()
    {
        $events = Event::with('users')->get(); // Inclui usuários participantes
        return response()->json($events);
    }

    // Retorna um evento específico
    public function show($id)
    {
        $event = Event::with('users')->find($id);

        if (!$event) {
            return response()->json(['message' => 'Evento não encontrado'], 404);
        }

        return response()->json($event);
    }
}
