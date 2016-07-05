<?php
namespace cmsgears\core\frontend\actions\comment;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\mappers\ModelCommentService;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Delete can be used to delete a comment and trigger notification and mail to admin and model owner.
 *
 * The controller must provide modelService variable using approprite service class.
 */
class Delete extends \yii\base\Action {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	public $modelScenario;

	public $parentService;

	// Private/Protected --

	// Constructor and Initialisation ------------------------------

    public function init() {

		if( !isset( $this->modelScenario ) && isset( Yii::$app->controller->modelScenario ) ) {

			$this->modelScenario = Yii::$app->controller->modelScenario;
		}

		if( !isset( $this->parentService ) && isset( Yii::$app->controller->parentService ) ) {

			$this->parentService = Yii::$app->controller->parentService;
		}
    }

	// Instance Methods --------------------------------------------

	// DeleteRequest ---------------------

	public function run( $id ) {

		$model		= ModelCommentService::findById( $id );
		$user		= Yii::$app->user->getIdentity();
		$parent		= $this->parentService->findById( $model->parentId );

		if( isset( $model ) && $parent->isOwner( $user ) ) {

			ModelCommentService::delete( $model );

			// TODO: Trigger notification and mail

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>