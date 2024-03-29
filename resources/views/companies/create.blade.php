<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Cadastrar empresa
        </h2>
    </x-slot>

    <div class="space-y-6">

        <form method="post" action="{{ route('companies.store') }}" class="flex gap-6 flex-col" enctype="multipart/form-data">
            @csrf
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                <header class="mb-5">
                    <h2 class="text-lg font-medium">
                        {{ __('Informações Gerais') }}
                    </h2>
                </header>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-4 gap-y-6 gap-x-4">
                    <div class="space-y-2 col-span-4">
                        <x-form.label for="name" :value="__('Nome *')" />

                        <x-form.input id="name" name="name" type="text" class="block w-full" :value="old('name')"
                            required autofocus autocomplete="name" />

                        <x-form.error :messages="$errors->get('name')" />
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <div class="space-y-2">
                            <x-form.label
                                for="user_id"
                                :value="__('Vendedor')"
                            />

                            <x-form.select
                                id="user_id"
                                name="user_id"
                                type="text"
                                class="block w-full select2"
                                :value="old('user_id')"
                                autofocus
                                autocomplete="user_id"
                            >
                                <option value="">Selecione</option>
                                @foreach ( $sellers as $seller )
                                    <option value="{{ $seller->id }}" {{ old('user_id') == $seller->id ? 'selected' : false }}>{{ $seller->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.error :messages="$errors->get('user_id')" />
                        </div>
                    @endif

                    @if (auth()->user()->role === 'user')
                        <input type="hidden" name="client_id" value="{{ auth()->id() }}">
                    @else
                        <div class="space-y-2">
                            <x-form.label
                                for="client_id"
                                :value="__('Cliente *')"
                            />

                            <x-form.select
                                id="client_id"
                                name="client_id"
                                type="text"
                                class="block w-full select2"
                                :value="old('client_id')"
                                required
                                autofocus
                                autocomplete="client_id"
                            >
                                <option value="">Selecione</option>
                                @foreach ( $clients as $client )
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : false }}>{{ $client->user->name }}</option>
                                @endforeach
                            </x-form.select>

                            <x-form.error :messages="$errors->get('client_id')" />
                        </div>
                    @endif

                    <div class="space-y-2">
                        <x-form.label for="phone" :value="__('Telefone *')" />

                        <x-form.input id="phone" name="phone" type="text" class="block w-full"
                            :value="old('phone')" x-mask:dynamic="phoneMask" autofocus required
                            autocomplete="phone" />

                        <x-form.error :messages="$errors->get('phone')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="phone2" :value="__('Telefone 2')" />

                        <x-form.input id="phone2" name="phone2" type="text" class="block w-full"
                            :value="old('phone2')" x-mask:dynamic="phoneMask" autofocus autocomplete="phone2" />

                        <x-form.error :messages="$errors->get('phone2')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="whatsapp" :value="__('Whatsapp')" />

                        <x-form.input id="whatsapp" name="whatsapp" type="text" class="block w-full"
                            :value="old('whatsapp')" x-mask:dynamic="phoneMask" autofocus
                            autocomplete="phone" />

                        <x-form.error :messages="$errors->get('whatsapp')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="whatsapp2" :value="__('Whatsapp 2')" />

                        <x-form.input id="whatsapp2" name="whatsapp2" type="text" class="block w-full"
                            :value="old('whatsapp2')" x-mask:dynamic="phoneMask" autofocus
                            autocomplete="phone" />

                        <x-form.error :messages="$errors->get('whatsapp2')" />
                    </div>

                    <div class="space-y-2 col-span-2">
                        <x-form.label for="payment_methods" :value="__('Métodos de Pagamento *')" />

                        <x-form.select
                            id="payment_methods"
                            name="payment_methods[]"
                            type="text"
                            class="block w-full select2-multiple"
                            required
                            autofocus
                            multiple
                            autocomplete="payment_methods"
                        >
                            <option value="dinheiro" {{ in_array('dinheiro', old('payment_methods', [])) ? 'selected' : false }}>Dinheiro</option>
                            <option value="pix" {{ in_array('pix', old('payment_methods', [])) ? 'selected' : false }}>Pix</option>
                            <option value="cartão de crédito" {{ in_array('cartão de crédito', old('payment_methods', [])) ? 'selected' : false }}>Cartão de crédito</option>
                            <option value="cartão de débito" {{ in_array('cartão de débito', old('payment_methods', [])) ? 'selected' : false }}>Cartão de débito</option>
                            <option value="boleto" {{ in_array('boleto', old('payment_methods', [])) ? 'selected' : false }}>Boleto</option>
                            <option value="transferência bancária" {{ in_array('transferência bancária', old('payment_methods', [])) ? 'selected' : false }}>Transferência bancária</option>
                        </x-form.select>

                        <x-form.error :messages="$errors->get('payment_methods')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="featured"
                            :value="__('Destaque *')"
                        />

                        <x-form.select
                            id="featured"
                            name="featured"
                            type="text"
                            class="block w-full"
                            :value="old('featured')"
                            required
                            autofocus
                            autocomplete="featured"
                        >
                            <option value="0" {{ !old('featured', 0) ? 'selected' : false }}>Não</option>
                            <option value="1" {{ old('featured', 0) ? 'selected' : false }}>Sim</option>
                        </x-form.select>

                        <x-form.error :messages="$errors->get('featured')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="opening_24h"
                            :value="__('Aberto 24h *')"
                        />

                        <x-form.select
                            id="opening_24h"
                            name="opening_24h"
                            type="text"
                            class="block w-full"
                            :value="old('opening_24h')"
                            required
                            autofocus
                            autocomplete="opening_24h"
                        >
                            <option value="0" {{ !old('opening_24h', 0) ? 'selected' : false }}>Não</option>
                            <option value="1" {{ old('opening_24h', 0) ? 'selected' : false }}>Sim</option>
                        </x-form.select>

                        <x-form.error :messages="$errors->get('opening_24h')" />
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="description"
                            :value="__('Descrição')"
                        />

                        <x-form.textarea
                            id="description"
                            name="description"
                            type="text"
                            class="block w-full"
                            :value="old('description')"
                            autofocus
                            autocomplete="description"
                        ></x-form.textarea>

                        <x-form.error :messages="$errors->get('description')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="opening_hours"
                            :value="__('Horário de funcionamento')"
                        />

                        <x-form.textarea
                            id="opening_hours"
                            name="opening_hours"
                            type="text"
                            class="block w-full"
                            :value="old('opening_hours', $openingHours)"
                            autofocus
                            autocomplete="opening_hours"
                        ></x-form.textarea>

                        <x-form.error :messages="$errors->get('opening_hours')" />
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                <header class="mb-5">
                    <h2 class="text-lg font-medium">
                        {{ __('Endereço') }}
                    </h2>
                </header>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label for="cep" :value="__('CEP *')" />

                        <x-form.input id="cep" name="cep" type="text" class="block w-full"
                            :value="old('cep')" x-mask="99999-999" autofocus autocomplete="cep"
                            x-on:keyup="getAddressByCep()" x-model="cep" x-ref="cep" required />

                        <x-form.error :messages="$errors->get('cep')" />
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label for="address" :value="__('Endereço *')" />

                        <x-form.input id="address" name="address" type="text" class="block w-full"
                            :value="old('address')" autofocus autocomplete="address" x-model="address" required />

                        <x-form.error :messages="$errors->get('address')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="number" :value="__('Número *')" />

                        <x-form.input id="number" name="number" type="text" class="block w-full"
                            :value="old('number')" autofocus autocomplete="number" x-model="number" required />

                        <x-form.error :messages="$errors->get('number')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="complement" :value="__('Complemento')" />

                        <x-form.input id="complement" name="complement" type="text" class="block w-full"
                            :value="old('complement')" autofocus autocomplete="complement" x-model="complement" />

                        <x-form.error :messages="$errors->get('complement')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="neighborhood" :value="__('Bairro *')" />

                        <x-form.input id="neighborhood" name="neighborhood" type="text" class="block w-full"
                            :value="old('neighborhood')" autofocus autocomplete="neighborhood" x-model="neighborhood" required />

                        <x-form.error :messages="$errors->get('neighborhood')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="city" :value="__('Cidade *')" />

                        <x-form.input id="city" name="city" type="text" class="block w-full"
                            :value="old('city')" autofocus autocomplete="city" x-model="city" required />

                        <x-form.error :messages="$errors->get('city')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="state" :value="__('Estado *')" />

                        <x-form.input id="state" name="state" type="text" class="block w-full"
                            :value="old('state')" autofocus autocomplete="state" x-model="state" required />

                        <x-form.error :messages="$errors->get('state')" />
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                <header class="mb-5">
                    <h2 class="text-lg font-medium">
                        {{ __('E-mail e Redes Sociais') }}
                    </h2>
                </header>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label for="email" :value="__('E-mail para contato')" />

                        <x-form.input id="email" name="email" type="email" class="block w-full"
                            :value="old('email')" autofocus autocomplete="email" />

                        <x-form.error :messages="$errors->get('email')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="site" :value="__('Site')" />

                        <x-form.input id="site" name="site" type="text" class="block w-full"
                            :value="old('site')" autofocus autocomplete="site" />

                        <x-form.error :messages="$errors->get('site')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="facebook" :value="__('Facebook')" />

                        <x-form.input id="facebook" name="facebook" type="text" class="block w-full"
                            :value="old('facebook')" autofocus autocomplete="facebook" />

                        <x-form.error :messages="$errors->get('facebook')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="instagram" :value="__('Instagram')" />

                        <x-form.input id="instagram" name="instagram" type="text" class="block w-full"
                            :value="old('instagram')" autofocus autocomplete="instagram" />

                        <x-form.error :messages="$errors->get('instagram')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="youtube" :value="__('Youtube')" />

                        <x-form.input id="youtube" name="youtube" type="text" class="block w-full"
                            :value="old('youtube')" autofocus autocomplete="youtube" />

                        <x-form.error :messages="$errors->get('youtube')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="google_my_business" :value="__('Google Meu Negócio')" />

                        <x-form.input id="google_my_business" name="google_my_business" type="text" class="block w-full"
                            :value="old('google_my_business')" autofocus autocomplete="google_my_business" />

                        <x-form.error :messages="$errors->get('google_my_business')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="video_link" :value="__('Link dos Vídeos')" />

                        <x-form.input id="video_link" name="video_link" type="text" class="block w-full" placeholder="Separe os links por vírgula"
                            :value="old('video_link')" autofocus autocomplete="video_link" />

                        <x-form.error :messages="$errors->get('video_link')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="photo_360_link" :value="__('Link da foto 360º')" />

                        <x-form.input id="photo_360_link" name="photo_360_link" type="text" class="block w-full"
                            :value="old('photo_360_link')" autofocus autocomplete="photo_360_link" placeholder="Cole aqui o LINK gerado pelo Google Maps" />

                        <x-form.error :messages="$errors->get('photo_360_link')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="photo_360_code" :value="__('Código da foto 360º')" />

                        <x-form.textarea
                            id="photo_360_code"
                            name="photo_360_code"
                            type="text"
                            class="block w-full"
                            :value="old('photo_360_code')"
                            autofocus
                            autocomplete="photo_360_code"
                            placeholder="Cole aqui o LINK gerado pelo Google Maps"
                        ></x-form.textarea>

                        <x-form.error :messages="$errors->get('photo_360_code')" />
                    </div>
                </div>
            </div>



            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                <header class="mb-5">
                    <h2 class="text-lg font-medium">
                        {{ __('Aplicativos') }}
                    </h2>
                </header>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="space-y-2">
                            <x-form.label for="apps_name{{ $i }}" :value="__('Aplicativo ' . $i)" />

                            <x-form.select
                                id="apps_name{{ $i }}"
                                name="apps[{{ $i }}][name]"
                                type="text"
                                class="block w-full select2"
                                autofocus
                                autocomplete="apps"
                            >
                                <option value="">Selecione</option>
                                @foreach ( $apps as $app )
                                    <option
                                        value="{{ $app->id }}"
                                        {{ $app->id == old('apps.' . $i . '.name') ? 'selected' : false }}
                                    >
                                        {{ $app->name }}
                                    </option>
                                @endforeach
                            </x-form.select>

                            <x-form.error :messages="$errors->get('apps.' . $i . '.name')" />
                        </div>

                        <div class="space-y-2">
                            <x-form.label for="apps_value{{ $i }}" :value="__('Link do aplicativo ' . $i)" />

                            <x-form.input id="apps_value{{ $i }}" name="apps[{{ $i }}][value]" type="text" class="block w-full"
                                :value="old('apps.' . $i . '.value')" autofocus autocomplete="apps_value{{ $i }}" />

                            <x-form.error :messages="$errors->get('apps.' . $i . '.value')" />
                        </div>
                    @endfor
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                <header class="mb-5">
                    <h2 class="text-lg font-medium">
                        {{ __('Imagens ') }}
                    </h2>
                </header>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4" x-data="imageViewer()">
                    <div class="space-y-2">
                        <x-form.label for="image" :value="__('Imagem principal (500x500px)')" />
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

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4" x-data="bannerViewer()">
                    <div class="space-y-2">
                        <x-form.label for="banner" :value="__('Banner (1920x520px)')" />
                        <x-form.input-file id="banner" name="banner" :value="old('banner')" autofocus autocomplete="banner" @change="fileChosen" />
                        <x-form.error :messages="$errors->get('banner')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="banner" :value="__('Preview')" />
                        <template x-if="bannerUrl">
                            <img :src="bannerUrl"
                                class="object-cover rounded border border-gray-200"
                                style="width: 100px; height: 100px;"
                            >
                        </template>
                        <!-- Show the gray box when banner is not available -->
                        <template x-if="!bannerUrl">
                            <div
                                class="border rounded border-gray-200 bg-gray-100 w-full lg:w-[100px] h-[100px]"
                            ></div>
                        </template>
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4" x-data="imagesViewer()">
                    <div class="space-y-2">
                        <x-form.label for="images" :value="__('Outras imagens')" />
                        <x-form.input-file id="images" name="images[]" :value="old('images')" autofocus autocomplete="images" multiple @change="fileChosen" />
                        <x-form.error :messages="$errors->get('images')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="images" :value="__('Preview')" />
                        <template x-if="imagesUrl">
                            {{-- loop for each imagesUrl --}}
                            <div class="flex gap-2">
                                <template x-for="(url, index) in imagesUrl" :key="index">
                                    <img :src="url"
                                        class="object-cover rounded border border-gray-200"
                                        style="width: 100px; height: 100px;"
                                    >
                                </template>
                            </div>
                        </template>
                        <!-- Show the gray box when images is not available -->
                        <template x-if="!imagesUrl">
                            <div
                                class="border rounded border-gray-200 bg-gray-100 w-full lg:w-[100px] h-[100px]"
                            ></div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="categories"
                            :value="__('Categorias *')"
                        />

                        <x-form.select
                            id="categories"
                            name="categories[]"
                            type="text"
                            class="block w-full select2-multiple"
                            required
                            autofocus
                            multiple
                            autocomplete="categories"
                        >
                            <option value="">Selecione</option>
                            @foreach ( $categories as $category )
                                <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : false }}>{{ $category->name }}</option>
                            @endforeach
                        </x-form.select>

                        <x-form.error :messages="$errors->get('categories')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="tags" :value="__('Tags')" />

                        <x-form.input id="tags" name="tags" type="text" class="block w-full" :value="old('tags')"
                            autofocus autocomplete="tags" placeholder="Separe as tags por vírgula" />

                        <x-form.error :messages="$errors->get('tags')" />
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
</x-app-layout>
