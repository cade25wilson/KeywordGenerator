<?php

namespace App\Http\Controllers;

use App\Models\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $groups = ProductGroup::where('user_id', $user->id)
            ->with(['products', 'products.pictures'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Dashboard', [
            'groups' => $groups,
        ]);
    }
}
