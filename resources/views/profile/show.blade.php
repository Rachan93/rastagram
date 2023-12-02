<x-user-layout>
<div class="flex items-center ml-10 mt-10">
    <x-avatar class="h-20 w-20" :user="$user" />
    <div class="ml-4 flex flex-col">
        <div class="text-gray-800 font-bold text-2xl">{{ $user->name }}</div>
        <div class="text-gray-700 ">{{ $user->email }}</div>
        <div class="text-gray-500 text-sm">
            Membre depuis {{ $user->created_at->diffForHumans() }}
        </div>

        <!-- Follow/Unfollow Button -->
        @auth
            @if(auth()->user()->id !== $user->id)
                @if(auth()->user()->following->contains($user))
                    <form action="{{ route('profile.unfollow', $user) }}" method="post">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                            Ne plus suivre
                        </button>
                    </form>
                @else
                    <form action="{{ route('profile.follow', $user) }}" method="post">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            Suivre
                        </button>
                    </form>
                @endif
            @endif
        @endauth
    </div>
</div>


    <!-- Profile Post Gallery -->
<div class="mt-8">
    <h2 class="font-bold text-2xl mb-20  text-center">Posts de {{ $user->name }}</h2>
    <ul class="grid sm:grid-cols-1 lg:grid-cols-1 2xl:grid-cols-2 gap-4 justify-center">
        @forelse ($posts as $post)
            <li class="w-full max-w-3xl mx-auto mt-1 p-8 rounded-md shadow-md bg-black">
                <a class="block h-full rounded-md shadow-md p-2 hover:shadow-lg hover:scale-105 transition bg-green-500 border-8 border-yellow-500" href="{{ route('posts.show', $post) }}">
                    <div class="relative overflow-hidden rounded-md aspect-w-1 aspect-h-1">
                        <img src="{{ asset('storage/' . $post->image_url) }}" alt="{{ $post->description }}" class="object-cover w-full h-full rounded-md">
                    </div>

                    <div class="flex items-center justify-between mt-2 pt-2 border-t-2 border-yellow-500">
                        <div class="flex items-center mb-6">
                            <x-avatar class="h-12 w-12" :user="$post->user" />
                            <span class="font-bold text-black text-xl ml-2">{{ $post->user->name }}</span>
                            <span class="text-gray-300 text-sm ml-96">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-black mt-1">{{ $post->description }}</p>
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
        @empty
            <div class="text-gray-700">Aucun post</div>
        @endforelse
    </ul>
</div>





<div class="mt-8 mb-10">
    <h2 class="font-bold text-2xl mb-20 mt-40 text-center">Commentaires de {{ $user->name }}</h2>

    <div class="flex-col space-y-4">
        @forelse ($comments as $comment)
            <div class="flex bg-green-500 rounded-md p-4 space-x-4 border-8 border-black w-1/2     mx-auto">
                <a href="{{ route('profile.show', $comment->user) }}" class="flex justify-start items-start h-full">
                    <x-avatar class="h-12 w-12" :user="$comment->user" />
                </a>
                <div class="flex flex-col justify-center">
                    <div class="text-gray-700">
                        <a href="{{ route('profile.show', $comment->user) }}" class="font-bold text-black">{{ $comment->user->name }}</a>
                    </div>
                    <div class="text-gray-600 text-xs ">
                        {{ $comment->created_at->diffForHumans() }}
                    </div>
                    <div class="text-black whitespace-normal overflow-hidden max-h-40 break-all  rounded-md p-4">
                        {{ $comment->content }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center bg-yellow-300 rounded-md max-w-3xl p-4 mx-auto">
                <p class="text-center">Aucun commentaire pour l'instant</p>
            </div>
        @endforelse
    </div>
</div >

</x-user-layout>