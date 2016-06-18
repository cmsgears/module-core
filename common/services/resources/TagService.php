<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Tag;
use cmsgears\core\common\models\mappers\ModelTag;

/**
 * The class TagService is base class to perform database activities for Tag Entity.
 */
class TagService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Tag
	 */
	public static function findById( $id ) {

		return Tag::findById( $id );
	}

	public static function findBySlug( $slug ) {

		return Tag::findBySlug( $slug );
	}

	public static function findByNameType( $name, $type ) {

		return Tag::findByNameType( $name, $type );
	}

	/**
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMap() {

		return self::findMap( 'id', 'name', CoreTables::TABLE_TAG );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of tag id and name.
	 */
	public static function getIdNameList() {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_TAG );
	}

	// Pagination -------

	public static function getPagination( $config = [] ) {

	    $sort = new Sort([
	        'attributes' => [
	            'name' => [
	                'asc' => [ 'name' => SORT_ASC ],
	                'desc' => ['name' => SORT_DESC ],
	                'default' => SORT_DESC,
	                'label' => 'name',
	            ]
	        ]
	    ]);

		if( !isset( $config[ 'conditions' ] ) ) {

			$config[ 'conditions' ] = [];
		}

		// Restrict to site
		if( !isset( $config[ 'site' ] ) || !$config[ 'site' ] ) {

			$config[ 'conditions' ][ 'siteId' ] = Yii::$app->cmgCore->siteId;

			unset( $config[ 'site' ] );
		}

		if( !isset( $config[ 'sort' ] ) ) {

			$config[ 'sort' ] = $sort;
		}

		if( !isset( $config[ 'search-col' ] ) ) {

			$config[ 'search-col' ] = 'name';
		}

		return self::getDataProvider( new Tag(), $config );
	}

	public static function getPaginationByType( $type ) {

		return self::getPagination( [ 'conditions' => [ 'type' => $type ] ] );
	}

	// Create ----------------

	public static function create( $tag ) {

		$tag->save();

		return $tag;
	}

	// Update -----------

	public static function update( $tag ) {

		// Find existing Tag
		$tagToUpdate	= self::findById( $tag->id );

		// Copy Attributes
		$tagToUpdate->copyForUpdateFrom( $tag, [ 'name', 'description' ] );

		// Update Tag
		$tagToUpdate->update();

		// Return updated Tag
		return $tagToUpdate;
	}

	// Delete -----------

	public static function delete( $tag ) {

		// Find existing Tag
		$tagToDelete	= self::findById( $tag->id );

		// Delete dependency
		ModelTag::deleteByTagId( $tag->id );

		// Delete Tag
		$tagToDelete->delete();

		return true;
	}
}

?>