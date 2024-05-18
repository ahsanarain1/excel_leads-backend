<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Http\Resources\LeadResource;
use App\Http\Resources\LeadCollection;
use Illuminate\Http\Request;

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
        $leads = Lead::where('is_hidden', false)
            ->orderBy('id', 'desc')
            ->get();

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
        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully']);
    }
}
