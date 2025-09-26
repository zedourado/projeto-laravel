<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;

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
            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $event->image = $imageName;
        }

        $event->user_id = $user->id;
        $event->save();

        // Notificações para todos os usuários exceto o criador
        $users = User::where('id', '!=', $user->id)->get();
        foreach ($users as $u) {
            Notification::create([
                'user_id' => $u->id,
                'title' => 'Novo Evento',
                'message' => "O evento '{$event->title}' foi adicionado ao sistema."
            ]);
        }

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

        if ($user->role !== 'admin' && $user->id !== $event->user_id) {
            abort(403, 'Acesso negado');
        }

        // Notificações para participantes exceto o dono
        $participants = $event->users()->where('id', '!=', $user->id)->get();
        foreach ($participants as $participant) {
            Notification::create([
                'user_id' => $participant->id,
                'title' => 'Evento Cancelado',
                'message' => "O evento '{$event->title}' foi excluído."
            ]);
        }

        // Remover relação de participantes
        $event->users()->detach();

        $event->delete();
        return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $event = Event::findOrFail($id);

        if ($user->role !== 'admin' && $user->id !== $event->user_id) {
            abort(403, 'Acesso negado');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $event = Event::findOrFail($request->id);

        if ($user->role !== 'admin' && $user->id !== $event->user_id) {
            abort(403, 'Acesso negado');
        }

        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime('now')) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }

        $event->update($data);

        // Notificações para participantes exceto o dono
        $participants = $event->users()->where('id', '!=', $user->id)->get();
        foreach ($participants as $participant) {
            Notification::create([
                'user_id' => $participant->id,
                'title' => 'Evento Atualizado',
                'message' => "O evento '{$event->title}' foi atualizado."
            ]);
        }

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinEvent($id)
    {
        $user = auth()->user();
        $user->eventsAsParticipant()->attach($id);
        $event = Event::findOrFail($id);

        Notification::create([
            'user_id' => $event->user_id,
            'title' => 'Nova Inscrição',
            'message' => "{$user->name} se inscreveu no seu evento '{$event->title}'."
        ]);

        return redirect('/dashboard')
            ->with('msg', 'Sua presença está confirmada no evento: ' . $event->title);
    }

    public function leaveEvent($id)
    {
        $user = auth()->user();
        $user->eventsAsParticipant()->detach($id);
        $event = Event::findOrFail($id);

        Notification::create([
            'user_id' => $event->user_id,
            'title' => 'Saiu do Evento',
            'message' => "{$user->name} removeu a sua inscrição do evento '{$event->title}'."
        ]);

        return redirect('/dashboard')->with('msg', 'Você saiu com sucesso do evento: ' . $event->title);
    }
}
