<?php
namespace cmsgears\core\common\services\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\services\mappers\ModelAttributeService;

/**
 * The class EntityService is base for a generic entity.
 */
abstract class EntityService extends OService {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	protected static $modelType;

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// EntityService ---------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// EntityService ---------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	public static function findAttributeMapByType( $model, $type ) {

		return ModelAttributeService::findMapByType( $model->id, static::$modelType, $type );
	}

	// Read - Others ---

	// Create -------------

	// Update -------------

	public static function updateAttributes( $model, $attributes ) {

		foreach ( $attributes as $attribute ) {

			ModelAttributeService::update( $attribute );
		}

		return true;
	}

	// Delete -------------
}

?>