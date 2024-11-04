<?php

namespace App\Http\Helpers;

class Http
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NO_CONTENT = 204;
    const MOVED_PERMANENTLY = 301;
    const FOUND = 302;
    const NOT_MODIFIED = 304;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const PRECONDITION_FAILED = 412;
    const UNSUPPORTED_MEDIA_TYPE = 415;
    const UNPROCESSABLE_ENTITY = 422;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const SERVICE_UNAVAILABLE = 503;
    const GATEWAY_TIMEOUT = 504;
}
