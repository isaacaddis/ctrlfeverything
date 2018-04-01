<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImgResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function boot() {
        Resource::withoutWrapping();
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'img' => $this->img,
            'takenAt' => $this->takenAt,
            'ip' => $this->ip,
            'deviceName' => $this->deviceName,
        ];
    }
}
