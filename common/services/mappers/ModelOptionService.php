<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;
use yii\db\Query;

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

	public function getModelCounts( $parentType, $categorySlug, $active = false ) {

		$categoryTable	= CoreTables::TABLE_CATEGORY;
		$optionTable	= CoreTables::TABLE_OPTION;
		$mOptionTable	= CoreTables::TABLE_MODEL_OPTION;
		$query			= new Query();

		$query->select( [ "$optionTable.name", "count($optionTable.id) as total" ] )
				->from( $optionTable )
				->leftJoin( $mOptionTable, "$mOptionTable.modelId=$optionTable.id" )
				->leftJoin( $categoryTable, "$categoryTable.id=$optionTable.categoryId" )
				->where( "$mOptionTable.parentType='$parentType' AND $categoryTable.slug='$categorySlug'" )
				->groupBy( "$optionTable.id" );

		if( $active ) {

			$query->andWhere( "$mOptionTable.active=$active" );
		}

		$counts		= $query->all();
		$returnArr	= [];
		$counter	= 0;

		foreach ( $counts as $count ) {

			$returnArr[ $count[ 'name' ] ] = $count[ 'total' ];

			$counter	= $counter + $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}


	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function update( $model, $config = [] ) {

		$attributes = isset( $config[ 'attributes' ] ) ? $config[ 'attributes' ] : [ 'order', 'active' ];

		return parent::update( $model, [
			'attributes' => $attributes
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
		$allData	= $binder->all;
		$activeData	= $binder->binded;

		foreach ( $allData as $id ) {

			$toSave		= ModelOption::findByModelId( $parentId, $parentType, $id );

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
				$toSave->modelId	= $id;
				$toSave->active		= true;

				$toSave->save();
			}
		}

		return true;
	}

	// Delete -------------

}
