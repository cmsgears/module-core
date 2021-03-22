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
 * Delete deletes the user file.
 *
 * @since 1.0.0
 */
class Delete extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	// It allows unlimited items by default.
	public $minFiles = 0;

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

	// Delete --------------------------------

	/**
	 * It deletes the file using $fid.
	 *
	 * @param type $fid File Id
	 * @return array
	 */
	public function run( $fid ) {

		$model = $this->model;

		if( $this->minFiles > 0 ) {

			$files = $model->files;

			if( count( $files ) <= $this->minFiles ) {

				// Trigger Ajax Failure
				return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'limit' => "Minimum $this->minFiles files are required." ] );
			}
		}

		$modelFile = $this->modelFileService->getFirstByParentModelId( $model->id, $this->parentType, $fid );

		if( isset( $modelFile ) ) {

			$data = [ 'mid' => $modelFile->id ];

			$this->modelFileService->delete( $modelFile );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}
	}

}
