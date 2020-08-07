<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\gallery\item;

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

	public $admin	= false;
	public $user	= false;

	// Protected --------------

	protected $galleryService;
	protected $modelFileService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->galleryService	= Yii::$app->factory->get( 'galleryService' );
		$this->modelFileService = Yii::$app->factory->get( 'modelFileService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Read ----------------------------------

	/**
	 * Find the gallery item using given $cid and $fid and returns the item details.
	 *
	 * @param type $cid Gallery Id
	 * @param type $fid File Id
	 * @return array
	 */
	public function run( $cid, $fid ) {

		$model = $this->model;

		$gallery = $this->galleryService->getById( $cid );

		if( isset( $gallery ) && ( $this->admin || ( isset( $model ) && $gallery->belongsTo( $model ) ) ) ) {

			$modelFile = $this->modelFileService->getFirstByParentModelId( $gallery->id, CoreGlobal::TYPE_GALLERY, $fid );

			if( isset( $modelFile ) && $modelFile->isParentValid( $gallery->id, CoreGlobal::TYPE_GALLERY ) ) {

				$file = $modelFile->model;

				$data = [
					'mid' => $modelFile->id, 'fid' => $file->id,
					'name' => $file->name, 'extension' => $file->extension,
					'title' => $file->title, 'caption' => $file->caption,
					'altText' => $file->altText, 'link' => $file->link,
					'description' => $file->description,
					'url' => $file->getFileUrl()
				];

				if( $file->type == 'image' ) {

					$data[ 'mediumUrl' ]	= $file->getMediumUrl();
					$data[ 'thumbUrl' ]		= $file->getThumbUrl();
				}

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
