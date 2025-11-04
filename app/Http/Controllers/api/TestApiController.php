<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'API is working!',
            'data' => [
                'name' => 'Laravel',
                'version' => app()->version(),
            ],
        ]);
    }
}
