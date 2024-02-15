<?php

namespace App\Http\Responses;

use App\Http\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;

class ServerErrorResponse extends BaseResponse
{
    public function send()
    {
        $this->setCode(JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        return parent::send();
    }
}
