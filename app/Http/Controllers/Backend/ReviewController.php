<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Requests\Backend\UpdateReviewRequest;

class ReviewController extends Controller
{
    /**
     * Display all reviews
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reviews                = array();
        $data                   = array();

        $name                   = $request->input('name');
        $mobile_number          = $request->input('mobile_number');
        $order_id               = $request->input('order_id');
        $email                  = $request->input('email');
        $status                 = $request->input('status');


        $query                  = Review::select('*')->orderBy('id', 'desc');

        if (!empty($order_id))
        {
            $query->where('order_id','like', '%' .$order_id. '%');
        }

        if (!empty($mobile_number))
        {
            $query->where('mobile_number','like', '%' .$mobile_number. '%');
        }

        if (!empty($name))
        {
            $query->where('name','like', '%' .$name. '%');
        }

        if (!empty($email))
        {
            $query->where('email','like', '%' .$email. '%');
        }

        if (!empty($status))
        {
            $query->where('status','=', $status);
        }

        

        $reviews                = $query->latest()->paginate(config('constants.per_page'));
        $data['reviews']        = $reviews;
        $data['reviewStatuses']  = config('constants.review_statues');

        return view('backend.reviews.index')->with($data);
    }

    /**
     * Edit review data
     * 
     * @param Review $review
     * 
     * @return \Illuminate\Http\Response
     */

    public function edit(Review $review)
    {
        return view('backend.reviews.edit', [
            'review' => $review,
            'reviewStatuses' => config('constants.review_statues')
        ]);
    }


    /**
     * Update review data
     * 
     * @param Review $review
     * @param UpdateReviewRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Review $review, UpdateReviewRequest $request)
    {
        $postReviewData = $request->validated();

        $review->update($postReviewData);

        return redirect()->route('reviews')
        ->withSuccess(__('Review updated successfully.'));
    }


    /**
     * Delete user data
     * 
     * @param Review $review
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review) 
    {
        $review->delete();

        return redirect()->route('reviews')
            ->withSuccess(__('Review deleted successfully.'));
    }


}
