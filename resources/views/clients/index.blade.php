<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            Clientes
        </h2>
        <div class="flex justify-end gap-3 flex-wrap flex-col-reverse sm:flex-row">
            @if (auth()->user()->role === 'admin')
                <x-button href="{{ route('clients.export') }}" variant="secondary"><i class="fa fa-file-excel mr-2"></i>Exportar</x-button>
            @endif
            <x-button href="{{ route('clients.create') }}"><i class="fa fa-plus mr-2"></i>Cadastrar</x-button>
        </div>
    </x-slot>

    <x-search-bar />

    <div class="py-12">
        <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-eval-1 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-hidden overflow-x-auto p-6 bg-white dark:bg-dark-eval-1 border-b border-gray-200 dark:border-dark-eval-1">
                    <div class="min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-500 border dark:border-gray-500 mt-5">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-left">
                                    <span class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">Nome</span>
                                </th>
                                @if (auth()->user()->role === 'admin')
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 text-left">
                                        <span class="text-xs leading-4 font-medium text-gray-500 dark:text-white uppercase tracking-wider">Ações</span>
                                    </th>
                                @endif
                            </tr>
                            </thead>

                            <tbody class="bg-white dark:bg-dark-eval-1 divide-y divide-gray-200 divide-solid">
                            @foreach($clients as $client)
                                <tr class="bg-white dark:bg-dark-eval-1">
                                    <td
                                        class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 dark:text-white">
                                        <div class="flex gap-3 items-center">
                                            @if ($client->user->status)
                                                <span class="block border border-green-500 bg-green-500 w-2 h-2 rounded-full"></span>
                                            @else
                                                <span class="block border border-red-500 bg-red-500 w-2 h-2 rounded-full"></span>
                                            @endif
                                            <p>
                                                {{ $client->user->name }}<br />
                                                @if (auth()->user()->role === 'admin')
                                                    <small>{{ $client->user->email }}</small><br />
                                                    <small>Vendedor: {{ $client->seller?->name ?? 'Sem vendedor' }}</small><br />
                                                @endif
                                                <small>{{ $client->phone }}</small>
                                            </p>
                                        </div>
                                    </td>
                                    @if (auth()->user()->role === 'admin')
                                        <td class="px-6 py-4 whitespace-no-wrap flex-wrap text-sm leading-5 text-gray-900 dark:text-white flex gap-3">
                                            <a href="https://wa.me/+55{{ preg_replace('/\D/', '', $client->phone) }}" target="_blank">
                                                <x-button variant="whatsapp" title="Whatsapp">
                                                    <i class="fab fa-whatsapp"></i>
                                                </x-button>
                                            </a>
                                            <a href="{{ route('clients.edit', $client->id) }}">
                                                <x-button variant="warning">
                                                    <i class="fas fa-edit"></i>
                                                </x-button>
                                            </a>
                                            <form method="POST" action="{{ route('clients.destroy', $client->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-button variant="danger" onclick="if (!confirm('Você tem certeza que quer deletar?')) return false">
                                                    <i class="fas fa-trash"></i>
                                                </x-button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        {{ $clients->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
