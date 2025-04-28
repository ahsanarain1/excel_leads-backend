<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'domain' => $this->domain,

        ];
    }
    public function with($request)
    {
        return [
            'success' => true
        ];
    }
    public function withResponse($request, $response)
    {
        $response->header('Accept', 'application/json');
    }
}
