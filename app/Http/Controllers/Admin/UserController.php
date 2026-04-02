<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'full_name' => ['required', 'string', 'max:255'],
            'roles' => ['required', Rule::in(['admin', 'siswa', 'super_admin'])],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'kelas' => ['required_if:roles,siswa', 'nullable', 'string', 'max:255'],
        ]);

        User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'roles' => $request->roles,
            'password' => Hash::make($request->password),
            'kelas' => $request->roles === 'siswa' ? $request->kelas : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'full_name' => ['required', 'string', 'max:255'],
            'roles' => ['required', Rule::in(['admin', 'siswa', 'super_admin'])],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
            'kelas' => ['required_if:roles,siswa', 'nullable', 'string', 'max:255'],
        ]);

        $data = [
            'username' => $request->username,
            'full_name' => $request->full_name,
            'roles' => $request->roles,
            'kelas' => $request->roles === 'siswa' ? $request->kelas : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }
}
