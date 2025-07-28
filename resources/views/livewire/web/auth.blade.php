<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Bem vindo</h1>
                                </div>
                                <form class="user" wire:submit.prevent="login">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email"
                                            required wire:model.live="form.email" placeholder="Endereço de email...">
                                        @error('form.email')
                                            <span>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" name="password"
                                            required wire:model.defer="form.password" placeholder="Senha">
                                        @error('form.password')
                                            <span>{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" name="remember"
                                                id="customCheck" wire:model.defer="remember">
                                            <label class="custom-control-label" for="customCheck">Manter logado</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>

                                    @error('form.error')
                                        <div class="alert alert-danger mt-3 text-center">
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </form>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
