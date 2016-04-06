<?php
namespace cmsgears\core\admin\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\resources\Gallery;

class GalleryService extends \cmsgears\core\common\services\resources\GalleryService {

	// Static Methods ----------------------------------------------

	// Pagination -------

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'owner' => [
	                'asc' => [ 'createdBy' => SORT_ASC ],
	                'desc' => ['createdBy' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'owner'
	            ],
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name'
	            ],
	            'slug' => [
	                'asc' => [ 'slug' => SORT_ASC ],
	                'desc' => ['slug' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'slug'
	            ],
	            'title' => [
	                'asc' => [ 'title' => SORT_ASC ],
	                'desc' => ['title' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'title'
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'conditions' ] ) ) {

			$config[ 'conditions' ] = [];
		}

		// Restrict to site
		if( !isset( $config[ 'site' ] ) || !$config[ 'site' ] ) {

			$config[ 'conditions' ] = [ 'siteId' => Yii::$app->cmgCore->siteId ];

			unset( $config[ 'site' ] );
		}

		if( !isset( $config[ 'query' ] ) ) {

			$config[ 'query' ] = Gallery::findWithOwner();
		}

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Gallery(), $config );
	}

	/**
	 * @return ActiveDataProvider
	 */
	public static function getPaginationByType( $type = null, $config = [] ) {

		if( isset( $type ) ) {

			$config[ 'conditions' ][ 'type' ] = $type;
		}

		return self::getPagination( $config );
	}

	// Update -----------

	/**
	 * @param Gallery $gallery
	 * @return Gallery
	 */
	public static function update( $gallery ) {

		// Template
		if( isset( $gallery->templateId ) && $gallery->templateId <= 0 ) {

			$gallery->templateId = null;
		}

		// Find existing Gallery
		$galleryToUpdate	= self::findById( $gallery->id );

		// Copy and set Attributes
		$galleryToUpdate->copyForUpdateFrom( $gallery, [ 'templateId', 'name', 'title', 'description', 'active' ] );

		// Update Gallery
		$galleryToUpdate->update();

		// Return updated Gallery
		return $galleryToUpdate;
	}
}

?>