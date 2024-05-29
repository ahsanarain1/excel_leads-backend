<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Lead;
use App\Events\LeadCopied;
use App\Events\LeadCreated;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Http\Resources\LeadCollection;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Number of items per page, default is 10
        // $leads = Lead::paginate($perPage);
        // $leads = Lead::orderBy('id', 'desc')->paginate($perPage);
        $leads = Lead::notHidden()
            ->orderBy('id', 'desc')
            ->get();
        $lead = new LeadResource(Lead::findOrFail(1));
        event(new LeadCreated($lead));
        return new LeadCollection($leads);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'details' => 'nullable|array',
        ]);

        $lead = Lead::create($validatedData);

        return new LeadResource($lead);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return new LeadResource(Lead::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $id)
    {
        $lead = Lead::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'details' => 'nullable|array',
        ]);

        $lead->update($validatedData);

        return new LeadResource($lead);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->hideLead();

        return response()->json(['message' => 'Lead deleted successfully']);
    }


    /**
     * Mark the specified lead as hidden.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLeadAsRead($id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        $lead->markAsRead();
        event(new LeadCopied(Auth::user(), $lead));
        return response()->json([
            'success' => true,
            'message' => 'Lead marked as read successfully.',
        ]);
    }

    public function stats()
    {
        $leadsToday = Lead::leadsToday();
        $leadsThisWeek = Lead::leadsThisWeek();
        $leadsThisMonth = Lead::leadsThisMonth();
        $leadsThisYear = Lead::leadsThisYear();
        // $leadsPerDay = Lead::leadsPerDay();
        // $leadsPerWeek = Lead::leadsPerWeek();
        $leadsPerMonth = Lead::leadsPerMonth();
        $monthWithHighestLeads = Lead::monthWithHighestLeads();
        $monthWithLowestLeads = Lead::monthWithLowestLeads();
        $avgLeadsPerMonth = Lead::avgLeadsPerMonth();


        return response()->json([
            'success' => true,
            'data' => [
                'leadsToday' => $leadsToday,
                'leadsThisWeek' => $leadsThisWeek,
                'leadsThisMonth' => $leadsThisMonth,
                'leadsThisYear' => $leadsThisYear,
                // 'leadsPerDay' => $leadsPerDay,
                // 'leadsPerWeek' => $leadsPerWeek,
                'leadsPerMonth' => $leadsPerMonth,
                'monthWithHighestLeads' => $monthWithHighestLeads,
                'monthWithLowestLeads' => $monthWithLowestLeads,
                'avgLeadsPerMonth' => $avgLeadsPerMonth,
            ],
            'message' => 'Lead statistics retrieved successfully'
        ]);
    }
}
