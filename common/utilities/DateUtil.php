<?php
namespace cmsgears\core\common\utilities;

use \DateTime;

/**
 * DateUtil provide several utility methods related to date and time.
 */
class DateUtil {

	// Week Days ---------------------------------------------------

	const WEEK_DAY_SUN		=  0;
	const WEEK_DAY_MON		=  1;
	const WEEK_DAY_TUE		=  2;
	const WEEK_DAY_WED		=  3;
	const WEEK_DAY_THU		=  4;
	const WEEK_DAY_FRI		=  5;
	const WEEK_DAY_SAT		=  6;
	const WEEK_DAY_ALL		= 10;

	public static $weekDaysMap = [
		self::WEEK_DAY_MON => 'Monday',
		self::WEEK_DAY_TUE => 'Tuesday',
		self::WEEK_DAY_WED => 'Wednesday',
		self::WEEK_DAY_THU => 'Thursday',
		self::WEEK_DAY_FRI => 'Friday',
		self::WEEK_DAY_SAT => 'Saturday',
		self::WEEK_DAY_SUN => 'Sunday'
	];

	public static $weekDaysWithAllMap = [
		self::WEEK_DAY_ALL => 'All Days',
		self::WEEK_DAY_MON => 'Monday',
		self::WEEK_DAY_TUE => 'Tuesday',
		self::WEEK_DAY_WED => 'Wednesday',
		self::WEEK_DAY_THU => 'Thursday',
		self::WEEK_DAY_FRI => 'Friday',
		self::WEEK_DAY_SAT => 'Saturday',
		self::WEEK_DAY_SUN => 'Sunday'
	];

	// Hrs/Mins ----------------------------------------------------

	// hours in 12 and 24 hours format
	public static $hrs12	= [ '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12' ];
	public static $hrs24	= [ '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24' ];

	// minutes with different interval
	public static $mins15	= [ '00', '15', '30', '45' ];
	public static $mins10	= [ '00', '10', '20', '30', '40', '50' ];
	public static $mins5	= [ '00', '05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55' ];

	// Static Methods ----------------------------------------------

	/**
	 * @return datetime - current datetime having specified format
	 */
	public static function getDateTime( $format = null ) {

		if( !isset( $format ) ) {

			$format	= 'Y-m-d H:i:s';
		}

		return	date ( $format );
	}

	/**
	 * @return datetime - current date having specified format
	 */
	public static function getDate( $format = null ) {

		if( !isset( $format ) ) {

			$format	= 'Y-m-d';
		}

		return	date ( $format );
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

		$date 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();

		return date( "N", $date ) - 1;
	}

	public static function getWeekStartDate( $date ) {

		$date 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();

		$nbDay 	= date( 'N', strtotime( $date ) );

		$monday = new DateTime();

		$monday->setTimestamp( $date );
		$monday->modify( '-' . ( $nbDay - 1 ) . ' days' );

		return $monday;
	}

	public static function getWeekEndDate( $date ) {

		$date 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();

		$nbDay 	= date( 'N', strtotime( $date ) );

		$sunday = new DateTime( $date );

		$sunday->modify( '+' . ( 7 - $nbDay ) . ' days' );

		return $sunday;
	}

	public static function addDays( $date, $days ) {

		$date 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();

	    $date 	= strtotime( "+" . $days ." days", $date );

	    return  date( "Y-m-d", $date );
	}

	public static function lessThan( $sourceDate, $toDate, $equal = false ) {

		$source = is_string( $sourceDate ) ? strtotime( $sourceDate ) : $sourceDate->getTimestamp();
		$test 	= is_string( $toDate ) ? strtotime( $toDate ) : $toDate->getTimestamp();

		// Test less than
		if( $equal ) {

			return $test <= $source;
		}

		return $test < $source;
	}

	public static function inBetween( $startDate, $endDate, $date ) {

		$start 	= is_string( $startDate ) ? strtotime( $startDate ) : $startDate->getTimestamp();
		$end 	= is_string( $endDate ) ? strtotime( $endDate ) : $endDate->getTimestamp();
		$test 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();

		// Test in between start and end
		return ( ( $test >= $start ) && ( $test <= $end ) );
	}

