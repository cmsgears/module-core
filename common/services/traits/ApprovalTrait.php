<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IApproval;

/**
 * Useful for services required registration process with admin approval.
 */
trait ApprovalTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ApprovalTrait -------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	public function updateStatus( $model, $status ) {

		$model->status	= $status;

		$model->update();

		return $model;
	}

	public function confirm( $model, $public = true ) {

		return $this->updateStatus( $model, IApproval::STATUS_CONFIRMED );
	}

	public function approve( $model, $public = true ) {

		return $this->updateStatus( $model, IApproval::STATUS_ACTIVE );
	}

	public function setRejectMessage( $model, $message = null ) {

		if( isset( $message ) && strlen( $message ) > 0 ) {

			$model->setDataAttribute( CoreGlobal::DATA_REJECT_REASON, $message );
		}
		else {

			$model->removeDataAttribute( CoreGlobal::DATA_REJECT_REASON );
		}
	}

	public function reject( $model, $message = null ) {

		$this->setRejectMessage( $model, $message );

		return $this->updateStatus( $model, IApproval::STATUS_REJECTED );
	}

	public function freeze( $model, $message = null ) {

		$this->setRejectMessage( $model, $message );

		return $this->updateStatus( $model, IApproval::STATUS_FROJEN );
	}

	public function block( $model, $message = null ) {

		$this->setRejectMessage( $model, $message );

		return $this->updateStatus( $model, IApproval::STATUS_BLOCKED );
	}

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// ApprovalTrait -------------------------

	// Data Provider ------

	// Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
