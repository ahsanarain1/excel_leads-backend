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
    public function toArray(Request $request): array
    {

        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'lead_from' => $this->lead_from,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'details' => $this->details,
            'is_read' => $this->is_read,
            'is_hidden' => $this->is_hidden,
            'created_at' => TimeHelper::karachiTime($this->created_at),

            // 'updated_at' => Carbon::parse($this->updated_at)->format('d-m-Y h:i:s A'),
            // 'self' => route('leads.show',$this->id()),
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
