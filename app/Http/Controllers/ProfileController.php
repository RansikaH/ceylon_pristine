<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile-edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            \Log::info('Avatar file detected in request.');
            $avatar = $request->file('avatar');
            $avatarName = uniqid('avatar_').'.'.$avatar->getClientOriginalExtension();
            try {
                $result = $avatar->storeAs('avatars', $avatarName, 'public');
                \Log::info('Avatar stored at: ' . $result);
                if (\Storage::disk('public')->exists('avatars/'.$avatarName)) {
                    \Log::info('Avatar physically exists in avatars folder.');
                } else {
                    \Log::warning('Avatar NOT found in avatars folder after storeAs.');
                }
                $user->avatar = $avatarName;
            } catch (\Exception $e) {
                \Log::error('Avatar upload failed: ' . $e->getMessage());
            }
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
