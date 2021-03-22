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
	 * It expects the model to support either userId or createdBy column. If both exist, userId will dominate.
	 */
	public function getPageByOwnerId( $ownerId, $config = [] ) {

		$owner = $config[ 'owner' ] ?? false;

		$modelTable	= $this->getModelTable();

		if( $owner ) {

			$config[ 'conditions' ][ "$modelTable.userId" ] = $ownerId;
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

		$owner = $config[ 'owner' ] ?? false;

		$modelClass	= static::$modelClass;
		$modelTable	= $this->getModelTable();

		$query = null;

		if( $owner ) {

			$query = $modelClass::queryWithOwnerAuthor();

			$query->andWhere( "$modelTable.userId =:oid OR ($modelTable.userId IS NULL AND $modelTable.createdBy =:cid )", [ ':oid' => $id, ':cid' => $id ] );
		}
		else {

			$query = $modelClass::queryWithAuthor();

			$query->andWhere( "$modelTable.createdBy =:cid", [ ':cid' => $id ] );
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

	public function getByStatus( $status, $config = [] ) {

		$limit = $config[ 'limit' ] ?? 10;

		$modelClass		= static::$modelClass;
		$modelTable		= $modelClass::tableName();
		$parentType		= static::$parentType;

		$query = null;

		// TODO: Cover the case where $parentType is same for different types

		if( isset( $parentType ) ) {

			$query = $modelClass::find()->where( [ "$modelTable.status" => $status, "$modelTable.type" => $parentType ] );
		}
		else {

			$query = $modelClass::find()->where( [ "$modelTable.status" => $status ] );
		}

		$query->limit( $limit );

		return $query->orderBy( [ 'id' => SORT_DESC ] )->all();
	}

	public function getActive( $config = [] ) {

		return $this->getByStatus( IApproval::STATUS_ACTIVE, $config );
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getApprovalNotificationMap() {

		return [
			IApproval::STATUS_ACCEPTED => CoreGlobal::TPL_NOTIFY_STATUS_ACCEPT,
			IApproval::STATUS_SUBMITTED => CoreGlobal::TPL_NOTIFY_STATUS_SUBMIT,
			IApproval::STATUS_REJECTED => CoreGlobal::TPL_NOTIFY_STATUS_REJECT,
			IApproval::STATUS_RE_SUBMIT => CoreGlobal::TPL_NOTIFY_STATUS_RESUBMIT,
			IApproval::STATUS_CONFIRMED => CoreGlobal::TPL_NOTIFY_STATUS_CONFIRM,
			IApproval::STATUS_APPROVED => CoreGlobal::TPL_NOTIFY_STATUS_APPROVE,
			IApproval::STATUS_CHANGED => CoreGlobal::TPL_NOTIFY_STATUS_CHANGE,
			IApproval::STATUS_ACTIVE => CoreGlobal::TPL_NOTIFY_STATUS_ACTIVE,
			IApproval::STATUS_FROJEN => CoreGlobal::TPL_NOTIFY_STATUS_FREEZE,
			IApproval::STATUS_UPLIFT_FREEZE => CoreGlobal::TPL_NOTIFY_STATUS_UP_FREEZE,
			IApproval::STATUS_BLOCKED => CoreGlobal::TPL_NOTIFY_STATUS_BLOCK,
			IApproval::STATUS_UPLIFT_BLOCK => CoreGlobal::TPL_NOTIFY_STATUS_UP_BLOCK,
			IApproval::STATUS_TERMINATED => CoreGlobal::TPL_NOTIFY_STATUS_TERMINATE,
			IApproval::STATUS_DELETED => CoreGlobal::TPL_NOTIFY_STATUS_DELETE
		];
	}

	public function getCountsByOwnerId( $ownerId, $config = [] ) {

		$owner = $config[ 'owner' ] ?? false;

		$modelTable	= $this->getModelTable();

		$query = new Query();

		$query->select( [ 'status', 'count(id) as total' ] )
			->from( $modelTable );

		if( $owner ) {

			$query->where( "$modelTable.userId=$ownerId" )->groupBy( 'status' );
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

			$query->where( "$modelTable.userId=$id" )->groupBy( 'status' );
		}
		else {

			$query->where( "($modelTable.userId IS NULL AND $modelTable.createdBy=$id) OR $modelTable.userId=$id" );
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

		if( $model->update() !== false ) {

			return $model;
		}

		return false;
	}

	/*
	 * Update the model status to accepted and trigger notification for appropriate admin to take action.
	 */
	public function accept( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isAccepted( true ) && $model->isAcceptable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_ACCEPTED );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_ACCEPTED ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyAdmin( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Update the model status to submitted and trigger notification for appropriate admin to take action.
	 */
	public function submit( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isSubmitted( true ) && $model->isSubmittable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_SUBMITTED );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_SUBMITTED ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyAdmin( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Reject the model and trigger notification for appropriate user to take action.
	 */
	public function reject( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isRejected( true ) && $model->isRejectable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_REJECTED );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_REJECTED ];

				$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
				$config[ 'data' ][ 'message' ]		= $model->getRejectMessage();

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Update the model status to re-submitted and trigger notification for appropriate admin to take action.
	 */
	public function reSubmit( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isReSubmit( true ) && $model->isReSubmittable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_RE_SUBMIT );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_RE_SUBMIT ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyAdmin( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Confirm the model and trigger notification for appropriate user to take action.
	 */
	public function confirm( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isConfirmed( true ) && $model->isConfirmable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_CONFIRMED );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_CONFIRMED ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Approve the model and trigger notification for appropriate user to take action.
	 */
	public function approve( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isActive( true ) && $model->isApprovable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_ACTIVE );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_APPROVED ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Activate the model and trigger notification for appropriate user to take action.
	 */
	public function activate( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isActive( true ) && $model->isApprovable() ) {

			$oldStatus = $model->getStatusStr();

			$model = $this->updateStatus( $model, IApproval::STATUS_ACTIVE );

			$newStatus = $model->getStatusStr();

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_ACTIVE ];

				$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
				$config[ 'data' ][ 'oldStatus' ]	= $oldStatus;
				$config[ 'data' ][ 'newStatus' ]	= $newStatus;

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Freeze the model and trigger notification for appropriate user to take action.
	 */
	public function freeze( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isFrojen( true ) && $model->isFreezable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_FROJEN );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_FROJEN ];

				$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
				$config[ 'data' ][ 'message' ]		= $model->getRejectMessage();

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Update the model state for activation from frozen state and trigger notification for appropriate admin to take action.
	 */
	public function upliftFreeze( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( $model->isFrojen( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_UPLIFT_FREEZE );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_UPLIFT_FREEZE ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyAdmin( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Block the model and trigger notification for appropriate user to take action.
	 */
	public function block( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isBlocked( true ) && $model->isBlockable() ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_BLOCKED );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_BLOCKED ];

				$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
				$config[ 'data' ][ 'message' ]		= $model->getRejectMessage();

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Update the model state for activation from block state and trigger notification for appropriate admin to take action.
	 */
	public function upliftBlock( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( $model->isBlocked( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_UPLIFT_BLOCK );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_UPLIFT_BLOCK ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyAdmin( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/*
	 * Terminate the model and trigger notification for appropriate user to take action.
	 */
	public function terminate( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isTerminated( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_TERMINATED );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_TERMINATED ];

				$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
				$config[ 'data' ][ 'message' ]		= $model->getTerminateMessage();

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	public function checkStatusChange( $model, $oldStatus, $config = [] ) {

		$modelClass = static::$modelClass;

		if( $model->status !== intval( $oldStatus ) ) {

			$oldStatus	= $modelClass::$statusMap[ $oldStatus ];
			$newStatus	= $modelClass::$statusMap[ $model->status ];

			$approvalNotificationMap = $this->getApprovalNotificationMap();

			$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_CHANGED ];

			$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
			$config[ 'data' ][ 'oldStatus' ]	= $oldStatus;
			$config[ 'data' ][ 'newStatus' ]	= $newStatus;
			$config[ 'data' ][ 'message' ]		= isset( $config[ 'data' ][ 'message' ] ) ? $config[ 'data' ][ 'message' ] : null;

			$this->notifyUser( $model, $config );

			return $model;
		}

		return false;
	}

	/*
	 * Soft Delete the model and trigger notification for appropriate user to take action.
	 */
	public function softDeleteNotify( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		if( !$model->isDeleted( true ) ) {

			$model = $this->updateStatus( $model, IApproval::STATUS_DELETED );

			if( $model && $notify ) {

				$approvalNotificationMap = $this->getApprovalNotificationMap();

				$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_DELETED ];

				$config[ 'data' ][ 'parentType' ] = $this->getParentTypeStr();

				$this->notifyUser( $model, $config );
			}

			return $model;
		}

		return false;
	}

	/**
	 * Toggle the model between Frozen and Active states.
	 *
	 * @param \cmsgears\core\common\models\interfaces\base\IApproval $model
	 * @param boolean $notify
	 * @param array $config
	 * @return \cmsgears\core\common\models\interfaces\base\IApproval
	 */
	public function toggleFrojen( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		$oldStatus = $model->getStatusStr();

		$model->toggleFrojen();
		$model->save();

		$newStatus = $model->getStatusStr();

		if( $notify ) {

			$approvalNotificationMap = $this->getApprovalNotificationMap();

			$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_CHANGED ];

			$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
			$config[ 'data' ][ 'oldStatus' ]	= $oldStatus;
			$config[ 'data' ][ 'newStatus' ]	= $newStatus;
			$config[ 'data' ][ 'message' ]		= null;

			$this->notifyUser( $model, $config );
		}

		return $model;
	}

	/**
	 * Toggle the model between Blocked and Active states.
	 *
	 * @param \cmsgears\core\common\models\interfaces\base\IApproval $model
	 * @param boolean $notify
	 * @param array $config
	 * @return \cmsgears\core\common\models\interfaces\base\IApproval
	 */
	public function toggleBlock( $model, $config = [] ) {

		$notify = isset( $config[ 'notify' ] ) ? $config[ 'notify' ] : true;

		$oldStatus = $model->getStatusStr();

		$model->toggleBlock();
		$model->save();

		$newStatus = $model->getStatusStr();

		if( $notify ) {

			$approvalNotificationMap = $this->getApprovalNotificationMap();

			$config[ 'template' ] = $approvalNotificationMap[ IApproval::STATUS_CHANGED ];

			$config[ 'data' ][ 'parentType' ]	= $this->getParentTypeStr();
			$config[ 'data' ][ 'oldStatus' ]	= $oldStatus;
			$config[ 'data' ][ 'newStatus' ]	= $newStatus;
			$config[ 'data' ][ 'message' ]		= null;

			$this->notifyUser( $model, $config );
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
