@if(session('success'))
    <div x-data="{ show: true }" x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed top-4 right-4 px-4 py-2 bg-green-600 text-white rounded shadow-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed top-4 right-4 px-4 py-2 bg-red-600 text-white rounded shadow-lg">
        {{ session('error') }}
    </div>
@endif
