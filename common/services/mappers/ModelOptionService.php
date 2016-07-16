<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\ModelOption;

use cmsgears\core\common\services\interfaces\resources\IOptionService;
use cmsgears\core\common\services\interfaces\mappers\IModelOptionService;

use cmsgears\core\common\services\traits\MapperTrait;

/**
 * The class ModelOptionService is base class to perform database activities for ModelCategory Entity.
 */
class ModelOptionService extends \cmsgears\core\common\services\base\EntityService implements IModelOptionService {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelOption';

	public static $modelTable	= CoreTables::TABLE_MODEL_OPTION;

	public static $parentType	= null;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $optionService;

	// Traits ------------------------------------------------------

	use MapperTrait;

	// Constructor and Initialisation ------------------------------

    public function __construct( IOptionService $optionService, $config = [] ) {

		$this->optionService	= $optionService;

        parent::__construct( $config );
    }

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelOptionService --------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		return parent::update( $model, [
			'attributes' => [ 'order', 'active' ]
		]);
 	}

	// Delete -------------


	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ModelOptionService --------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function bindOptions( $binder, $parentType ) {

		$parentId	= $binder->binderId;
		$allData	= $binder->allData;
		$activeData	= $binder->bindedData;

		foreach ( $allData as $id ) {

			$toSave		= ModelOption::findByOptionId( $parentId, $parentType, $id );

			// Existing mapping
			if( isset( $toSave ) ) {

				if( in_array( $id, $activeData ) ) {

					$toSave->active	= true;
				}
				else {

					$toSave->active	= false;
				}

				$toSave->update();
			}
			// Save only required data
			else if( in_array( $id, $activeData ) ) {

				$toSave		= new ModelOption();

				$toSave->parentId	= $parentId;
				$toSave->parentType	= $parentType;
				$toSave->optionId	= $id;
				$toSave->active		= true;

				$toSave->save();
			}
		}

		return true;
	}

	// Delete -------------
}
