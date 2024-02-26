<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'phone', 'details', 'is_read', 'is_hidden',
    ];

    protected $casts = [
        'details' => 'json',
        'is_read' => 'boolean',
        'is_hidden' => 'boolean',
    ];


    public function id():string{
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
     * @param int $leadId
     * @return bool
     */
    public static function markAsRead($leadId)
    {
        $lead = self::find($leadId);
        if ($lead) {
            $lead->is_read = true;
            return $lead->save();
        }
        return false;
    }

    /**
     * Hide a lead.
     *
     * @param int $leadId
     * @return bool
     */
    public static function hideLead($leadId)
    {
        $lead = self::find($leadId);
        if ($lead) {
            $lead->is_hidden = true;
            return $lead->save();
        }
        return false;
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
}