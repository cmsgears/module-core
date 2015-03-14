<?php
namespace cmsgears\core\common\utilities;

class DateUtil {

	// Static Methods ----------------------------------------------

    public static function getMysqlDate() {

		return date( 'Y-m-d H:i:s' );
    }
}

?>