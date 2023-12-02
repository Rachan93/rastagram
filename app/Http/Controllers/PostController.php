<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Post;
use Illuminate\Http\Requests;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */



     public function index(Request $request)
     {
         // Récupérer l'utilisateur authentifié
         $user = auth()->user();
 
         // Récupérer les IDs des utilisateurs suivis par l'utilisateur authentifié
         $followingIds = $user->following()->pluck('users.id');
 
         // Si un terme de recherche est présent, appliquer les filtres de recherche
         if ($request->has('search')) {
             $searchTerm = $request->query('search');
 
             // Effectuer la recherche dans la base de données pour les posts d'utilisateurs suivis
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
 
             // Effectuer la recherche dans la base de données pour tous les posts
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

     
    //     /**
    //      * Show the form for creating a new resource.
    //      */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $post = Post::make();
        $post->description = $request->validated()['description'];
        $post->localisation = $request->validated()['localisation'];
        $post->date = $request->validated()['date'];
        $post->user_id = Auth::id();

        // Storing the image in the 'posts' directory
        $path = $request->file('image')->store('posts', 'public');

        // Saving the relative path (without 'public') in the database
        $post->image_url = 'posts/' . basename($path);

        $post->save();

        return redirect()->route('posts.index');
    }




    //     /**
    //      * Display the specified resource.
    //      */



    public function show($id)
    {

        $post = Post::findOrFail($id);





        // Load the comments for the post, including the associated user
        $comments = $post->comments()

            ->with('user')

            ->orderByDesc('created_at')

            ->get();
            //dd($comments);

        return view('posts.show', [

            'post' => $post,

            'comments' => $comments,

        ]);
    }


    /* Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('updatePost', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(PostUpdateRequest $request, Post $post)
{
    $this->authorize('updatePost', $post);

    $post->description = $request->validated()['description'];
    $post->localisation = $request->validated()['localisation'];
    $post->date = $request->validated()['date'];

    // Check if a new image is provided
    if ($request->hasFile('image')) {
        // Store the new image and update the image_url
        $path = $request->file('image')->store('posts', 'public');
        $post->image_url = 'posts/' . basename($path);
    }

    $post->save();

    return redirect()->route('posts.index');
}

    public function addComment(CommentStoreRequest $request, Post $post)
    {
        // Le reste de votre code pour créer et sauvegarder le comment
        $comment = new Comment([
            'content' => $request->validated()['content'],
            'user_id' => Auth::id(),
        ]);

        $post->comments()->save($comment);

        return redirect()->route('posts.show', $post->id);
    }


    public function like(Post $post)
    {
        auth()->user()->likes()->create(['post_id' => $post->id]);

        return back();
    }

    public function unlike(Post $post)
    {
        auth()->user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
}
