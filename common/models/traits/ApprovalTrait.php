<?php
namespace cmsgears\core\common\models\traits;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IApproval;

/**
 * Useful for models required registration process with admin approval. The model using this trait must use status column for tracking state.
 */
trait ApprovalTrait {

	public static $statusMap = [
		IApproval::STATUS_NEW => 'New',
		IApproval::STATUS_REJECTED => 'Rejected',
		IApproval::STATUS_RE_SUBMIT => 'Re Submitted',
		IApproval::STATUS_APPROVED => 'Approved',
		IApproval::STATUS_FROJEN => 'Frozen',
		IApproval::STATUS_BLOCKED => 'Blocked'
	];

	// Used for url params
	public static $revStatusMap = [
		'new' => IApproval::STATUS_NEW,
		'rejected' => IApproval::STATUS_REJECTED,
		're-submitted' => IApproval::STATUS_RE_SUBMIT,
		'approved' => IApproval::STATUS_APPROVED,
		'frozen' => IApproval::STATUS_FROJEN,
		'blocked' => IApproval::STATUS_BLOCKED
	];

	public function getStatusStr() {

		if( $this->status >= IApproval::STATUS_NEW ) {

			return self::$statusMap[ $this->status ];
		}

		return 'Registration';
	}

	public function isRegistration() {

		return $this->status < IApproval::STATUS_NEW;
	}

	public function isNew(  $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_NEW;
		}

		return $this->status >= IApproval::STATUS_NEW;
	}

	public function isRejected(  $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_REJECTED;
		}

		return $this->status >= IApproval::STATUS_REJECTED;
	}

	public function isReSubmit(  $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_RE_SUBMIT;
		}

		return $this->status >= IApproval::STATUS_RE_SUBMIT;
	}

	public function isApproved(  $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_APPROVED;
		}

		return $this->status >= IApproval::STATUS_APPROVED;
	}

	public function isFrojen(  $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_FROJEN;
		}

		return $this->status >= IApproval::STATUS_FROJEN;
	}

	public function isBlocked(  $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_BLOCKED;
		}

		return $this->status >= IApproval::STATUS_BLOCKED;
	}

	public function isTerminated( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_TERMINATED;
		}

		return $this->status >= IApproval::STATUS_TERMINATED;
	}

	// User can edit model in these situations
	public function isEditable() {

		return $this->status != IApproval::STATUS_NEW && $this->status != IApproval::STATUS_RE_SUBMIT;
	}

	// User can't make any changes in submitted mode
	public function isSubmitted() {

		return $this->status == IApproval::STATUS_NEW || $this->status == IApproval::STATUS_RE_SUBMIT;
	}

	// User can submit the model for limit removal
	public function isSubmittable() {

		return $this->status < IApproval::STATUS_NEW || $this->status == IApproval::STATUS_REJECTED ||
				$this->status == IApproval::STATUS_FROJEN || $this->status == IApproval::STATUS_BLOCKED;
	}

	public function getRejectReason() {

		$reason = $this->getDataAttribute( CoreGlobal::DATA_REJECT_REASON );
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
}

?>