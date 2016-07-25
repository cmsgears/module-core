<?php
namespace cmsgears\core\common\models\interfaces;

/**
 * Useful for models required registration process with admin approval. User can register a model and submit it for admin approval.
 * The model implementing this interface can also use Approval Trait to re-use implementation.
 */
interface IApproval {

	// Note: Applications having registration process for a model can allocate registration status till 10000 and than follow these standard status as part of approval process.

	// Pre-Defined Status
	const STATUS_NEW		=     0;	// Status is set to new for newly added models.
	const STATUS_SUBMITTED	= 10000;	// Status is set to submitted for models submitted for first time approval. Approver might reject, confirm or activate.
	const STATUS_REJECTED	= 12000;	// Approver can reject the model in case not satisfied by given info.
	const STATUS_RE_SUBMIT	= 14000;	// Model can be re-submitted after making appropriate changes.
	const STATUS_CONFIRMED	= 15000;	// Approver can acknowledge the application and mark it pending for activation.
	const STATUS_ACTIVE		= 16000;	// Approver activate the model.
	const STATUS_FROJEN		= 18000;	// Approver can freeze the model for minimal activities.
	const STATUS_BLOCKED	= 19000;	// Approver can block the model, but data will be used for analysis and other purpose.
	const STATUS_TERMINATED	= 20000;	// Approver can permanently terminate the model without deleting to preserve data for historical purpose.

	public function isNew(  $strict = true );

	// Registration process to be followed between status new and submitted. It can be multi-step process where required.
	public function isRegistration();

	public function isSubmitted( $strict = true );

	public function isBelowSubmitted( $strict = true );

	public function isRejected( $strict = true );

	public function isReSubmit( $strict = true );

	public function isConfirmed( $strict = true );

	public function isActive( $strict = true );

	public function isFrojen( $strict = true );

	public function isBlocked( $strict = true );

	public function isTerminated( $strict = true );

	// Toggle between active and frozen
	public function toggleFrojen();

	// Toggle between active and blocked
	public function toggleBlock();

	// User can edit model in these situations
	public function isEditable();

	// User can submit the model for limit removal in case admin have freezed or blocked the model
	public function isSubmittable();

	// Is available for non owners - few of the features can be disabled for frozen state based on model nature.
	public function isPublic();

	// Reject reason provided by admin for rejection or frozen mode
	public function getRejectReason();
}
