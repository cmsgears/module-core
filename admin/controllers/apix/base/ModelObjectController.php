<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\apix\base;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * ModelObjectController provides actions specific to model object.
 *
 * @since 1.0.0
 */
abstract class ModelObjectController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $parentService;

	protected $objectService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		$this->modelService = Yii::$app->factory->get( 'modelObjectService' );

		$this->objectService = Yii::$app->factory->get( 'objectService' );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					// Avatar
					'assign-avatar' => [ 'permission' => $this->crudPermission ],
					'clear-avatar' => [ 'permission' => $this->crudPermission ],
					// Banner
					'assign-banner' => [ 'permission' => $this->crudPermission ],
					'clear-banner' => [ 'permission' => $this->crudPermission ],
					// Video
					'assign-video' => [ 'permission' => $this->crudPermission ],
					'clear-video' => [ 'permission' => $this->crudPermission ],
					// Files
					'assign-file' => [ 'permission' => $this->crudPermission ],
					'clear-file' => [ 'permission' => $this->crudPermission ],
					// Gallery
					'update-gallery' => [ 'permission' => $this->crudPermission ],
					'get-gallery-item' => [ 'permission' => $this->crudPermission ],
					'add-gallery-item' => [ 'permission' => $this->crudPermission ],
					'update-gallery-item' => [ 'permission' => $this->crudPermission ],
					'delete-gallery-item' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					// Avatar
					'assign-avatar' => [ 'post' ],
					'clear-avatar' => [ 'post' ],
					// Banner
					'assign-banner' => [ 'post' ],
					'clear-banner' => [ 'post' ],
					// Video
					'assign-video' => [ 'post' ],
					'clear-video' => [ 'post' ],
					// Files
					'assign-file' => [ 'post' ],
					'clear-file' => [ 'post' ],
					// Gallery
					'update-gallery' => [ 'post' ],
					'get-gallery-item' => [ 'post' ],
					'add-gallery-item' => [ 'post' ],
					'update-gallery-item' => [ 'post' ],
					'delete-gallery-item' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			// Avatar
			'assign-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Assign', 'modelService' => $this->objectService ],
			'clear-avatar' => [ 'class' => 'cmsgears\core\common\actions\content\avatar\Clear', 'modelService' => $this->objectService ],
			// Banner
			'assign-banner' => [ 'class' => 'cmsgears\core\common\actions\content\banner\Assign', 'modelService' => $this->objectService ],
			'clear-banner' => [ 'class' => 'cmsgears\core\common\actions\content\banner\Clear', 'modelService' => $this->objectService ],
			// Video
			'assign-video' => [ 'class' => 'cmsgears\core\common\actions\content\video\Assign', 'modelService' => $this->objectService ],
			'clear-video' => [ 'class' => 'cmsgears\core\common\actions\content\video\Clear', 'modelService' => $this->objectService ],
			// Files
			'assign-file' => [ 'class' => 'cmsgears\core\common\actions\file\Assign', 'modelService' => $this->objectService ],
			'clear-file' => [ 'class' => 'cmsgears\core\common\actions\file\Clear', 'modelService' => $this->objectService ],
			// Gallery
			'update-gallery' => [ 'class' => 'cmsgears\core\common\actions\gallery\Update', 'modelService' => $this->objectService ],
			'get-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Read', 'modelService' => $this->objectService ],
			'add-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Create', 'modelService' => $this->objectService ],
			'update-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Update', 'modelService' => $this->objectService ],
			'delete-gallery-item' => [ 'class' => 'cmsgears\core\common\actions\gallery\item\Delete', 'modelService' => $this->objectService ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelObjectController -----------------

	public function actionDelete( $id, $pid ) {

		$model		= $this->modelService->getById( $id );
		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $model ) && isset( $parent ) && $model->isParentValid( $parent->id, $parentType ) ) {

			$object = $this->objectService->getById( $model->modelId );

			// Delete Object and Mappings
			$this->objectService->delete( $object, [ 'admin' => true ] );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
