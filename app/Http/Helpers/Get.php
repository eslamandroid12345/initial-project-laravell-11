<?php

namespace App\Http\Helpers;

use App\Repository\RepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class Get
{
    public static function handle($resource, RepositoryInterface $repository, $method = 'getAll', $parameters = [], $is_instance = false, $message = 'Success', $resource_parameters = [])
    {
        try {
            $executable = $repository->$method(...$parameters);
            $records = $is_instance ? new $resource($executable, ...$resource_parameters) : $resource::collection($executable);
            return Response::success(message: $message, data: $records);
        } catch (Exception $e) {
            Log::error('CATCH: '. $e);
//            return $e;
            return Response::fail(status: 404, message: __('messages.No data found'));
        }
    }
}
