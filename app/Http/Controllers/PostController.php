<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Post;
use Illuminate\Http\Requests;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;




class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
{
    // Get all posts with user information
    $posts = Post::with('user');

    // If there is a search term, apply search filters
    if ($request->has('search')) {
        $searchTerm = $request->query('search');
        $posts->where(function ($query) use ($searchTerm) {
            $query->where('description', 'LIKE', '%' . $searchTerm . '%')
                ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhere('localisation', 'LIKE', '%' . $searchTerm . '%');
        });
    }

    // Get the IDs of followed users
$followedUserIds = auth()->user()->following()->pluck('users.id')->toArray();

// Include posts from followed users and the authenticated user, order by custom criteria
$posts->orderByRaw("FIELD(user_id, " . implode(',', $followedUserIds) . ") DESC")
      ->orderByDesc('updated_at');


    // Paginate the results
    $posts = $posts->paginate(10);

    return view('posts.index', [
        'posts' => $posts,
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
        //dd($request->all());
        $post = Post::make();
        $post->description = $request->validated()['description'];
        $post->image_url = $request->validated()['image_url'];
        $post->localisation = $request->validated()['localisation'];
        $post->date = $request->validated()['date'];
        $post->user_id = Auth::id();
        $post->save();

        return redirect()->route('posts.index');
    }

//     /**
//      * Display the specified resource.
//      */



    public function show($id){

        $post = Post::findOrFail($id);





        // Load the comments for the post, including the associated user
        $comments = $post->commentaires()

            ->with('user')

            ->orderBy('created_at')

            ->get();


        return view('posts.show', [

            'post' => $post,

            'comments' => $comments,

        ]);

    }


     /* Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

public function update(PostUpdateRequest $request, Post $post)
{

    $post->description = $request->validated()['description'];
    $post->image_url = $request->validated()['image_url'];
    $post->localisation = $request->validated()['localisation'];
    $post->date = $request->validated()['date'];
    $post->save();


    return redirect()->route('posts.index');

}
public function addComment(CommentStoreRequest $request, Post $post)
{
    // Le reste de votre code pour créer et sauvegarder le commentaire
    $comment = new Commentaire([
        'content' => $request->validated()['content'],
        'user_id' => Auth::id(),
    ]);

    $post->commentaires()->save($comment);

    return redirect()->route('posts.show', $post->id);
}

/*public function addComment(Request $request, Post $post)
{
    dd($request->all);
    // Ensure that the user is authenticated
    $request->validate([

        'content' => 'required|string|max:255',
    ]);

    // Create a new comment associated with the post
    $comment = $post->commentaires()->make();

    // Set the comment body and user_id
    $comment->content = $request->input('content');
    $comment->user_id = auth()->user()->id;

    // Save the comment
    $comment->save();

    // Redirect back to the post
    return redirect()->back();
}*/

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(Post $post)
//     {
//         //
//     }

// }


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