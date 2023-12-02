<x-user-layout>

    <!-- Search Bar -->
    <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
        <div class="flex items-center">
            <input
                type="text"
                name="search"
                id="search"
                placeholder="Rechercher un post"
                class="flex-grow border border-gray-300 rounded shadow px-4 py-2 mr-4"
                value="{{ request()->search }}"
                autofocus
            />
            <button
                type="submit"
                class="bg-green-500 text-gray-800 px-4 py-2 rounded-lg shadow" 
            ><!-- Yellow button -->
                <!-- You can replace this with your own search icon -->
                Search
            </button>
        </div>
    </form>

    <div class="flex justify-center mb-4">
        <a href="{{ route('posts.create') }}" class="bg-red-600 text-white p-2 rounded-md hover:bg-red-700">Ajouter un post</a> <!-- Green button -->
    </div>

    <!-- Post Listing -->
    <ul class="grid sm:grid-cols-1 lg:grid-cols-1 2xl:grid-cols-1 gap-4 justify-center ">
        @foreach($posts as $post)
            <li class="w-full max-w-3xl mx-auto mt-1   p-8 rounded-md shadow-md bg-black "> <!-- Red background -->
                <a class="block rounded-md shadow-md p-2 hover:shadow-lg hover:scale-105 transition bg-green-500 border-8 border-yellow-500 " href="{{ route('posts.show', $post) }}"> <!-- Lighter red background -->
                    <div class="relative overflow-hidden rounded-md aspect-w-1 aspect-h-1">
                        <img src="{{ asset('storage/' . $post->image_url) }}" alt="{{ $post->description }}" class="object-cover w-full h-full rounded-md ">
                    </div>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t-2 border-yellow-500 ">
                        <div class="flex items-center">
                            <x-avatar class="h-8 w-8" :user="$post->user" />
                            <span class="font-bold text-white ml-2 text-sm">{{ $post->user->name }}</span> <!-- Yellow text -->
                        </div>
                        <span class=" text-gray-300  text-sm ">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-white mt-1 text-sm">{{ $post->description }}</p>
                    <p class="text-gray-600 text-sm">{{ $post->localisation }}</p>
                    <p class="text-gray-600 text-sm">{{ $post->date }}</p>
                    <p class="text-gray-300 text-sm">{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</p>
                    @if($post->comments)
                        <p class="text-gray-300 text-sm">{{ $post->comments->count() }} {{ Str::plural('commentaire', $post->comments->count()) }}</p>
                    @else
                        <p class="text-gray-700 text-sm">0 commentaires</p>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>

</x-user-layout>
