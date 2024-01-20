<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Editar venda {{ "#{$order->id}" }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
            <form
                method="post"
                action="{{ route('orders.update', $order->id) }}"
                enctype="multipart/form-data"
                x-data="{
                    parcels: {{ old('parcels', $order->parcels) }},
                    parcelData: [
                        @for ($i = 0; $i < old('parcels', $order->parcels); $i++)
                            {
                                id: {{ $i+1 }},
                                value: {{ old("parcelData.$i.value", $order->parcels_data[$i]['value'] ?? 0) }},
                                due_date: '{{ old("parcelData.$i.due_date", $order->parcels_data[$i]['due_date'] ?? now()->addMonths($i)->format('Y-m-d')) }}',
                                payment_method: '{{ old("parcelData.$i.payment_method", $order->parcels_data[$i]['payment_method'] ?? '') }}',
                            },
                        @endfor
                    ],
                }"
            >
                @csrf
                @method('PUT')

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-5 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="id"
                            :value="__('ID')"
                        />

                        <x-form.input
                            id="id"
                            name="id"
                            type="text"
                            class="block w-full"
                            :value="old('id', $order->id)"
                            autofocus
                            disabled
                            readonly
                        />

                        <x-form.error :messages="$errors->get('id')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="created_at"
                            :value="__('Cadastrado em')"
                        />

                        <x-form.input
                            id="created_at"
                            name="created_at"
                            type="text"
                            class="block w-full"
                            :value="old('created_at', $order->created_at->format('d/m/Y H:i:s'))"
                            autofocus
                        />

                        <x-form.error :messages="$errors->get('created_at')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="updated_at"
                            :value="__('Atualizado em')"
                        />

                        <x-form.input
                            id="updated_at"
                            name="updated_at"
                            type="text"
                            class="block w-full"
                            :value="old('updated_at', $order->updated_at->format('d/m/Y H:i:s'))"
                            autofocus
                        />

                        <x-form.error :messages="$errors->get('updated_at')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="expire_at"
                            :value="__('Data de expiração')"
                        />

                        <x-form.input
                            id="expire_at"
                            name="expire_at"
                            type="text"
                            class="block w-full"
                            :value="old('expire_at', $order->expire_at?->format('d/m/Y'))"
                            autofocus
                        />

                        <x-form.error :messages="$errors->get('expire_at')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="approved_at"
                            :value="__('Data de Aprovação')"
                        />

                        <x-form.input
                            id="approved_at"
                            name="approved_at"
                            type="text"
                            class="block w-full"
                            :value="old('approved_at', $order->approved_at?->format('d/m/Y H:i:s'))"
                            autofocus
                        />

                        <x-form.error :messages="$errors->get('approved_at')" />
                    </div>
                </div>

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
                            :value="old('company_id', $order->company_id)"
                            required
                            autofocus
                            autocomplete="company_id"
                        >
                            <option value="">Selecione</option>
                            @foreach ( $companies as $company )
                                <option value="{{ $company->id }}" {{ $company->id === old('company_id', $order->company_id) ? 'selected' : false }}>{{ $company->name }}</option>
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
                            :value="old('pack_id', $order->pack_id)"
                            required
                            autofocus
                            autocomplete="pack_id"
                        >
                            <option value="">Selecione</option>
                            @foreach ( $packs as $pack )
                                <option value="{{ $pack->id }}" {{ $pack->id === old('pack_id', $order->pack_id) ? 'selected' : false }}>{{ $pack->title }}</option>
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
                                :value="old('value', number_format($order->value, 2, ',', '.'))"
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
                            :value="old('status', $order->status)"
                            required
                            autofocus
                            autocomplete="status"
                        >
                            <option value="accomplished" {{ old('status', $order->status) === 'accomplished' ? 'selected' : false }}>Concretizado</option>
                            <option value="opened" {{ old('status', $order->status) === 'opened' ? 'selected' : false }}>Em Aberto</option>
                            <option value="cancelled" {{ old('status', $order->status) === 'cancelled' ? 'selected' : false }}>Cancelado</option>
                            <option value="not_renewed" {{ old('status', $order->status) === 'not_renewed' ? 'selected' : false }}>Não renovado</option>
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
                                    x-model="parcel.value"
                                    x-on:change="updateParcelData(parcelData, parcel.id, 'value', parcel.value)"
                                />
                            </x-form.input-with-icon-wrapper>
                        </div>

                        <div class="space-y-2">
                            <x-form.label
                                for="due_date"
                                :value="__('Vencimento')"
                            />

                            <x-form.input
                                id="due_date"
                                type="date"
                                class="block w-full"
                                required
                                autofocus
                                autocomplete="due_date"
                                x-model="parcel.due_date"
                                x-on:change="updateParcelData(parcelData, parcel.id, 'due_date', parcel.due_date)"
                            />
                        </div>

                        <div class="space-y-2">
                            <x-form.label
                                for="payment_method"
                                :value="__('Forma de pagamento')"
                            />

                            <x-form.select
                                id="payment_method"
                                type="text"
                                class="block w-full"
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
                            </x-form.select>
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
                            <x-form.input-file id="image" name="image" autofocus autocomplete="image" @change="fileChosen" />
                            <x-form.error :messages="$errors->get('image')" />
                        </div>
                        <input type="hidden" name="image_url" value="{{ old('image', $order->image_url) }}">

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
