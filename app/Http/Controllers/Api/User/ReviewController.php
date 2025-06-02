<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Review;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReviewRequest;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', auth()->id())->with('product')->get();
        return ReviewResource::collection($reviews);
    }

    public function store(ReviewRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $review = Review::create($data);

        return new ReviewResource($review->load('product'));
    }

    public function show(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new ReviewResource($review->load('product'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->update($request->validated());

        return new ReviewResource($review->load('product'));
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }
}
