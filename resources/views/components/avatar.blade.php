<div {{ $attributes->merge(['class' => 'rounded-full overflow-hidden']) }}>
    @if ($user->profile_photo)
        <img class=" aspect-square object-cover object-center" src="{{ asset('storage/' . $user->profile_photo) }}"
            alt="{{ $user->name }}" />
    @else
        <div class="flex items-center justify-center bg-indigo-100">
            <span class="text-2xl font-medium text-indigo-800">
                {{ $user->name[0] }}
            </span>
        </div>
    @endif
</div>