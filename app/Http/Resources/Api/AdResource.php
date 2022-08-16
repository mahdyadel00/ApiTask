<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
class AdResource extends JsonResource
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
			'id'               => $this->id,
			'type'             => $this->type,
			'image'            => $this->image,
			'video'            => $this->video,
			'start_date'       => $this->start_date,
			'end_date'         => $this->end_date,
			'place_id'         => PlaceResource::collection($this->whenLoaded('place')),
			'plat_form'        => $this->plat_form,
		];
    }
}
