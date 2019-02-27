<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\file;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\actions\base\ModelAction;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * It assigns the uploaded file to model in action using ModelFile mapper.
 */
class Assign extends ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	public $fileName = 'File';

	// Protected --------------

	protected $fileService;

	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->fileService = Yii::$app->factory->get( 'fileService' );

		$this->modelFileService = Yii::$app->factory->get( 'modelFileService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Assign --------------------------------

	public function run( $tag ) {

		if( isset( $this->model ) ) {

			$model = $this->model;

			// Populate File
			$mapper	= $this->modelFileService->getByFileTag( $model->id, $this->parentType, $tag );
			$file	= isset( $file ) ? $mapper->model : new File();

			// Load File
			$file = File::loadFile( $file, $this->fileName );

			// Configure File
			$file->tag = $tag;

			// Create/Update File
			$file = $this->fileService->saveFile( $file );

			// Create/Update Mapping
			$modelMapper = $this->modelFileService->activateByModelId( $model->id, $this->parentType, $file->id, $this->modelType );

			$data = [ 'cid' => $modelMapper->id, 'fileUrl' => $file->getFileUrl() ];

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
