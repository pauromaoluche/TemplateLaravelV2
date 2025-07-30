<div>
    <x-dashboard.ui.breadcrumb />
    <livewire:dashboard.components.form :route="Route::currentRouteName()" :columns="$columns" :model="$model"/>
</div>
