<?php

namespace App\Http\Responses;

use App\Http\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;

class NotFoundResponse extends BaseResponse
{
    public function send()
    {
        $this->setCode(JsonResponse::HTTP_NOT_FOUND);
        return parent::send();
    }
}
