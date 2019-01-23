<?php

namespace App\Http\Controllers\Api;

use App\Http\Enum\Geolocation;
use App\Http\Controllers\Controller;
use App\Http\Traits\GeolocationTrait;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    use GeolocationTrait;

    public function __construct()
    {

    }
    //
    /**
     * @param $address
     * @return array|bool
     */
    public function geoCode(Request $request)
    {
        $ip         = $request->getClientIp();
        $service    = trim($request->input('service'));
        if(!$this->validateIP($ip)){
            return response()->json(['error' => Geolocation::ip_address_error], 500);
        }
        $geo    = $this->getGeolocationFromIP($ip, $service);
        $result = [
            'ip'  => $ip,
            'geo' => $geo,
        ];

        return response()->json($result, 200);
    }

    /**
     * @param Request $request
     * @param $ip_address
     * @return \Illuminate\Http\JsonResponse
     */
    public function geoCodeByIp(Request $request, $ip_address)
    {
        $service    = trim($request->input('service'));
        if(!$this->validateIP($ip_address)){
            return response()->json(['error' => Geolocation::ip_address_error], 500);
        }
        $geo     = $this->getGeolocationFromIP($ip_address, $service);
        $result = [
            'ip'  => $ip_address,
            'geo' => $geo,
        ];

        return response()->json($result, 200);

    }
    /**
     * @param $ip
     * @return array
     */
    private function getGeolocationFromIP($valid_ip_address,$url_service):array
    {
       $service = strtolower($url_service);
        switch ($service){
            case Geolocation::IPAPI:
                $url=env('IPAPI_API_SERVER').$valid_ip_address;
                break;
            case Geolocation::FREEGEOIP:
                $params=[
                    'access_key'=>env('IPSTACK_API_KEY'),
                    'format'  =>1,
                ];
                $url=env('IPSTACK_API_SERVER')."$valid_ip_address?".http_build_query($params);
                break;
            default:
                $service=Geolocation::IPAPI;
                $url=env('IPAPI_API_SERVER').$valid_ip_address;
                break;
        }
        $ch  = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        list($header, $body) = explode("\r\n\r\n", $response, 2);
        $header = explode("\r\n", $header);
        $geo = [
            'service' => $service,
            'city'    => null,
            'region'  => null,
            'country' => null,
        ];
        if ($service === Geolocation::IPAPI) {
            $result = unserialize($body);
            if (isset($result['status']) && $result['status'] == 'success') {
                $geo = [
                    'service' => $service,
                    'city'    => $result['city'],
                    'region'  => $result['region'],
                    'country' => $result['country'],
                ];
            }
        }else{
            $result = json_decode($body);
            $geo = [
                'service' => $service,
                'city'    => $result->city,
                'region'  => $result->region_name,
                'country' => $result->country_name,
            ];
        }

        return $geo;
    }
    /**
     * @param $ip
     * @return array
     */
    private function getWeatherFromCity($city): array
    {
        $url    = env('WEATHER_API_SERVER');
        $params = [
            'q'     => $city,
            'units' => 'metric',
            'mode'  => 'json',
            'appid' => env('OPEN_WEATHER_MAP_API_KEY'),
        ];
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . http_build_query($params));
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        $weather = [
            'temperature' => [
                'current' => '',
                'low'     => '',
                'high'    => '',
            ],
            'wind'        => [
                'speed'     => '',
                'direction' => '',
            ]

        ];
        list($header, $body) = explode("\r\n\r\n", $response, 2);
        $result = json_decode($body);
        if (isset($result->main, $result->wind)) {
            $weather = [
                'temperature' => [
                    'current' => $result->main->temp,
                    'low'     => $result->main->temp_min,
                    'high'    => $result->main->temp_max,
                ],
                'wind'        => [
                    'speed'     => $result->wind->speed,
                    'direction' => $result->wind->deg,  //meteorological
                ]

            ];
        }
        return $weather;
    }

    /** get weather by client ip
     * @param Request $request
     */
    public function weather(Request $request){
        $response = $this->geoCode($request);
        $data     = $response->getData();
        $city     = $data->geo->city ?? '';
        $ip       = $request->getClientIp();
        if(!empty($city)){
            $weather = $this->getWeatherFromCity($city);
        }else{
            return response()->json(['error' => Geolocation::ip_address_error], 500);
        }
        $result = [
            'ip'   => $ip,
            'city' => $city,
        ];
        $result = array_merge($result,$weather);
        return response()->json($result, 200);
    }

    /** get weather by ip specified.
     * @param Request $request
     * @param $ip_address
     * @return \Illuminate\Http\JsonResponse
     */
    public function weatherByIp(Request $request, $ip_address)
    {
        $response = $this->geoCodeByIp($request, $ip_address);
        $data     = $response->getData();
        $city     = $data->geo->city ?? '';
        if(!empty($city)){
            $weather = $this->getWeatherFromCity($city);
        }else{
            return response()->json(['error' => Geolocation::city_address_error], 500);
        }
        $result = [
            'ip'   => $ip_address,
            'city' => $city,
        ];
        $result = array_merge($result,$weather);

        return response()->json($result, 200);
    }



}