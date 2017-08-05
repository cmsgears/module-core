<?php
namespace cmsgears\core\common\utilities;

use \DateTime;

/**
 * DateUtil provide several utility methods related to date and time.
 */
class DateUtil {

	// Week Days ---------------------------------------------------

	// PHP Week Days : 0 - Sunday, 1 - Monday, 2 - Tuesday, 3 - Wednesday, 4 - Thursday, 5 - Friday, 6 - Saturday
	// $w = date( 'w', strtotime( '2017-06-28' ) );

	// MySQL Week Days : 0 - Monday, 1 - Tuesday, 2 - Wednesday, 3 - Thursday, 4 - Friday, 5 - Saturday, 6 - Sunday
	// select weekday('2017-06-28');

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
	public static $hrs24	= [ '00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23' ];

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

		return date( "N", $date );
	}

	public static function getWeekStartDate( $date ) {

		$date 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();
		$w 		= date( 'w', $date );
		$monday = null;

		if( $w > 0 ) {

			$sunday = date( 'Y-m-d', $date - ( 86400 * $w ) );
			$monday = date( 'Y-m-d', strtotime( $sunday ) + 86400 );
		}
		else {

			$monday = date( 'Y-m-d', $date - ( 86400 * 6 ) );
		}

		return $monday;
	}

	public static function getWeekEndDate( $date ) {

		$date 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();
		$w 		= date( 'w', $date );
		$sunday = null;

		if( $w == 0 ) {

			$sunday = $date;
		}
		else {

			$sunday = date( 'Y-m-d', $date + ( 86400 * ( 6 - $w ) ) );
		}

		return $sunday;
	}

	public static function addDays( $date, $days ) {

		$date 	= is_string( $date ) ? strtotime( $date ) : $date->getTimestamp();

	    $date 	= strtotime( "+" . $days ." days", $date );

	    return  date( "Y-m-d", $date );
	}

	public static function getDayDifference( $startDate, $endDate ) {

		$start 	= is_string( $startDate ) ? strtotime( $startDate ) : $startDate->getTimestamp();
		$end 	= is_string( $endDate ) ? strtotime( $endDate ) : $endDate->getTimestamp();
		$diff	= $end - $start;
		$diff	= floor( $diff / ( 60 * 60 * 24 ) );

		return $diff;
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

	// Return whole day Hour and Minutes slots
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

	// Return whole day Hour and Minutes slots
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

	public static function formatMillis( $millis ) {

	    $seconds	= ( $millis / 1000 ) % 60;
	    $minutes	= ( $millis / ( 1000 * 60 ) ) % 60;
	    $hours		= ( $millis / ( 1000 * 60 * 60 ) ) % 24;

	    return "$hours::$minutes::$seconds";
	}

	public static function getYearsList( $start = null, $config = [] ) {

		$years	= [];

		// Get Current year if year is not set
		$currentyear	= date( "Y" );
		$start			= $start != null ? $start : $currentyear;

		$endYear		= isset( $config[ 'endYear' ] ) ? $config[ 'endYear' ] : $currentyear;
		$increment		= isset( $config[ 'increment' ] ) ? $config[ 'increment' ] : 1;
		$end			= $endYear != null ? $endYear : $limit;
		$i				= $start;

		while( $i <= $end ) {

			$years[ $i ]	= $i;

			$i = $i + $increment;
		}

		return $years;
	}

	// Reference: https://stackoverflow.com/questions/1727077/generating-a-drop-down-list-of-timezones-with-php/17355238#17355238
	function getTimezoneList() {

		$regions = [
			DateTimeZone::AFRICA,
			DateTimeZone::AMERICA,
			DateTimeZone::ANTARCTICA,
			DateTimeZone::ASIA,
			DateTimeZone::ATLANTIC,
			DateTimeZone::AUSTRALIA,
			DateTimeZone::EUROPE,
			DateTimeZone::INDIAN,
			DateTimeZone::PACIFIC,
		];

		$timezones = array();

		foreach( $regions as $region ) {

			$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
		}

		$timezone_offsets = array();

		foreach( $timezones as $timezone ) {

			$tz = new DateTimeZone( $timezone );

			$timezone_offsets[$timezone] = $tz->getOffset( new DateTime );
		}

		// sort timezone by offset
		asort( $timezone_offsets );

		$timezone_list = array();

		foreach( $timezone_offsets as $timezone => $offset ) {

			$offset_prefix		= $offset < 0 ? '-' : '+';
			$offset_formatted 	= gmdate( 'H:i', abs($offset) );

			$pretty_offset 		= "UTC${offset_prefix}${offset_formatted}";

			$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
		}

		return $timezone_list;
	}
}
