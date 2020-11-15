<?php

namespace App\Http\Controllers\API\Card;

use App\Card;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
