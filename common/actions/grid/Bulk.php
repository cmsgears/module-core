<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\actions\grid;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * Bulk action performs selected bulk action on selected models.
 *
 * @since 1.0.0
 */
class Bulk extends \cmsgears\core\common\base\Action {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $admin	= false;
	public $user	= false;

	public $config = [];

	public $modelService;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->modelService = isset( $this->modelService ) ? $this->modelService : $this->controller->modelService;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Bulk ----------------------------------

	public function run() {

		$column	= Yii::$app->request->post( 'column' );
		$action	= Yii::$app->request->post( 'action' );
		$target	= Yii::$app->request->post( 'target' );

		if( isset( $action ) && isset( $column ) && isset( $target ) ) {

			$target	= preg_split( '/,/', $target );

			$parentId	= Yii::$app->request->get( 'parent-id' );
			$parentType = Yii::$app->request->get( 'parent-type' );

			// Apply bulk action on admin specific models
			if( $this->admin ) {

				$this->modelService->applyBulkByTargetId( $column, $action, $target, $this->config );
			}
			// Apply bulk action on user specific models
			else if( $this->user ) {

				$this->modelService->applyBulkByTargetIdUser( $column, $action, $target, $this->config );
			}
			// Apply bulk by parent
			else if( isset( $parentId ) && isset( $parentType ) ) {

				$this->modelService->applyBulkByTargetIdParent( $column, $action, $target, $parentId, $parentType, $this->config );
			}

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}

}
