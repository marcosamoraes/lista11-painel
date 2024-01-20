<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-end w-full">
            <h2 class="text-xl font-semibold leading-tight">
                <x-button variant="black" class="toggle-filters"><i class="fa fa-filter mr-2"></i>Filtros</x-button>
            </h2>
        </div>
    </x-slot>

    <div class="relative pt-5">
        <div class="px-4 md:px-10 mx-auto w-full">
            <div>
                <div class="flex flex-wrap">
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'seller')
                        <div class="filters" style="display: none">
                            <div class="w-full mb-10 px-4">
                                <form class="flex flex-wrap gap-5">
                                    <div>
                                        <label class="block uppercase text-xs font-bold mb-2" for="initial_date">Data
                                            Inicial</label>
                                        <input type="date" id="initial_date" name="initial_date"
                                            value="{{ request()->initial_date ??now()->subDays(30)->format('Y-m-d') }}"
                                            class="border-0 px-3 py-3 text-black bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" />
                                    </div>
                                    <div>
                                        <label class="block uppercase text-xs font-bold mb-2" for="final_date">Data
                                            Final</label>
                                        <input type="date" id="final_date" name="final_date"
                                            value="{{ request()->final_date ?? now()->format('Y-m-d') }}"
                                            class="border-0 px-3 py-3 text-black placeholder-dark bg-white rounded text-sm shadow focus:outline-none focus:ring w-full" />
                                    </div>
                                    <div>
                                        <button
                                            class="bg-primary-500 text-white mt-6 active:bg-primary-500 font-bold uppercase text-xs px-4 py-3 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1"
                                            type="submit" style="transition: all 0.15s ease">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="w-full"></div>
                    @endif
                    @if (auth()->user()->role === 'admin')
                        <div class="w-6/12 sm:w-4/12 lg:w-3/12 px-4 flex justify-center items-center">
                            <a href="/clients">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-2">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-[100px] flex-initial flex flex-col gap-3 items-center justify-center">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="text-black uppercase font-bold text-xs h-[30px]">Clientes</h5>
                                                    <span class="font-semibold text-sm text-black">{{ $countClients }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-6/12 sm:w-4/12 lg:w-3/12 px-4 flex justify-center items-center">
                            <a href="/companies?status=1">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-2">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-[100px] h-[120px] flex-initial flex flex-col gap-3 items-center justify-center">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                                    <i class="fa fa-building"></i>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="text-black uppercase font-bold text-xs h-[30px]">Empresas</h5>
                                                    <div class="flex justify-between items-center">
                                                        <span class="font-semibold text-sm text-black flex items-center gap-2">
                                                            <span class="block border border-green-500 bg-green-500 w-2 h-2 rounded-full"></span>
                                                            {{ $countActiveCompanies }}
                                                        </span>
                                                        <span class="font-semibold text-sm text-black flex items-center gap-2">
                                                            <span class="block border border-red-500 bg-red-500 w-2 h-2 rounded-full"></span>
                                                            {{ $countInactiveCompanies }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-6/12 sm:w-4/12 lg:w-3/12 px-4 flex justify-center items-center">
                            <a href="/companies?initial_expire_at={{ now()->subYear()->format('Y-m-d') }}&final_expire_at={{ now()->format('Y-m-d') }}">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-2">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-[100px] h-[120px] flex-initial flex flex-col gap-3 items-center justify-center">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                                    <i class="fa fa-building"></i>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="text-black uppercase font-bold text-xs h-[30px]">Empresas Expiradas</h5>
                                                    <span class="font-semibold text-sm text-black">{{ $countExpiredCompanies }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-6/12 sm:w-4/12 lg:w-3/12 px-4 flex justify-center items-center">
                            <a href="/sellers">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-2">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-[100px] h-[120px] flex-initial flex flex-col gap-3 items-center justify-center">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-pink-500">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="text-black uppercase font-bold text-xs h-[30px]">Vendedores</h5>
                                                    <span class="font-semibold text-sm text-black">{{ $countSellers }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-6/12 sm:w-4/12 lg:w-3/12 px-4 flex justify-center items-center">
                            <a href="/orders">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-2">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-[100px] h-[120px] flex-initial flex flex-col gap-3 items-center justify-center">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-yellow-500">
                                                    <i class="fa fa-shopping-cart"></i>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="text-black uppercase font-bold text-xs h-[30px]">Vendas</h5>
                                                    <span class="font-semibold text-sm text-black">R$ {{ number_format($sumOrdersTotal, 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-6/12 sm:w-4/12 lg:w-3/12 px-4 flex justify-center items-center">
                            <a href="/contacts">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-2">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-[100px] h-[120px] flex-initial flex flex-col gap-3 items-center justify-center">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-blue-500">
                                                    <i class="fa fa-address-book"></i>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="text-black uppercase font-bold text-xs h-[30px]">Contatos</h5>
                                                    <span class="font-semibold text-sm text-black">{{ $countContacts }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-6/12 sm:w-4/12 lg:w-3/12 px-4 flex justify-center items-center">
                            <a href="/registers">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-2">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-[100px] h-[120px] flex-initial flex flex-col gap-3 items-center justify-center">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-cyan-500">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="text-black uppercase font-bold text-xs h-[30px]">Interessados</h5>
                                                    <span class="font-semibold text-sm text-black">{{ $countRegisters }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="w-full lg:w-4/12 px-4 pt-10">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                            <h5 class="text-black uppercase font-bold text-xs">Empresas por cidade</h5>
                                            <div id="chart-container" class="mt-3">
                                                <canvas class="chart" data-type="pie"
                                                    data-labels="[{!! $companiesPerCityLabels !!}]"
                                                    data-series="[{{ $companiesPerCityValues }}]"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-4/12 px-4 pt-10">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                            <h5 class="text-black uppercase font-bold text-xs">Métodos de pagamento</h5>
                                            <div id="chart-container" class="mt-3">
                                                <canvas class="chart" data-type="pie"
                                                    data-labels="[{!! $paymentMethodsLabels !!}]"
                                                    data-series="[{{ $paymentMethodsValues }}]"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-full lg:w-4/12 px-4 pt-10">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                            <h5 class="text-black uppercase font-bold text-xs">Vendas por vendedor</h5>
                                            <div id="chart-container" class="mt-3">
                                                <canvas class="chart" data-type="pie"
                                                    data-labels="[{!! $percentSalesPerSellerLabels !!}]"
                                                    data-series="[{{ $percentSalesPerSellerValues }}]"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->role === 'seller')
                        <div class="w-full lg:w-6/12 xl:w-4/12 px-4 mt-3">
                            <a href="/companies?status=1">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                <h5 class="text-black uppercase font-bold text-xs">Empresas Ativas</h5>
                                                <span
                                                    class="font-semibold text-xl text-black">{{ $countActiveCompanies }}</span>
                                            </div>
                                            <div class="relative w-auto pl-4 flex-initial">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                                    <i class="fa fa-building"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-full lg:w-6/12 xl:w-4/12 px-4 mt-3">
                            <a href="/companies?status=0">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                <h5 class="text-black uppercase font-bold text-xs">Empresas Inativas
                                                </h5><span
                                                    class="font-semibold text-xl text-black">{{ $countInactiveCompanies }}</span>
                                            </div>
                                            <div class="relative w-auto pl-4 flex-initial">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-yellow-500">
                                                    <i class="fa fa-building"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="w-full lg:w-6/12 xl:w-4/12 px-4 mt-3">
                            <a href="/orders">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                <h5 class="text-black uppercase font-bold text-xs">Vendas</h5><span
                                                    class="font-semibold text-xl text-black">R$
                                                    {{ number_format($sumOrdersTotal, 2, ',', '.') }}</span>
                                            </div>
                                            <div class="relative w-auto pl-4 flex-initial">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-blue-500">
                                                    <i class="fa fa-cart-shopping"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </a>
                    @endif

                    @if (auth()->user()->role === 'user')
                        <div class="w-full lg:w-6/12 xl:w-4/12 px-4">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                <div class="flex-auto p-4">
                                    <div class="flex flex-wrap">
                                        <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                            <h5 class="text-black uppercase font-bold text-xs">Impressões</h5><span
                                                class="font-semibold text-xl text-black">{{ $countVisits }}</span>
                                        </div>
                                        <div class="relative w-auto pl-4 flex-initial">
                                            <div
                                                class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500">
                                                <i class="fa fa-eye"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-6/12 xl:w-4/12 px-4">
                            <a href="/contacts">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                <h5 class="text-black uppercase font-bold text-xs">Contatos</h5><span
                                                    class="font-semibold text-xl text-black">{{ $countContacts }}</span>
                                            </div>
                                            <div class="relative w-auto pl-4 flex-initial">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-blue-500">
                                                    <i class="fa fa-address-book"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @foreach ($activeCompanies as $activeCompany)
                            <div class="w-full lg:w-6/12 xl:w-4/12 px-4">
                                <div
                                    class="relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg">
                                    <div class="flex-auto p-4">
                                        <div class="flex flex-wrap">
                                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                                <h5 class="text-black uppercase font-bold text-xs">Seu Contrato Vence
                                                    Em
                                                    {{ $activeCompanies->count() > 1 ? "- {$activeCompany->name}" : false }}
                                                </h5><span
                                                    class="font-semibold text-xl text-black">{{ $activeCompany->expireAt }}</span>
                                            </div>
                                            <div class="relative w-auto pl-4 flex-initial">
                                                <div
                                                    class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-yellow-500">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
