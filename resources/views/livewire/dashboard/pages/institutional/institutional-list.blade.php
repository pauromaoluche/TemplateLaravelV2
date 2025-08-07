<div>
    <x-dashboard.ui.breadcrumb />
    <livewire:dashboard.components.list-item :route="Route::currentRouteName()" :columns="[['column' => 'title', 'width' => '40%'], ['column' => 'value', 'width' => '30%']]" :model="$model" />
</div>
