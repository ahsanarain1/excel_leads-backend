<?php

namespace App\Http\Resources;

use App\Models\Lead;
use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $leadDetails = null;
        $campaign = null;
        if ($this->lead_id) {
            $lead = Lead::withTrashed()->with('campaign')->findOrFail($this->lead_id);
            $campaign = new CampaignResource($lead->campaign);
            $leadDetails = [
                'lead_from' => $campaign->name,
                'name' => $lead->name,
                'id' => $lead->id,
            ];
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'lead_id' => $this->lead_id,
            'lead' => $leadDetails, // Include lead details as array
            'ip_address' => $this->ip_address,
            'campaign' => $campaign,
            'activity_type' => $this->activity_type,
            'created_at_pkt' => TimeHelper::karachiTime($this->created_at),
            'created_at_chicago' => TimeHelper::chicagoTime($this->created_at),
        ];
    }
    /**
     * Add additional data to the resource response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        // Add a success flag to the response
        return [
            'success' => true
        ];
    }

    /**
     * Add headers to the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        // Add the 'Accept: application/json' header to the response
        $response->header('Accept', 'application/json');
    }
}
