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
 * The Read action find gallery item for given item id and returh item data.
 *
 * @since 1.0.0
 */
class Read extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

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

	// Read ----------------------------------

	/**
	 * Find the file using given $fid and returns the file details.
	 *
	 * @param type $fid File Id
	 * @return array
	 */
	public function run( $fid ) {

		$model = $this->model;

		$modelFile = $this->modelFileService->getFirstByParentModelId( $model->id, $this->parentType, $fid );

		if( isset( $modelFile ) ) {

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

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
