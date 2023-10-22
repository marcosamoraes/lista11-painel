<x-perfect-scrollbar
    as="nav"
    aria-label="main"
    class="flex flex-col flex-1 gap-4 px-3"
>
    <x-sidebar.link
        title="Painel"
        href="{{ route('dashboard') }}"
        :isActive="request()->routeIs('dashboard')"
    >
        <x-slot name="icon">
            <i class="fa fa-dashboard" aria-hidden="true"></i>
        </x-slot>
    </x-sidebar.link>

    @if (auth()->user()->role === 'admin')
        <x-sidebar.dropdown
            title="Comercial"
            :active="request()->routeIs('sellers.*') ||
            request()->routeIs('clients.*') ||
            request()->routeIs('companies.*') ||
            request()->routeIs('orders.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            </x-slot>

            <x-sidebar.sublink
                title="Vendedores"
                href="{{ route('sellers.index') }}"
                :isActive="request()->routeIs('sellers.*')"
            />

            <x-sidebar.sublink
                title="Clientes"
                href="{{ route('clients.index') }}"
                :isActive="request()->routeIs('clients.*')"
            />

            <x-sidebar.sublink
                title="Empresas"
                href="{{ route('companies.index') }}"
                :isActive="request()->routeIs('companies.*')"
            />

            <x-sidebar.sublink
                title="Vendas"
                href="{{ route('orders.index') }}"
                :isActive="request()->routeIs('orders.*')"
            />
        </x-sidebar.dropdown>

        <x-sidebar.dropdown
            title="Comunicação"
            :active="request()->routeIs('contacts.*') ||
            request()->routeIs('registers.*') ||
            request()->routeIs('reviews.*') ||
            request()->routeIs('posts.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-comments" aria-hidden="true"></i>
            </x-slot>

            <x-sidebar.sublink
                title="Contatos"
                href="{{ route('contacts.index') }}"
                :isActive="request()->routeIs('contacts.*')"
            />

            <x-sidebar.sublink
                title="Interessados"
                href="{{ route('registers.index') }}"
                :isActive="request()->routeIs('registers.*')"
            />
            <x-sidebar.sublink
                title="Avaliações"
                href="{{ route('reviews.index') }}"
                :isActive="request()->routeIs('reviews.*')"
            />

            <x-sidebar.sublink
                title="Blog"
                href="{{ route('posts.index') }}"
                :isActive="request()->routeIs('posts.*')"
            />
        </x-sidebar.dropdown>

        <x-sidebar.dropdown
            title="Administração"
            :active="request()->routeIs('contracts.*') ||
            request()->routeIs('packs.*') ||
            request()->routeIs('categories.*') ||
            request()->routeIs('banners.*') ||
            request()->routeIs('apps.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-cog" aria-hidden="true"></i>
            </x-slot>

            <x-sidebar.sublink
                title="Contratos"
                href="{{ route('contracts.index') }}"
                :isActive="request()->routeIs('contracts.*')"
            />

            <x-sidebar.sublink
                title="Pacotes"
                href="{{ route('packs.index') }}"
                :isActive="request()->routeIs('packs.*')"
            />

            <x-sidebar.sublink
                title="Categorias"
                href="{{ route('categories.index') }}"
                :isActive="request()->routeIs('categories.*')"
            />

            <x-sidebar.sublink
                title="Banners"
                href="{{ route('banners.index') }}"
                :isActive="request()->routeIs('banners.*')"
            />

            <x-sidebar.sublink
                title="Aplicativos"
                href="{{ route('apps.index') }}"
                :isActive="request()->routeIs('apps.*')"
            />
        </x-sidebar.dropdown>
    @endif

    @if (auth()->user()->role === 'seller')
        <x-sidebar.link
            title="Clientes"
            href="{{ route('clients.index') }}"
            :isActive="request()->routeIs('clients.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-users" aria-hidden="true"></i>
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.link
            title="Empresas"
            href="{{ route('companies.index') }}"
            :isActive="request()->routeIs('companies.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-building" aria-hidden="true"></i>
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.link
            title="Vendas"
            href="{{ route('orders.index') }}"
            :isActive="request()->routeIs('orders.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-cart-shopping" aria-hidden="true"></i>
            </x-slot>
        </x-sidebar.link>
    @endif

    @if (auth()->user()->role === 'user')
        <x-sidebar.link
            title="Empresas"
            href="{{ route('companies.index') }}"
            :isActive="request()->routeIs('companies.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-building" aria-hidden="true"></i>
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.link
            title="Contatos"
            href="{{ route('contacts.index') }}"
            :isActive="request()->routeIs('contacts.*')"
        >
            <x-slot name="icon">
                <i class="fa fa-file" aria-hidden="true"></i>
            </x-slot>
        </x-sidebar.link>

        <x-sidebar.link
            title="Meus Dados"
            href="{{ route('settings') }}"
            :isActive="request()->routeIs('settings')"
        >
            <x-slot name="icon">
                <i class="fa fa-user" aria-hidden="true"></i>
            </x-slot>
        </x-sidebar.link>
    @endif

    <!-- Authentication -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <x-sidebar.link
            :href="route('logout')"
            title="Sair"
            onclick="event.preventDefault(); this.closest('form').submit();"
        >
            <x-slot name="icon">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
            </x-slot>
        </x-sidebar.link>
    </form>

</x-perfect-scrollbar>
