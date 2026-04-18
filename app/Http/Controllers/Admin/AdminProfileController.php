<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    /**
     * Display the admin's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'admin' => $request->user('admin'),
        ]);
    }

    /**
     * Update the admin's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $admin = $request->user('admin');
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,'.$admin->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = uniqid('admin_avatar_').'.'.$avatar->getClientOriginalExtension();
            
            try {
                $result = $avatar->storeAs('admin_avatars', $avatarName, 'public');
                $admin->avatar = $avatarName;
            } catch (\Exception $e) {
                \Log::error('Admin avatar upload failed: ' . $e->getMessage());
                return Redirect::route('admin.profile.edit')
                    ->with('error', 'Failed to upload avatar. Please try again.');
            }
        }

        $admin->save();

        return Redirect::route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $admin = $request->user('admin');
        $admin->password = Hash::make($request->password);
        $admin->save();

        return Redirect::route('admin.profile.edit')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Remove the admin's avatar.
     */
    public function removeAvatar(Request $request): RedirectResponse
    {
        $admin = $request->user('admin');
        
        if ($admin->avatar) {
            // Delete old avatar from storage
            try {
                \Storage::disk('public')->delete('admin_avatars/'.$admin->avatar);
            } catch (\Exception $e) {
                \Log::error('Failed to delete admin avatar: ' . $e->getMessage());
            }
            
            $admin->avatar = null;
            $admin->save();
        }

        return Redirect::route('admin.profile.edit')
            ->with('success', 'Avatar removed successfully.');
    }

    /**
     * Get the admin's profile information for API.
     */
    public function getProfile(Request $request)
    {
        $admin = $request->user('admin');
        
        return response()->json([
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'role' => $admin->role,
                'avatar' => $admin->avatar ? asset('storage/admin_avatars/'.$admin->avatar) : null,
                'created_at' => $admin->created_at->format('Y-m-d H:i:s'),
                'last_login' => $admin->last_login_at ? $admin->last_login_at->format('Y-m-d H:i:s') : null,
            ]
        ]);
    }
}
