<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Institutional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class InstitutionalController extends Controller
{
    private $routeBaseName;

    public function __construct()
    {
        $currentRouteName = Route::currentRouteName();
        $this->routeBaseName = Str::beforeLast($currentRouteName, '.');
    }

    public function index()
    {
        $model = Institutional::class;
        $route = $this->routeBaseName;

        return view('dashboard.institutional.index', compact('model', 'route'));
    }
}
