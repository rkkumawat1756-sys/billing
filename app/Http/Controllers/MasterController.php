<?php

namespace App\Http\Controllers;

use App\Models\DeliveryAddress;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Models\CategoryModel;

use App\Models\Setting;

class MasterController extends Controller
{

// UserController.php
public function viewProfile($id)
{
    $user = User::with(['role', 'deliveryAddresses'])->findOrFail($id);

    return view('user.profile', compact('user'));
}

public function toggleStatus(Request $request, $id)
{
    $address = DeliveryAddress::findOrFail($id);
    // नया status प्राप्त करें (या invert करें)
    $newStatus = $request->input('status'); // 1 या 0

    $address->status = $newStatus;
    $address->save();

    return response()->json([
        'success' => true,
        'status' => $address->status,
        'message' => 'Delivery address status updated successfully.'
    ]);
}


    public function settings(Request $request)
    {
        if ($request->isMethod('post')) {

            // ✅ Validation
            $request->validate([
                'name' => 'required|string|max:150',
                'mobile' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:150',
                'title' => 'nullable|string|max:150',
                'address' => 'nullable|string|max:255',
                'logo' => 'nullable|',
                'background_image' => 'nullable|',
            ]);

            // ✅ Record fetch करो (id = 1), या नया बनाओ
            $setting = Setting::firstOrNew(['id' => 1]);

            // ✅ Fields assign करो
            $setting->name = $request->name;
            $setting->title = $request->title;
            $setting->mobile = $request->mobile;
            $setting->email = $request->email;
            $setting->address = $request->address;

            // ✅ Logo Upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_logo.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $setting->logo = 'uploads/settings/' . $filename;
            }

            // ✅ Background Image Upload
            if ($request->hasFile('background_image')) {
                $file = $request->file('background_image');
                $filename = time() . '_bg.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/settings'), $filename);
                $setting->background_image = 'uploads/settings/' . $filename;
            }

            // ✅ Save record
            $setting->save();

            return redirect()->route('settings')->with('success', 'Setting saved successfully!');
        }

        // ✅ GET method: show form with existing data
        $setting = Setting::find(1); // fetch record with id 1

        return view('setting.add', compact('setting'));
    }


    public function role()
    {
        $roles = Role::whereNull('deleted_at')->get();
        return view('role.index', compact('roles'));
    }

    public function role_add()
    {
        $sidebars = getSidebarRoleData();
        $assigned = [];

        $role = null; // 👈 Add this line

        return view('role.add', compact('sidebars', 'assigned', 'role'));
    }

    public function rolestore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $permissions = $request->input('permissions', []);

        $permissions = array_map('intval', $permissions);
        Role::create([
            'name' => $request->name,
            'status' => '1',
            'permissions' => $permissions, // ✅ Laravel will handle JSON
        ]);

        return redirect()->route('role.index')->with('success', 'Role created successfully');
    }


    public function roleedit($id)
    {
        $role = Role::findOrFail($id);
        $sidebars = getSidebarRoleData(); // Also get sidebar data
        $assigned = $role->permissions ?? []; // Get already selected permissions

        return view('role.add', compact('role', 'sidebars', 'assigned'));
    }

    public function roleupdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $permissions = $request->input('permissions', []);
        $permissions = array_map('intval', $permissions);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'permissions' => $permissions,
        ]);

        return redirect()->route('role.index')->with('success', 'Role updated successfully');
    }


    public function roledestroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role deleted successfully');
    }




    public function userindex()
    {
        $users = User::with(['role'])->get(); // Pagination optional

        return view('user.index', compact('users'));
    }


    public function userCreate()
    {
        $role = Role::get();
        return view('user.add', compact('role'));
    }

    public function userstore(Request $request)
    {
        $request->validate([
            'full_name'  => 'required|string|max:255',
            'mobile'  => 'required|string|max:255',
            'address'  => 'nullable|string|max:255',
            'user_name'  => 'required|string|max:255|unique:users,user_name',
            'email'      => 'nullable|email|unique:users,email',
            'password'   => 'required|min:6',
            'photo'      => 'nullable',
            'status'     => 'nullable|in:0,1',
            'role_id'    => 'required|exists:roles,id',
        ]);

        $data = [
            'full_name'  => $request->full_name,
            'user_name'  => $request->user_name,
            'email'      => $request->email,
            'mobile'      => $request->mobile,
            'address'      => $request->address,
            'role_id'    => $request->role_id,
            'status'     => $request->status ?? 1,
            'password'   => Hash::make($request->password),
        ];

        // Optional Photo Upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_user.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $filename);
            $data['photo'] = 'uploads/users/' . $filename;
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function useredit($id)
    {
        $user = User::findOrFail($id);
        $role = Role::get();
        return view('user.edit', compact('user', 'role'));
    }

    public function userupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);



        $data = [
            'full_name' => $request->full_name,
            'user_name' => $request->user_name,
            'email'     => $request->email,
            'mobile'     => $request->mobile,
            'address'     => $request->address,
            'role_id'   => $request->role_id,
            'status'    => $request->status ?? 1,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_user.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/users'), $filename);
            $data['photo'] = 'uploads/users/' . $filename;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    public function userdestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }




    public function index()
    {
        return view('index');
    }
    

    
}
