<?php
namespace cmsgears\core\common\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Testimonial adds a review for a model using ModelComment resource.
 *
 * The controller must provide appropriate model service having model class, table and type defined for the base model.
 */
class Testimonial extends Create {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType	= ModelComment::TYPE_TESTIMONIAL;

	public $scenario	= ModelComment::TYPE_TESTIMONIAL;

	// Protected --------------

	public $typed = false;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Testimonial ---------------------------
}
