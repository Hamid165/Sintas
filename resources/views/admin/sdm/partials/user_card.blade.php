<li class="flex flex-col items-center">
    <div class="bg-gray-50 border-2 border-blue-100 rounded-2xl p-4 w-48 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-blue-600 rounded-xl mx-auto mb-3 flex items-center justify-center text-white font-bold">
            {{ substr($user->name, 0, 1) }}
        </div>
        <h3 class="font-bold text-gray-800 text-sm truncate">{{ $user->name }}</h3>
        <p class="text-[10px] text-blue-600 font-black uppercase tracking-tighter">{{ $user->jabatan }}</p>
        <p class="text-[9px] text-gray-400 mt-1">{{ $user->role }}</p>
    </div>

    @if($user->bawahan->count() > 0)
        <ul class="flex justify-center">
            @foreach($user->bawahan as $sub)
                @include('admin.sdm.partials.user_card', ['user' => $sub])
            @endforeach
        </ul>
    @endif
</li>