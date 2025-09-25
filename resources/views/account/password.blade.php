@extends('layouts.main')

@section('title', 'Alterar Senha')

@section('content')
<div class="col-md-6 offset-md-3">
    <h1>Alterar Senha</h1>

    @if(session('msg'))
        <p class="alert alert-success">{{ session('msg') }}</p>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('account.updatePassword') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="current_password">Senha Atual:</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Nova Senha:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Nova Senha:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <input type="submit" class="btn btn-sm btn-success" value="Atualizar Senha">
    </form>
</div>
@endsection
