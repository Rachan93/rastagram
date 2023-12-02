<x-user-layout>

    <!-- Search Bar -->
    <form action="{{ route('posts.index') }}" method="GET" class="  mb-20 w-1/2 mx-auto my-10">
        <div class="flex items-center">
            <input
                type="text"
                name="search"
                id="search"
                placeholder="Rechercher un post"
                class="border-black border-2 flex-grow focus:ring-0 focus:border-red-600  rounded px-4 py-2 mr-4"
                value="{{ request()->search }}"
                
            />
            <button
                type="submit"
                class="bg-green-500 font-bold text-white px-4 py-2 rounded-lg shadow hover:bg-green-600" 
            ><!-- Yellow button -->
                <!-- You can replace this with your own search icon -->
                Rechercher
            </button>
        </div>
    </form>

    <button class="flex justify-center my-10 mx-auto">
        <a href="{{ route('posts.create') }}" class=" font-bold bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">Ajouter un post</a> 
</button>

<!-- Post Listing -->
<ul class="grid sm:grid-cols-1 lg:grid-cols-1 2xl:grid-cols-1 gap-4 justify-center ">
    @foreach($posts as $post)
        <li class="w-full max-w-3xl mx-auto mt-1 p-8 rounded-md shadow-md bg-black "> 
            <a class="block rounded-md shadow-md p-2 hover:shadow-lg hover:scale-105 transition bg-green-500 border-8 border-yellow-500 " href="{{ route('posts.show', $post) }}">  
                <div class="relative overflow-hidden rounded-md aspect-w-1 aspect-h-1">
                    <img src="{{ asset('storage/' . $post->image_url) }}" alt="{{ $post->description }}" class="object-cover w-full h-full rounded-md ">
                </div>

                <div class="flex items-center justify-between mt-2 pt-2 border-t-2 border-yellow-500 ">
                    <div class="flex items-center mb-6">
                        <x-avatar class="h-12 w-12" :user="$post->user" />
                        <span class="font-bold text-black text-xl ml-2  ">{{ $post->user->name }}</span>
                        <span class="text-gray-300 text-sm ml-96">{{ $post->created_at->diffForHumans() }}</span> 
                    </div>
                   
                </div>
                <p class="text-black mt-1 ">{{ $post->description }}</p>
                <p class="text-gray-600 text-sm">{{ $post->localisation }}</p>
                <p class="text-gray-600 text-sm">{{ $post->date }}</p>
                <p class="text-gray-300 text-sm">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</p>
                @if($post->comments)
                    <p class="text-gray-300 text-sm">{{ $post->comments->count() }} {{ Str::plural('commentaire', $post->comments->count()) }}</p>
                @else
                    <p class="text-gray-700 text-sm">0 commentaires</p>
                @endif

                <!-- Like/Unlike Buttons -->
                @auth
                    @if (!$post->likedBy(auth()->user()))
                        <form action="{{ route('posts.like', $post) }}" method="post">
                            @csrf
                            <button type="submit">Like</button>
                        </form>
                    @else
                        <form action="{{ route('posts.unlike', $post) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Unlike</button>
                        </form>
                    @endif
                @endauth
            </a>
        </li>
    @endforeach
</ul>


    <div class="mt-8">
        {{ $posts->links() }}
    </div>

</x-user-layout>
