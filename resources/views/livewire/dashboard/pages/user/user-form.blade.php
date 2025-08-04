<div>
    <form wire:submit.prevent="save" class="form" enctype="multipart/form-data">
        <div class="row profile">
            <div class="col-xl-4">
                <div class="card mb-4 mb-xl-0 border-left-primary shadow">
                    <div class="card-header">Foto de perfil</div>
                    <div class="card-body text-center">
                        <img style="height: 200px" class="img-account-profile rounded-circle mb-2 img-fluid"
                            src="{{ asset('storage/' . $profilImage->path) }}" alt>
                        <div class="small font-italic text-muted mb-4">JPG ou PNG tamanho de 2mb no maximo</div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Foto de usuario</label>
                            <input type="file" class="form-control" wire:model.live="image"
                                accept="image/png, image/gif, image/jpeg">
                        </div>
                        @error('image')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card mb-4 border-left-primary shadow">
                    <div class="card-header">Detalhes da conta</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small mb-1" for="name">Nome</label>
                            <input class="form-control" id="name" name="name" type="text"
                                wire:model.live='form.name' placeholder="Seu nome" required>
                            @error('form.name')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="email">Endereço de email</label>
                            <input class="form-control" id="email" name="email" placeholder="Seu email"
                                type="email" wire:model.live="form.email" required>
                            @error('form.email')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        @can('admin')
                            <div class="mb-3">
                                <label class="small mb-1" for="password">Senha</label>
                                <input class="form-control" id="password" name="password" placeholder="Senha"
                                    type="password" wire:model.live="form.password">
                                @error('form.password')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="small mb-1" for="password_confirmation">Confirme a Senha</label>
                                <input class="form-control" id="password_confirmation" placeholder="Confirme a Senha"
                                    type="password" wire:model.live="form.password_confirmation">
                                @error('form.password')
                                    <span class="text-danger text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endcan
                        <button class="btn btn-primary" type="submit">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
