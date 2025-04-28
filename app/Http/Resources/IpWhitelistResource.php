<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IpWhitelistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
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
