<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function index()
    {
        return ReviewResource::collection(
            Review::with('product')->where('user_id', auth()->id())->get()
        );
    }

    public function store(ReviewRequest $request)
    {
        $review = Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return new ReviewResource($review->load('product'));
    }
    public function show(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new ReviewResource($review->load('product'));
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}

