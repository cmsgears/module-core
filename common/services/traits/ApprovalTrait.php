<?php
namespace cmsgears\core\common\services\traits;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IApproval;

/**
 * Useful for services required registration process with admin approval.
 */
trait ApprovalTrait {

	public static function updateStatus( $model, $status ) {

		$model->status	= $status;

		$model->update();

		return $model;
	}

	public static function approve( $model, $public = true ) {

		return self::updateStatus( $model, IApproval::STATUS_APPROVED );
	}

	public static function setRejectMessage( $model, $message = null ) {

		if( isset( $message ) && strlen( $message ) > 0 ) {

			$model->setDataAttribute( CoreGlobal::DATA_REJECT_REASON, $message );
		}
		else {
			
			$model->removeDataAttribute( CoreGlobal::DATA_REJECT_REASON );
		}
	}

	public static function reject( $model, $message = null ) {

		self::setRejectMessage( $model, $message );

		return self::updateStatus( $model, IApproval::STATUS_REJECTED );
	}

	public static function freeze( $model, $message = null ) {

		self::setRejectMessage( $model, $message );

		return self::updateStatus( $model, IApproval::STATUS_FROJEN );
	}

	public static function block( $model, $message = null ) {

		self::setRejectMessage( $model, $message );

		return self::updateStatus( $model, IApproval::STATUS_BLOCKED );
	}
}

?>