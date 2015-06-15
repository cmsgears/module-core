<?php
namespace cmsgears\core\common\utilities;

/**
 * DateUtil provide several utility methods related to date and time.
 */
class DateUtil {

	// Static Methods ----------------------------------------------

	/**
	 * @return datetime - having mysql format
	 */
    public static function getDate() {

		return date( 'Y-m-d H:i:s' );
    }

	/**
	 * @return date - from mysql date
	 */
    public static function getDateFromTimestamp( $date ) {
		
		$date	= preg_split( "/ /", $date );

		return $date[0];
    } 

	/**
	 * @return time - current date having specified format
	 */
	public static function getCurrentDate( $format = null ) {
		
		if( !isset( $format ) ) {
			
			$format	= 'l, F j, Y';
		}

		return  date ( $format );
	}

	/**
	 * @return time - current time having specified format
	 */
	public static function getCurrentTime( $format = null ) {

		if( !isset( $format ) ) {
			
			$format	= 'h:i A';
		}

		return date( $format );
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