<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        return Inertia::render('Profile', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'studentid' => 'required|string|unique:users,studentid,' . Auth::user()->id,
            'courseSection' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female,Other',
        ]);

        Auth::user()->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateBasicProfile(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::user()->id,
            'phone_number' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
