<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{

    // Affiche une liste de ressources.

    public function index(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = auth()->user();

        // Récupérer les IDs des utilisateurs suivis par l'utilisateur authentifié
        $followingIds = $user->following()->pluck('users.id');

        // Filtrer les posts en fonction d'un terme de recherche s'il est présent
        if ($request->has('search')) {
            $searchTerm = $request->query('search');

            // Recherche dans la base de données pour les posts d'utilisateurs suivis
            $postsFollowed = Post::whereIn('user_id', $followingIds)
                ->where(function ($query) use ($searchTerm) {
                    $query->whereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                    })
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('localisation', 'like', '%' . $searchTerm . '%');
                })
                ->orderByDesc('updated_at')
                ->get();

            // Recherche dans la base de données pour tous les posts
            $postsAll = Post::where(function ($query) use ($searchTerm) {
                $query->whereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                })
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('localisation', 'like', '%' . $searchTerm . '%');
            })
                ->orderByDesc('updated_at')
                ->get();
        } else {
            // S'il n'y a pas de terme de recherche, récupérer les messages des utilisateurs suivis
            $postsFollowed = Post::whereIn('user_id', $followingIds)
                ->orderByDesc('updated_at')
                ->get();

            // Récupérer tous les messages
            $postsAll = Post::orderByDesc('updated_at')->get();
        }

        // Obtenir le nombre d'abonnés pour chaque utilisateur dans $postsAll
        $userFollowerCounts = collect($postsAll)->groupBy('user_id')->map->count();

        // Trier $postsAll par nombre d'abonnés par ordre décroissant
        $postsAll = $postsAll->sortByDesc(function ($post) {
            return $post->likes()->count();
        });

        // Fusionner les deux ensembles de messages et supprimer les doublons
        $mergedPosts = $postsFollowed->merge($postsAll)->unique('id');

        // Paginer les messages fusionnés et triés
        $paginatedPosts = $this->paginateCollection($mergedPosts, 10);

        return view('posts.index', ['posts' => $paginatedPosts]);
    }

    // Méthode d'aide pour paginer une collection
    private function paginateCollection($items, $perPage)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    }

    // Affiche le formulaire de création d'une nouvelle ressource.
    public function create()
    {
        return view('posts.create');
    }

    // Stocke une nouvelle ressource nouvellement créée dans le stockage.
    public function store(PostStoreRequest $request)
    {
        $post = Post::make();
        $post->description = $request->validated()['description'];
        $post->localisation = $request->validated()['localisation'];
        $post->date = $request->validated()['date'];
        $post->user_id = Auth::id();

        // Stocker l'image dans le répertoire 'posts'
        $path = $request->file('image')->store('posts', 'public');

        // Enregistrer le chemin relatif (sans 'public') dans la base de données
        $post->image_url = 'posts/' . basename($path);

        $post->save();

        return redirect()->route('posts.index');
    }

    // Affiche la ressource spécifiée.
    public function show($id)
    {
        $post = Post::findOrFail($id);

        // Charger les commentaires pour le post, y compris l'utilisateur associé
        $comments = $post->comments()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        return view('posts.show', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    // Affiche le formulaire de modification de la ressource spécifiée.
    public function edit(Post $post)
    {
        $this->authorize('updatePost', $post);

        return view('posts.edit', compact('post'));
    }

    // Met à jour la ressource spécifiée dans le stockage.
    public function update(PostUpdateRequest $request, Post $post)
    {
        $this->authorize('updatePost', $post);

        $post->description = $request->validated()['description'];
        $post->localisation = $request->validated()['localisation'];
        $post->date = $request->validated()['date'];

        // Vérifier si une nouvelle image est fournie
        if ($request->hasFile('image')) {
            // Stocker la nouvelle image et mettre à jour l'image_url
            $path = $request->file('image')->store('posts', 'public');
            $post->image_url = 'posts/' . basename($path);
        }

        $post->save();

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    // Ajoute un commentaire à la ressource spécifiée.
    public function addComment(CommentStoreRequest $request, Post $post)
    {
        // Le reste du code pour créer et sauvegarder le commentaire
        $comment = new Comment([
            'content' => $request->validated()['content'],
            'user_id' => Auth::id(),
        ]);

        $post->comments()->save($comment);

        return redirect()->route('posts.show', $post->id);
    }

    // Ajoute un "j'aime" à la ressource spécifiée.
    public function like(Post $post)
    {
        auth()->user()->likes()->create(['post_id' => $post->id]);  //likes() est bien défini mais l'analyse statique de code de VSC ne permet pas de le déterminer

        return back();
    }

    // Supprime un "j'aime" de la ressource spécifiée.
    public function unlike(Post $post)
    {
        auth()->user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
}
