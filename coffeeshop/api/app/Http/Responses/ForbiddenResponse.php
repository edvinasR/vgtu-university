<?php

namespace App\Http\Responses;

use App\Http\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;

class ForbiddenResponse extends BaseResponse
{
    public function send()
    {
        $this->setCode(JsonResponse::HTTP_FORBIDDEN);
        return parent::send();
    }
}
