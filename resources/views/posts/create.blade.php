<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Cadastrar post
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800">
            <form
                method="post"
                action="{{ route('posts.store') }}"
                enctype="multipart/form-data"
                id="postForm"
            >
                @csrf

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-4" x-data="imageViewer()">
                    <div class="space-y-2">
                        <x-form.label for="image" :value="__('Imagem')" />
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

                <div class="mb-5 grid grid-cols-1 sm:grid-cols-3 gap-y-6 gap-x-4">
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
                            :value="old('title')"
                            autofocus
                            autocomplete="title"
                            required
                        />

                        <x-form.error :messages="$errors->get('title')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label for="tags" :value="__('Tags')" />

                        <x-form.input id="tags" name="tags" type="text" class="block w-full" :value="old('tags')"
                            autofocus autocomplete="tags" placeholder="Separe as tags por vírgula" />

                        <x-form.error :messages="$errors->get('tags')" />
                    </div>

                    <div class="space-y-2">
                        <x-form.label
                            for="active"
                            :value="__('Status *')"
                        />

                        <x-form.select-status name="active" :value="old('active', true)" />
                        <x-form.error :messages="$errors->get('active')" />
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 gap-y-6 gap-x-4">
                    <div class="space-y-2">
                        <x-form.label
                            for="content"
                            :value="__('Conteúdo')"
                        />

                        <div id="editor" style="height: 300px">
                            {!! old('content') !!}
                        </div>
                        <input type="hidden" id="content" name="content">

                        <x-form.error :messages="$errors->get('content')" />
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

<script>
    var form = document.getElementById('postForm');
    var contentInput = document.getElementById('content');
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        contentInput.value = quill.root.innerHTML;
        form.submit();
    });
</script>
