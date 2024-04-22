<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Cadastrar venda
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
            <form
                method="post"
                action="{{ route('orders.store') }}"
                enctype="multipart/form-data"
                x-data="{
                    parcels: {{ old('parcels', 1) }},
                    parcelData: [
                        @for ($i = 1; $i <= old('parcels', 1); $i++)
                            {
                                id: {{ $i }},
                                value: {{ old("parcelData.$i.value", 0) }},
                                due_date: '{{ old("parcelData.$i.due_date", now()->format('Y-m-d')) }}',
                                payment_method: '{{ old("parcelData.$i.payment_method", '') }}',
                            },
                        @endfor
                    ],
                }"
            >
                @csrf

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-5 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="company_id"
                            :value="__('Empresa *')"
                        />

                        <x-form.select
                            id="company_id"
                            name="company_id"
                            type="text"
                            class="block w-full select2"
                            :value="old('company_id')"
                            required
                            autofocus
                            autocomplete="company_id"
                        >
                            <option value="">Selecione</option>
                            @foreach ( $companies as $company )
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.error :messages="$errors->get('company_id')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="pack_id"
                            :value="__('Pacote *')"
                        />

                        <x-form.select
                            id="pack_id"
                            name="pack_id"
                            type="text"
                            class="block w-full"
                            :value="old('pack_id')"
                            required
                            autofocus
                            autocomplete="pack_id"
                        >
                            <option value="">Selecione</option>
                            @foreach ( $packs as $pack )
                                <option value="{{ $pack->id }}">{{ $pack->title }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.error :messages="$errors->get('pack_id')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="value"
                            :value="__('Valor *')"
                        />

                        <x-form.input-with-icon-wrapper>
                            <x-slot name="icon">
                                <i class="fa-solid fa-brazilian-real-sign"></i>
                            </x-slot>

                            <x-form.input
                                withicon
                                id="value"
                                name="value"
                                type="text"
                                class="block w-full"
                                :value="old('value')"
                                x-mask:dynamic="$money($input, ',', '.', 2)"
                                required
                                autofocus
                                autocomplete="value"
                            />
                        </x-form.input-with-icon-wrapper>

                        <x-form.error :messages="$errors->get('value')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="parcels"
                            :value="__('Parcelas *')"
                        />

                        <x-form.input
                            id="parcels"
                            name="parcels"
                            type="number"
                            class="block w-full"
                            min="1"
                            max="12"
                            required
                            autofocus
                            autocomplete="parcels"
                            x-model="parcels"
                            x-on:change="updateParcels(parcels, parcelData)"
                        />

                        <x-form.error :messages="$errors->get('parcels')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="status"
                            :value="__('Status *')"
                        />

                        <x-form.select
                            id="status"
                            name="status"
                            type="text"
                            class="block w-full"
                            :value="old('status')"
                            required
                            autofocus
                            autocomplete="status"
                        >
                            <option {{ !old('status') ? 'selected' : false }}>Selecione</option>
                            <option value="accomplished" {{ old('status') === 'accomplished' ? 'selected' : false }}>Concretizado</option>
                            <option value="opened" {{ old('status') === 'opened' ? 'selected' : false }}>Em Aberto</option>
                            <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : false }}>Cancelado</option>
                            <option value="not_renewed" {{ old('status') === 'not_renewed' ? 'selected' : false }}>Não renovado</option>
                        </x-form.select>

                        <x-form.error :messages="$errors->get('status')" />
                    </div>
                </div>


                <template x-for="parcel in parcelData" :key="parcel.id">
                    <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                        <div class="space-y-2">
                            <x-form.label
                                x-text="'Parcela ' + parcel.id"
                            />

                            <x-form.input-with-icon-wrapper>
                                <x-slot name="icon">
                                    <i class="fa-solid fa-brazilian-real-sign"></i>
                                </x-slot>

                                <x-form.input
                                    withicon
                                    type="text"
                                    class="block w-full"
                                    x-mask:dynamic="$money($input, ',', '.', 2)"
                                    required
                                    autofocus
                                    autocomplete="parcelData[0][value]"
                                    x-model="parcel.value"
                                    x-on:change="updateParcelData(parcelData, parcel.id, 'value', parcel.value)"
                                />
                            </x-form.input-with-icon-wrapper>

                            <x-form.error :messages="$errors->get('parcelData.0.value')" />
                        </div>

                        <div class="space-y-2">
                            <x-form.label
                                for="due_date"
                                :value="__('Vencimento')"
                            />

                            <x-form.input
                                id="due_date"
                                name="parcelData[0][due_date]"
                                type="date"
                                class="block w-full"
                                :value="old('parcelData.0.due_date')"
                                required
                                autofocus
                                autocomplete="due_date"
                                x-model="parcel.due_date"
                                x-on:change="updateParcelData(parcelData, parcel.id, 'due_date', parcel.due_date)"
                            />

                            <x-form.error :messages="$errors->get('parcelData.0.due_date')" />
                        </div>

                        <div class="space-y-2">
                            <x-form.label
                                for="payment_method"
                                :value="__('Forma de pagamento')"
                            />

                            <x-form.select
                                id="payment_method"
                                name="parcelData[0][payment_method]"
                                type="text"
                                class="block w-full"
                                :value="old('parcelData.0.payment_method')"
                                autofocus
                                autocomplete="payment_method"
                                x-model="parcel.payment_method"
                                x-on:change="updateParcelData(parcelData, parcel.id, 'payment_method', parcel.payment_method)"
                            >
                                <option value="">Selecione</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="pix">Pix</option>
                                <option value="cartão de crédito">Cartão de crédito</option>
                                <option value="cartão de débito">Cartão de débito</option>
                                <option value="boleto">Boleto</option>
                                <option value="transferência bancária">Transferência bancária</option>
                                <option value="A combinar">A combinar</option>
                            </x-form.select>

                            <x-form.error :messages="$errors->get('parcelData.0.payment_method')" />
                        </div>
                    </div>
                </template>

                <input type="hidden" name="parcels_data" x-model="JSON.stringify(parcelData)">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                    <header class="mb-5">
                        <h2 class="text-lg font-medium">
                            {{ __('Imagens ') }}
                        </h2>
                    </header>

                    <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4" x-data="imageViewer()">
                        <div class="space-y-2">
                            <x-form.label for="image" :value="__('Imagem do contrato')" />
                            <x-form.input-file id="image" name="image" :value="old('image')" autofocus autocomplete="image" @change="fileChosen" />
                            <x-form.error :messages="$errors->get('image')" />
                        </div>

                        <div class="space-y-2">
                            <x-form.label for="image" :value="__('Preview')" />
                            <template x-if="imageUrl">
                                <img :src="imageUrl"
                                    class="object-cover rounded border border-gray-200"
                                    style="width: 100px; height: 100px;"
                                >
                            </template>
                            <!-- Show the gray box when image is not available -->
                            <template x-if="!imageUrl">
                                <div
                                    class="border rounded border-gray-200 bg-gray-100 w-full lg:w-[100px] h-[100px]"
                                ></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <x-button>
                        {{ __('Save') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateParcelData(parcelData, id, field, value) {
            parcelData[id - 1][field] = value;
        }

        function updateParcels(parcels, parcelData) {
            if (parcels > parcelData.length) {
                for (let i = parcelData.length + 1; i <= parcels; i++) {
                    let dueDate = new Date();
                    dueDate.setMonth(dueDate.getMonth() + (i-1));
                    parcelData.push({
                        id: i,
                        value: 0,
                        due_date: dueDate.toISOString().slice(0, 10),
                        payment_method: '',
                    });
                }
            } else {
                parcelData.splice(parcels);
            }
        }
    </script>
</x-app-layout>
