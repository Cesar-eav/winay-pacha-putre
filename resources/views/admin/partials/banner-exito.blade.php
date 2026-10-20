@if (session('success'))
    <div class="mb-6 rounded-lg bg-winay-andino/10 border border-winay-andino/30 text-winay-andino px-4 py-3 text-sm">
        {{ session('success') }}
    </div>
@endif

@if (session('aviso'))
    <div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 text-sm">
        {{ session('aviso') }}
    </div>
@endif
