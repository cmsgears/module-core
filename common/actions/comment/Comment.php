<?php
namespace cmsgears\core\common\actions\comment;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CommentProperties;

use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Comment action creates a comment for discovered model using ModelComment resource.
 */
class Comment extends Create {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType	= ModelComment::TYPE_COMMENT;

	public $scenario	= ModelComment::SCENARIO_IDENTITY;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Comment -------------------------------

	public function run() {

		$commentProperties = CommentProperties::getInstance();

		// Comments are disabled
		if( !$commentProperties->isComments() ) {

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NO_COMMENTS ) );
		}

		return parent::run();
	}
}
