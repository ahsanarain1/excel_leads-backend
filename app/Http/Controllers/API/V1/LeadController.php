<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Lead;
use App\Models\Location;
use App\Events\LeadCopied;
use App\Models\LeadDetail;
use App\Events\LeadCreated;
use App\Events\LeadDeleted;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\LeadCollection;
use App\Http\Requests\StoreLeadRequest;

class LeadController extends Controller
{
    public function getindex($id)
    {

        return new LeadResource(Lead::findOrFail($id));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Number of items per page, default is 10
        // $leads = Lead::paginate($perPage);
        // $leads = Lead::orderBy('id', 'desc')->paginate($perPage);
        $user = auth()->user(); // Get the authenticated user
        /** @var \App\Models\User $user **/
        if ($user->hasRole('admin')) {
            // Admin can see all leads
            $leads = Lead::orderBy('id', 'desc')
                ->get();
        } elseif ($user->hasRole('manager') || $user->hasRole('seller')) {
            // Managers and Sellers see only the leads assigned to them
            $leads = Lead::notHidden()
                ->orderBy('id', 'desc')
                ->get();
        }
        return new LeadCollection($leads);
        // $leads = Lead::notHidden()
        //     ->orderBy('id', 'desc')
        //     ->get();

        // return new LeadCollection($leads);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'lead_from' => 'required|string',
    //         'name' => 'required|string',
    //         'email' => 'required|email',
    //         'phone' => 'nullable|string',
    //     ]);

    //     $leadData = Arr::only($request->all(), ['lead_from', 'name', 'email', 'phone']);

    //     // Store all remaining keys in the 'details' field
    //     $leadData['details'] = $request->all();


    //     $lead = Lead::create($leadData);
    //     $lead = new LeadResource($lead);
    //     event(new LeadCreated($lead));
    //     return $lead;
    // }
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            // Create Lead
            $lead = Lead::create([
                'campaign_id' => $request->input('campaign_id'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'description' => $request->input('description'),
            ]);

            // Optional: Create Location if provided
            if ($request->has('location')) {

                Location::create([
                    'lead_id' => $lead->id,
                    'ip_address' => $request->input('location.ip'),
                    'country' => $request->input('location.country'),
                    'state' => $request->input('location.state'),
                    'city' => $request->input('location.city'),
                ]);
            }

            // Optional: Create Lead Details if provided
            if ($request->has('details')) {

                foreach ($request->input('details') as $key => $value) {
                    // Adjust for associative details array
                    LeadDetail::create([
                        'lead_id' => $lead->id,
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }

            DB::commit();
            $lead = new LeadResource($lead);
            event(new LeadCreated($lead));
            return response()->json([
                'message' => 'Lead created successfully',
                'lead' => $lead,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to create lead', 'details' => $e->getMessage()], 500);
        }
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
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->markAsHidden();
        event(new LeadDeleted(Auth::user(), $lead));
        // $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully']);
    }

    public function forceDelete($id)
    {
        // Find the record, including soft-deleted ones
        $lead = Lead::withTrashed()->findOrFail($id);

        // Force delete the record
        $lead->forceDelete();

        return response()->json(['message' => 'Lead permanently deleted successfully.']);
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
            'data'    => new LeadResource($lead),
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
