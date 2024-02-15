<?php

namespace App\Http\Responses;

interface ResponseInterface
{
    /**
     * Sets message response param
     * @param string $msg
     * @return \App\Http\Responses\ResponseInterface
    */
    function setMessage($msg);
    /**
     * Sets data response param
     * @param  mixed  $data
     * @return \App\Http\Responses\ResponseInterface
    */
    function setData($data);
    /**
     * Return response created from all the given data
     * @return \Illuminate\Http\Response
    */
    function send();

}

