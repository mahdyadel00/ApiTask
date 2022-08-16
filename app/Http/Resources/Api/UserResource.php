<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource
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
			'id'                => $this->id,
			'name'              => $this->name,
			'email'             => $this->email,
			'mobile'            => $this->mobile,
			'gender'            => $this->gender,
			'address'           => $this->address,
			'api_token'         => $this->api_token,
			'pin_code'          => $this->pin_code,
		];
    }
}
