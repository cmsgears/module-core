<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

// Yii Imports
use yii\base\UnknownMethodException;
use yii\db\Expression;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IApproval;

/**
 * Useful for services required registration process with admin approval.
 *
 * @since 1.0.0
 */
trait ApprovalTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

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

		$owner		= $config[ 'owner' ] ?? false;
		$modelTable	= $this->getModelTable();

		if( $owner ) {

			$config[ 'conditions' ][ "$modelTable.holderId" ] = $ownerId;
		}
		else {

			$config[ 'conditions' ][ "$modelTable.createdBy" ] = $ownerId;
		}

		return $this->getPage( $config );
	}

	public function getPageByOwnerIdStatus( $ownerId, $status, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.status" ] = $status;

		return $this->getPageByOwnerId( $ownerId, $config );
	}

	/**
	 * It expects the model to support either createdBy or createdBy and ownerId columns
	 */
	public function getPageByAuthorityId( $id, $config = [] ) {

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();
		$query		= null;

		$owner = $config[ 'owner' ] ?? false;

		if( $owner ) {

			$query = $modelClass::queryWithOwnerAuthor();

			$query->andWhere( "$modelTable.holderId =:oid OR ($modelTable.holderId IS NULL AND $modelTable.createdBy =:creator )", [ ':oid' => $id, ':creator' => $id ] );
		}
		else {

			$query = $modelClass::queryWithAuthor();

			$query->andWhere( "$modelTable.createdBy =:creator", [ ':creator' => $id ] );
		}

		$config[ 'query' ] = $query;

		return $this->getPage( $config );
	}

	public function getPageByAuthorityIdStatus( $id, $status, $config = [] ) {

		$modelTable	= $this->getModelTable();

		$config[ 'conditions' ][ "$modelTable.status" ] = $status;

		return $this->getPageByAuthorityId( $id, $config );
	}

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getCountsByOwnerId( $ownerId, $config = [] ) {

		$owner = $config[ 'owner' ] ?? false;

		$modelTable	= $this->getModelTable();

		$query = new Query();

		$query->select( [ 'status', 'count(id) as total' ] )
				->from( $modelTable );

		if( $owner ) {

			$query->where( "$modelTable.holderId=$ownerId" )->groupBy( 'status' );
		}
		else {

			$query->where( "$modelTable.createdBy=$ownerId" )->groupBy( 'status' );
		}

		$counts     = $query->all();
		$returnArr  = [];
		$counter    = 0;

		foreach( $counts as $count ) {

			$returnArr[ $count[ 'status' ] ] = $count[ 'total' ];

			$counter += $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	public function getCountsByAuthorityId( $id, $config = [] ) {

		$owner	= $config[ 'owner' ] ?? false;
		$index	= $config[ 'index' ] ?? null;

		$modelTable	= $this->getModelTable();

		$query = new Query();

		$query->select( [ 'status', 'count(id) as total' ] );

		if( isset( $index ) ) {

			$query->from( [ new Expression( "{{%$modelTable}} USE INDEX ($index)" ) ] );
		}
		else {

			$query->from( $modelTable );
		}

		if( $owner ) {

			$query->where( "$modelTable.holderId=$id" )->groupBy( 'status' );
		}
		else {

			$query->where( "($modelTable.holderId IS NULL AND $modelTable.createdBy=$id) OR $modelTable.holderId=$id" );
		}

		$query->groupBy( 'status' );

		$counts     = $query->all();
		$returnArr  = [];
		$counter    = 0;

		foreach( $counts as $count ) {

			$returnArr[ $count[ 'status' ] ] = $count[ 'total' ];

			$counter += $count[ 'total' ];
		}

		$returnArr[ 'all' ] = $counter;

		return $returnArr;
	}

	// Create -------------

	// Update -------------

	// Model status

	public function updateStatus( $model, $status ) {

		$model->status = $status;

		$model->update();

		return $model;
	}

	public function submit( $model, $notify = true, $config = [] ) {

		if( !$model->isSubmitted( true ) && $model->isSubmittable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_SUBMITTED );

			if( $notify ) {

				$title = $model->getClassName() . ' Submitted';

				$config[ 'template' ] = $config[ 'template' ] ?? CoreGlobal::TEMPLATE_NOTIFY_SUBMIT;

				$this->notifyAdmin( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function confirm( $model, $notify = true, $config = [] ) {

		if( !$model->isConfirmed( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_CONFIRMED );

			if( $notify ) {

				$title = $model->getClassName() . ' Confirmed';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_CONFIRM;

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function approve( $model, $notify = true, $config = [] ) {

		if( !$model->isActive( true ) && $model->isApprovable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_ACTIVE );

			if( $notify ) {

				$title = $model->getClassName() . ' Approved';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_APPROVE;

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function activate( $model, $notify = true, $config = [] ) {

		if( !$model->isActive( true ) ) {

			$oldStatus = $model->getStatusStr();

			$model = $this->updateStatus( $model, IApproval::STATUS_ACTIVE );

			$newStatus = $model->getStatusStr();

			if( $notify ) {

				$title = $model->getClassName() . ' Activated';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_ACTIVE;

				$config[ 'data' ][ 'oldStatus' ] = $oldStatus;
				$config[ 'data' ][ 'newStatus' ] = $newStatus;

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function reject( $model, $notify = true, $config = [] ) {

		if( !$model->isRejected( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_REJECTED );

			if( $notify ) {

				$title = $model->getClassName() . ' Rejected';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_REJECT;
				$config[ 'data' ][ 'message' ] = $model->getRejectMessage();

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function freeze( $model, $notify = true, $config = [] ) {

		if( !$model->isFrojen( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_FROJEN );

			if( $notify ) {

				$title = $model->getClassName() . ' Frozen';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_ACTIVE;
				$config[ 'data' ][ 'message' ] = $model->getRejectMessage();

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function block( $model, $notify = true, $config = [] ) {

		if( !$model->isBlocked( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_BLOCKED );

			if( $notify ) {

				$title = $model->getClassName() . ' Blocked';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_BLOCK;
				$config[ 'data' ][ 'message' ] = $model->getRejectMessage();

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function terminate( $model, $notify = true, $config = [] ) {

		if( !$model->isTerminated( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_TERMINATED );

			if( $notify ) {

				$title = $model->getClassName() . ' Terminated';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_TERMINATE;
				$config[ 'data' ][ 'message' ] = $model->getTerminateMessage();

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function softDelete( $model, $notify = true, $config = [] ) {

		if( !$model->isDeleted( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_DELETED );

			if( $notify ) {

				$title = $model->getClassName() . ' Deleted';

				$config[ 'template' ] = CoreGlobal::TEMPLATE_NOTIFY_DELETE;

				$this->notifyUser( $model, $config, $title );
			}

			return $model;
		}

		return false;
	}

	public function toggleFrojen( $model, $notify = true, $config = [] ) {

		$oldStatus = $model->getStatusStr();

		$model->toggleFrojen();

		$newStatus = $model->getStatusStr();

		if( $notify ) {

			$title = $model->isActive( true ) ? $model->getClassName() . ' Activated' : $model->getClassName() . ' Frozen';

			$config[ 'template' ] = $model->isActive( true ) ? CoreGlobal::TEMPLATE_NOTIFY_ACTIVE : CoreGlobal::TEMPLATE_NOTIFY_FREEZE;

			$config[ 'data' ][ 'oldStatus' ] = $oldStatus;
			$config[ 'data' ][ 'newStatus' ] = $newStatus;

			$this->notifyUser( $model, $config, $title );
		}

		return $model;
	}

	public function toggleBlock( $model, $notify = true, $config = [] ) {

		$oldStatus = $model->getStatusStr();

		$model->toggleBlock();

		$newStatus = $model->getStatusStr();

		if( $notify ) {

			$title = $model->isActive( true ) ? $model->getClassName() . ' Activated' : $model->getClassName() . ' Blocked';

			$config[ 'template' ] = $model->isActive( true ) ? CoreGlobal::TEMPLATE_NOTIFY_ACTIVE : CoreGlobal::TEMPLATE_NOTIFY_BLOCK;

			$config[ 'data' ][ 'oldStatus' ] = $oldStatus;
			$config[ 'data' ][ 'newStatus' ] = $newStatus;

			$this->notifyUser( $model, $config, $title );
		}

		return $model;
	}

	// Model messages

	public function getRejectMessage( $model ) {

		return $model->getRejectMessage();
	}

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

	public function getTerminateMessage( $model ) {

		return $model->getTerminateMessage();
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

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

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
