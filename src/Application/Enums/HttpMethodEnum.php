<?php

namespace App\Application\Enums;

enum HttpMethodEnum: string {
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case GET = 'GET';
    case DELETE = 'DELETE';
    case OPTIONS = 'OPTIONS';
}