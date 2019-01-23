<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# Geolocation & Weather APIs

#####For retrieve geolocation from IP,make sure you have two:
- api_key. default value is 12345678
- service. ip-api or freegoip
- valid ip address. ex: 172.253.7.4 (127.0.0.1 will not be valid)
- laravel:5.7 PHP:7.1+

| Verb | Path | Action | Params |
| :--- | :--- | :--- | :--- |
| GET | /api/geolocation | search | http://example.com/api/geolocation?api_key=12345678&service=ip-api|
| GET | /api/geolocation/:ip_address | search | http://example.com/api/geolocation/172.253.7.?api_key=12345678&service=ip-api
| GET | /api/weather | search | http://example.com/api/weather?api_key=12345678&service=ip-api|
| GET | /api/weather/:ip_address | search | http://example.com/api/weather/172.253.7.4?api_key=12345678&service=ip-api|

#####installation
- git clone
- composer install
