<div id="breadcrumb" class="shadow-lg mb-3 bg-body-tertiary rounded border-left-primary" style="background-color: white">
    <div class="breadcrumb mb-0 d-flex justify-content-between align-items-center" style="background-color: white">
        {{ Breadcrumbs::render() }}
        @if (Route::currentRouteName() == 'dashboard.index')
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Gerar Relatório</a>
        @endif
    </div>
</div>
