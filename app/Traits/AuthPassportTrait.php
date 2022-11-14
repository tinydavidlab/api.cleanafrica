<?php

    namespace App\Traits;

    trait AuthPassportTrait
    {
        /**
         * Find the user instance for the given username.
         *
         * @param string $phone_number
         *
         * @return ?self
         */
        public function findForPassport( string $phone_number ): ?self
        {
            return $this->wherePhoneNumber( $phone_number )->first();
        }
    }
