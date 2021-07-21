<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\base;

/**
 * Useful for models which requires registration process with admin approval. User can
 * register a model and submit it for admin approval. The model implementing this interface
 * can also use Approval Trait to re-use implementation.
 *
 * Applications having registration process for a model can allocate registration status between
 * 0 and 10000 and than follow the standard status specified within this interface as part of
 * approval process. In between status can also be further added if required.
 *
 * @since 1.0.0
 */
interface IApproval {

	// Pre-Defined Status

	/**
	 * Status is set to new for newly added models.
	 */
	const STATUS_NEW = 0;

	/**
	 * The model has been accepted by the appropriate authority in case status is new and submitted for acceptance. The
	 * further steps of the registration process can be continued after acceptance.
	 */
	const STATUS_ACCEPTED = 2;

	/**
	 * The model has been invited by the appropriate authority.
	 */
	const STATUS_INVITED = 4;

	/**
	 * The model has been accepted if already invited by the appropriate authority. The model or
	 * appropriate owner might accept the invitation.
	 */
	const STATUS_INVITE_ACCEPTED = 6;

	/**
	 * Status is set to submitted for models submitted for first time approval. Approver might
	 * reject, confirm or activate.
	 */
	const STATUS_SUBMITTED = 10000;

	/**
	 * Approver can reject the model in case not satisfied by given info.
	 */
	const STATUS_REJECTED = 12000;

	/**
	 * Model can be re-submitted for approval after making appropriate changes.
	 */
	const STATUS_RE_SUBMIT = 14000;

	/**
	 * Approver can acknowledge the application and mark it pending for activation.
	 */
	const STATUS_CONFIRMED = 15000;

	/**
	 * Placeholder constants to support approvalNotificationMap in ApprovalTrait.
	 */
	const STATUS_APPROVED	= 15005; // The model must not be persisted in this state.
	const STATUS_CHANGED	= 15010;

	/**
	 * Approver activate the model. The active models will have full access to all the
	 * corresponding features.
	 */
	const STATUS_ACTIVE = 16000;

	/**
	 * Approver can freeze the model with minimal activities to continue working.
	 */
	const STATUS_FROJEN = 18000;

	/**
	 * Model owner can request admin to uplift frozen status.
	 */
	const STATUS_UPLIFT_FREEZE = 18500;

	/**
	 * Approver can block the model, but data will be used for analysis and other purpose.
	 */
	const STATUS_BLOCKED = 19000;

	/**
	 * Model owner can request admin to uplift block status.
	 */
	const STATUS_UPLIFT_BLOCK = 19500;

	/**
	 * Approver can permanently terminate the model without deleting to preserve data
	 * for historical purpose.
	 */
	const STATUS_TERMINATED = 20000;

	/**
	 * Soft delete
	 */
	const STATUS_DELETED = 25000;

	/**
	 * Check whether model is in new state.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isNew( $strict = true );

	public function isAccepted(	$strict = true );

	public function isInvited( $strict = true );

	public function isInviteAccepted( $strict = true );

	// Registration process to be followed between status new and submitted. It can be multi-step process where required.
	public function isRegistration();

	/**
	 * Check whether model is in submitted state.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isSubmitted( $strict = true );

	/**
	 * Check whether model was submitted and processed at least one by the respective admin.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isAboveSubmitted( $strict = true );

	/**
	 * Check whether model state is below rejected.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isBelowRejected( $strict = true );

	/**
	 * Check whether model is rejected.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isRejected( $strict = true );

	/**
	 * Check whether model is re-submitted.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isReSubmit( $strict = true );

	/**
	 * Check whether model state is below confirmed.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isBelowConfirmed( $strict = true );

	/**
	 * Check whether model is confirmed.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isConfirmed( $strict = true );

	/**
	 * Check whether model is not activated yet.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isBelowActive( $strict = true );

	/**
	 * Check whether model is active.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isActive( $strict = true );

	/**
	 * Check whether model is frozen.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isFrojen( $strict = true );

	/**
	 * Check whether model is submitted to uplift freeze state.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isUpliftFreeze( $strict = true );

	/**
	 * Check whether model is blocked.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isBlocked( $strict = true );

	/**
	 * Check whether model is submitted to uplift block state.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isUpliftBlock( $strict = true );

	/**
	 * Check whether model is terminated and require only for historical purpose.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isTerminated( $strict = true );

	/**
	 * Check whether model is soft deleted and ready for garbage collection.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isDeleted( $strict = true );

	/**
	 * Check whether model is submitted to uplift the restrictions.
	 *
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isUpliftRequested();

	/**
	 * Toggle between active and frozen states.
	 *
	 * @return boolean
	 */
	public function toggleFrojen();

	/**
	 * Toggle between active and blocked states.
	 *
	 * @return boolean
	 */
	public function toggleBlock();

	/**
	 * Check whether model can be edited (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isEditable()]]).
	 *
	 * @return boolean
	 */
	public function isEditable();

	/**
	 * Check whether model can be accepted.
	 * (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isAcceptable()]]).
	 *
	 * @return boolean
	 */
	public function isAcceptable();

	/**
	 * Check whether model can be submitted for limit removal
	 * (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isSubmittable()]]).
	 *
	 * @return boolean
	 */
	public function isSubmittable();

	/**
	 * Check whether model can be rejected.
	 * or blocked the model (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isRejectable()]]).
	 *
	 * @return boolean
	 */
	public function isRejectable();

	/**
	 * Check whether model can be re-submitted for limit removal
	 * (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isSubmittable()]]).
	 *
	 * @return boolean
	 */
	public function isReSubmittable();

	/**
	 * Check whether model can be confirmed
	 * (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isConfirmable()]]).
	 *
	 * @return boolean
	 */
	public function isConfirmable();

	/**
	 * Check whether model can be activated by admin
	 * (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isApprovable()]]).
	 *
	 * @return boolean
	 */
	public function isApprovable();

	/**
	 * Check whether model can be frozen by admin
	 * (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isFreezable()]]).
	 *
	 * @return boolean
	 */
	public function isFreezable();

	/**
	 * Check whether model can be blocked by admin
	 * (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isBlockable()]]).
	 *
	 * @return boolean
	 */
	public function isBlockable();

	/**
	 * Check whether model is available for non owners - few of the features can be
	 * disabled for frozen state based on model nature. (see [[\cmsgears\core\common\models\traits\base\ApprovalTrait::isPublic()]]).
	 *
	 * @return boolean
	 */
	public function isPublic();

	/**
	 * Set message of rejection.
	 *
	 * @param string $message
	 */
	public function setRejectMessage( $message );

	/**
	 * Returns reason of rejection provided by admin for rejection, frozen and blocked states.
	 *
	 * @return string
	 */
	public function getRejectMessage();

	/**
	 * Set message of termination.
	 *
	 * @param string $message
	 */
	public function setTerminateMessage( $message );

	/**
	 * Returns reason of termination provided by admin for termination state.
	 *
	 * @return string
	 */
	public function getTerminateMessage();

}
