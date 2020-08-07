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

use cmsgears\core\common\models\forms\Comment;
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

	/**
	 * A comment can be created with or without scenario. The possible scenarios
	 * are - all, identity, captcha, review, feedback and testimonial.
	 */
	public $scenario;

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

			$modelClass = $modelCommentService->getModelClass();

			$modelComment	= new $modelClass;
			$commentForm	= new Comment();

			$modelComment->parentId		= $this->model->id;
			$modelComment->parentType	= $this->parentType;

			$modelComment->status	= $this->status;
			$modelComment->type		= $this->modelType;

			// To set name and email in case user is logged in. The same details can be fetched from user table using createdBy column.
			if( $this->setUser && isset( $user ) ) {

				$modelComment->name		= $user->getName();
				$modelComment->email	= $user->email;
			}

			if( isset( $this->scenario ) ) {

				$commentForm->scenario = $this->scenario;
			}

			if( $commentForm->load( Yii::$app->request->post(), $commentForm->getClassName() ) && $commentForm->validate() ) {

				$modelComment->copyForUpdateFrom( $commentForm, [
					'baseId', 'bannerId', 'videoId', 'title', 'avatarUrl', 'websiteUrl',
					'rate1', 'rate2', 'rate3', 'rate4', 'rate5', 'rating',
					'anonymous', 'content'
				]);

				if( !$this->setUser || !isset( $user ) ) {

					$modelComment->copyForUpdateFrom( $commentForm, [ 'name', 'email' ] );
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

}
