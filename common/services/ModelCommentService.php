<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\ModelComment;
 
/**
 * The class ModelCommentService is base class to perform database activities for ModelComment Entity.
 */
class ModelCommentService extends Service {

	// Static Methods ----------------------------------------------

	// Read ---------------- 
 
	// Create -----------
 	
 	public static function create( $model ) {
 		
		$model->save();
 	}
 	
	// Update -----------
 
	// Delete -----------
	 
}

?>