<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CampaignCollection;

class CampaignController extends Controller
{
    /**
     * List all campaigns.
     */
    public function index()
    {
        return new CampaignCollection(Campaign::all());
    }

    /**
     * Store a new campaign.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
        ]);

        $campaign = Campaign::create($data);

        return new CampaignResource($campaign);
    }

    /**
     * Show a single campaign.
     */
    public function show(Campaign $campaign)
    {
        return new CampaignResource($campaign);
    }

    /**
     * Update a campaign.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'domain' => 'sometimes|string|max:255',
        ]);

        $campaign->update($data);

        return response()->json($campaign);
    }

    /**
     * Delete a campaign.
     */
    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return response()->noContent();
    }
}
