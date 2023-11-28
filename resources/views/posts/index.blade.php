<x-user-layout>
<ul class="grid sm:grid-cols-1 lg:grid-cols-1 2xl:grid-cols-1 justify-center">
        @foreach($posts as $post)
            <li class="mb-4">  
                <a class="flex bg-white rounded-md shadow-md p-5 mx-auto max-w-screen-md w-full hover:shadow-lg hover:scale-105 transition"
                href="{{ route('posts.show', $post) }}">
            
                    {{ $post->user->name }}
                    <br>
                    {{ $post->image_url }}
                    {{ $post->description }}
                </a>
            </li>
        @endforeach
    </ul>
 <div class="mt-8">
        {{ $posts->links() }}
    </div> 
</x-user-layout>