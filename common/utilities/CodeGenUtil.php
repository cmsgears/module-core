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
	public static function getPaginationDetail( $dataProvider ) {

		$total			= $dataProvider->getTotalCount();
		$pagination		= $dataProvider->getPagination();
		$current_page 	= $pagination->getPage();
		$page_size		= $pagination->getPageSize();
		$start			= $page_size * $current_page;
		$end			= $page_size * ( $current_page + 1 ) - 1;
		$currentSize	= count( $dataProvider->getModels() );
		$currentDisplay	= $end - $start;

		if( $currentSize < $currentDisplay ) {

			$end	= $start + $currentSize;
		}

		return "Showing $start to $end out of $total entries";
	}

	/**
	 * @return string - csv of urls from the given associative array and base url
	 */
	public static function generateLinksFromMap( $baseUrl, $map, $csv = true ) {

		$html	= [];

		foreach ( $map as $key => $value ) {

			$html[]	= Html::a( $value, [ $baseUrl . "$key" ] );
		}

		if( $csv ) {

			return implode( ",", $html );
		}
		else {

			return $html;
		}
	}

	/**
	 * @return string - csv of urls from the given array and base url
	 */
	public static function generateLinksFromList( $baseUrl, $list, $csv = true ) {

		$html	= [];
		$length	= count( $list );

		for ( $i = 0; $i < $length; $i++ ) {
			
			$element 	= $list[ $i ];
			$html[]		= Html::a( $element, [ $baseUrl . "$element" ] );
		}
		
		if( $csv ) {

			return implode( ",", $html );
		}
		else {

			return $html;
		}
	}

	/**
	 * @return array - associative array from array having id and name keys for each entry 
	 */ 
	public static function generateIdNameMap( $data ) {

		return self::generateAssociativeArray( $data, 'id', 'name' );
	}

	/**
	 * @return array - associative array from array having key and value keys for each entry 
	 */
	public static function generateNameValueMap( $data ) {

		return self::generateAssociativeArray( $data, 'name', 'value' );
	}

	/**
	 * @return array - associative array from array having $key1 and $key2 keys for each entry 
	 */
	public static function generateAssociativeArray( $data, $key1, $key2 ) {

		$options	= array();

		if( isset( $data ) ) {

			foreach ( $data as $element ) {

				$options[ $element[ $key1 ] ] = $element[ $key2 ];
			}
		}

		return $options;
	}

	// Select for Option Table - By Id and Name
	public static function generateSelectOptionsIdName( $data, $selected = null ) {

		return self::generateSelectOptions( $data, $selected, "id", "name" );
	}

	// Select for Option Table - By Name and Value 
	public static function generateSelectOptionsNameValue( $data, $selected = null ) {

		return self::generateSelectOptions( $data, $selected, "name", "value" );
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