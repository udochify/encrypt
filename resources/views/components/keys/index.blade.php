@aware(['keys'])

<p class="text-[1em] mb-3">Your RSA Keys</p>
<div class="p-4 grid gap-x-5 gap-y-3" style="grid-template-columns: repeat(2, max-content)">
    <p class="font-bold text-[1em]">s/no</p>
    <p class="font-bold text-[1em]">Name</p>
    @foreach ($keys as $key)
    <p class="text-[1em]">{{ $key->id }}</p>
    <p class="text-[1em]">{{ $key->name }}</p>
    @endforeach
</div>