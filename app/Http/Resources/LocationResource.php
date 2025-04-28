<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ip' => $this->ip_address,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
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
