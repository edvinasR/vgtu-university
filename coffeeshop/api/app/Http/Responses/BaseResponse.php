<?php

namespace App\Http\Responses;

use App\Http\Responses\ResponseInterface;
use Illuminate\Http\JsonResponse;

class BaseResponse implements ResponseInterface
{
    private $code = JsonResponse::HTTP_OK;
    private $message = "";
    private $data = [];

    public function setMessage($msg)
    {
        $this->message = $msg;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    /**
     * Sets code response param
     * @param  integer $code
     * @return \App\Http\Responses\ResponseInterface
    */
    protected function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function send()
    {
        return response()->json([
            'message' => $this->message,
            'data' => $this->data,
        ], $this->code);
    }

}
