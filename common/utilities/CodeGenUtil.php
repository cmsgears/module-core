<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

// Yii Imports
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The class CodeGenUtil provides utility methods to generate code snippets for commonly used code.
 */
class CodeGenUtil {

	// Static Methods ----------------------------------------------

	/**
	 * Return pagination info to be displayed on data grid footer or header.
	 * @return string - pagination info
	 */
	public static function getPaginationDetail( $dataProvider, $config = [] ) {

		$label = $config[ 'label' ] ?? 'entries';

		$total			= $dataProvider->getTotalCount();
		$pagination		= $dataProvider->getPagination();
		$current_page	= $pagination->getPage();
		$page_size		= $pagination->getPageSize();
		$start			= $page_size * $current_page;
		$end			= $page_size * ( $current_page + 1 ) - 1;
		$currentSize	= count( $dataProvider->getModels() );
		$currentDisplay	= $end - $start;

		if( $currentSize < $currentDisplay ) {

			$end = $start + $currentSize;
		}

		if( $end > 0 ) {

			$start += 1;
		}

		if( $currentSize == $page_size ) {

			$end += 1;
		}

		return "Showing $start to $end out of $total $label";
	}

	public static function getPaginationOptions( $limits = [], $pageLimit = null ) {

		$pageLimits = count( $limits ) > 0 ? $limits : [ '5' => 5, '10' => 10, '15' => 15, '20' => 20 ];

		$pageLimit	= Yii::$app->request->get( 'per-page' );
		$pageLimit	= isset( $pageLimit ) && in_array( $pageLimit, $pageLimits ) ? $pageLimit : $pageLimit;

		$pageLimitIdx = array_search( $pageLimit, $pageLimits );

		$options = self::generateSelectOptionsFromArray( $pageLimits, $pageLimitIdx );

		return $options;
	}

