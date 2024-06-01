<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_from', 'name', 'email', 'phone', 'details', 'is_read', 'is_hidden',
    ];

    protected $casts = [
        'details' => 'json',
        'is_read' => 'boolean',
        'is_hidden' => 'boolean',
    ];


    public function id(): string
    {
        return (string) $this->id;
    }
    /**
     * Scope a query to only include leads that are not hidden.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotHidden($query)
    {
        return $query->where('is_hidden', false);
    }

    /**
     * Scope a query to only include leads that are not read.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotRead($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Get all leads.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllLeads()
    {
        return self::all();
    }

    /**
     * Get a lead by ID.
     *
     * @param int $leadId
     * @return Lead|null
     */
    public static function getLeadById($leadId)
    {
        return self::find($leadId);
    }

    /**
     * Mark a lead as read.
     *
     * @return bool
     */
    public function markAsRead()
    {
        $this->is_read = true;
        return $this->save();
    }

    /**
     * Mark the lead as hidden.
     *
     * @return bool
     */
    public function markAsHidden()
    {
        $this->is_hidden = true;
        return $this->save();
    }

    /**
     * Create a new lead.
     *
     * @param array $data
     * @return Lead
     */
    public static function createLead($data)
    {
        return self::create($data);
    }

    /**
     * Update a lead.
     *
     * @param int $leadId
     * @param array $data
     * @return bool
     */
    public static function updateLead($leadId, $data)
    {
        $lead = self::find($leadId);
        if ($lead) {
            return $lead->update($data);
        }
        return false;
    }

    /**
     * Delete a lead.
     *
     * @param int $leadId
     * @return bool|null
     */
    public static function deleteLead($leadId)
    {
        $lead = self::find($leadId);
        if ($lead) {
            return $lead->delete();
        }
        return false;
    }

    /**
     * Get all leads that are not hidden and not read.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllNotHiddenNotRead()
    {
        return self::notHidden()->notRead()->get();
    }



    // Get leads for today
    public static function leadsToday()
    {
        return self::whereDate('created_at', today())->count();
    }

    // Get leads for this week
    public static function leadsThisWeek()
    {
        return self::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
    }

    // Get leads for this month
    public static function leadsThisMonth()
    {
        return self::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
    }

    // Get leads for this year
    public static function leadsThisYear()
    {
        return self::whereYear('created_at', now()->year)->count();
    }

    // Get leads per day
    public static function leadsPerDay()
    {
        return self::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    // Get leads per week
    public static function leadsPerWeek()
    {
        return self::select(DB::raw('YEARWEEK(created_at, 1) as week'), DB::raw('count(*) as total'))
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get();
    }

    // Get leads per month
    public static function leadsPerMonth()
    {
        return self::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }

    // Get the month with the highest number of leads
    public static function monthWithHighestLeads()
    {
        return self::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('total', 'desc')
            ->first();
    }

    // Get the month with the lowest number of leads
    public static function monthWithLowestLeads()
    {
        return self::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('total', 'asc')
            ->first();
    }

    public static function avgLeadsPerMonth()
    {
        // Calculate the average leads per month using SQL
        return DB::table('leads')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as leads_count')
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get()
            ->groupBy('year')
            ->map(function ($yearData) {
                return $yearData->avg('leads_count');
            });
    }
}
