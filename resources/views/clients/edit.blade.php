<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Editar cliente {{ "#{$client->id} - {$client->user->name}" }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
            <form
                method="post"
                action="{{ route('clients.update', $client->id) }}"
            >
                @csrf
                @method('PUT')

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
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
                            :value="old('id', $client->id)"
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
                            :value="old('created_at', $client->created_at->format('d/m/Y H:i:s'))"
                            autofocus
                            disabled
                            readonly
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
                            :value="old('updated_at', $client->updated_at->format('d/m/Y H:i:s'))"
                            autofocus
                            disabled
                            readonly
                        />

                        <x-form.error :messages="$errors->get('updated_at')" />
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="name"
                            :value="__('Nome *')"
                        />

                        <x-form.input
                            id="name"
                            name="name"
                            type="text"
                            class="block w-full"
                            :value="old('name', $client->user->name)"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <x-form.error :messages="$errors->get('name')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="email"
                            :value="__('E-mail *')"
                        />

                        <x-form.input
                            id="email"
                            name="email"
                            type="email"
                            class="block w-full"
                            :value="old('email', $client->user->email)"
                            required
                            autofocus
                            autocomplete="email"
                        />

                        <x-form.error :messages="$errors->get('email')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="status"
                            :value="__('Status *')"
                        />

                        <x-form.select-status name="status" :value="old('status', $client->user->status)" />
                        <x-form.error :messages="$errors->get('status')" />
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="cpf_cnpj"
                            :value="__('CPF/CNPJ *')"
                        />

                        <x-form.input
                            id="cpf_cnpj"
                            name="client[cpf_cnpj]"
                            type="text"
                            class="block w-full"
                            :value="old('client.cpf_cnpj', $client->cpf_cnpj)"
                            x-mask:dynamic="cpfCnpjMask"
                            autofocus
                            required
                            autocomplete="client[cpf_cnpj]"
                        />

                        <x-form.error :messages="$errors->get('client.cpf_cnpj')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="phone"
                            :value="__('Telefone *')"
                        />

                        <x-form.input
                            id="phone"
                            name="client[phone]"
                            type="text"
                            class="block w-full"
                            :value="old('client.phone', $client->phone)"
                            x-mask:dynamic="phoneMask"
                            autofocus
                            required
                            autocomplete="client[phone]"
                        />

                        <x-form.error :messages="$errors->get('client.phone')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="phone2"
                            :value="__('Whatsapp')"
                        />

                        <x-form.input
                            id="phone2"
                            name="client[phone2]"
                            type="text"
                            class="block w-full"
                            :value="old('client.phone2', $client->phone2)"
                            x-mask:dynamic="phoneMask"
                            autofocus
                            autocomplete="client[phone2]"
                        />

                        <x-form.error :messages="$errors->get('client.phone2')" />
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="cep"
                            :value="__('CEP')"
                        />

                        <x-form.input
                            id="cep"
                            name="client[cep]"
                            type="text"
                            class="block w-full"
                            :value="old('client.cep', $client->cep)"
                            x-mask="99999-999"
                            autofocus
                            autocomplete="client[cep]"
                            x-on:keyup="getAddressByCep()"
                            x-model="cep"
                            x-ref="cep"
                        />

                        <x-form.error :messages="$errors->get('client.cep')" />
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="address"
                            :value="__('Endereço')"
                        />

                        <x-form.input
                            id="address"
                            name="client[address]"
                            type="text"
                            class="block w-full"
                            :value="old('client.address', $client->address)"
                            autofocus
                            autocomplete="client[address]"
                            x-model="address"
                        />

                        <x-form.error :messages="$errors->get('client.address')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="number"
                            :value="__('Número')"
                        />

                        <x-form.input
                            id="number"
                            name="client[number]"
                            type="text"
                            class="block w-full"
                            :value="old('client.number', $client->number)"
                            autofocus
                            autocomplete="client[number]"
                            x-model="number"
                        />

                        <x-form.error :messages="$errors->get('client.number')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="complement"
                            :value="__('Complemento')"
                        />

                        <x-form.input
                            id="complement"
                            name="client[complement]"
                            type="text"
                            class="block w-full"
                            :value="old('client.complement', $client->complement)"
                            autofocus
                            autocomplete="client[complement]"
                            x-model="complement"
                        />

                        <x-form.error :messages="$errors->get('client.complement')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="neighborhood"
                            :value="__('Bairro')"
                        />

                        <x-form.input
                            id="neighborhood"
                            name="client[neighborhood]"
                            type="text"
                            class="block w-full"
                            :value="old('client.neighborhood', $client->neighborhood)"
                            autofocus
                            autocomplete="client[neighborhood]"
                            x-model="neighborhood"
                        />

                        <x-form.error :messages="$errors->get('client.neighborhood')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="city"
                            :value="__('Cidade')"
                        />

                        <x-form.input
                            id="city"
                            name="client[city]"
                            type="text"
                            class="block w-full"
                            :value="old('client.city', $client->city)"
                            autofocus
                            autocomplete="client[city]"
                            x-model="city"
                        />

                        <x-form.error :messages="$errors->get('client.city')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="state"
                            :value="__('Estado')"
                        />

                        <x-form.input
                            id="state"
                            name="client[state]"
                            type="text"
                            class="block w-full"
                            :value="old('client.state', $client->state)"
                            autofocus
                            autocomplete="client[state]"
                            x-model="state"
                        />

                        <x-form.error :messages="$errors->get('client.state')" />
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
</x-app-layout>
