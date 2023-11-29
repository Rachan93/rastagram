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
                class="bg-white text-gray-600 px-4 py-2 rounded-lg shadow"
            >
                <!-- You can replace this with your own search icon -->
                Search
            </button>
        </div>
    </form>


    <div class="flex justify-center mb-4">
        <a href="{{ route('posts.create') }}" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-700">Ajouter un post</a>
    </div>

    

    <!-- Post Listing -->
    <ul class="grid sm:grid-cols-1 lg:grid-cols-1 2xl:grid-cols-1 justify-center">
        @foreach($posts as $post)
            <li class="mb-4">  
                <a class="flex bg-white rounded-md shadow-md p-5 mx-auto max-w-screen-md w-full hover:shadow-lg hover:scale-105 transition"
                   href="{{ route('posts.show', $post) }}">
            
                    {{ $post->user->name }}
                    <br>
                    {{ $post->image_url }}
                    {{ $post->description }}
                    {{ $post->localisation }}
                    {{ $post->date }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="mt-8">
        {{ $posts->links() }}
    </div> 
</x-user-layout>
