<?php // routes/breadcrumbs.php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Route;

$items =
    [
        'dashboard.institutional' => 'Institucional',
    ];

Breadcrumbs::for('dashboard.index', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard.index'));
});

foreach ($items as $key => $item) {
    if (!Breadcrumbs::exists($key) && Route::has($key)) {
        Breadcrumbs::for($key, function (BreadcrumbTrail $trail) use ($item, $key) {
            $trail->parent("dashboard.index");
            $trail->push($item, route($key));
        });
    }

    if (!Breadcrumbs::exists("{$key}.create") && Route::has("{$key}.create")) {
        Breadcrumbs::for("{$key}.create", function (BreadcrumbTrail $trail) use ($item, $key) {
            $trail->parent($key);
            $trail->push("Criar $item", route("{$key}.create"));
        });
    }

    if (!Breadcrumbs::exists("{$key}.show") && Route::has("{$key}.show")) {
        Breadcrumbs::for("{$key}.show", function (BreadcrumbTrail $trail, $id) use ($item, $key) {
            $trail->parent("{$key}.index");
            $trail->push("Editar $item", route("{$key}.show", $id));
        });
    }
}
