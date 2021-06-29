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

use cmsgears\core\common\models\resources\File;
use cmsgears\core\common\models\resources\ModelComment;

use cmsgears\files\components\FileManager;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Create action creates comment, review or testimonial of discovered model using
 * ModelComment resource.
 *
 * @since 1.0.0
 */
abstract class Create extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	public $modelType = null;

	public $status = ModelComment::STATUS_NEW;

	public $setUser = true;

	public $media = false;

	public $mediaType = FileManager::FILE_TYPE_MIXED;

	public $mediaModel = 'File';

	public $additionalAttributes = [];

	/**
	 * A comment can be created with or without scenario. The possible scenarios
	 * are - all, identity, captcha, review, feedback and testimonial.
	 */
	public $scenario;

	public $notification = false;

	public $notifyAdmin		= false;
	public $notifyUser		= false;
	public $notifyParent	= false;

	public $notifyAdminUrl;
	public $notifyUserUrl;
	public $notifyParentUrl;

	public $notifyAdminTemplate;
	public $notifyUserTemplate;
	public $notifyParentTemplate;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	public function run() {

		if( isset( $this->model ) ) {

			$modelCommentService = Yii::$app->factory->get( 'modelCommentService' );

			$user = Yii::$app->core->getUser();

			$modelClass		= $modelCommentService->getModelClass();
			$commentClass	= $modelCommentService->getCommentClass();

			$modelComment	= new $modelClass;
			$commentForm	= new $commentClass;

			$modelComment->parentId		= $this->model->id;
			$modelComment->parentType	= $this->parentType;

			$modelComment->status	= $this->status;
			$modelComment->type		= $this->modelType;

			// To set name and email in case user is logged in. The same details can be fetched from user table using createdBy column.
			if( $this->setUser && isset( $user ) ) {

				$modelComment->name		= $user->getName();
				$modelComment->email	= $user->email;

				$commentForm->name	= $user->getName();
				$commentForm->email	= $user->email;
			}

			if( isset( $this->scenario ) ) {

				$commentForm->scenario = $this->scenario;
			}

			if( $commentForm->load( Yii::$app->request->post(), $commentForm->getClassName() ) && $commentForm->validate() ) {

				$modelComment->copyForUpdateFrom( $commentForm, [
					'baseId', 'bannerId', 'videoId', 'title', 'avatarUrl', 'websiteUrl',
					'field1', 'field2', 'field3', 'field4', 'field5',
					'rate1', 'rate2', 'rate3', 'rate4', 'rate5', 'rating',
					'anonymous', 'content'
				]);

				if( count( $this->additionalAttributes ) > 0 ) {

					$modelComment->copyForUpdateFrom( $commentForm, $this->additionalAttributes );
				}

				if( !$this->setUser || !isset( $user ) ) {

					$modelComment->copyForUpdateFrom( $commentForm, [ 'name', 'email' ] );
				}

				if( isset( $user ) ) {

					$modelComment->userId = $user->id;
				}

				$modelComment = $modelCommentService->create( $modelComment );

				if( isset( $modelComment ) ) {

					// Refresh model for further processing
					$modelComment->refresh();

					// Set controller model for post processing
					if( Yii::$app->controller->hasProperty( 'modelComment' ) ) {

						Yii::$app->controller->modelComment = $modelComment;
					}

					// Attach media to comment if allowed and available
					if( $this->media ) {

						$filesCount = count( Yii::$app->request->post( $this->mediaModel ) );

						$files = $this->initFiles( $filesCount );

						if( File::loadMultiple( $files, Yii::$app->request->post(), $this->mediaModel ) && File::validateMultiple( $files ) ) {

							foreach( $files as $file ) {

								$modelCommentService->attachFile( $modelComment, $file, $this->mediaType, ModelComment::TYPE_COMMENT );
							}
						}
					}

					// Trigger Notification
					if( isset( $this->notification ) ) {

						if( isset( $this->notifyAdmin ) && isset( $this->notifyAdminUrl ) ) {

							$this->triggerAdminNotification( $modelComment );
						}

						if( isset( $this->notifyUser ) && isset( $this->notifyUserUrl ) ) {

							$this->triggerUserNotification( $modelComment );
						}

						if( isset( $this->notifyParent ) && isset( $this->notifyParentUrl ) ) {

							$this->triggerParentNotification( $modelComment );
						}
					}

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
				}
			}

			// Generate Validation Errors
			$errors = AjaxUtil::generateErrorMessage( $commentForm );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	protected function initFiles( $count ) {

		$files = [];

		for( $i = 0; $i < $count; $i++ ) {

			$files[] = new File();
		}

		return $files;
	}

	protected function triggerAdminNotification( $model ) {

		$modelService = Yii::$app->factory->get( 'modelCommentService' );

		Yii::$app->eventManager->triggerNotification(
			$this->notifyAdminTemplate,
			[ 'model' => $model, 'service' => $modelService ],
			[
				'admin' => true, 'direct' => false,
				'parentId' => $model->id, 'parentType' => $modelService->getParentType(),
				'adminLink' => "{$this->notifyAdminUrl}?id={$model->id}"
			]
		);
	}

	protected function triggerUserNotification( $model ) {

		$modelService = Yii::$app->factory->get( 'modelCommentService' );

		Yii::$app->eventManager->triggerNotification(
			$this->notifyAdminTemplate,
			[ 'model' => $model, 'service' => $modelService ],
			[
				'user' => true, 'direct' => false,
				'parentId' => $model->id, 'parentType' => $modelService->getParentType(),
				'adminLink' => "{$this->notifyUserUrl}?id={$model->id}"
			]
		);
	}

	protected function triggerParentNotification( $model ) {

		$modelService = Yii::$app->factory->get( 'modelCommentService' );

		Yii::$app->eventManager->triggerNotification(
			$this->notifyAdminTemplate,
			[ 'model' => $model, 'service' => $modelService ],
			[
				'admin' => false, 'user' => false, 'direct' => true,
				'parentId' => $model->id, 'parentType' => $modelService->getParentType(),
				'adminLink' => "{$this->notifyParentUrl}?id={$model->id}"
			]
		);
	}

}
