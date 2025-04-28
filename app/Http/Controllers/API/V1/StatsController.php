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
        $max_days = 15;
        $max_months = 12;
        // Validate the start and end dates
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Default to last 30 days if no dates provided
        $endDate = $request->input('end_date', now());
        $startDate = $request->input('start_date', now()->subDays($max_days));

        // Ensure max 3 months gap
        if ($startDate->diffInDays($endDate) > $max_days) {
            $startDate = $endDate->copy()->subDays($max_days);
        }

        //     // Generate the last 15 days dates
        $dates = [];
        $currentDate = $endDate->copy();
        for ($i = $max_days - 1; $i >= 0; $i--) {
            $dates[] = $currentDate->copy()->subDays($i)->toDateString();
        }

        // Get leads count for each date
        $leadsData = [];
        foreach ($dates as $date) {
            $leadsCount = Lead::whereDate('created_at', $date)->count();
            $leadsData[] = [
                'date' => $date,
                'count' => $leadsCount,
            ];
        }

        // Prepare response data for the last 15 days
        $formattedLeads = collect($leadsData)->map(function ($lead) {
            return [
                'x' => Carbon::parse($lead['date'])->format('d-M-y'),
                'y' => $lead['count'],
            ];
        });
        // Prepare response data
        // $formattedLeads = $leads->map(function ($lead) {
        //     $date = Carbon::parse($lead->date)->format('d-M-y'); // Format as May-02-24
        //     return [
        //         'x' => $date,
        //         'y' => $lead->count,
        //     ];
        // });
        // Prepare response data

        // Generate the last 12 months dates and counts
        $monthsData = [];
        for ($i = $max_months - 1; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            $leadsCount = Lead::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthsData[] = [
                'month' => $monthStart->format('Y-m'),
                'count' => $leadsCount,
            ];
        }

        // Prepare response data for the last 12 months
        $formattedMonths = collect($monthsData)->map(function ($month) {
            return [
                'x' => Carbon::parse($month['month'])->format('M-y'),
                'y' => $month['count'],
            ];
        });
        $response = [
            'success' => true,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'data' => [
                'last_15_days' => $formattedLeads,
                'last_12_months' => $formattedMonths,
            ],
            'count' => [
                'last_15_days' => count($formattedLeads),
                'last_12_months' => count($formattedMonths),
            ],
        ];

        // Return the response
        return response()->json($response);
    }
    // public function getLeadsStats(Request $request)
    // {
    //     $max_days = 15;
    //     $max_months = 12;

    //     // Validate the start and end dates
    //     $request->validate([
    //         'start_date' => 'nullable|date',
    //         'end_date' => 'nullable|date|after_or_equal:start_date',
    //     ]);

    //     // Default to last 15 days if no dates provided
    //     $endDate = $request->input('end_date', now());
    //     $startDate = $request->input('start_date', now()->subDays($max_days));

    //     // Ensure max 15 days gap
    //     if ($startDate->diffInDays($endDate) > $max_days) {
    //         $startDate = $endDate->copy()->subDays($max_days);
    //     }

    //     // Generate the last 15 days dates
    //     $dates = [];
    //     $currentDate = $endDate->copy();
    //     for ($i = 0; $i < $max_days; $i++) {
    //         $dates[] = $currentDate->copy()->subDays($i)->toDateString();
    //     }

    //     // Get leads count for each date
    //     $leadsData = [];
    //     foreach ($dates as $date) {
    //         $leadsCount = Lead::whereDate('created_at', $date)->count();
    //         $leadsData[] = [
    //             'date' => $date,
    //             'count' => $leadsCount,
    //         ];
    //     }

    //     // Prepare response data for the last 15 days
    //     $formattedLeads = collect($leadsData)->map(function ($lead) {
    //         return [
    //             'x' => Carbon::parse($lead['date'])->format('d-M-y'),
    //             'y' => $lead['count'],
    //         ];
    //     });

    //     // Generate the last 12 months dates and counts
    //     $monthsData = [];
    //     for ($i = 0; $i < $max_months; $i++) {
    //         $monthStart = now()->subMonths($i)->startOfMonth();
    //         $monthEnd = now()->subMonths($i)->endOfMonth();
    //         $leadsCount = Lead::whereBetween('created_at', [$monthStart, $monthEnd])->count();
    //         $monthsData[] = [
    //             'month' => $monthStart->format('Y-m'),
    //             'count' => $leadsCount,
    //         ];
    //     }

    //     // Prepare response data for the last 12 months
    //     $formattedMonths = collect($monthsData)->map(function ($month) {
    //         return [
    //             'x' => Carbon::parse($month['month'])->format('M-Y'),
    //             'y' => $month['count'],
    //         ];
    //     });

    //     // Return the response
    //     return response()->json([
    //         'success' => true,
    //         'start_date' => Carbon::parse($startDate)->format('d-M-y'),
    //         'end_date' => Carbon::parse($endDate)->format('d-M-y'),
    //         'data' => [
    //             'last_15_days' => $formattedLeads,
    //             'last_12_months' => $formattedMonths,
    //         ],
    //         'count' => [
    //             'last_15_days' => count($formattedLeads),
    //             'last_12_months' => count($formattedMonths),
    //         ],
    //     ]);
    // }

    // public function getUserActivitiesStats(Request $request)
    // {
    //     // Validate the start and end dates
    //     $request->validate([
    //         'start_date' => 'nullable|date',
    //         'end_date' => 'nullable|date|after_or_equal:start_date',
    //     ]);

    //     // Default to last 30 days if no dates provided
    //     $endDate = $request->input('end_date', now());
    //     $startDate = $request->input('start_date', now()->subDays(30));

    //     // Ensure max 3 months gap
    //     if ($startDate->diffInMonths($endDate) > 3) {
    //         $startDate = $endDate->copy()->subMonths(3);
    //     }

    //     // Get user activities within the specified date range
    //     $activities = UserActivity::with('user', 'lead')
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     // Transform data using the resource
    //     $formattedActivities = UserActivityResource::collection($activities);

    //     // Return the response
    //     return response()->json([
    //         'success' => true,
    //         'start_date' => $startDate->format('M-d-y'),
    //         'end_date' => $endDate->format('M-d-y'),
    //         'data' => $formattedActivities,
    //     ]);
    // }
    public function getUserActivitiesStats(Request $request)
    {
        $validated = $request->validate([
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'per_page'     => 'nullable|integer|min:1|max:500',
            'search'       => 'nullable|string|max:255',
            'sort'         => 'nullable|string|in:id,user_name,lead,activity_type,created_at',
            'order'        => 'nullable|string|in:asc,desc',
            'activity_type' => 'nullable|string', // optional enum filter
        ]);

        // Setup dates with default fallbacks and max 3-month gap
        $endDate   = isset($validated['end_date'])
            ? Carbon::parse($validated['end_date'])
            : now();

        $startDate = isset($validated['start_date'])
            ? Carbon::parse($validated['start_date'])
            : $endDate->copy()->subDays(30);

        if ($startDate->diffInMonths($endDate) > 3) {
            $startDate = $endDate->copy()->subMonths(3);
        }

        $perPage = $validated['per_page'] ?? 15;
        $sort    = $validated['sort'] ?? 'created_at';
        $order   = $validated['order'] ?? 'desc';
        $search  = $validated['search'] ?? '';
        $activityType = $validated['activity_type'] ?? null;

        $query = UserActivity::with(['user', 'lead'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($activityType, fn($q) => $q->where('activity_type', $activityType))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%")
                        ->orWhere('activity_type', 'like', "%{$search}%")
                        ->orWhereHas('lead', fn($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy($sort, $order);

        // $activities = $query->paginate($perPage)->withQueryString();
        // return UserActivityResource::collection($activities)->additional([
        //     'success'     => true,
        //     'start_date'  => $startDate->format('M-d-y'),
        //     'end_date'    => $endDate->format('M-d-y'),
        // ]);
        $activities = UserActivity::orderBy('id', 'desc')
            ->get();
        return UserActivityResource::collection($activities);
    }
}
