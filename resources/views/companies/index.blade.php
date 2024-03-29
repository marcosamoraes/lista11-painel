<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Empresas
        </h2>

        <div class="flex justify-end gap-3 flex-wrap flex-col-reverse sm:flex-row">
            @if (auth()->user()->role === 'admin')
                <x-button href="{{ route('companies.export') }}" variant="secondary"><i class="fa fa-file-excel mr-2"></i>Exportar</x-button>
            @endif
            <x-button variant="black" class="toggle-filters"><i class="fa fa-filter mr-2"></i>Filtros</x-button>
            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'seller')
                <x-button href="{{ route('companies.create') }}"><i class="fa fa-plus mr-2"></i>Cadastrar</x-button>
            @endif
        </div>
    </x-slot>

    <x-search-bar />

    <div class="filters" style="display: {{ request()->has('search') ? 'flex' : 'none' }}">
        <div class="max-w-10xl sm:px-6 lg:px-8 mt-5">
            <div class="flex justify-between">
                <form method="GET" class="flex flex-wrap gap-3 w-full">
                    <input type="hidden" name="search" value="{{ request()->search }}">

                    <x-form.select id="seller" name="seller" type="text" class="block" style="min-width: 250px" :value="request()->seller"
                        autofocus autocomplete="seller">
                        <option value="">Vendedor</option>
                        @foreach ($sellers as $seller)
                            <option value="{{ $seller->id }}" {{ request()->seller == $seller->id ? 'selected' : false }}>{{ $seller->name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select id="category" name="category" type="text" class="block" style="min-width: 250px" :value="request()->category"
                        autofocus autocomplete="category">
                        <option value="">Categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request()->category == $category->id ? 'selected' : false }}>{{ $category->name }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select id="city" name="city" type="text" class="block" style="min-width: 250px" :value="request()->city"
                        autofocus autocomplete="city">
                        <option value="">Cidade</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" {{ request()->city == $city ? 'selected' : false }}>{{ $city }}</option>
                        @endforeach
                    </x-form.select>

                    <x-form.select id="status" name="status" type="text" class="block" style="min-width: 250px" :value="request()->status"
                        autofocus autocomplete="status">
                        <option value="">Status</option>
                        <option value="1" {{ request()->status === '1' ? 'selected' : false }}>Ativo</option>
                        <option value="0" {{ request()->status === '0' ? 'selected' : false }}>Inativo</option>
                    </x-form.select>

                    <div class="flex flex-col">
                        <label for="">Data de criação inicial</label>
                        <x-form.input type="date" name="initial_created_at" :value="request()->initial_created_at" style="min-width: 250px" />
                    </div>
                    <div class="flex flex-col">
                        <label for="">Data de criação final</label>
                        <x-form.input type="date" name="final_created_at" :value="request()->final_created_at" style="min-width: 250px" />
                    </div>
                    <div class="flex flex-col">
                        <label for="">Data de expiração inicial</label>
                        <x-form.input type="date" name="initial_expire_at" :value="request()->initial_expire_at" style="min-width: 250px" />
                    </div>
                    <div class="flex flex-col">
                        <label for="">Data de expiração final</label>
                        <x-form.input type="date" name="final_expire_at" :value="request()->final_expire_at" style="min-width: 250px" />
                    </div>

                    <x-button>
                        <i class="fas fa-search"></i>
                    </x-button>
                </form>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-eval-1 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white dark:bg-dark-eval-1 border-b border-gray-200 dark:border-dark-eval-1">
                    <div class="min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-500 border dark:border-gray-500 mt-5">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">ID</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">Nome</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-center">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">Data de expiração</span>
                                </th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">Ações</span>
                                </th>
                            </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-dark-eval-1 divide-y divide-gray-200 divide-solid">
                            @foreach($companies as $company)
                                <tr class="bg-white dark:bg-dark-eval-1">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-white">
                                        {{ $company->id }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-white">
                                        <div class="flex gap-3 items-center">
                                            @if ($company->lastOrderApproved?->expire_at && $company->lastOrderApproved?->expire_at->isPast())
                                                <span class="block border border-yellow-500 bg-yellow-500 w-2 h-2 rounded-full"></span>
                                            @else
                                                @if ($company->is_approved)
                                                    <span class="block border border-green-500 bg-green-500 w-2 h-2 rounded-full"></span>
                                                @else
                                                    <span class="block border border-red-500 bg-red-500 w-2 h-2 rounded-full"></span>
                                                @endif
                                            @endif
                                            <p>
                                                {{ $company->name }}
                                                @if ($company->parent_id)
                                                    <span class="text-xs bg-black text-white px-2 py-1 rounded-full">Cópia</span>
                                                @endif
                                                <br />
                                                @if (auth()->user()->role === 'admin')
                                                    <small>Vendedor: {{ $company->user?->name ?? 'Sem vendedor' }}</small><br />
                                                @endif
                                                @if ((auth()->user()->role === 'admin' || auth()->user()->role === 'seller') && $company->client)
                                                    <small>Cliente: {{ $company->client?->user?->name }}</small>
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-white text-center">
                                        @if ($company->lastOrderApproved?->expire_at && $company->lastOrderApproved?->expire_at->isPast())
                                            <span class=" text-red-500">
                                                {{ $company->lastOrderApproved?->expire_at?->format('d/m/Y') ?? '-' }}
                                            </span>
                                        @else
                                            {{ $company->lastOrderApproved?->expire_at?->format('d/m/Y') ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 flex-wrap text-gray-900 dark:text-white flex gap-3">
                                        @if (auth()->user()->role === 'admin')
                                            <a href="https://wa.me/+55{{ preg_replace('/\D/', '', $company->phone) }}" target="_blank">
                                                <x-button variant="whatsapp" title="Whatsapp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </x-button>
                                            </a>
                                        @endif
                                        @if ($company->lastOrderApproved && auth()->user()->role !== 'seller')
                                            <a href="{{ route('orders.contract', $company->lastOrderApproved->uuid) }}" target="_blank">
                                                <x-button variant="black" title="Link do contrato">
                                                    <i class="fas fa-file"></i>
                                                </x-button>
                                            </a>
                                        @endif
                                        <a href="https://lista11brasil.com.br/empresa/{{ str()->slug($company->categories[0]->name) }}/{{ str()->slug($company->city) }}/{{ $company->slug }}" target="_blank">
                                            <x-button variant="info" title="Ver site">
                                                <i class="fas fa-eye"></i>
                                            </x-button>
                                        </a>

                                        @if (!$company->parent_id)
                                            <a href="{{ route('companies.duplicate', $company->id) }}">
                                                <x-button variant="secondary">
                                                    <i class="fas fa-clone"></i>
                                                </x-button>
                                            </a>
                                        @endif

                                        <a href="{{ route('companies.edit', $company->id) }}">
                                            <x-button variant="warning">
                                                <i class="fas fa-edit"></i>
                                            </x-button>
                                        </a>

                                        @if ($company->parent_id)
                                            <a href="{{ route('companies.edit', $company->parent_id) }}">
                                                <x-button variant="primary" title="Editar empresa principal">
                                                    <i class="fas fa-edit"></i>
                                                </x-button>
                                            </a>
                                        @endif

                                        @if (auth()->user()->role === 'admin')
                                            <form method="POST" action="{{ route('companies.destroy', $company->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="danger" onclick="if (!confirm('Você tem certeza que quer deletar?')) return false">
                                                    <i class="fas fa-trash"></i>
                                                </x-button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        {{ $companies->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
