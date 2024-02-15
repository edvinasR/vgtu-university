<?php

namespace App\Helpers;


use Illuminate\Support\Facades\Auth;
/**
 *
 * @author Edvin
 *
 */

/***
 * Class
 *
 * @package App\Helpers
 *
 */
class HttpUtility {


    public static function buildSuccessfullResponse($message = null, $data = null) {
		return HttpUtility::build ( 200, true, $message, $data );
	}

	public static function buildErroreusResponse($message, $data = null) {
		return HttpUtility::build ( 400, false, $message, $data );
	}
	
	public static function buildInternalServerErrorResponse($message, $data = null) {
		return HttpUtility::build ( 500, false, $message, $data );
	}
	
	public static function buildNotFoundServerErrorResponse($message, $data = null) {
		return HttpUtility::build ( 404, false, $message, $data );
	}

	public static function buildUanauthorizedErrorResponse($message, $data = null) {
		return HttpUtility::build ( 401, false, $message, $data );
	}

	public static function buildConfilctResponse($message, $data = null){
		return HttpUtility::build ( 409, false, $message, $data );	
	}

	private static function build($code, $success, $message, $data) {

			return response ()->json ( array (
					'data' => $data,
					'code' => $code,
					'success' => $success,
					'message' => $message,
					//'token' => TokenBasedResponseBuilder::token()  // Uncomment this line if u want to form new token on every request response
			), $code );	
	}
}