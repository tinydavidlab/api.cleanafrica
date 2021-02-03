<?php


namespace App\Traits;


trait CompanyProps
{
    public function getIsActivatedAttribute(): bool
    {
        return !is_null( $this->getAttribute( 'activated' ) );
    }
}
