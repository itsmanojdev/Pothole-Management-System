<?php

namespace App\Http\Controllers;

use App\Models\Pothole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DatabaseController extends Controller
{
    public function getUsers(): View
    {
        $users = User::latest()->paginate(10);

        return view('db.user', compact('users'));
    }

    public function getPotholes(): View
    {
        $potholes = Pothole::latest()->paginate(10);

        return view('db.pothole', compact('potholes'));
    }
}
