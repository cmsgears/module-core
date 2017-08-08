<?php
namespace cmsgears\core\common\actions\comment;

// CMG Imports
use cmsgears\core\common\models\resources\ModelComment;

/**
 * Review action creates a review for discovered model using ModelComment resource.
 */
class Review extends Create {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType	= ModelComment::TYPE_REVIEW;

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

	// Review --------------------------------

}
