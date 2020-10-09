<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\mapper;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Direct action maps existing option to model column.
 */
class Direct extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $mapperService;

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Direct --------------------------------

	public function run( $cid = null ) {

		$post = yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'itemId' ] ) && isset( $post[ 'column' ] ) ) {

			$mapperId	= $post[ 'itemId' ];
			$column		= $post[ 'column' ];

			$mapper = $this->mapperService->getById( $mapperId );

			if( isset( $mapper ) ) {

				if( isset( $cid ) ) {

					$this->model->$column = null;

					$this->modelService->update( $this->model );

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
				}
				else {

					$this->model->$column = $mapperId;

					$this->modelService->update( $this->model );

					$data = [ 'cid' => $mapper->id, 'name' => $mapper->name ];

					// Trigger Ajax Success
					return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
				}
			}
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
