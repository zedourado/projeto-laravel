@extends('layouts.main')

@section('title', 'Minha Conta')

@section('content')
<div class="col-md-6 offset-md-3">
    <h1>Minha Conta</h1>

    @if(session('msg'))
        <p class="alert alert-success">{{ session('msg') }}</p>
    @endif

    <p><strong>Nome:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>

    <a href="{{ route('account.edit') }}" class="btn btn-primary">Editar Dados</a>
    <a href="{{ route('account.password') }}" class="btn btn-warning">Alterar Senha</a>
</div>
@endsection
