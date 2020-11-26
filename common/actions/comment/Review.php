<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\comment;

// CMG Imports
use cmsgears\core\common\models\forms\Comment as CommentForm;
use cmsgears\core\common\models\resources\ModelComment;

/**
 * Review action creates a review of discovered model using ModelComment resource.
 *
 * @since 1.0.0
 */
class Review extends Create {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType = ModelComment::TYPE_REVIEW;

	public $scenario = CommentForm::SCENARIO_REVIEW;

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
