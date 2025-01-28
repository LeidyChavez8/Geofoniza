<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Data;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use RegistersUsers;


    public function index()
    {
        $users = User::all();
        return view('Users.index', compact('users'));
    }

    public function create()
    {
        return view('Users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return redirect()->route('Users.index')->with('success', 'Usuario creado con éxito.');
    }

    public function edit(User $user)
    {
        return view('Users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string|min:6',
            'rol' => 'required|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->has('password') ? bcrypt($request->password) : $user->password,
            'rol' => $request->rol,
        ]);

        return redirect()->route('Users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    public function destroy(User $user)
    {
        $data = Data::all();
        foreach ($data as $dataItem) {
            if($dataItem->id_user === $user->id){
                return redirect()->route('Users.index')->with('error', 'No se puede eliminar un usuario que tiene asignaciones.');
            }
            if($user->rol === 'admin'){
                return redirect()->route('Users.index')->with('error', 'No puede eliminar a un administrador.');
            }
        }

        $user->delete();
        return redirect()->route('Users.index')->with('success', 'Usuario eliminado con éxito.');
    }




}
