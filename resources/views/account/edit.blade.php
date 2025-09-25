@extends('layouts.main')

@section('title', 'Editar Conta')

@section('content')
<div class="col-md-6 offset-md-3">
    <h1>Editar Dados</h1>

    @if(session('msg'))
        <p class="alert alert-success">{{ session('msg') }}</p>
    @endif

    <form method="POST" action="{{ route('account.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <input type="submit" class="btn btn-sm btn-success" value="Salvar Alterações">
    </form>
</div>
@endsection
