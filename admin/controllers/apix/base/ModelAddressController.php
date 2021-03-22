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
 * ModelAddressController provides actions specific to model address.
 *
 * @since 1.0.0
 */
abstract class ModelAddressController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $parentService;

	protected $addressService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		$this->modelService = Yii::$app->factory->get( 'modelAddressService' );

		$this->addressService = Yii::$app->factory->get( 'addressService' );
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
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'bulk' => [
				'class' => 'cmsgears\core\common\actions\grid\Bulk', 'admin' => true,
				'config' => [ 'admin' => true ]
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelAddressController ----------------

	public function actionDelete( $id, $pid ) {

		$model		= $this->modelService->getById( $id );
		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $model ) && isset( $parent ) && $model->isParentValid( $parent->id, $parentType ) ) {

			$address = $this->addressService->getById( $model->modelId );

			// Delete Address and Mappings
			$this->addressService->delete( $address, [ 'admin' => true ] );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
