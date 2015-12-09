<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\User;

/**
 * CreateModifyTrait can be used to add creator and modifier methods to relevant models.
 */
trait CreateModifyTrait {

	/**
	 * @return User - who created the model
	 */
	public function getCreator() {

		return $this->hasOne( User::className(), [ 'id' => 'createdBy' ] );
	}

	/**
	 * @return User - who modified the model
	 */
	public function getModifier() {

		return $this->hasOne( User::className(), [ 'id' => 'modifiedBy' ] );
	}

	/**
	 * @return boolean - whether given user is creator. It's useful to apply filter to check model ownership.
	 */
	public function checkOwner( $user ) {

		return $this->createdBy	= $user->id;
	}
}

?>