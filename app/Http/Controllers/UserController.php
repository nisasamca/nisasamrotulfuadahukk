<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->role;

        $users = User::when($role, function ($q) use ($role) {
            $q->where('role', $role);
        })->get();

        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ]);

        $prefix = substr($request->email, 0, 4);
        $lastUser = User::latest()->first();
        $number = $lastUser ? $lastUser->id + 1 : 1;
        $plainPassword = $prefix . '123';

        User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($plainPassword),
            'is_default_password' => true,
        ]);

        return redirect()->back()
        ->with('success', 'User berhasil ditambahkan')
        ->with('generated_password', $plainPassword);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
        'nama' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required',
        'password' => 'nullable|min:6', 
        ]);

        $data = [
        'name' => $request->nama,
        'email' => $request->email,
        'role' => $request->role,
        ];


        if ($request->filled('password')) {
        $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'User berhasil dihapus');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $prefix = substr($user->email, 0, 4);
        $plainPassword = $prefix . '123';

        $user->update([
            'password' => bcrypt($plainPassword),
            'is_default_password' => true,
        ]);

        return back()->with('reset_password', $plainPassword);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profile berhasil diupdate');
    }
}
