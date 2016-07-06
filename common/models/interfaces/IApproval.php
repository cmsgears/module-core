<?php
namespace cmsgears\core\common\models\interfaces;

/**
 * Useful for models required registration process with admin approval. User can register a model and submit it for admin approval.
 * The model implementing this interface can also use Approval Trait to re-use implementation.
 */
interface IApproval {

	// Note: Applications having registration process for a model can allocate registration status till 10000 and than follow these standard status as part of approval process.

	// Pre-Defined Status
	const STATUS_NEW		= 10000;
	const STATUS_REJECTED	= 12000;
	const STATUS_RE_SUBMIT	= 14000;
	const STATUS_CONFIRMED	= 15000;
	const STATUS_ACTIVE		= 16000;
	const STATUS_FROJEN		= 18000;
	const STATUS_BLOCKED	= 19000;
	const STATUS_TERMINATED	= 20000;

	public function isRegistration();

	public function isNew(  $strict = true );

	public function isRejected( $strict = true );

	public function isReSubmit( $strict = true );

	public function isConfirmed( $strict = true );

	public function isActive( $strict = true );

	public function isFrojen( $strict = true );

	public function isBlocked( $strict = true );

	public function isTerminated( $strict = true );

	// User can edit model in these situations
	public function isEditable();

	// User can't make any changes in submitted mode
	public function isSubmitted();

	// User can submit the model for limit removal in case admin have freezed or blocked the model
	public function isSubmittable();

	// Reject reason provided by admin for rejection or frozen mode
	public function getRejectReason();
}
