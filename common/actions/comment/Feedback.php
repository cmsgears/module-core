<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\comment;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\Comment as CommentForm;
use cmsgears\core\common\models\resources\ModelComment;

/**
 * Feedback action creates a feedback of discovered model using ModelComment resource.
 *
 * @since 1.0.0
 */
class Feedback extends Create {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType = ModelComment::TYPE_FEEDBACK;

	public $scenario = CommentForm::SCENARIO_FEEDBACK;

	public $notification = true;

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

	protected function triggerNotification( $model ) {

		$modelService = Yii::$app->factory->get( 'modelCommentService' );

		Yii::$app->eventManager->triggerNotification(
			CoreGlobal::TPL_NOTIFY_FEEDBACK,
			[ 'model' => $model, 'service' => $modelService ],
			[
				'admin' => true, 'direct' => false,
				'parentId' => $model->id, 'parentType' => $modelService->getParentType(),
				'adminLink' => "core/feedback/update?id={$model->id}"
			]
		);
	}

}
