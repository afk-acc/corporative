<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserChatResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->collection->transform(function ($value) {
            $value->user = [
                'name'=>$value->reciver->name,
                'photo'=>$value->reciver->photo,
            ];
            return $value;
        });

        return $this->collection->map(function ($item) {
            return collect($item)->forget(['reciver_id','sender_id','user_id','reciver']);
        });
    }
}
