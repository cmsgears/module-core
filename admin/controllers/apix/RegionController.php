<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\AjaxUtil;

class RegionController extends \cmsgears\core\admin\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService = Yii::$app->factory->get( 'regionService' );
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
					'auto-search' => [ 'permission' => CoreGlobal::PERM_ADMIN ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'generic' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ],
					'options-list' => [ 'permission' => CoreGlobal::PERM_ADMIN ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'auto-search' => [ 'post' ],
					'bulk' => [ 'post' ],
					'generic' => [ 'post' ],
					'delete' => [ 'post' ],
					'options-list' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk', 'admin' => true ],
			'generic' => [ 'class' => 'cmsgears\core\common\actions\grid\Generic' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ],
			'options-list' => [ 'class' => 'cmsgears\core\common\actions\location\data\RegionOptions' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// RegionController ----------------------

	public function actionAutoSearch() {

		$name		= Yii::$app->request->post( 'name' );
		$provinceId	= Yii::$app->request->get( 'pid' );

		$regionClass = $this->modelService->getModelClass();

		$query = $regionClass::find();

		$query->andWhere( "provinceId=:pid", [ ':pid' => $provinceId ] );

		$data = $this->modelService->searchByName( $name, [ 'query' => $query ] );

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

}
