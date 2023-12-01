<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
//profils publics
    public function show(User $user): View
    {
        // Les articles publiés par l'utilisateur
        $posts = $user
            ->posts()
           // ->where('published_at', '<', now())
            ->withCount('comments')
            ->orderBy('created_at')
            ->get()
        ;
    
        // Les comments de l'utilisateur triés par date de création
        $comments = $user
            ->comments()
            ->orderByDesc('created_at')
            ->get()
        ;
        //dd($comments);
    
        // On renvoie la vue avec les données
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
            'comments' => $comments,
        ]);
      
    }




    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        //dd($request->all()); // Inspect the entire request
    
        $request->user()->fill($request->validated());
        $request->user()->bio = $request->bio;
    
        //dd($request->user()); // Inspect the user model before saving
    
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
    
        $request->user()->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    
    public function updateAvatar(Request $request): RedirectResponse
    {
       // dd($request);
        // Validation de l'image sans passer par une form request
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);
 
        // Si l'image est valide, on la sauvegarde
        if ($request->hasFile('avatar')) {
            $user = $request->user();
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->profile_photo = $path;
            $user->save();
        }
 
        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
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




    public function follow(User $user)
    {
        auth()->user()->following()->attach($user);

        return back();
    }

    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user);

        return back();
    }

}
