@extends('web.layouts.app')
@section('title', 'Pagina Principal')
@section('content')
   <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700">
       login
   </a>
@endsection
