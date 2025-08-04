<div class="container-fluid bg-white shadow p-3 mb-5 bg-body-tertiary rounded border-left-primary">
    <small>Aviso: campos com * são obrigatórios</small>
    <form class="form" wire:submit.prevent="save(false)" enctype="multipart/form-data">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'home' ? 'active' : '' }}"
                    wire:click.prevent="$set('activeTab', 'home')" href="#">Formulário</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === 'profile' ? 'active' : '' }}"
                    wire:click.prevent="$set('activeTab', 'profile')" href="#">Imagem</a>
            </li>
        </ul>

        <div class="tab-content py-3" id="myTabContent">
            <div class="tab-pane fade {{ $activeTab === 'home' ? 'show active' : '' }}" id="home" role="tabpanel">
                @foreach ($columns as $key => $column)
                    @if ($key !== 'active')
                        <div class="mb-3">
                            @if ($key == 'code')
                                @can('admin')
                                    <label for="{{ $key }}"
                                        class="form-label labelForm">{{ __("columns.$key") }}</label>
                                    <input name="form.data.{{ $key }}"
                                        wire:model.live="form.data.{{ $key }}" type="{{ $column }}"
                                        class="form-control" id="{{ $key }}">

                                    @error('form.data.' . $key)
                                        <span class="text-danger text-sm">{{ $message }}</span>
                                    @enderror
                                @endcan
                            @else
                                <label for="{{ $key }}"
                                    class="form-label labelForm">{{ __("columns.$key") }}</label>
                                <input name="form.data.{{ $key }}"
                                    wire:model.live="form.data.{{ $key }}" type="{{ $column }}"
                                    class="form-control" id="{{ $key }}">

                                @error('form.data.' . $key)
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            @endif

                        </div>
                    @else
                        <div class="mb-3 form-check">
                            <div class="form-check">
                                <input value="1" class="form-check-input" type="radio"
                                    name="form.data.{{ $key }}"
                                    wire:model.lazy="form.data.{{ $key }}" id="flexRadioDefault1"
                                    {{ (isset($data) && $data->$key == 1) || Route::currentRouteName() == $route . '.create' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Ativado
                                </label>
                            </div>
                            <div class="form-check">
                                <input value="0" class="form-check-input" type="radio"
                                    name="form.data.{{ $key }}"
                                    wire:model.lazy="form.data.{{ $key }}" id="flexRadioDefault2"
                                    {{ isset($data) && $data->$key == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Desativado
                                </label>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="tab-pane fade {{ $activeTab === 'profile' ? 'show active' : '' }}" id="profile"
                role="tabpanel">
                <div class="mb-3 mt-3">
                    <label for="images" class="form-label">Upload de Imagem(ns)</label>
                    <input type="file" class="form-control" id="images" wire:model.live="images"
                        accept="image/png, image/gif, image/jpeg" multiple>

                    <div wire:loading wire:target="images">
                        <span class="text-info">Carregando imagem(ns)...</span>
                    </div>

                    @error('images.*')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                    @error('images')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror

                    <div class="images image-load">
                        @if (!empty($existingImages))
                            <div class="d-flex flex-wrap image-preview-grid py-3">
                                @foreach ($existingImages as $image)
                                    <div class="card me-3 mx-1 my-2 @if (in_array($image['id'], $imagesToRemove)) image-remove @endif"
                                        style="width: 250px;">
                                        <div class="card-header d-flex justify-content-end">
                                            <button type="button" class="btn-close bg-transparent"
                                                wire:click="toggleImageRemoval({{ $image['id'] }})"
                                                aria-label="Close">&times;</button>
                                        </div>
                                        <div class="card-body p-1 text-center">
                                            <img src="{{ asset('storage/' . $image['path']) }}"
                                                class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Nenhuma imagem encontrada para este teste.</p>
                        @endif

                        @if ($images)
                            <h5 class="mt-3">Pré-visualização de Novas Imagens:</h5>
                            <div class="d-flex flex-wrap image-preview-grid">
                                @foreach ($images as $key => $image)
                                    @if (is_object($image) && method_exists($image, 'temporaryUrl'))
                                        <div class="card me-3 my-3" style="width: 250px;">
                                            <div class="card-header d-flex justify-content-end">
                                                <button type="button" class="btn-close bg-transparent"
                                                    aria-label="Close"
                                                    wire:click="removeTemporaryImage({{ $key }})">&times;</button>
                                            </div>
                                            <div class="card-body p-1 text-center">
                                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded"
                                                    style="max-height: 150px; object-fit: cover;">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <nav class="d-none">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-form-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-form" type="button" role="tab" aria-controls="nav-form"
                        aria-selected="true">Formulário</button>
                    <button class="nav-link" id="nav-image-tab" data-bs-toggle="tab" data-bs-target="#nav-image"
                        type="button" role="tab" aria-controls="nav-image"
                        aria-selected="false">Imagem</button>
                </div>
            </nav>
            <div class="d-flex justify-content-end">
                <div class="btn-group" role="group" aria-label="Ações">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <button type="button" class="btn btn-success" wire:click="save(true)">Salvar e adicionar
                        outro</button>
                    <a href="{{ route(Str::beforeLast($this->route, '.')) }}" class="btn btn-danger"
                        wire:click="back">Voltar</a>
                </div>
            </div>
    </form>
</div>
