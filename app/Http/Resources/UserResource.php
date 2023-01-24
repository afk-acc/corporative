<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email'=>$this->email,
            'permissions'=> $this->role->permissions->map(function ($item) {
                return collect($item)->forget(['pivot']);
            }),
            'role' => $this->role->name,
            'photo' => $this->photo
        ];
    }
}
