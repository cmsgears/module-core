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

    // Used for url params
    public static $revStatusMap = [
        'new' => IApproval::STATUS_NEW,
        'submitted' => IApproval::STATUS_SUBMITTED,
        'rejected' => IApproval::STATUS_REJECTED,
        're-submitted' => IApproval::STATUS_RE_SUBMIT,
        'confirmed' => IApproval::STATUS_CONFIRMED,
        'active' => IApproval::STATUS_ACTIVE,
        'frozen' => IApproval::STATUS_FROJEN,
        'uplift-freeze' => IApproval::STATUS_UPLIFT_FREEZE,
        'blocked' => IApproval::STATUS_BLOCKED,
        'uplift-block' => IApproval::STATUS_UPLIFT_BLOCK
    ];

    public function getStatusStr() {

        if( $this->status >= IApproval::STATUS_NEW ) {

            return self::$statusMap[ $this->status ];
        }

        return 'Registration';
    }

    public function isNew( $strict = true ) {

        if( $strict ) {

            return $this->status == IApproval::STATUS_NEW;
        }

        return $this->status >= IApproval::STATUS_NEW;
    }

    public function isRegistration() {

        return $this->status >= IApproval::STATUS_NEW && $this->status < IApproval::STATUS_SUBMITTED;
    }

    public function isSubmitted( $strict = true ) {

        if( $strict ) {

            return $this->status == IApproval::STATUS_SUBMITTED;
        }

        return $this->status == IApproval::STATUS_SUBMITTED || $this->status == IApproval::STATUS_RE_SUBMIT;
    }

    public function isBelowSubmitted( $strict = true ) {

        if( $strict ) {

            return $this->status < IApproval::STATUS_REJECTED;
        }

        return $this->status <= IApproval::STATUS_REJECTED;
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

    public function isUpliftFreeze( $strict = true ) {

        if( $strict ) {

            return $this->status == IApproval::STATUS_UPLIFT_FREEZE;
        }

        return $this->status >= IApproval::STATUS_UPLIFT_FREEZE;
    }

    public function isBlocked( $strict = true ) {

        if( $strict ) {

            return $this->status == IApproval::STATUS_BLOCKED;
        }

        return $this->status >= IApproval::STATUS_BLOCKED;
    }

    public function isUpliftBlock( $strict = true ) {

        if( $strict ) {

            return $this->status == IApproval::STATUS_UPLIFT_BLOCK;
        }

        return $this->status >= IApproval::STATUS_UPLIFT_BLOCK;
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

    // User cannot edit model in submitted states.
    public function isEditable() {

        $editable = [ IApproval::STATUS_SUBMITTED, IApproval::STATUS_RE_SUBMIT, IApproval::STATUS_UPLIFT_FREEZE, IApproval::STATUS_UPLIFT_BLOCK ];

        return !in_array( $this->status, $editable );
    }

    // User can submit the model for limit removal in selected states i.e. new, rejected, frozen or blocked.
    public function isSubmittable() {

        return $this->isRegistration() || $this->status == IApproval::STATUS_REJECTED ||
                $this->status == IApproval::STATUS_FROJEN || $this->status == IApproval::STATUS_BLOCKED;
    }

    public function isApprovable() {

        return $this->status == IApproval::STATUS_FROJEN || $this->status == IApproval::STATUS_UPLIFT_FREEZE ||
                $this->status == IApproval::STATUS_BLOCKED || $this->status == IApproval::STATUS_UPLIFT_BLOCK;
    }

    // Is available for non owners - few of the features can be disabled for frozen state based on model nature.
    public function isPublic() {

        return $this->status == IApproval::STATUS_ACTIVE || $this->status == IApproval::STATUS_FROJEN;
    }

    public function getRejectReason() {

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
}
