<?php
namespace cmsgears\core\common\models\interfaces;

/**
 * Useful for models required registration process with admin approval. User can register a model and submit it for admin approval.
 * The model implementing this interface can also use Approval Trait to re-use implementation.
 */
interface IApproval {

	// Pre-Defined Status
	const STATUS_NEW		= 500;
	const STATUS_REJECTED	= 520;
	const STATUS_RE_SUBMIT	= 540;
	const STATUS_APPROVED	= 560;
	const STATUS_FROJEN		= 580;
	const STATUS_BLOCKED	= 600;

	public function isRegistration();

	public function isNew();

	public function isRejected();

	public function isReSubmit();

	public function isApproved();

	public function isFrojen();

	public function isBlocked();

	// User can edit model in these situations
	public function isEditable();

	// User can't make any changes in submitted mode
	public function isSubmitted();

	// User can submit the model for limit removal in case admin have freezed or blocked the model
	public function isSubmittable();

	// Reject reason provided by admin for rejection or frozen mode
	public function getRejectReason();
}

?>