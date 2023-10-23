<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Vendedores
        </h2>
        <a href="{{ route('sellers.create') }}"><x-button><i class="fa fa-plus mr-2"></i>Cadastrar</x-button></a>
    </x-slot>

    <x-search-bar />

    <div class="py-12">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-eval-1 overflow-hidden shadow-sm sm:rounded-lg">
                <div
                    class="overflow-hidden overflow-x-auto p-6 bg-white dark:bg-dark-eval-1 border-b border-gray-200 dark:border-dark-eval-1">
                    <div class="min-w-full align-middle">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-500 border dark:border-gray-500 mt-5">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-left">
                                        <span
                                            class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">Nome</span>
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-left">
                                        <span
                                            class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">Ações</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-dark-eval-1 divide-y divide-gray-200 divide-solid">
                                @foreach ($sellers as $seller)
                                    <tr class="bg-white dark:bg-dark-eval-1">
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-white">
                                            <div class="flex gap-3 items-center">
                                                @if ($seller->user->status)
                                                    <span class="block border border-green-500 bg-green-500 w-2 h-2 rounded-full"></span>
                                                @else
                                                    <span class="block border border-red-500 bg-red-500 w-2 h-2 rounded-full"></span>
                                                @endif
                                                <p>
                                                    {{ $seller->user->name }}<br />
                                                    <small>{{ $seller->phone }}</small>
                                                </p>
                                            </div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-no-wrap text-sm leading-5 flex-wrap text-gray-900 dark:text-white flex gap-3">
                                            <a href="{{ route('sellers.edit', $seller->id) }}">
                                                <x-button variant="warning">
                                                    <i class="fas fa-edit"></i>
                                                </x-button>
                                            </a>
                                            <form method="POST" action="{{ route('sellers.destroy', $seller->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="danger"
                                                    onclick="if (!confirm('Você tem certeza que quer deletar?')) return false">
                                                    <i class="fas fa-trash"></i>
                                                </x-button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        {{ $sellers->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
