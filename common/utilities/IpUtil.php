<?php
namespace cmsgears\core\common\utilities;

// Yii Imports
use \Yii;

class IpUtil {

    public static function getClientIp( $long = false ) {

        $clientIp   = null;

        if( getenv( 'HTTP_CLIENT_IP' ) ) {

            $clientIp   = getenv( 'HTTP_CLIENT_IP' );
        }
        else if( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {

            $clientIp   = getenv( 'HTTP_X_FORWARDED_FOR' );
        }
        else if( getenv( 'HTTP_X_FORWARDED' ) ) {

            $clientIp   = getenv( 'HTTP_X_FORWARDED' );
        }
        else if( getenv( 'HTTP_FORWARDED_FOR' ) ) {

            $clientIp   = getenv( 'HTTP_FORWARDED_FOR' );
        }
        else if( getenv( 'HTTP_FORWARDED' ) ) {

            $clientIp   = getenv( 'HTTP_FORWARDED' );
        }
        else if( getenv( 'REMOTE_ADDR' ) ) {

            $clientIp   = getenv( 'REMOTE_ADDR' );
        }

        if( $long ) {

            return ip2long( $clientIp );
        }

        return $clientIp;
    }
}
