<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
	public $incrementing = false;
    public $fillable = ['player_id', 'location', 'type', 'game_id', 'id'];

	public function game()
	{
		return $this->belongsTo('App\Game', 'game_id');
	}

}
