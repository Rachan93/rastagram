<div {{ $attributes->merge(['class' => 'rounded-full overflow-hidden']) }}>
    @if ($user->profile_photo)
        <img class=" aspect-square object-cover object-center" src="{{ asset('storage/' . $user->profile_photo) }}"
            alt="{{ $user->name }}" />
    @else
        <div class="h-full w-full flex items-center justify-center text-center bg-yellow-300">
            <span class="font-bold text-3xl font-medium text-red-600">
                {{ $user->name[0] }}
            </span>
        </div>
    @endif
</div>