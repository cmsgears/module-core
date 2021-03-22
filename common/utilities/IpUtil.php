<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

class IpUtil {

	public static function getClientIp( $long = false ) {

		$clientIp = null;

		if( getenv( 'HTTP_CLIENT_IP' ) ) {

			$clientIp = getenv( 'HTTP_CLIENT_IP' );
		}
		else if( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {

			$clientIp = getenv( 'HTTP_X_FORWARDED_FOR' );
		}
		else if( getenv( 'HTTP_X_FORWARDED' ) ) {

			$clientIp = getenv( 'HTTP_X_FORWARDED' );
		}
		else if( getenv( 'HTTP_FORWARDED_FOR' ) ) {

			$clientIp = getenv( 'HTTP_FORWARDED_FOR' );
		}
		else if( getenv( 'HTTP_FORWARDED' ) ) {

			$clientIp = getenv( 'HTTP_FORWARDED' );
		}
		else if( getenv( 'REMOTE_ADDR' ) ) {

			$clientIp = getenv( 'REMOTE_ADDR' );
		}

		if( $long ) {

			return ip2long( $clientIp );
		}

		return $clientIp;
	}

}
