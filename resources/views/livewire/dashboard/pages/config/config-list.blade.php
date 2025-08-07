<div>
    <x-dashboard.ui.breadcrumb />
    <livewire:dashboard.components.list-item :route="Route::currentRouteName()" :columns="[['column' => 'title', 'width' => '50%'], ['column' => 'value', 'width' => '20%']]" :model="$model" />
</div>
