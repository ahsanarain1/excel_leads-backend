<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserActivityCollection;
use App\Http\Resources\UserActivityResource;
use App\Models\Lead;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function getLeadsStats(Request $request)
    {
        // Validate the start and end dates
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Default to last 30 days if no dates provided
        $endDate = $request->input('end_date', now());
        $startDate = $request->input('start_date', now()->subDays(90));

        // Ensure max 3 months gap
        if ($startDate->diffInMonths($endDate) > 3) {
            $startDate = $endDate->copy()->subMonths(3);
        }

        // Get leads within the specified date range
        $leads = Lead::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
        // Prepare response data
        $formattedLeads = $leads->map(function ($lead) {
            $date = Carbon::parse($lead->date)->format('d-M-y'); // Format as May-02-24
            return [
                'x' => $date,
                'y' => $lead->count,
            ];
        });
        // Prepare response data
        $response = [
            'success' => true,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'data' => $formattedLeads,
            'count' => $leads->count(),
        ];

        // Return the response
        return response()->json($response);
    }
    public function getUserActivitiesStats(Request $request)
    {
        // Validate the start and end dates
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Default to last 30 days if no dates provided
        $endDate = $request->input('end_date', now());
        $startDate = $request->input('start_date', now()->subDays(30));

        // Ensure max 3 months gap
        if ($startDate->diffInMonths($endDate) > 3) {
            $startDate = $endDate->copy()->subMonths(3);
        }

        // Get user activities within the specified date range
        $activities = UserActivity::with('user', 'lead')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'DESC')
            ->get();

        // Transform data using the resource
        $formattedActivities = UserActivityResource::collection($activities);

        // Return the response
        return response()->json([
            'success' => true,
            'start_date' => $startDate->format('M-d-y'),
            'end_date' => $endDate->format('M-d-y'),
            'data' => $formattedActivities,
        ]);
    }
}
