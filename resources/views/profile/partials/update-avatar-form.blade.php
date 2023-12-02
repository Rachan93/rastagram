<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Avatar') }}
        </h2>
 
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Here you can change your avatar. It will be displayed on your
                  profile and on your articles.') }}
        </p>
    </header>
 
    <form method="post" action="{{ route('profile.avatar.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf @method('patch')
 
        <div class="flex flex-col space-y-2">
            <x-avatar :user="$user" class="h-20 w-20"></x-avatar>
 
            <div class="">
                <label for="avatar" class="block text-sm font-medium text-gray-700">
                    {{ __('Avatar') }}
                </label>
 
                <div class="mt-1">
                    <input type="file" name="avatar" id="avatar"
                        class="block w-full shadow-sm sm:text-sm rounded-md/>
                </div>
 
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </div>
        </div>
 
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
 
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>