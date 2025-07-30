@extends('dashboard.layouts.app')
@section('title', 'Institucional')
@section('content')
    <x-dashboard.ui.breadcrumb />
    <livewire:dashboard.list-item :route="$route" :columns="[['column' => 'title', 'width' => '30%'], ['column' => 'value', 'width' => '30%']]" :model="$model" />
@endsection
