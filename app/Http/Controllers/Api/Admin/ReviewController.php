<?php

namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        return ReviewResource::collection(Review::with('user', 'product')->get());
    }

    public function show(Review $review)
    {
        return new ReviewResource($review->load(['user', 'product']));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(['message' => 'Review deleted']);
    }
}
