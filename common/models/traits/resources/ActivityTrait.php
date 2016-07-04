<?php
namespace cmsgears\core\common\models\traits\resources;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Activity;

/**
 * ActivityTrait can be used to store model activities.
 */
trait ActivityTrait {

	/**
	 * @return array - Activity associated with parent
	 */
	public function getActivities() {

    	return $this->hasMany( Activity::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->mParentType'" );
	}
}

?>