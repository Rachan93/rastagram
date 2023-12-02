<section>
    <header>
        
 
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ici, vous pouvez changer votre avatar. Il sera affiché sur votre profil, vos posts et vos commentaires.') }}
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
                        class="block w-1/2 shadow-sm sm:text-sm rounded-md"/>
                </div>
 
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
            </div>
        </div>
 
        <div class="flex items-center gap-4 ">
            <x-primary-button class="bg-red-600 hover:bg-red-700">{{ __('Enregistrer') }}</x-primary-button>
 
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>