<?php
namespace cmsgears\core\common\utilities;

use \Yii;
use yii\helpers\Html; 

/**
 * The class CodeGenUtil provides utility methods to generate code snippets.
 */
class CodeGenUtil {
	
	// Static Methods ----------------------------------------------

	// Return pagination info	
	public static function getPaginationDetail( $pages, $page, $total ) {
		
		$current_page 	= $pages->page;
		$page_size		= $pages->pageSize;
		$start			= $page_size * $current_page;
		$end			= $page_size * ( $current_page + 1 ) - 1;
		$currentSize	= count($page);
		$currentDisplay	= $end - $start;

		if( $currentSize < $currentDisplay ) {

			$end	= $start + $currentSize;
		}

		return "Showing $start to $end out of $total entries";
	}

	// Generate Slug
	public static function generateSlug( $text ) {
		
		$text	= strtolower( $text );
		$text	= explode( " ", $text );
		$text	= implode( "-", $text );
		
		return $text;
	}
	
	// Generate Categories CSV
	public static function generateCategoriesCsv( $rootUrl, $map ) {
		
		$html	= [];

		foreach ( $map as $key => $value ) {

			$html[]	= Html::a( $value, ["#"] );
		}
		
		return implode( ",", $html );
	}

	// Generate an Associative Array from array having id name variables
	public static function generateIdNameArray( $data ) {

		return self::generateAssociativeArray( $data, 'id', 'name' );
	}

	// Generate an Associative Array from array having key value variables
	public static function generateKeyValueArray( $data ) {

		return self::generateAssociativeArray( $data, 'key', 'value' );
	}

	// Generate an Associative Array from array having id key variables
	public static function generateIdKeyArray( $data ) {

		return self::generateAssociativeArray( $data, 'id', 'key' );
	}

	// Generate an Associative Array from array having $key1 and $key2 variables
	public static function generateAssociativeArray( $data, $key1, $key2 ) {

		$options	= array();

		if( isset( $data ) ) {

			foreach ( $data as $key => $value ) {

				$options[ $value[ $key1 ] ] = $value[ $key2 ];
			}
		}

		return $options;
	}

	// Select for Option Table - By Id and Name
	public static function generateSelectOptionsIdKey( $data, $selected = null ) {

		return self::generateSelectOptions( $data, $selected, "id", "key" );
	}

	// Select for Option Table - By Name and Value 
	public static function generateSelectOptionsKeyValue( $data, $selected = null ) {

		return self::generateSelectOptions( $data, $selected, "key", "value" );
	}

	// Generic Select for any table
	public static function generateSelectOptions( $data, $selected = null, $key1, $key2 ) {
		
		$options	= "";
		
		if( isset( $data ) ) {
			
			if( isset($selected) ) {

				foreach ( $data as $key => $value ) {
					
					$val 	= $value[ $key1 ];
					$option = $value[ $key2 ];
					
					if( $selected === $val ) {

						$options .= "<option value='$val' selected>$option</option>";
					}
					else {
						$options .= "<option value='$val'>$option</option>";
					}
				}
			}
			else {

				foreach ( $data as $key => $value ) {

					$val 	= $value[ $key1 ];
					$option = $value[ $key2 ];

					$options .= "<option value='$val'>$option</option>";
				}
			}
		}

		return $options;
	}
	
	// Generic Select for any table
	public static function generateSelectOptionsFromArray( $data, $selected = null ) {
		
		$options	= "";
		
		if( isset( $data ) ) {
			
			if( isset($selected) ) {

				foreach ( $data as $key => $value ) {

					if( $selected === $key ) {

						$options .= "<option value='$key' selected>$value</option>";
					}
					else {
						$options .= "<option value='$key'>$value</option>";
					}
				}
			}
			else {

				foreach ( $data as $key => $value ) {

					$options .= "<option value='$key'>$value</option>";
				}
			}
		}

		return $options;
	}
}

?>