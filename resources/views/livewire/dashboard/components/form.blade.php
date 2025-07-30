<div class="container-fluid bg-white shadow p-3 mb-5 bg-body-tertiary rounded border-left-primary">
    <small>Aviso campos com * são obrigatorios</small>
    <form class="form" wire:submit.prevent="save" enctype="multipart/form-data">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                    aria-controls="home" aria-selected="true">Formulario</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                    aria-controls="profile" aria-selected="false">Imagem</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                @foreach ($columns as $key => $column)
                    @if ($key !== 'active')
                        <div class="mb-3">
                            <label for="{{ $key }}"
                                class="form-label labelForm">{{ __("columns.$key") }}</label>
                            <input name="form.data.{{ $key }}" wire:model.lazy="form.data.{{ $key }}"
                                value="{{ isset($data) ? $data->$key : '' }}" type="{{ $column }}"
                                class="form-control" id="{{ $key }}">

                            @error('form.data.' . $key)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="mb-3 form-check">
                            <div class="form-check">
                                <input value="1" class="form-check-input" type="radio" name="form.data.{{ $key }}" wire:model.lazy="form.data.{{ $key }}"
                                    id="flexRadioDefault1"
                                    {{ (isset($data) && $data->$key == 1) || Route::currentRouteName() == $route . '.create' ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Ativado
                                </label>
                            </div>
                            <div class="form-check">
                                <input value="0" class="form-check-input" type="radio" name="form.data.{{ $key }}"  wire:model.lazy="form.data.{{ $key }}" id="flexRadioDefault2"
                                    {{ isset($data) && $data->$key == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Desativado
                                </label>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                @if (Route::currentRouteName() == $route . '.show')
                    <div class="images">
                        @if (!empty($data->images) && $data->images->isNotEmpty())
                            @foreach ($data->images as $image)
                                <div class="card mr-3 my-3">
                                    <div class="card-header">
                                        <button
                                            data-route="{{ route('admin.handleImageRemoval', ['id' => $image->id]) }}"
                                            type="button" id="{{ $image->id }}" class="removeImage btn-close"
                                            aria-label="Close">&times;</button>
                                    </div>
                                    <div class="card-body">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Imagem">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Nenhuma imagem encontrada para este teste.</p>
                        @endif
                    </div>
                @endif
                <div class="mb-3">
                    <label for="image" class="form-label">Upload de Imagem</label>
                    <input type="file" class="form-control" id="images" name="images[]"
                        accept="image/png, image/gif, image/jpeg" multiple>
                    <div class="image-preview" id="imagePreview"></div>
                </div>
            </div>
        </div>
        <nav class="d-none">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-form-tab" data-bs-toggle="tab" data-bs-target="#nav-form"
                    type="button" role="tab" aria-controls="nav-form" aria-selected="true">Formulario</button>
                <button class="nav-link" id="nav-image-tab" data-bs-toggle="tab" data-bs-target="#nav-image"
                    type="button" role="tab" aria-controls="nav-image" aria-selected="false">Imagem</button>
            </div>
        </nav>
        <div class="d-flex justify-content-end">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="submit" class="btn btn-success">Salvar</button>
                <button type="button" class="btn btn-success" wire:click="save(true)">Salvar e adicionar
                    outro</button>
                <a href="{{ route(Str::beforeLast($route, '.')) }}" wire:navigate type="button" class="btn btn-danger" wire:click="back">Voltar</a>
            </div>
        </div>
    </form>
</div>
