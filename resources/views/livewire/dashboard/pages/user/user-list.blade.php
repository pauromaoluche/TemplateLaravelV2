<div>
    <x-dashboard.ui.breadcrumb />
    <livewire:dashboard.components.list-item :route="Route::currentRouteName()" :columns="[['column' => 'name', 'width' => '30%'], ['column' => 'email', 'width' => '30%']]" :model="$model" />
</div>
