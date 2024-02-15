<?php

namespace App\Http\Responses;

use App\Http\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;

class SuccessfullResponse extends BaseResponse
{
    public function send()
    {
        $this->setCode(JsonResponse::HTTP_OK);
        return parent::send();
    }
}
