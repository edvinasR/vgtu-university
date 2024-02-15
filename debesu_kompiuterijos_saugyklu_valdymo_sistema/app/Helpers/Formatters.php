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
class Formatters {


	public static function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
		// Uncomment one of the following alternatives
		if((1 << (10 * $pow)== 0)) {
			$bytes = 0;
		}else{
			$bytes /= (1 << (10 * $pow)); 
		}
         
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 
}