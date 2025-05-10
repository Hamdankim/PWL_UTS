<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Profil',
            'list' => ['Home', 'Profil']
        ];

        $page = (object) [
            'title' => 'Daftar profil yang terdaftar dalam sistem'
        ];

        $activeMenu = 'profile';
        $user = Auth::user(); // This should return a User model instance

        return view('profile.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'user' => $user
        ]);
    }

    public function show()
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(500, 'User not found');
        }

        $breadcrumb = (object) [
            'title' => 'Detail Profil',
            'list' => ['Home', 'Profil', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail profil pengguna'
        ];

        $activeMenu = 'profile';

        return view('profile.show', compact('user', 'activeMenu', 'breadcrumb', 'page'));
    }

    public function edit()
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(500, 'User not found');
        }

        $breadcrumb = (object) [
            'title' => 'Edit Profil',
            'list' => ['Home', 'Profil', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit profil pengguna'
        ];

        $activeMenu = 'profile';

        return view('profile.edit', compact('user', 'activeMenu', 'breadcrumb', 'page'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Ensure we have a valid User model instance
        if (!$user instanceof User) {
            abort(500, 'User not found');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'alamat' => 'nullable|string|max:255'
        ]);

        // Update basic data
        $user->name = $request->name;

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto && Storage::disk('public')->exists('profile/' . $user->foto)) {
                Storage::disk('public')->delete('profile/' . $user->foto);
            }

            // Store new photo
            $file = $request->file('foto');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile', $filename);
            $user->foto = $filename;
        }

        // Save the user model
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function deleteFoto()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(500, 'User not found');
        }

        if ($user->foto && Storage::disk('public')->exists('profile/' . $user->foto)) {
            Storage::disk('public')->delete('profile/' . $user->foto);
            $user->foto = null;
            $user->save();
        }

        return redirect()->back()->with('success', 'Foto profil berhasil dihapus.');
    }
}