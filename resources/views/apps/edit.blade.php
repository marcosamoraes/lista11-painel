<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Editar app {{ "#{$app->id}" }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
            <form
                method="post"
                action="{{ route('apps.update', $app->id) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4" x-data="imageViewer()">
                    <div class="space-y-2">
                        <x-form.label for="image" :value="__('Imagem')" />
                        <x-form.input-file id="image" name="image" :value="old('image')" autofocus autocomplete="image" @change="fileChosen" />
                        <x-form.error :messages="$errors->get('image')" />
                    </div>
                    <input type="hidden" name="image_url" value="{{ old('image', $app->image_url) }}">

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

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="title"
                            :value="__('Título')"
                        />

                        <x-form.input
                            id="title"
                            name="title"
                            type="text"
                            class="block w-full"
                            :value="old('title', $app->title)"
                            autofocus
                            autocomplete="title"
                            required
                        />

                        <x-form.error :messages="$errors->get('title')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="active"
                            :value="__('Status *')"
                        />

                        <x-form.select-status name="active" :value="old('active', $app->active)" />
                        <x-form.error :messages="$errors->get('active')" />
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
