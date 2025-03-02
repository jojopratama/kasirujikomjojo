<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
      $title = 'Pengguna';
      $subtitle = 'Index';
      $users = User::select('id', 'name', 'email', 'role', 'created_at')->get();
      return view('admin.user.index', compact('title', 'subtitle', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      $title = 'Pengguna';
      $subtitle = 'Create';
      return view('admin.user.create', compact('title', 'subtitle'));
    }

    public function store(Request $request)
    {
      $validate = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users,email',
        'password' => 'required|confirmed|string|min:8',
        'role' => 'required|in:admin,petugas',
      ], [
        'name.required' => 'Kolom nama lengkap harus diisi.',
        'required' => 'Kolom :attribute harus diisi.',
        'email.unique' => 'Email sudah digunakan, silahkan gunakan alamat email lain.',
        'confirmed' => 'Password dan Konfirmasi Password tidak cocok.',
      ]);

      $validate['password'] = bcrypt($validate['password']);

      $simpan = User::create($validate);

      if ($simpan) {
        return response()->json(['status' => 200, 'message' => 'User Berhasil Ditambahkan']);
      } else {
        return response()->json(['status' => 500, 'message' => 'User Gagal Ditambahkan']);
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      $user = User::findOrFail($id);
      $title = 'Pengguna';
      $subtitle = 'Edit';
      return view('admin.user.edit', compact('title', 'subtitle', 'user'));
    }

    public function update(Request $request, User $user)
    {
      $validate = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|confirmed|string|min:8',
        'role' => 'required|in:admin,petugas',
      ],[
        'name.required' => 'Kolom nama lengkap harus diisi.',
        'required' => 'Kolom :attribute harus diisi.',
        'email.unique' => 'Email sudah digunakan, silahkan gunakan alamat email lain.',
        'confirmed' => 'Password dan Konfirmasi Password tidak cocok.',
      ]);

      $simpan = $user->update([
        'name' => $validate['name'],
        'email' => $validate['email'],
        'role' => $validate['role'],
        'password' => $validate['password'] ? bcrypt($validate['password']) : $user->password
      ]);

      if ($simpan) {
        return response()->json(['status' => 200, 'message' => 'User Berhasil Diubah']);
      } else {
        return response()->json(['status' => 500, 'message' => 'User Gagal Diubah']);
      }
    }

    public function destroy(string $id)
    {
      try {

        $currentUserId = Auth::id();

        if ($currentUserId == $id) {
          return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
      } catch (\Exception $e) {
        return redirect()->route('user.index')->with('error', 'User gagal dihapus');
      }
    }

    public function login(){
        return view('auth.login');
    }

    public function register(){
        return view('auth.register');
    }

    public function registerStore(Request $request){
        $validate = $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|same:password_confirmation',
        ]);

        $validate['password'] = bcrypt($validate['password']);

        $simpan = User::create($validate);
        if ($simpan) {
            return redirect()->route('login')->with('success', 'Registrasi Berhasil');
        } else {
            return redirect()->route('register ')->with('error', 'Registrasi Gagal');
        }
    }

    public function loginCheck(Request $request) {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validate)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        } else {
            return back()->with('error', 'Login Gagal');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout Berhasil');
    }
}
