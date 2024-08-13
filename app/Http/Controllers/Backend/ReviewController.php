<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display all reviews
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reviews = array();
        $reviews = Review::select('*')->orderBy('id', 'desc');
        $reviews = $reviews->latest()->paginate(config('constants.per_page'));
        return view('backend.reviews.index', compact('reviews'));
    }
}
