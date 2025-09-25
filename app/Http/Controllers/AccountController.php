<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // Página principal da conta
    public function index()
    {
        $user = auth()->user();
        return view('account.index', compact('user'));
    }

    // Form de edição de dados
    public function edit()
    {
        $user = auth()->user();
        return view('account.edit', compact('user'));
    }

    // Atualiza os dados do usuário
    public function update(Request $request)
    {
        $user = auth()->user();
        $user->update($request->only('name', 'email'));
        return redirect()->route('account.index')->with('msg', 'Dados atualizados com sucesso!');
    }

    // Form de alteração de senha
    public function editPassword()
    {
        return view('account.password');
    }

    // Atualiza a senha
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = auth()->user();

        // Verifica se a senha atual está correta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.index')->with('msg', 'Senha atualizada com sucesso!');
    }
}
