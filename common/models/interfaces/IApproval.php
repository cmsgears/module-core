<?php
namespace cmsgears\core\common\models\interfaces;

/**
 * Useful for models required registration process with admin approval.
 */
interface IApproval {

	// Pre-Defined Status
	const STATUS_NEW		= 500;
	const STATUS_REJECTED	= 520;
	const STATUS_RE_SUBMIT	= 540;
	const STATUS_APPROVED	= 560;
	const STATUS_FROJEN		= 580;
	const STATUS_BLOCKED	= 600;
}

?>