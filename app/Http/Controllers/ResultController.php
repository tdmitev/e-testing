<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $submissions = Submission::where('user_id', Auth::user()->id)
            ->with(['test.questions.answers'])
            ->get();
    
        return view('results.index', compact('submissions'));
    }
}