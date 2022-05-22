<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;

class ReviewsController extends Controller
{
    /**
     * Devuelve las reviews de un producto
     * 
     * @return \Illuminate\Http\Response
     */
    public static function getReviews($userOrProduct, $id )
    {
        $conditions = ["{$userOrProduct}_id" => $id];
        $reviews = ReviewResource::collection(Review::where($conditions)->get());

        foreach($reviews as $review){
            if($review->score < 5){
                $review->state = "bad";
            } else if ($review->score >= 5 && $review->score < 8){
                $review->state = "ok";
            } else {
                $review->state = "good";
            }
        }

        return Controller::giveResponse($reviews, null);
    }

    /**
     * Crea una review de un producto
     * 
     * @return \Illuminate\Http\Response
     */
    public static function postReview(ReviewRequest $request)
    {

        $review = Review::query()->create($request->toArray());

        date_default_timezone_set('Europe/Madrid');
        $review->post_date = date('y-m-d H:i:s', time());
        $review->update();

        return Controller::giveResponse($review, "Su valoración ha sido añadida");

        
    }

    /**
     * Edita una review de un producto
     * 
     * @return \Illuminate\Http\Response
     */
    public static function editReview(ReviewRequest $request, $id)
    {
        $review = Review::find($id);
        $review->update($request->toArray());
        date_default_timezone_set('Europe/Madrid');
        $review->post_date = date('y-m-d H:i:s', time());
        $review->update();

        return Controller::giveResponse($review, "Su valoración ha sido editada");
    }


    /**
     * Borra una review de un producto
     * 
     * @return \Illuminate\Http\Response
     */
    public static function deleteReview($id)
    {
        $review = Review::find($id);
        $review->delete();

        return Controller::giveResponse($review, "La valoración ha sido eliminada");
    }

}
