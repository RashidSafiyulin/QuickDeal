<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

final class NotFoundException extends HttpResponseException
{
    public function __construct()
    {
        parent::__construct(
            new JsonResponse([
                'message' => 'Задача не найдена!'
            ], 404)
        );
    }
}