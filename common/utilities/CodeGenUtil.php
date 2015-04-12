<?php
namespace cmsgears\core\common\utilities;

use \Yii;
use yii\helpers\Html; 

/**
 * The class CodeGenUtil provides utility methods to generate code snippets for commonly used code.
 */
class CodeGenUtil {
	
	// Static Methods ----------------------------------------------

	/**
	 * Return pagination info to be displayed on data grid footer or header.
	 * @return string - pagination info
	 */	
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

	/**
	 * @return string - Slug generated by replacing the spaces with hyphen(-)
	 */
	public static function generateSlug( $text ) {
		
		$text	= strtolower( $text );
		$text	= explode( " ", $text );
		$text	= implode( "-", $text );
		
		return $text;
	}

	/**
	 * @return string - csv of category urls
	 */
	public static function generateCategoriesCsv( $rootUrl, $map ) {

		$html	= [];

		foreach ( $map as $key => $value ) {

			$html[]	= Html::a( $value, [ $rootUrl . "/$key" ] );
		}

		return implode( ",", $html );
	}

	/**
	 * @return array - associative array from array having id and name keys for each entry 
	 */ 
	public static function generateIdNameArray( $data ) {

		return self::generateAssociativeArray( $data, 'id', 'name' );
	}

	/**
	 * @return array - associative array from array having key and value keys for each entry 
	 */
	public static function generateKeyValueArray( $data ) {

		return self::generateAssociativeArray( $data, 'key', 'value' );
	}

	/**
	 * @return array - associative array from array having id and key keys for each entry 
	 */
	public static function generateIdKeyArray( $data ) {

		return self::generateAssociativeArray( $data, 'id', 'key' );
	}

	/**
	 * @return array - associative array from array having $key1 and $key2 keys for each entry 
	 */
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