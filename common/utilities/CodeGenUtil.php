<?php
namespace cmsgears\core\common\utilities;

// Yii Imports
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

		if( $end > 0 ) {

			$start += 1;
		}

		if( $currentSize == $page_size ) {

			$end	+= 1;
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

	public static function generateListItemsIdName( $data ) {

		return self::generateListItems( $data, "id", "name" );
	}

	public static function generateListItems( $data, $key1, $key2 ) {

		$listItems	= "";

		if( isset( $data ) ) {

			foreach ( $data as $key => $value ) {

				$val 	= $value[ $key1 ];
				$item 	= $value[ $key2 ];

				$listItems .= "<li data-value='$val'>$item</li>";
			}
		}

		return $listItems;
	}

	// Return Image Tag
	public static function getImageThumbTag( $image, $options = [] ) {

		// Use Image from DB
		if( isset( $image ) ) {

			$thumbUrl = $image->getThumbUrl();

			if( isset( $options[ 'class' ] ) ) {

				$class = $options[ 'class' ];

				return "<img class='$class' src='$thumbUrl'>";
			}
			else {

				return "<img src='$thumbUrl'>";
			}
		}
		else {

			// Use Image from web root directory
			if( isset( $options[ 'image' ] ) ) {

				$images = Yii::getAlias( '@images' );
				$img	= $options[ 'image' ];

				if( isset( $options[ 'class' ] ) ) {

					$class 	= $options[ 'class' ];

					return "<img class='$class' src='$images/$img'>";
				}
				else {

					return "<img src='$images/$img'>";
				}
			}
			// Use icon
			else if( isset( $options[ 'icon' ] ) ) {

				$icon = $options[ 'icon' ];

				return "<span class='$icon'></span>";
			}
		}
	}

	public static function getFileUrl( $file, $options = [] ) {

		if( $file == null ) {

			if( isset( $options[ 'image' ] ) ) {

				$image	= $options[ 'image' ];

				return Yii::getAlias( '@images' ) . "/$image";
			}
		}
		else {

			return $file->getFileUrl();
		}

		return null;
	}

	public static function generateMetaTags( $params ) {

		$metaContent	= '';

		if( isset( $params[ 'desc' ] ) ) {

			$description	= $params[ 'desc' ];
			$metaContent 	.= "<meta name='description' content='$description' />";
		}

		if( isset( $params[ 'meta' ] ) ) {

			$keywords		= $params[ 'keywords' ];
			$metaContent 	.= "<meta name='keywords' content='$keywords' />";
		}

		if( isset( $params[ 'robot' ] ) ) {

			$robot			= $params[ 'robot' ];
			$metaContent 	.= "<meta name='robots' content='$robot' />";
		}

		return $metaContent;
	}

	public static function generateSeoH1( $params ) {

		if( isset( $params[ 'summary' ] ) ) {

			$summary	= $params[ 'summary' ];
			$seoH1		= "<h1 class='hidden'>$summary</h1>";

			return $seoH1;
		}

		return null;
	}

	// Return the username by splitting given email.
	public static function splitEmail( $email ) {

		$parts = explode( '@', $email );

		return $parts[ 0 ];
	}

	public static function isEmptyString( $string ) {

		return !( isset( $string ) && ( strlen( trim( $string ) ) > 0 ) );
	}

	public static function notEmptyString( $string ) {

		return ( isset( $string ) && ( strlen( trim( $string ) ) > 0 ) );
	}

	public static function registerJsFromFile( $view, $position, $filePath ) {

		ob_start();

		include( $filePath );

		$script	= ob_get_clean();

		$view->registerJs( $script, $position );
	}

	public static function getFileContent( $filePath ) {

		ob_start();

		include( $filePath );

		$content	= ob_get_clean();

		return $content;
	}

	public static function getYearOptionsList( $start, $end = null ) {

		if( !isset( $end ) ) {

			$end	= date( "Y" );
		}

		$options	= null;

		for( $i = $end; $i >= $start; $i-- ) {

			$options	.= "<option value='$i'>$i</option>";
		}

		return $options;
	}
}

?>