<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\file\mapper;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Create creates the user file.
 *
 * @since 1.0.0
 */
class Create extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	public $fileName = 'File';

	// It allows unlimited items by default.
	public $maxFiles = 0;

	// Protected --------------

	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelFileService = Yii::$app->factory->get( 'modelFileService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	/**
	 * It creates the file
	 *
	 * @return array
	 */
	public function run() {

		$model = $this->model;

		if( $this->maxFiles > 0 ) {

			$files = $model->files;

			if( count( $files ) >= $this->maxFiles ) {

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'limit' => "You are not allowed to add more than $this->maxFiles files." ] );
			}
		}

		$modelFileClass = $this->modelFileService->getModelClass();

		$modelFile = new $modelFileClass;

		$modelFile->active = true;

		$user = Yii::$app->core->getUser();
		$file = new File();

		$file->siteId	= Yii::$app->core->siteId;
		$file->userId	= $user->id;
		$file->shared	= false;

		if( $file->load( Yii::$app->request->post(), $this->fileName ) && $file->validate() ) {

			$modelFile = $this->modelFileService->createWithParent( $file, [
				'parentId' => $model->id, 'parentType' => $this->parentType,
				'model' => $modelFile
			]);

			$file = $modelFile->model;

			$data = [
				'mid' => $modelFile->id, 'fid' => $file->id,
				'name' => $file->name, 'extension' => $file->extension,
				'title' => $file->title, 'caption' => $file->caption,
				'altText' => $file->altText, 'link' => $file->link,
				'description' => $file->description,
				'url' => $file->getFileUrl(), 'icon' => $file->getFileIcon()
			];

			if( $file->type == 'image' ) {

				$data[ 'mediumUrl' ]	= $file->getMediumUrl();
				$data[ 'thumbUrl' ]		= $file->getThumbUrl();
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Generate Errors
		$errors = AjaxUtil::generateErrorMessage( $file );

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
	}

}
