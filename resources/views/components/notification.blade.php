@if(session('status') ?? false)
<div class="w-full h-full bg-green-100">
    <p class="text-green-600 text-sm p-2">{{ session('status') }}</p>
</div>
@elseif(session('error') ?? false)
<div class="w-full h-full bg-red-100 p-2">
    <p class="text-red-600 text-sm">{{ session('error') }}</p>
</div>
@endif