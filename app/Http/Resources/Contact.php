<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Contact extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // this layer sits between the frontend and the api so when there is a change in column name, it doesen't affect our api
        return [
            'contact_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
            'birthday' => $this->birthday->format('m/d/Y'),
            'last_updated' => $this->updated_at->diffForHumans(),


        ];
    }
}
