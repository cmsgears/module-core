<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;
use yii\db\Query;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\mappers\ModelOption;

/**
 * The class ModelOptionService is base class to perform database activities for ModelCategory Entity.
 */
class ModelOptionService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	// Read - Models ---

	// Read - Lists ----

	// Create -----------

	// Update -----------

	public static function bindOptions( $binder, $parentType ) {

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

	// Delete -----------
}

?>