<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoffeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "title" => $this->title,
            "price" => $this->price." EUR",
            "image" => asset("storage/".$this->image),
            "last_updated_at"=> date("Y-m-d H:i", strtotime($this->updated_at)),
            "id" => $this->id,
        ];
    }
}