	/**
	 * @return string - csv of urls from the given associative array and base url
	 */
	public static function generateLinksFromMap( $baseUrl, $map, $csv = true, $absolute = true ) {

		$html = [];

		foreach( $map as $key => $value ) {

			if( $absolute ) {

				$html[]	= Html::a( $value, Url::to( $baseUrl . $key, true ) );
			}
			else {

				$html[]	= Html::a( $value, Url::toRoute( $baseUrl . $key ) );
			}
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

		for( $i = 0; $i < $length; $i++ ) {

			$element = $list[ $i ];

			$html[] = Html::a( $element, Url::to( $baseUrl . $element, true ) );
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

		$options = [];

		if( isset( $data ) ) {

			foreach ( $data as $element ) {

				$options[ $element[ $key1 ] ] = $element[ $key2 ];
			}
		}

		return $options;
	}

	public static function generateMapFromCsv( $csv ) {

		$items = preg_split( '/,/', $csv );

		$map = [];

		foreach( $items as $item ) {

			$map[ $item ] = $item;
		}

		return $map;
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

		$options = "";

		if( isset( $data ) ) {

			if( isset( $selected ) ) {

				foreach( $data as $key => $value ) {

					$val	= $value[ $key1 ];
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

				foreach( $data as $key => $value ) {

					$val	= $value[ $key1 ];
					$option = $value[ $key2 ];

					$options .= "<option value='$val'>$option</option>";
				}
			}
		}

		return $options;
	}

	// Generic Select for any table
	public static function generateSelectOptionsFromArray( $data, $selected = null, $config = [] ) {

		$keyPrefix = isset( $config[ 'keyPrefix' ] ) ? $config[ 'keyPrefix' ] : null;

		$options = "";

		if( isset( $data ) ) {

			if( isset( $selected ) ) {

				foreach( $data as $key => $value ) {

					$oKey = isset( $keyPrefix ) ? $keyPrefix . $key : $key;

					if( $selected === $oKey ) {

						$options .= "<option value='$oKey' selected>$value</option>";
					}
					else {

						$options .= "<option value='$oKey'>$value</option>";
					}
				}
			}
			else {

				foreach( $data as $key => $value ) {

					$oKey = isset( $keyPrefix ) ? $keyPrefix . $key : $key;

					$options .= "<option value='$oKey'>$value</option>";
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

			foreach( $data as $key => $value ) {

				$val	= $value[ $key1 ];
				$item	= $value[ $key2 ];

				$listItems .= "<li data-value='$val'>$item</li>";
			}
		}

		return $listItems;
	}

	public static function getRange( $start, $end ) {

		$arr = [];

		for( $i = $start; $i <= $end; $i++ ) {

			$arr[ $i ] = $i;
		}

		return $arr;
	}

	public static function getRangeOptions( $start, $end, $selected = null ) {

		$options = '';

		for( $i = $start; $i <= $end; $i++ ) {

			if( $selected === $i ) {

				$options .= "<option value='$i' selected>$i</option>";
			}
			else {

				$options .= "<option value='$i'>$i</option>";
			}
		}

		return $options;
	}

	// Return Image Tag
	public static function getImageThumbTag( $image, $options = [] ) {

		$type = isset( $options[ 'type' ] ) ? $options[ 'type' ] : 'thumb';

		// Use Image from DB
		if( isset( $image ) ) {

			$imageUrl = null;

			if( $type == 'thumb' ) {

				$imageUrl = $image->getThumbUrl();
			}
			else if( $type == 'medium' ) {

				$imageUrl = $image->getMediumUrl();
			}
			else if( $type == 'file' ) {

				$imageUrl = $image->getFileUrl();
			}

			if( isset( $options[ 'class' ] ) ) {

				$class = $options[ 'class' ];

				return "<img class=\"{$class}\" src=\"{$imageUrl}\">";
			}
			else {

				return "<img src=\"{$imageUrl}\">";
			}
		}
		else {

			// Use Image from web root directory
			if( isset( $options[ 'image' ] ) ) {

				$images = Yii::getAlias( '@images' );
				$img	= $options[ 'image' ];

				if( isset( $options[ 'class' ] ) ) {

					$class	= $options[ 'class' ];

					return "<img class=\"{$class}\" src=\"{$images}/{$img}\">";
				}
				else {

					return "<img src=\"{$images}/{$img}\">";
				}
			}
			// Use icon
			else if( isset( $options[ 'icon' ] ) ) {

				$icon = $options[ 'icon' ];

				return "<span class=\"icon $icon\" data-icon=\"{$icon}\"></span>";
			}
		}
	}

	public static function getFileUrl( $file, $options = [] ) {

		if( $file == null ) {

			if( isset( $options[ 'image' ] ) ) {

				$image = $options[ 'image' ];

				return YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . "/images/$image" : Url::toRoute( "/images/$image" );
			}
		}
		else {

			return $file->getFileUrl();
		}

		return null;
	}

	public static function getMediumUrl( $file, $options = [] ) {

		if( $file == null ) {

			if( isset( $options[ 'image' ] ) ) {

				$image = $options[ 'image' ];

				return YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . "/images/$image" : Url::toRoute( "/images/$image" );
			}
		}
		else {

			return $file->getMediumUrl();
		}

		return null;
	}

	public static function getSmallUrl( $file, $options = [] ) {

		if( $file == null ) {

			if( isset( $options[ 'image' ] ) ) {

				$image = $options[ 'image' ];

				return YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . "/images/$image" : Url::toRoute( "/images/$image" );
			}
		}
		else {

			return $file->getSmallUrl();
		}

		return null;
	}

	public static function getThumbUrl( $file, $options = [] ) {

		if( $file == null ) {

			if( isset( $options[ 'image' ] ) ) {

				$image = $options[ 'image' ];

				return YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . "/images/$image" : Url::toRoute( "/images/$image" );
			}
		}
		else {

			return $file->getThumbUrl();
		}

		return null;
	}

	public static function getPlaceholderUrl( $file, $options = [] ) {

		if( $file == null ) {

			if( isset( $options[ 'image' ] ) ) {

				$image = $options[ 'image' ];

				return YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . "/images/$image" : Url::toRoute( "/images/$image" );
			}
		}
		else {

			return $file->getPlaceholderUrl();
		}

		return null;
	}

	public static function getSmallPlaceholderUrl( $file, $options = [] ) {

		if( $file == null ) {

			if( isset( $options[ 'image' ] ) ) {

				$image = $options[ 'image' ];

				return YII_ENV_PROD ? CoreProperties::getInstance()->getResourceUrl() . "/images/$image" : Url::toRoute( "/images/$image" );
			}
		}
		else {

			return $file->getSmallPlaceholderUrl();
		}

		return null;
	}

	public static function generateMetaTags( $params, $config = [] ) {

		$descLimit		= isset( $config[ 'descLimit' ] ) ? $config[ 'descLimit' ] : 160;
		$keywordsLimit	= isset( $config[ 'keywordsLimit' ] ) ? $config[ 'keywordsLimit' ] : 10;

		$metaContent = '';

		// Last Updated
		if( isset( $params[ 'model' ] ) ) {

			$model		= $params[ 'model' ];
			$content	= isset( $model->modelContent ) ? $model->modelContent : null;

			$date = !empty( $model->modifiedAt ) ? ( ($model->modifiedAt instanceof \yii\db\Expression) ? DateUtil::getDateTime() : $model->modifiedAt ) : null;

			if( isset( $date ) && isset( $content ) && isset( $content->modifiedAt ) ) {

				$date = DateUtil::greaterThan( $date, $content->modifiedAt ) ? $content->modifiedAt : $date;
			}

			if( isset( $date ) ) {

				$metaContent .= "<meta name=\"last-updated\" content=\"$date UTC\">";
			}
		}

		// Description
		if( isset( $params[ 'desc' ] ) ) {

			$description = $params[ 'desc' ];

			$description = filter_var( $description, FILTER_SANITIZE_STRING );

			// SEO Limit - 160
			if( strlen( $description ) > $descLimit ) {

				$description = substr( $description, 0, $descLimit );
			}

			$metaContent .= "<meta name=\"description\" content=\"$description\" />";
		}

		// Keywords
		if( isset( $params[ 'keywords' ] ) ) {

			$keywords	= $params[ 'keywords' ];
			$keywords	= preg_split( "/,/", $keywords );

			if( count( $keywords ) > $keywordsLimit ) {

				$keywords = array_slice( $keywords, 0, $keywordsLimit );
			}

			$keywords = join( ',', $keywords );

			$metaContent .= "<meta name=\"keywords\" content=\"$keywords\" />";
		}

		// Robot
		if( isset( $params[ 'robot' ] ) ) {

			$robot = $params[ 'robot' ];

			$metaContent .= "<meta name=\"robots\" content=\"$robot\" />";
		}

		// User defined Schema
		if( isset( $params[ 'schema' ] ) ) {

			$schema = $params[ 'schema' ];

			$metaContent .= $schema;
		}

		return $metaContent;
	}

	public static function generateSeoH1( $params, $config = [] ) {

		$stripTags = isset( $config[ 'stripTags' ] ) ? $config[ 'stripTags' ] : true;

		$settings = isset( $params[ 'settings' ] ) ? $params[ 'settings' ] : [];

		if( isset( $params[ 'summary' ] ) ) {

			$summary = $stripTags ? strip_tags( $params[ 'summary' ] ) : $params[ 'summary' ];

			$seoH1 = "<h1>$summary</h1>";

			return isset( $settings->h1Summary ) && !$settings->h1Summary ? null : $seoH1;
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

		$content = ob_get_clean();

		return $content;
	}

	public static function getYearOptionsList( $start, $end = null ) {

		if( !isset( $end ) ) {

			$end = date( "Y" );
		}

		$options = null;

		for( $i = $end; $i >= $start; $i-- ) {

			$options .= "<option value='$i'>$i</option>";
		}

		return $options;
	}

	public static function getSummary( $content, $limit = CoreGlobal::TEXT_LARGE ) {

		if( strlen( $content ) > $limit ) {

			$content = "$content ...";

			return substr( $content, 0, $limit );
		}

		return $content;
	}

	public static function compressStyles( $styles ) {

		$compressed = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles );
		$compressed = str_replace( ': ', ':', $styles );
		$compressed = str_replace( '; ', ';', $styles );
		$compressed = str_replace( '{ ', '{', $styles );
		$compressed = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $styles );

		return $compressed;
	}

	public static function generateRandomString( $length = 8, $uc = true, $num = true, $special = false ) {

		$source = 'abcdefghijklmnopqrstuvwxyz';

		$str = '';

		if( $uc ) {

			$source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}

		if( $num ) {

			$source .= '0123456789';
		}

		if( $special ) {

			$source .= '~@#$%^*()_±={}|][';
		}

		$max = strlen( $source ) - 1;

		for( $i = 0; $i < $length; $i++ ) {

			$str .= $source[ ( mt_rand( 0, $max ) ) ];
		}

		return $str;
	}

	public static function generateNestedSetLists( $nestedSet, $config = [] ) {

		$wrap		= isset( $config[ 'wrap' ] ) ? $config[ 'wrap' ] : false;
		$url		= isset( $config[ 'url' ] ) ? $config[ 'url' ] : false;
		$slug		= isset( $config[ 'slug' ] ) ? $config[ 'slug' ] : false;
		$urlBase	= isset( $config[ 'urlBase' ] ) ? $config[ 'urlBase' ] : null;

		$rootId = 0;
		$depth	= 0;
		$change	= false;
		$child	= false;
		$cstart	= false;

		$result = $wrap ? '<ul>' : '';

		foreach( $nestedSet as $element ) {

			$entry = $element[ 'name' ];

			if( $url ) {

				if( $slug ) {

					$eslug = $element[ 'slug' ];

					$entry = "<a href=\"$urlBase/$eslug\">$entry</a>";
				}
				else {

					$eid = $element[ 'id' ];

					$entry = "<a href=\"$urlBase?id=$eid\">$entry</a>";
				}
			}

			if( $element[ 'rootId' ] != $rootId ) {

				if( $depth > 0 ) {

					if( $child ) {

						$result .= '</ul></li>';
					}
					else {

						$result .= '</li>';
					}
				}

				$depth	= 0;
				$rootId	= $element[ 'rootId' ];
				$change	= true;
				$child	= false;
			}
			else {

				if( $depth == 0 ) {

					$cstart = true;
				}
				else {

					$cstart = false;
				}

				$depth	= $element[ 'depth' ];
				$change	= false;
				$end	= false;
				$child	= true;
			}

			if( $change ) {

				$result .= '<li>' . $entry;
			}
			else {

				if( $cstart ) {

					$result .= '<ul>';
				}

				$result .= '<li>' . $entry . '</li>';
			}
		}

		if( count( $nestedSet ) > 0 ) {

			if( $depth > 0 ) {

				$result .= '</ul></li>';
			}
			else {

				$result .= '</li>';
			}
		}

		$result .= $wrap ? '</ul>' : '';

		return $result;
	}

	public static function generateNestedSetMenu( $nestedSet, $config = [] ) {

		$wrap		= isset( $config[ 'wrap' ] ) ? $config[ 'wrap' ] : false;
		$url		= isset( $config[ 'url' ] ) ? $config[ 'url' ] : false;
		$slug		= isset( $config[ 'slug' ] ) ? $config[ 'slug' ] : false;
		$urlBase	= isset( $config[ 'urlBase' ] ) ? $config[ 'urlBase' ] : null;

		$rootId = 0;
		$depth	= 0;
		$change	= false;
		$child	= false;
		$cstart	= false;

		$result = $wrap ? '<ul>' : '';

		foreach( $nestedSet as $element ) {

			$entry = $element[ 'name' ];

			if( $url ) {

				if( $slug ) {

					$eslug = $element[ 'slug' ];

					$entry = "<a href=\"$urlBase/$eslug\">$entry</a>";
				}
				else {

					$eid = $element[ 'id' ];

					$entry = "<a href=\"$urlBase?id=$eid\">$entry</a>";
				}
			}

			if( $element[ 'rootId' ] != $rootId ) {

				if( $depth > 0 ) {

					if( $child ) {

						$result .= '</span></li>';
					}
					else {

						$result .= '</li>';
					}
				}

				$depth	= 0;
				$rootId	= $element[ 'rootId' ];
				$change	= true;
				$child	= false;
			}
			else {

				if( $depth == 0 ) {

					$cstart = true;
				}
				else {

					$cstart = false;
				}

				$depth	= $element[ 'depth' ];
				$change	= false;
				$end	= false;
				$child	= true;
			}

			if( $change ) {

				$result .= '<li>' . $entry;
			}
			else {

				if( $cstart ) {

					$result .= '<span class="inline-block list-caret"><i class="cmti cmti-angle-down"></i></span><span class="nav-sub">';
				}

				$result .= '<span>' . $entry . '</span>';
			}
		}

		if( count( $nestedSet ) > 0 ) {

			if( $depth > 0 ) {

				$result .= '</span></li>';
			}
			else {

				$result .= '</li>';
			}
		}

		$result .= $wrap ? '</ul>' : '';

		$result = strip_tags( $result, '<ul><li><span><a><i>' );

		return $result;
	}

	public static function pluralize( $singular, $plural = null ) {

		if( !empty( $plural ) ) {

			return $plural;
		}

		$char = strtolower( $singular[ strlen( $singular ) - 1 ] );

		switch( $char ) {

			case 'y': {

				return substr( $singular, 0, -1 ) . 'ies';
			}
			case 's': {

				return $singular . 'es';
			}
			default: {

				return $singular.'s';
			}
		}
	}

	// Number Format

	public static function getMinNum( $num ) {

		if( strlen( $num ) >= 8 ) {

			$num = $num / 10000000;
		}
		else if( strlen( $num ) >= 6 ) {

			$num = $num / 100000;
		}
		else if( strlen( $num ) >= 5 ) {

			$num = $num / 1000;
		}
		else if( $num == 0 ) {

			$num = null;
		}

		return $num;
	}

	public static function getMinNumUnit( $num ) {

		$priceUnit = null;

		if( strlen( $num ) >= 8 ) {

			$priceUnit = 'Cr';
		}
		else if( strlen( $num ) >= 6 ) {

			$priceUnit = 'L';
		}
		else if( strlen( $num ) >= 5 ) {

			$priceUnit = 'K';
		}

		return $priceUnit;
	}

	public static function getMinNumText( $num, $round = 2 ) {

		if( strlen( $num ) >= 8 ) {

			$num = round( $num / 10000000, $round ) . ' Cr';
		}
		else if( strlen( $num ) >= 6 ) {

			$num = round( $num / 100000, $round ) . ' L';
		}
		else if( strlen( $num ) >= 5 ) {

			$num = round( $num / 1000, $round ) . ' K';
		}
		else if( $num == 0 ) {

			$num = null;
		}

		return $num;
	}

	public static function createAcronym( $string ) {

		$output	= null;
		$token	= strtok( $string, ' ' );

		while( $token !== false ) {

			$output .= $token[ 0 ];

			$token = strtok( ' ' );
		}

		return $output;
	}

}
