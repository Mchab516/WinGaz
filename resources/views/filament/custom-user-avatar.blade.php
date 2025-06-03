<div class="bg-[#0094C9] text-white rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold">
    {{ collect(explode(' ', $user->name))->map(fn($word) => Str::substr($word, 0, 1))->join('') }}
</div>