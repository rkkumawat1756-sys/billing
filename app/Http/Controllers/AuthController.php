<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Sessions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{

    public function login()
    {
        return view('login');
    }

    public function Loginstore(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find user by email or user_name
        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->login)
                ->orWhere('user_name', $request->login); // ✅ 'user_name' is correct
        })
            ->where('status', 1)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Invalid credentials or inactive account.',
            ]);
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }




    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    # Roles

    public function roleList()
    {
        $roles = Role::whereNull('deleted_at')->get();
        return view('role.index', compact('roles'));
    }

    public function saveRole(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = Role::updateOrCreate(
            ['id' => $id],
            ['name' => $request->name, 'status' => 1]
        );

        $message = $id ? 'Role updated successfully' : 'Role created successfully';

        return redirect()->route('role.index')->with('success', $message);
    }

    public function roledestroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role deleted successfully');
    }
}
