<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii; 

// CMG Imports
use cmsgears\core\common\models\entities\ModelForm;

/**
 * The class ModelFormService is base class to perform database activities for ModelForm Entity.
 */
class ModelFormService extends Service {

	// Static Methods ----------------------------------------------
	
	// Read ----------------

	public static function findByParentType( $parentType ) {

		return ModelForm::findByParentType( $parentType );
	}
	
	public static function findByParentId( $parentId ) {

		return ModelForm::findByParentId( $parentId );
	}

	public static function findByFormId( $parentId, $parentType, $formId ) {

		return ModelForm::findByFormId( $parentId, $parentType, $formId );
	}

	// Create ----------------

	public static function create( $model ) {

		$model->save();
	}

	// Update ----------------

	// Delete ----------------

	public static function delete( $model ) {

		$model->delete();

		return true;
	}
}

?>