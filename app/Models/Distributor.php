<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distributor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'distributors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vendor_id',
        // Engineering Tab
        'engineering_activities',
        // After Sales Tab
        'priority_treatment',
        'complaint_agreement',
        'has_contact_centre',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'priority_treatment' => 'string',
        'complaint_agreement' => 'string', 
        'has_contact_centre' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the vendor that owns the distributor.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Initialize default values for new distributor
     */
    public function initializeDefaults(): void
    {
        // Set default empty values if needed
        if (empty($this->engineering_activities)) {
            $this->engineering_activities = '';
        }

        if (empty($this->priority_treatment)) {
            $this->priority_treatment = null;
        }

        if (empty($this->complaint_agreement)) {
            $this->complaint_agreement = null;
        }

        if (empty($this->has_contact_centre)) {
            $this->has_contact_centre = null;
        }

        $this->save();
    }

    /**
     * Get options for dropdown fields
     */
    public static function getYesNoOptions(): array
    {
        return [
            'yes' => 'Yes',
            'no' => 'No'
        ];
    }

    /**
     * Check if distributor profile is complete
     */
    public function isComplete(): bool
    {
        return !empty($this->engineering_activities) &&
               !empty($this->priority_treatment) &&
               !empty($this->complaint_agreement) &&
               !empty($this->has_contact_centre);
    }

    /**
     * Get completion percentage for distributor specific fields
     */
    public function getCompletionPercentage(): float
    {
        $fields = [
            'engineering_activities',
            'priority_treatment', 
            'complaint_agreement',
            'has_contact_centre'
        ];

        $filledFields = 0;
        $totalFields = count($fields);

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }

        return $totalFields > 0 ? round(($filledFields / $totalFields) * 100, 2) : 0;
    }
}