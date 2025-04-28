<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['campaign_id', 'name', 'email', 'phone', 'description', 'assigned_to', 'is_read', 'is_hidden'];
    protected $casts = [
        'is_read' => 'boolean',
        'is_hidden' => 'boolean',
    ];
    /**
     * Relationship with campaign.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Relationship with lead details.
     */
    public function details()
    {
        return $this->hasMany(LeadDetail::class);
    }

    /**
     * Relationship with location.
     */
    public function location()
    {
        return $this->hasOne(Location::class);
    }

    /**
     * Assign lead to a project manager.
     */
    public function assignTo(int $userId)
    {
        $this->assigned_to = $userId;
        $this->save();
    }


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
    // Lead model
    public function leadCopyActivities()
    {
        return $this->hasMany(UserActivity::class)
            ->where('activity_type', 'lead_copy');
    }
    public function deletedByActivity()
    {
        return $this->hasOne(UserActivity::class, 'lead_id', 'id')
            ->where('activity_type', 'lead_delete');
    }
}
