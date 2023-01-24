<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskResourse extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->collection->transform(function ($value) {
            $value->from = [
                'name'=>$value->from_u->name,
                'photo'=>$value->from_u->photo,
                'role'=>$value->from_u->role->name,
            ];
            $value->to = [
                'name'=>$value->to_u->name,
                'photo'=>$value->to_u->photo,
                'role'=>$value->to_u->role->name,
            ];
            return $value;
        });

        return $this->collection->map(function ($item) {
            return collect($item)->forget(['from_user','to_user','from_u','to_u']);
        });
    }
}
