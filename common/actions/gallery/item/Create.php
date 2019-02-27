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

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Create creates the gallery item.
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

	public $fileName = 'File';

	public $direct = false; // Gallery Items are directly managed without gallery parent

	// It allows unlimited items by default.
	public $maxItems = 0;

	// Protected --------------

	protected $galleryService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->galleryService = Yii::$app->factory->get( 'galleryService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Create --------------------------------

	/**
	 * It creates the gallery item using given $cid.
	 *
	 * @param type $cid Gallery Id
	 * @return array
	 */
	public function run( $cid ) {

		$model		= $this->model;
		$gallery	= $this->galleryService->getById( $cid );

		if( isset( $gallery ) && ( $this->direct || $gallery->belongsTo( $model ) ) ) {

			if( $this->maxItems > 0 ) {

				$items = $gallery->files;

				if( count( $items ) >= $this->maxItems ) {

					// Trigger Ajax Failure
					return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), [ 'limit' => "You are not allowed to add more than $this->maxItems items." ] );
				}
			}

			$file = new File();

			$file->siteId = $gallery->siteId;

			if( $file->load( Yii::$app->request->post(), $this->fileName ) && $file->validate() ) {

				$modelFile = $this->galleryService->createItem( $gallery, $file, $this->modelType );

				$file = $modelFile->model;

				$data = [
					'mid' => $modelFile->id, 'fid' => $file->id,
					'name' => $file->name, 'extension' => $file->extension,
					'title' => $file->title, 'caption' => $file->caption,
					'altText' => $file->altText, 'link' => $file->link, 'description' => $file->description,
					'url' => $file->getFileUrl()
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

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
