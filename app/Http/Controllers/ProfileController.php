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
    // Affiche les profils publics.
    public function show(User $user): View
    {
        // Récupérer les articles publiés par l'utilisateur avec le nombre de commentaires
        $posts = $user
            ->posts()
            ->withCount('comments')
            ->orderBy('created_at')
            ->get();

        // Récupérer les commentaires de l'utilisateur triés par date de création
        $comments = $user
            ->comments()
            ->orderByDesc('created_at')
            ->get();

        // Renvoyer la vue avec les données
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }

    // Affiche le formulaire de modification du profil utilisateur.
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // Met à jour les informations de profil de l'utilisateur.
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Remplir les données du modèle utilisateur avec les données validées du formulaire
        $request->user()->fill($request->validated());
        $request->user()->bio = $request->bio;

        // Réinitialiser la vérification de l'e-mail si l'adresse e-mail change
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Sauvegarder les modifications dans la base de données
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // Met à jour l'avatar de l'utilisateur.
    public function updateAvatar(Request $request): RedirectResponse
    {
        // Validation de l'image sans utiliser une form request
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        // Si l'image est valide, la sauvegarder
        if ($request->hasFile('avatar')) {
            $user = $request->user();
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->profile_photo = $path;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    // Supprime le compte utilisateur.
    public function destroy(Request $request): RedirectResponse
    {
        // Validation du mot de passe actuel avant la suppression du compte
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        // Invalidater la session et régénérer le jeton CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Ajoute un utilisateur à la liste des abonnements.
    public function follow(User $user)
    {
        auth()->user()->following()->attach($user);

        return back();
    }

    // Supprime un utilisateur de la liste des abonnements.
    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user);

        return back();
    }
}
