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

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * It finds the file using the unique code and delete the file and corresponding
 * mappings from db and also clear the file from disk.
 */
class Clear extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

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

	// Clear ---------------------------------

	public function run( $code ) {

		if( isset( $this->model ) ) {

			$model = $this->model;

			// Populate File
			$mapper	= $this->modelFileService->getByFileCode( $model->id, $this->parentType, $code );

			if( isset( $mapper ) && $mapper->isParentValid( $model->id, $this->parentType ) ) {

				// Delete the file and mapper models
				$this->fileService->delete( $mapper->model );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
