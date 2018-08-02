<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IApproval;

/**
 * Useful for models required registration process with admin approval. The model using this
 * trait must use status column to track current state.
 *
 * @property integer $status
 *
 * @since 1.0.0
 */
trait ApprovalTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	public static $statusMap = [
		IApproval::STATUS_NEW => 'New',
		IApproval::STATUS_SUBMITTED => 'Submitted',
		IApproval::STATUS_REJECTED => 'Rejected',
		IApproval::STATUS_RE_SUBMIT => 'Re Submitted',
		IApproval::STATUS_CONFIRMED => 'Confirmed',
		IApproval::STATUS_ACTIVE => 'Active',
		IApproval::STATUS_FROJEN => 'Frozen',
		IApproval::STATUS_UPLIFT_FREEZE => 'Uplift Frozen',
		IApproval::STATUS_BLOCKED => 'Blocked',
		IApproval::STATUS_UPLIFT_BLOCK => 'Uplift Block',
		IApproval::STATUS_TERMINATED => 'Terminated'
	];

	public static $minStatusMap = [
		IApproval::STATUS_NEW => 'New',
		IApproval::STATUS_ACTIVE => 'Active',
		IApproval::STATUS_BLOCKED => 'Blocked',
		IApproval::STATUS_TERMINATED => 'Terminated'
	];

	// Used for external docs
	public static $revStatusMap = [
		'New' => IApproval::STATUS_NEW,
		'Submitted' => IApproval::STATUS_SUBMITTED,
		'Rejected' => IApproval::STATUS_REJECTED,
		'Re Submitted' => IApproval::STATUS_RE_SUBMIT,
		'Confirmed' => IApproval::STATUS_CONFIRMED,
		'Active' => IApproval::STATUS_ACTIVE,
		'Frozen' => IApproval::STATUS_FROJEN,
		'Uplift Frozen' => IApproval::STATUS_UPLIFT_FREEZE,
		'Blocked' => IApproval::STATUS_BLOCKED,
		'Uplift Block' => IApproval::STATUS_UPLIFT_BLOCK,
		'Terminated' => IApproval::STATUS_TERMINATED
	];

	// Used for url params
	public static $urlRevStatusMap = [
		'new' => IApproval::STATUS_NEW,
		'submitted' => IApproval::STATUS_SUBMITTED,
		'rejected' => IApproval::STATUS_REJECTED,
		're-submitted' => IApproval::STATUS_RE_SUBMIT,
		'confirmed' => IApproval::STATUS_CONFIRMED,
		'active' => IApproval::STATUS_ACTIVE,
		'frozen' => IApproval::STATUS_FROJEN,
		'uplift-freeze' => IApproval::STATUS_UPLIFT_FREEZE,
		'blocked' => IApproval::STATUS_BLOCKED,
		'uplift-block' => IApproval::STATUS_UPLIFT_BLOCK,
		'terminated' => IApproval::STATUS_TERMINATED
	];

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// ApprovalTrait -------------------------

	/**
	 * Returns string representation of [[$status]].
	 *
	 * @param boolean $strict
	 * @return string
	 */
	public function getStatusStr( $strict = false ) {

		if( $strict ) {

			return self::$statusMap[ $this->status ];
		}
		else if( $this->status >= IApproval::STATUS_SUBMITTED ) {

			return self::$statusMap[ $this->status ];
		}

		return 'Registration';
	}

	/**
	 * @inheritdoc
	 */
	public function isNew( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_NEW;
		}

		return $this->status >= IApproval::STATUS_NEW;
	}

	/**
	 * @inheritdoc
	 */
	public function isRegistration() {

		return $this->status >= IApproval::STATUS_NEW && $this->status < IApproval::STATUS_SUBMITTED;
	}

	/**
	 * @inheritdoc
	 */
	public function isSubmitted( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_SUBMITTED;
		}

		return $this->status == IApproval::STATUS_SUBMITTED || $this->status == IApproval::STATUS_RE_SUBMIT;
	}

	/**
	 * @inheritdoc
	 */
	public function isBelowRejected( $strict = true ) {

		if( $strict ) {

			return $this->status < IApproval::STATUS_REJECTED;
		}

		return $this->status <= IApproval::STATUS_REJECTED;
	}

	/**
	 * @inheritdoc
	 */
	public function isRejected( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_REJECTED;
		}

		return $this->status >= IApproval::STATUS_REJECTED;
	}

	/**
	 * @inheritdoc
	 */
	public function isReSubmit( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_RE_SUBMIT;
		}

		return $this->status >= IApproval::STATUS_RE_SUBMIT;
	}

	/**
	 * @inheritdoc
	 */
	public function isBelowConfirmed( $strict = true ) {

		if( $strict ) {

			return $this->status < IApproval::STATUS_CONFIRMED;
		}

		return $this->status <= IApproval::STATUS_CONFIRMED;
	}

	/**
	 * @inheritdoc
	 */
	public function isConfirmed( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_CONFIRMED;
		}

		return $this->status >= IApproval::STATUS_CONFIRMED;
	}

	/**
	 * @inheritdoc
	 */
	public function isActive( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_ACTIVE;
		}

		return $this->status >= IApproval::STATUS_ACTIVE;
	}

	/**
	 * @inheritdoc
	 */
	public function isFrojen( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_FROJEN;
		}

		return $this->status >= IApproval::STATUS_FROJEN;
	}

	/**
	 * @inheritdoc
	 */
	public function isUpliftFreeze( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_UPLIFT_FREEZE;
		}

		return $this->status >= IApproval::STATUS_UPLIFT_FREEZE;
	}

	/**
	 * @inheritdoc
	 */
	public function isBlocked( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_BLOCKED;
		}

		return $this->status >= IApproval::STATUS_BLOCKED;
	}

	/**
	 * @inheritdoc
	 */
	public function isUpliftBlock( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_UPLIFT_BLOCK;
		}

		return $this->status >= IApproval::STATUS_UPLIFT_BLOCK;
	}

	/**
	 * @inheritdoc
	 */
	public function isTerminated( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_TERMINATED;
		}

		return $this->status >= IApproval::STATUS_TERMINATED;
	}

	/**
	 * @inheritdoc
	 */
	public function isDeleted( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_DELETED;
		}

		return $this->status >= IApproval::STATUS_DELETED;
	}

	/**
	 * @inheritdoc
	 */
	public function toggleFrojen() {

		if( $this->isFrojen() ) {

			$this->status = IApproval::STATUS_ACTIVE;
		}
		else {

			$this->status = IApproval::STATUS_FROJEN;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function toggleBlock() {

		if( $this->isBlocked() || !$this->isActive( false ) ) {

			$this->status = IApproval::STATUS_ACTIVE;
		}
		else {

			$this->status = IApproval::STATUS_BLOCKED;
		}
	}

	/**
	 * The model owner cannot update it in the states defined within this method.
	 *
	 * @return boolean
	 */
	public function isEditable() {

		$editable = [ IApproval::STATUS_SUBMITTED, IApproval::STATUS_RE_SUBMIT, IApproval::STATUS_UPLIFT_FREEZE, IApproval::STATUS_UPLIFT_BLOCK ];

		return !in_array( $this->status, $editable );
	}

	/**
	 * The model owner can submit the model for limit removal in selected states i.e. new,
	 * rejected, frozen or blocked. defined within this method.
	 *
	 * @return boolean
	 */
	public function isSubmittable() {

		return $this->isRegistration() || $this->status == IApproval::STATUS_REJECTED ||
			$this->status == IApproval::STATUS_FROJEN || $this->status == IApproval::STATUS_BLOCKED;
	}

	/**
	 * Admin can check whether the model is ready for approval in selected states i.e. frozen,
	 * uplift-freeze, blocked or uplift-block defined within this method. A model can be activated only
	 * in the states specified within this method.
	 *
	 * @return boolean
	 */
	public function isApprovable() {

		return $this->status == IApproval::STATUS_SUBMITTED || $this->status == IApproval::STATUS_FROJEN ||
			$this->status == IApproval::STATUS_UPLIFT_FREEZE || $this->status == IApproval::STATUS_BLOCKED ||
			$this->status == IApproval::STATUS_UPLIFT_BLOCK;
	}

	/**
	 * Check whether the model is publicly available for non owners. Some of the features can
	 * be disabled for frozen state based on model nature.
	 *
	 * @return boolean
	 */
	public function isPublic() {

		return $this->status == IApproval::STATUS_ACTIVE || $this->status == IApproval::STATUS_FROJEN;
	}

	/**
	 * @inheritdoc
	 */
	public function getRejectMessage() {

		$reason = $this->getDataMeta( CoreGlobal::DATA_REJECT_REASON );
		$text	= 'rejection';

		if( $this->isFrojen() ) {

			$text	= 'freeze';
		}
		else if( $this->isBlocked() ) {

			$text	= 'block';
		}

		if( isset( $reason ) && strlen( $reason ) > 0 ) {

			$reason	= "The reason for $text is - $reason";
		}
		else {

			$reason	= "No reason was specified by admin.";
		}

		return $reason;
	}

	/**
	 * @inheritdoc
	 */
	public function getTerminateMessage() {

		$reason = $this->getDataMeta( CoreGlobal::DATA_TERMINATE_REASON );
		$text	= 'termination';

		if( isset( $reason ) && strlen( $reason ) > 0 ) {

			$reason	= "The reason for $text is - $reason";
		}
		else {

			$reason	= "No reason was specified by admin.";
		}

		return $reason;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// ApprovalTrait -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
