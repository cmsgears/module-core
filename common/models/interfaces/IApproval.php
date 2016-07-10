<?php
namespace cmsgears\core\common\models\interfaces;

/**
 * Useful for models required registration process with admin approval. User can register a model and submit it for admin approval.
 * The model implementing this interface can also use Approval Trait to re-use implementation.
 */
interface IApproval {

	// Note: Applications having registration process for a model can allocate registration status till 10000 and than follow these standard status as part of approval process.

	// Pre-Defined Status
	const STATUS_NEW		= 10000;	// Status is set to new for newly added models.
	const STATUS_REJECTED	= 12000;	// Admin can reject the model in case not satisfied by given info.
	const STATUS_RE_SUBMIT	= 14000;	// User can re-submit after making appropriate changes.
	const STATUS_CONFIRMED	= 15000;	// Admin can acknowledge the application and mark it pending for activation. In case of user registration under admin-approval process, appropriate admin must activate user.
	const STATUS_ACTIVE		= 16000;	// Admin activate the model. In case of user registration under auto-approval process, user get's activate under confirmation process.
	const STATUS_FROJEN		= 18000;	// Admin can freeze the model for minimal activities.
	const STATUS_BLOCKED	= 19000;	// Admin can block the model, but data will be used for analysis and other purpose.
	const STATUS_TERMINATED	= 20000;	// Admin can permanently terminate the model without deleting to preserve data for historical purpose.

	public function isRegistration();

	public function isNew(  $strict = true );

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

	// User can't make any changes in submitted mode
	public function isSubmitted();

	// User can submit the model for limit removal in case admin have freezed or blocked the model
	public function isSubmittable();

	// Reject reason provided by admin for rejection or frozen mode
	public function getRejectReason();
}
