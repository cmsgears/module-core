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

use cmsgears\core\admin\controllers\base\Controller;

/**
 * FileController provide actions specific to file model.
 *
 * @since 1.0.0
 */
class FileController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function __construct( $id, $module, $config = [] ) {

		parent::__construct( $id, $module, $config );

		$this->enableCsrfValidation = false;
	}

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission 	= CoreGlobal::PERM_ADMIN;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'fileService' );
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
					'file-handler'  => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'file-handler'  => [ 'post' ],
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'file-handler' => [ 'class' => 'cmsgears\core\common\actions\file\FileHandler' ],
			'bulk' => [
				'class' => 'cmsgears\core\common\actions\grid\Bulk',
				'config' => [ 'admin' => true ]
			],
			'delete' => [
				'class' => 'cmsgears\core\common\actions\grid\Delete',
				'config' => [ 'admin' => true ]
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

}
