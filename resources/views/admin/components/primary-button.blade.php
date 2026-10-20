<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-winay-terracota border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-winay-tierra focus:bg-winay-tierra active:bg-winay-tierra focus:outline-none focus:ring-2 focus:ring-winay-terracota focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
