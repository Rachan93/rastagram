<x-guest-layout>

<!-- Rastagram Description Section -->
<section class="my-10 mx-auto p-8 max-w-3xl rounded-md shadow-md bg-green-500 text-white border-8 border-black">
    <h2 class="font-bold text-black text-3xl mb-6 text-center">Bienvenue sur Rastagram</h2>
    <div class="relative overflow-hidden rounded-md aspect-w-16 aspect-h-9">
        <!-- Placeholder image -->
        <img src="{{ asset('images/logo/rastagram-logo-1024.png') }}" alt="Logo" height="" width="">
    </div>
    <p class="text-gray-800 text-lg mb-6 py-2 px-4 bg-green-400 rounded-md">
        Rastagram, mec, c'est l'endroit où les vibes cool rencontrent les moments planants ! Rejoins la tribu, connecte-toi avec tes potes, et plonge dans un océan de photos qui respirent le bonheur. C'est pas juste une plateforme, c'est un voyage visuel où la weed est optionnelle, mais les good vibes sont obligatoires. 🌿✌️
    </p>

    <!-- Login and Register Buttons -->
    <div class="flex justify-center space-x-4">
        <a href="{{ route('login') }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">Connexion</a>
        <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">Inscription</a>
    </div>
</section>

</x-guest-layout>
