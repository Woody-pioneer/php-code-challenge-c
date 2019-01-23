<?php
/**
 * Created by PhpStorm.
 */

namespace App\Http\Enum;


class Geolocation
{
    const IPAPI        = 'ip-api';
    const FREEGEOIP    = 'freegeoip';

    //error message
    const url_error    = 'API key or Parameters  not correct';
    const ip_address_error    = 'Can not analyze IP or invalid IP';

}
