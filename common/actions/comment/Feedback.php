<?php
namespace cmsgears\core\common\actions\comment;

// CMG Imports
use cmsgears\core\common\models\resources\ModelComment;

/**
 * Feedback action creates a feedback for discovered model using ModelComment resource.
 */
class Feedback extends Create {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType	= ModelComment::TYPE_FEEDBACK;

	public $scenario	= ModelComment::TYPE_REVIEW;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Feedback ------------------------------

}
