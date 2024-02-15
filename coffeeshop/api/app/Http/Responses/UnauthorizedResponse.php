<?php

namespace App\Http\Responses;

use App\Http\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;

class UnauthorizedResponse extends BaseResponse
{
    public function send()
    {
        $this->setCode(JsonResponse::HTTP_UNAUTHORIZED);
        return parent::send();
    }
}