	public static function greaterThan( $sourceDate, $toDate, $equal = false ) {

		$source = is_string( $sourceDate ) ? strtotime( $sourceDate ) : $sourceDate->getTimestamp();
		$test 	= is_string( $toDate ) ? strtotime( $toDate ) : $toDate->getTimestamp();

		// Test greater than
		if( $equal ) {

			return $test >= $source;
		}

		return $test > $source;
	}

	/**
	 * @return array - of week dates for given mon
	 */
	public static function getWeekDates( $monday ) {

		$currentDay = is_string( $monday ) ? strtotime( $monday ) : $monday->getTimestamp();
		$dates		= [];

		if( !isset( $format ) ) {

			$format	= 'Y-m-d';
		}

		for ( $i = 0 ; $i < 7 ; $i++ ) {

			$dates[]	= date( $format, $currentDay );

			$currentDay += 24 * 3600;
		}

		return $dates;
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

			$dates[]	= date( $format, $currentDay );

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

			$dates[]	= date( $format, $currentDay );

			$currentDay += 24 * 3600;
		}

		return $dates;
	}

	/**
	 * @return array - of current month dates having specified format
	 */
	public static function getCurrentMonthDates( $format = null ) {

		$currentDay = date( 'Y-m-01' );
		$lastDay	= date( 'Y-m-t' );
		$daysCount	= ( strtotime( $lastDay ) - strtotime( $currentDay ) ) / 3600 / 24;
		$currentDay	= strtotime( $currentDay );
		$dates		= [];

		if( !isset( $format ) ) {

			$format	= 'Y-m-d';
		}

		for ( $i = 0 ; $i <= $daysCount ; $i++ ) {

			$dates[]	= date( $format, $currentDay );

			$currentDay += 24 * 3600;
		}

		return $dates;
	}

	/**
	 * @return array - of last month dates having specified format
	 */
	public static function getLastMonthDates( $format = null ) {

		$currentDay = new \DateTime( "first day of last month" );
		$lastDay	= new \DateTime( "last day of last month" );

		$currentDay = $currentDay->format( 'Y-m-d' );
		$lastDay	= $lastDay->format( 'Y-m-d' );
		$daysCount	= ( strtotime( $lastDay ) - strtotime( $currentDay ) ) / 3600 / 24;
		$currentDay	= strtotime( $currentDay );
		$dates		= [];

		if( !isset( $format ) ) {

			$format	= 'Y-m-d';
		}

		for ( $i = 0 ; $i <= $daysCount ; $i++ ) {

			$dates[]	= date( $format, $currentDay );

			$currentDay += 24 * 3600;
		}

		return $dates;
	}

	public static function getHrMinSlots( $startHr = 9, $minInterval = 15 ) {

		$interval	= [];
		$hour		= $startHr;
		$minute		= 0;

		for( $slot = 1; $slot <= 97; $slot++ ) {

			$houri	= $hour;

			if( $hour < 10 ) {

				$houri	= "0$hour";
			}

			$minutei = $minute;

			if( $minute < 10 ) {

				$minutei	= "0$minute";
			}

			$interval[]	= $houri . ':' . $minutei . ':00';

			$minute += $minInterval;

			if( $slot % 4 == 0 ) {

				 $hour++;
				 $minute	= 0;

				 if( $hour == 24 ) {

					$hour	= 0;
				 }
			}

			if( $slot == 97 ) {

				 $hour		= 0;
				 $minute	= 0;
			}
		}

		return	$interval;
	}

	public static function getHrMinSlotsForSelect( $startHr = 9, $minInterval = 15 ) {

		$interval	= [];
		$hour		= $startHr;
		$minute		= 0;

		for( $slot = 1; $slot <= 97; $slot++ ) {

			$houri	= $hour;

			if( $hour < 10 ) {

				$houri	= "0$hour";
			}

			$minutei = $minute;

			if( $minute < 10 ) {

				$minutei	= "0$minute";
			}

			$time				= $houri . ':' . $minutei . ':00';
			$interval[ $time ]	= $time;

			$minute += $minInterval;

			if( $slot % 4 == 0 ) {

				 $hour++;
				 $minute	= 0;

				 if( $hour == 24 ) {

					$hour	= 0;
				 }
			}

			if( $slot == 97 ) {

				 $hour		= 0;
				 $minute	= 0;
			}
		}

		return	$interval;
	}
}
