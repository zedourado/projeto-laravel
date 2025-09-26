@extends('layouts.main')

@section('title', 'Editar Conta')

@section('content')
<div class="col-md-6 offset-md-3">
    <h1>Editar Conta</h1>

    <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}">
        </div>

        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}">
        </div>

        <div class="form-group">
            <label for="profile_image">Foto de Perfil:</label>
            <input type="file" name="profile_image" id="profile_image" class="form-control">
        </div>

        @if($user->profile_image)
            <p>Foto atual:</p>
            <img src="/img/profiles/{{ $user->profile_image }}" alt="Foto do Usuário" 
                 style="width:100px; height:100px; object-fit:cover; border-radius:50%;">
        @endif

        <input type="submit" class="btn btn-primary mt-3" value="Salvar Alterações">
    </form>
</div>
@endsection
