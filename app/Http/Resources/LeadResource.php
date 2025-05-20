<?php

namespace App\Http\Resources;

use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;



class LeadResource extends JsonResource
{
    // public static $wrap = 'lead';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request): array
    // {

    //     // return parent::toArray($request);
    //     return [
    //         'id' => $this->id,
    //         'lead_from' => $this->lead_from,
    //         'name' => $this->name,
    //         'email' => $this->email,
    //         'phone' => $this->phone,
    //         'details' => $this->details,
    //         'is_read' => $this->is_read,
    //         'is_hidden' => $this->is_hidden,
    //         'time_pkt' => TimeHelper::karachiTime($this->created_at),
    //         'time_chicago' => TimeHelper::chicagoTime($this->created_at),
    //         'data_to_copy' => [
    //             'id' => $this->id,
    //             'name' => $this->name,
    //             'email' => $this->email,
    //             'phone' => $this->phone,
    //             'lead_from' => $this->lead_from,
    //             'description' => $this->details['description'] ?? null,
    //         ]
    //         // 'updated_at' => Carbon::parse($this->updated_at)->format('d-m-Y h:i:s A'),
    //         // 'self' => route('leads.show',$this->id()),
    //     ];
    // }
    public function toArray(Request $request): array
    {
        $leadFromDetail = $this->details->firstWhere('key', 'lead_from');
        $formname = $this->details->firstWhere('key', 'form_name');

        // Initialize lead_from as null
        $leadFrom = null;

        if ($leadFromDetail) {
            $url = $leadFromDetail->value; // e.g., divine.com/landing-page

            // Parse the domain and path
            $urlParts = parse_url($url);
            $siteName = $urlParts['host'] ?? null; // Get domain (e.g., divine.com)
            $path = $urlParts['path'] ?? ''; // Get path (e.g., /landing-page)

            // Remove "www." if present in the domain
            $siteName = str_replace('www.', '', $siteName);
            $siteName = str_replace('.com', '', $siteName);

            // Format the result (e.g., divine - landing-page)
            $leadFrom =  $siteName;
            $leadFrom .=  ' ' . $formname->value;
        }
        return [
            'id' => $this->id,
            // 'name' => $this->name,
            // 'email' => $this->email,
            // 'phone' => $this->phone,
            'lead_data' => [
                'id' => $this->id,
                'lead_from' => $leadFrom, // Campaign's domain fetched via campaign relationship
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'description' => $this->description, // Direct description field
            ],
            'details' => [
                'extra_details' => LeadDetailResource::collection($this->details),
                'location' => new LocationResource($this->location), // Location relationship
            ],
            'campaign' => new CampaignResource($this->campaign), // Campaign relationship
            'is_read' =>  $this->is_read,
            'copied_by' => $this->leadCopyActivities->map(function ($activity) {
                return [
                    'user_name' => $activity->user->name,  // Assuming User has a 'name' field
                    'copied_at' => TimeHelper::karachiTime($activity->created_at), // Format the date
                ];
            }),
            'is_hidden' => $this->is_hidden,
            'hidden_by' => $this->deletedByActivity && $this->deletedByActivity->user ? [
                'email' => $this->deletedByActivity->user->email,
                'name' => $this->deletedByActivity->user->name,
                'hidden_at' => TimeHelper::karachiTime(($this->deletedByActivity->created_at))
            ] : null,
            'time_pkt' => TimeHelper::karachiTime($this->created_at), // Karachi time
            'time_chicago' => TimeHelper::chicagoTime($this->created_at), // Chicago time
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
