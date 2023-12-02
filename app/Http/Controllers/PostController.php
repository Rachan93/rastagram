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
         // Get the IDs of followed users
         $followedUserIds = auth()->user()->following()->pluck('users.id')->toArray();
     
         // Fetch all posts with an additional column for like_count and comments
         $posts = Post::leftJoin('likes', 'posts.id', '=', 'likes.post_id')
             ->with('comments') // Eager load comments
             ->select(
                 'posts.*',
                 DB::raw('COUNT(likes.id) AS like_count'),
                 DB::raw('NULL AS user_followed')
             )
             ->groupBy('posts.id')
             ->orderBy('created_at', 'desc')
             ->get();
     
         // Mark posts from followed users
         foreach ($posts as $post) {
             $post->user_followed = in_array($post->user_id, $followedUserIds);
         }
     
         // If there is a search term, apply search filters
         if ($request->has('search')) {
             $searchTerm = $request->query('search');
             $filteredPosts = $posts->filter(function ($post) use ($searchTerm) {
                 return stripos($post->description, $searchTerm) !== false
                     || stripos($post->user->name, $searchTerm) !== false
                     || stripos($post->localisation, $searchTerm) !== false;
             });
     
             // Sort the filtered posts based on the same ordering logic
             $filteredPosts = $filteredPosts->sortByDesc(function ($post) use ($followedUserIds) {
                 return [
                     $post->user_followed,
                     $post->user_followed ? $post->created_at : $post->like_count,
                 ];
             });
     
             // Paginate the filtered and sorted collection using Laravel Paginator
             $currentPage = Paginator::resolveCurrentPage('page');
             $perPage = 10;
     
             // Use LengthAwarePaginator to create a paginator with the correct total count
             $paginatedPosts = new LengthAwarePaginator(
                 $filteredPosts->forPage($currentPage, $perPage),
                 $filteredPosts->count(),
                 $perPage,
                 $currentPage,
                 ['path' => Paginator::resolveCurrentPath()]
             );
     
             return view('posts.index', [
                 'posts' => $paginatedPosts,
             ]);
         }
     
         // If there is no search term, continue with the regular index logic
     
         // Sort the collection in PHP
         $sortedPosts = $posts->sortByDesc(function ($post) use ($followedUserIds) {
             // Order by user_followed and then either created_at or like_count
             return [
                 $post->user_followed,
                 $post->user_followed ? $post->created_at : $post->like_count,
             ];
         });
     
         // Paginate the sorted collection using Laravel Paginator
         $currentPage = Paginator::resolveCurrentPage('page');
         $perPage = 10;
     
         // Use LengthAwarePaginator to create a paginator with the correct total count
         $paginatedPosts = new LengthAwarePaginator(
             $sortedPosts->forPage($currentPage, $perPage),
             $sortedPosts->count(),
             $perPage,
             $currentPage,
             ['path' => Paginator::resolveCurrentPath()]
         );
     
         return view('posts.index', [
             'posts' => $paginatedPosts,
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
            $post->image_url = asset('storage/' . $path);
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
