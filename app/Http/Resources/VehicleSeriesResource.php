<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleSeriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'maker_id'          => (int) $this->maker_id,
            'type_id'           => (int) $this->type_id,
            'image_url'         => $this->image_url,
            'feature_image_url' => $this->feature_image_url,
            'name'              => $this->name,
            'slug'           => $this->slug,
            'is_global_model' => (bool) $this->is_global_model,
            'is_local_model' => (bool) $this->is_local_model,
        ];
    }
}
