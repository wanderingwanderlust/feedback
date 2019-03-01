<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Carbon\Carbon;

class Feedback extends Resource
{
    /**
     * A resource class represents a single model that needs to
     * be transformed into a JSON structure.
     */

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $created_at = $this->created_at;
        return [
            'critic_name' => $this->critic_name,
            'movie_name' => $this->movie_name,
            'created_at' => $created_at,
            'updated_at' =>$this->updated_at
        ];
    }
}
