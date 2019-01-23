<?php

namespace App\Http\Traits;



trait GeolocationTrait
{
    /** check api user authorized or not
     * @param Request $request
     * @return bool
     */
    private function validateIP($ip)
    {
        $validateIP = filter_var($ip, FILTER_VALIDATE_IP);

        return $validateIP ? true : false;
    }
}