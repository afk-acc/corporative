<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserListResource extends ResourceCollection
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

            $value->role->permissions = $value->role->permissions->map(function ($item) {
                return collect($item)->forget(['pivot']);
            });

            $value->role = $value->role->name;


            return $value;
        });

        return $this->collection->map(function ($item) {
            return collect($item)->forget(['role_id']);
        });
    }
}
