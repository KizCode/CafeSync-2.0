<?php

namespace App\Http\Controllers;

use App\Support\AccessMatrix;
use Illuminate\View\View;

class AccessMatrixController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.access.index', [
            'roles' => AccessMatrix::roleLabels(),
            'rows' => AccessMatrix::rows(),
        ]);
    }
}
