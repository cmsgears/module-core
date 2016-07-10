<?php
namespace cmsgears\core\common\models\traits\interfaces;

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
		IApproval::STATUS_CONFIRMED => 'Confirmed',
		IApproval::STATUS_ACTIVE => 'Active',
		IApproval::STATUS_FROJEN => 'Frozen',
		IApproval::STATUS_BLOCKED => 'Blocked',
		IApproval::STATUS_TERMINATED => 'Terminated'
	];

	// Used for url params
	public static $revStatusMap = [
		'new' => IApproval::STATUS_NEW,
		'rejected' => IApproval::STATUS_REJECTED,
		're-submitted' => IApproval::STATUS_RE_SUBMIT,
		'confirmed' => IApproval::STATUS_CONFIRMED,
		'active' => IApproval::STATUS_ACTIVE,
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

	public function isNew( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_NEW;
		}

		return $this->status >= IApproval::STATUS_NEW;
	}

	public function isRejected( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_REJECTED;
		}

		return $this->status >= IApproval::STATUS_REJECTED;
	}

	public function isReSubmit( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_RE_SUBMIT;
		}

		return $this->status >= IApproval::STATUS_RE_SUBMIT;
	}

	public function isConfirmed( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_CONFIRMED;
		}

		return $this->status >= IApproval::STATUS_CONFIRMED;
	}

	public function isActive( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_ACTIVE;
		}

		return $this->status >= IApproval::STATUS_ACTIVE;
	}

	public function isFrojen( $strict = true ) {

		if( $strict ) {

			return $this->status == IApproval::STATUS_FROJEN;
		}

		return $this->status >= IApproval::STATUS_FROJEN;
	}

	public function isBlocked( $strict = true ) {

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

	public function toggleFrojen() {

		if( $this->isFrojen() ) {

			$this->status	= IApproval::STATUS_ACTIVE;
		}
		else {

			$this->status	= IApproval::STATUS_FROJEN;
		}
	}

	public function toggleBlock() {

		if( $this->isBlocked() ) {

			$this->status	= IApproval::STATUS_ACTIVE;
		}
		else {

			$this->status	= IApproval::STATUS_BLOCKED;
		}
	}

	// User can edit model in these situations i.e. either new or re-submit.
	public function isEditable() {

		return $this->status != IApproval::STATUS_NEW && $this->status != IApproval::STATUS_RE_SUBMIT;
	}

	// User can't make any changes in submitted mode i.e. submit(new) and re-submit.
	public function isSubmitted() {

		return $this->status == IApproval::STATUS_NEW || $this->status == IApproval::STATUS_RE_SUBMIT;
	}

	// User can submit the model for limit removal in selected states i.e. new, rejected, frozen or blocked.
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
