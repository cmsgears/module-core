<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\tag\mapper;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Assign action map tags for models using ModelTag mapper using given csv having tag name. In
 * case a tag does not exist for model type, it will be created and than mapping will be done.
 *
 * @since 1.0.0
 */
class Assign extends \cmsgears\core\common\actions\base\ModelAction {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parent = true;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Assign --------------------------------

	public function run() {

		$post = yii::$app->request->post();

		if( isset( $this->model ) && isset( $post[ 'list' ] ) ) {

			$modelTagService = Yii::$app->factory->get( 'modelTagService' );

			$parentId	= $this->model->id;
			$parentType	= $this->parentType;
			$tags		= $post[ 'list' ];

			$modelTagService->createFromCsv( $parentId, $parentType, $tags );

			$modelTags = $this->model->activeModelTags;

			$data = [];

			foreach( $modelTags as $modelTag ) {

				$data[]	= [ 'cid' => $modelTag->id, 'name' => $modelTag->model->name ];
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
