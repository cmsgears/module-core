<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\gallery;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Update creates the gallery item.
 *
 * @since 1.0.0
 */
class Update extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $direct = false;

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

	// Update --------------------------------

	/**
	 * Updates the using given $cid.
	 *
	 * @param type $cid Gallery Id
	 * @return array
	 */
	public function run( $cid ) {

		$model		= $this->model;
		$gallery	= $this->galleryService->getById( $cid );

		if( isset( $gallery ) && ( $this->direct || $gallery->belongsTo( $model ) ) ) {

			if( $gallery->load( Yii::$app->request->post(), $gallery->getClassName() ) && $gallery->validate() ) {

				$this->galleryService->update( $gallery );

				// Trigger Ajax Success
				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
			}

			// Generate Errors
			$errors = AjaxUtil::generateErrorMessage( $gallery );

			// Trigger Ajax Failure
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
