<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;
use yii\base\UnknownMethodException;

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

	/**
	 * It expects the model to support either of createdBy or ownerId column. If both exist, ownerId will dominate.
	 */
	public function getPageByOwnerId( $ownerId, $config = [] ) {

		$owner		= isset( $config[ 'owner' ] ) ? $config[ 'owner' ] : false;
		$modelTable	= static::$modelTable;

		if( $owner ) {

			$config[ 'conditions' ][ "$modelTable.ownerId" ]	= $ownerId;
		}
		else {

			$config[ 'conditions' ][ "$modelTable.createdBy" ]	= $ownerId;
		}

		return $this->getPage( $config );
	}

	public function getPageByOwnerIdStatus( $ownerId, $status, $config = [] ) {

		$modelTable	= static::$modelTable;

		$config[ 'conditions' ][ "$modelTable.status" ]	  = $status;

		return $this->getPageByOwnerId( $ownerId, $config );
	}

	/**
	 * It expects the model to support either createdBy or createdBy and ownerId columns
	 */
	public function getPageByAuthorityId( $id, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= static::$modelTable;
		$query		= null;

		$owner		= isset( $config[ 'owner' ] ) ? $config[ 'owner' ] : false;

		if( $owner ) {

			$query		= $modelClass::queryWithOwnerAuthor();

			$query->andWhere( "$modelTable.ownerId =:owner OR ($modelTable.ownerId IS NULL AND $modelTable.createdBy =:creator )", [ ':owner' => $id, ':creator' => $id ] );
		}
		else {

			$query		= $modelClass::queryWithAuthor();

			$query->andWhere( "$modelTable.createdBy =:creator", [ ':creator' => $id ] );
		}

		$config[ 'query' ] = $query;

		return $this->getPage( $config );
	}

	public function getPageByAuthorityIdStatus( $id, $status, $config = [] ) {

		$modelTable	= static::$modelTable;

		$config[ 'conditions' ][ "$modelTable.status" ]	  = $status;

		return $this->getPageByAuthorityId( $id, $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Model status

	public function updateStatus( $model, $status ) {

		$model->status	= $status;

		$model->update();

		return $model;
	}

	public function submit( $model, $public = true ) {

		return $this->updateStatus( $model, IApproval::STATUS_SUBMITTED );
	}

	public function confirm( $model, $public = true ) {

		return $this->updateStatus( $model, IApproval::STATUS_CONFIRMED );
	}

	public function approve( $model, $public = true ) {

		return $this->updateStatus( $model, IApproval::STATUS_ACTIVE );
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

	public function terminate( $model, $message = null ) {

		if( !$model->isTerminated() ) {

			$this->setTerminateMessage( $model, $message );

			return $this->updateStatus( $model, IApproval::STATUS_TERMINATED );
		}

		return $model;
	}

	// Model messages

	public function setRejectMessage( $model, $message = null ) {

		try {

			if( isset( $message ) && strlen( $message ) > 0 ) {

				$model->setDataMeta( CoreGlobal::DATA_REJECT_REASON, $message );
			}
			else {

				$model->removeDataMeta( CoreGlobal::DATA_REJECT_REASON );
			}
		}
		catch( UnknownMethodException $e ) {

			// Do nothing
		}
	}

	public function setTerminateMessage( $model, $message = null ) {

		try {

			if( isset( $message ) && strlen( $message ) > 0 ) {

				$model->setDataMeta( CoreGlobal::DATA_TERMINATE_REASON, $message );
			}
			else {

				$model->removeDataMeta( CoreGlobal::DATA_TERMINATE_REASON );
			}
		}
		catch( UnknownMethodException $e ) {

			// Do nothing
		}
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
