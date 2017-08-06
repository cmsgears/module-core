<?php
namespace cmsgears\core\common\actions\comment;

// CMG Imports
use cmsgears\core\common\models\resources\ModelComment;

/**
 * Review adds a review for a model using ModelComment resource.
 *
 * The controller must provide appropriate model service having model class, table and type defined for the base model.
 */
class Review extends Create {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $type		= ModelComment::TYPE_REVIEW;

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
