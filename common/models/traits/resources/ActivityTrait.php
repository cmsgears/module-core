<?php
namespace cmsgears\core\common\models\traits\resources;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\ModelActivity;

/**
 * ActivityTrait can be used to store model activities.
 */
trait ActivityTrait {

	/**
	 * @return array - ActivityTrait associated with parent
	 */
	public function getModelActivities() {

    	return $this->hasMany( ModelActivity::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}
}

?>