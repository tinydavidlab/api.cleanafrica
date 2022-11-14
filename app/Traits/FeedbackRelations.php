<?php


namespace App\Traits;


use App\Models\Company;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait FeedbackRelations
{
    /**
     * Customer relationship.
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
