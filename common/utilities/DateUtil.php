<?php
namespace cmsgears\core\common\utilities;

use \DateTime;

/**
 * DateUtil provide several utility methods related to date and time.
 */
class DateUtil {

	// Static Methods ----------------------------------------------

	/**
	 * @return datetime - current datetime having specified format
	 */
    public static function getDateTime( $format = null ) {

		if( !isset( $format ) ) {

			$format	= 'Y-m-d H:i:s';
		}

		return  date ( $format );
    }

	/**
	 * @return datetime - current date having specified format
	 */
    public static function getDate( $format = null ) {

		if( !isset( $format ) ) {

			$format	= 'Y-m-d';
		}

		return  date ( $format );
    }

	/**
	 * @return time - current time having specified format
	 */
	public static function getTime( $format = null ) {

		if( !isset( $format ) ) {
			
			$format	= 'H:i:s';
		}

		return date( $format );
    }

	/**
	 * @return date - from datetime
	 */
    public static function getDateFromDateTime( $date ) {

		$date	= preg_split( "/ /", $date );

		return $date[ 0 ];
    }

	/**
	 * @return date - from datetime
	 */
    public static function getTimeFromDateTime( $date ) {

		$date	= preg_split( "/ /", $date );

		return $date[ 1 ];
    }

	/**
	 * @return date - from datetime
	 */
    public static function getDayFromDate( $date, $format = null ) {

		if( !isset( $format ) ) {
			
			$format	= 'H:i:s';
		}

		$date	= preg_split( "/-/", $date );

		return $date[ 2 ];
    }

	/**
	 * @return date - from datetime
	 */
    public static function getWeekDayFromDate( $date, $format = null ) {

		if( !isset( $format ) ) {

			$format	= 'H:i:s';
		}

		$date = strtotime( $date );

		return date( "N", $date ) - 1;
    }

	/**
	 * @return array - of current week dates having specified format
	 */
	public static function getCurrentWeekDates( $format = null ) {

		$currentDay = strtotime( 'last sunday' );
		$dates		= [];

		if( !isset( $format ) ) {
			
			$format	= 'Y-m-d';
		}

		for ( $i = 0 ; $i < 7 ; $i++ ) {

		    $dates[] 	= date( $format, $currentDay );

		    $currentDay += 24 * 3600;
		}
		
		return $dates;
	}

	/**
	 * @return array - of last week dates having specified format
	 */
	public static function getLastWeekDates( $format = null ) {

		$currentDay = strtotime( 'last sunday' ) - 7*24*3600;
		$dates		= [];

		if( !isset( $format ) ) {
			
			$format	= 'Y-m-d';
		}

		for ( $i = 0 ; $i < 7 ; $i++ ) {

		    $dates[] 	= date( $format, $currentDay );

		    $currentDay += 24 * 3600;
		}
		
		return $dates;
	}

	/**
	 * @return array - of current month dates having specified format
	 */
	public static function getCurrentMonthDates( $format = null ) {

		$currentDay = date( 'Y-m-01' );
		$lastDay 	= date( 'Y-m-t' );
		$daysCount	= ( strtotime( $lastDay ) - strtotime( $currentDay ) ) / 3600 / 24;
		$currentDay	= strtotime( $currentDay );
		$dates		= [];

		if( !isset( $format ) ) {
			
			$format	= 'Y-m-d';
		}

		for ( $i = 0 ; $i <= $daysCount ; $i++ ) {

		    $dates[] 	= date( $format, $currentDay );

		    $currentDay += 24 * 3600;
		}
		
		return $dates;
	}
	
	/**
	 * @return array - of last month dates having specified format
	 */
	public static function getLastMonthDates( $format = null ) {

		$currentDay = new \DateTime( "first day of last month" );
		$lastDay 	= new \DateTime( "last day of last month" );

		$currentDay = $currentDay->format( 'Y-m-d' );
		$lastDay 	= $lastDay->format( 'Y-m-d' );
		$daysCount	= ( strtotime( $lastDay ) - strtotime( $currentDay ) ) / 3600 / 24;
		$currentDay	= strtotime( $currentDay );
		$dates		= [];

		if( !isset( $format ) ) {
			
			$format	= 'Y-m-d';
		}

		for ( $i = 0 ; $i <= $daysCount ; $i++ ) {

		    $dates[] 	= date( $format, $currentDay );

		    $currentDay += 24 * 3600;
		}
		
		return $dates;
	}
}

?>