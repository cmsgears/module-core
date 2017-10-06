<?php
namespace cmsgears\core\common\models\traits\interfaces;

// Yii Imports
use Yii;

/**
 * It will be useful for models whose owner is identified by createdBy column. Rest of the models must implement the method and must not use this trait.
 */
trait OwnerTrait {

	public function isOwner( $user = null, $strict = false ) {

		if( !isset( $user ) && !$strict ) {

			$user	= Yii::$app->user->getIdentity();
		}

		if( isset( $user ) ) {

			return $user->id == $this->createdBy;
		}

		return false;
	}
}
