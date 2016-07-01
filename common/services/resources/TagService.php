<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;
use yii\data\Sort;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Tag;
use cmsgears\core\common\models\mappers\ModelTag;

use cmsgears\core\common\services\interfaces\resources\ITagService;

/**
 * The class TagService is base class to perform database activities for Tag Entity.
 */
class TagService extends \cmsgears\core\common\services\base\EntityService implements ITagService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\resources\Tag';

	public static $modelTable	= CoreTables::TABLE_TAG;

	public static $parentType	= CoreGlobal::TYPE_TAG;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TagService ----------------------------

	// Data Provider ------

	public function getPage( $config = [] ) {

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

		$config[ 'sort' ] = $sort;

		return parent::findPage( $config );
	}

	public static function getPageByType( $type ) {

		return self::getPage( [ 'conditions' => [ 'type' => $type ] ] );
	}

	// Read ---------------

    // Read - Models ---

	public function getBySlug( $slug ) {

		return self::findBySlug( $slug );
	}

	public function getByNameType( $name, $type ) {

		return self::findByNameType( $name, $type );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'name', 'description' ]
		]);
 	}

	// Delete -------------

	public function delete( $model, $config = [] ) {

		// Delete dependencies
		ModelTag::deleteByModelId( $model->id );

		// Delete model
		return parent::delete( $model, $config );
	}

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// TagService ----------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

	public static function findBySlug( $slug ) {

		return Tag::findBySlug( $slug );
	}

	public static function findByNameType( $name, $type ) {

		return Tag::findByNameType( $name, $type );
	}

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------
}

?>