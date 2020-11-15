<?php

namespace App\Http\Controllers\API\Card;

use App\Card;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mockery\Exception;

class CardController extends Controller
{
    public function index(Card $card){
        try {
            $data = $card->getCards();
            return response(['cards'=>$data, 'status' => true], 200);
        }
        catch(\Exception $exception){
            return response(['error' => 'Action Could not be performed', 'status' => false], 500);
        }
    }

    public function store(Card $card){
        try {
            $newCard = $card->createCard();
            return response(['card'=>$newCard, 'status' => true, "message" =>"Card Successfully Created"], 200);
        }
        catch (\Exception $exception) {
            return response(['error' => 'Action Could not be performed', 'status' => false], 500);
        }
    }
}
