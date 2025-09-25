<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    public function index()
    {
        $search = request('search');

        $events = $search
            ? Event::where('title', 'like', "%{$search}%")->get()
            : Event::all();

        return view('welcome', ['events' => $events, 'search' => $search]);
    }

    // Apenas admin pode acessar
    public function create()
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Acesso negado');
        }

        return view('events.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'admin') {
            abort(403, 'Acesso negado');
        }

        $event = new Event;
        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName().strtotime('now')) . "." .$extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $event->image = $imageName;
        }

        $event->user_id = $user->id;
        $event->save();

        return redirect('/')->with('msg', 'Evento Cadastrado com Sucesso!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        $user = auth()->user();
        $hasUserJoined = $user ? $user->eventsAsParticipant->contains($id) : false;
        $eventOwner = User::find($event->user_id)->toArray();

        return view('events.show', [
            'event' => $event,
            'eventOwner' => $eventOwner,
            'hasUserJoined' => $hasUserJoined
        ]);
    }

    public function dashboard()
    {
        $user = auth()->user();
        $events = $user->events;
        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', [
            'events' => $events,
            'eventsasparticipant' => $eventsAsParticipant
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $event = Event::findOrFail($id);

        // Apenas admin ou criador do evento pode deletar
        if ($user->role !== 'admin' && $user->id !== $event->user_id) {
            abort(403, 'Acesso negado');
        }

        $event->delete();
        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $event = Event::findOrFail($id);

        // Apenas admin ou criador do evento pode editar
        if ($user->role !== 'admin' && $user->id !== $event->user_id) {
            abort(403, 'Acesso negado');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $event = Event::findOrFail($request->id);

        // Apenas admin ou criador do evento pode atualizar
        if ($user->role !== 'admin' && $user->id !== $event->user_id) {
            abort(403, 'Acesso negado');
        }

        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName().strtotime('now')) . "." .$extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }

        $event->update($data);
        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinEvent($id)
    {
        $user = auth()->user();
        $user->eventsAsParticipant()->attach($id);
        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Sua presença está confirmada no evento: ' . $event->title);
    }

    public function leaveEvent($id)
    {
        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);
        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);
    }
}
