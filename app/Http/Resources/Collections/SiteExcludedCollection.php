<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\SiteExcludedResource;
use App\Models\Site;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SiteExcludedCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return SiteExcludedResource::collection($this->resource);
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'count' => Site::has('excludedPages')->get()->count()
            ],
        ];
    }
}
