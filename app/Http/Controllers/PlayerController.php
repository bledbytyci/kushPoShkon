<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function index()
    {
        $player = Player::where('isHisTurn', 1)->get()->toArray();
        $time = new \DateTime($player[0]['updated_at']);
        $time->setTimezone(new \DateTimeZone("GMT+2"));
        return view('index', ['name' => $player[0]['Name'], 'updatedAt' => $time->format('Y-m-d H:i:s')]);
    }

    public function skip() {
        $players = Player::all()->toArray();
        for ($i = 0; $i< sizeof($players); $i++){
            $playersTurns[$i] = $players[$i]['isHisTurn'];
        }
        $key = array_search('1', $playersTurns);
        $tempNextPlayer= current(array_filter($players, function($item)  {
            return $item['tempNext'] == 1;
        }));

        if($key+1 == count($players)){
            $nextPlayer = $players[0];
        }else {
            $nextPlayer = $players[$key + 1];
        }
        if($tempNextPlayer) {
            $nextPlayer = $tempNextPlayer;
        }

        DB::table('players')->where('isHisTurn',  1)->update(['isHisTurn' => 0, 'hasSkipped' => 1]);
        DB::table('players')->where('id', $nextPlayer['id'])->update(['isHisTurn' => 1, 'tempNext' => 0]);

        $time = new \DateTime(Date::now());
        $time->setTimezone(new \DateTimeZone("GMT+2"));

        return view('index', ['name' => $nextPlayer['Name'], 'updatedAt' =>  $time->format('Y-m-d H:i:s')]);    }

    public function next() {
        $players = Player::all()->toArray();
        for ($i = 0; $i< sizeof($players); $i++ ){
            $playersTurns[$i] = $players[$i]['isHisTurn'];
        }
        $key = array_search('1', $playersTurns);

        $skippedPlayer= current(array_filter($players, function($item)  {
            return $item['hasSkipped'] == 1;
        }));
        $currentPlayer= current(array_filter($players, function($item)  {
            return $item['isHisTurn'] == 1;
        }));
        $tempNextPlayer= current(array_filter($players, function($item)  {
            return $item['tempNext'] == 1;
        }));

        if ($skippedPlayer) {
            $nextPlayer = $skippedPlayer;
            DB::table('players')->where('id', $key+1 >= count($players) ? $players[0] : $players[$key + 1] )->update(['tempNext' => 1]);
        }
        elseif ($tempNextPlayer){
            $nextPlayer = $tempNextPlayer;

        }
        else if($key+1 >= count($players)){
            $nextPlayer = $players[0];
        }else {
            $nextPlayer = $players[$key + 1];
        }


        DB::table('players')->where('id',  $currentPlayer['id'])->update(['isHisTurn' => 0, 'hasSkipped' => 0]);
        DB::table('players')->where('id', $nextPlayer['id'])->update(['isHisTurn' => 1,'hasSkipped' => 0, 'tempNext' => 0, 'updated_at' => Date::now()]);
        $time = new \DateTime(Date::now());
        $time->setTimezone(new \DateTimeZone("GMT+2"));
        return view('index', ['name' => $nextPlayer['Name'], 'updatedAt' =>  $time->format('Y-m-d H:i:s')]);
    }
}
