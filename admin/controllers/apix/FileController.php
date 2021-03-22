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

/**
 * FileController provide actions specific to file model.
 *
 * @since 1.0.0
 */
class FileController extends \cmsgears\core\admin\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService = Yii::$app->factory->get( 'fileService' );
	}

	public function beforeAction( $action ) {

		if( $action->id == 'file-handler' ) {

			$this->enableCsrfValidation = false;
		}

		return parent::beforeAction( $action );
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
					'file-handler' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'generic' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'file-handler'  => [ 'post' ],
					'bulk' => [ 'post' ],
					'generic' => [ 'post' ],
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
				'class' => 'cmsgears\core\common\actions\grid\Bulk', 'admin' => true,
				'config' => [ 'backend' => true ]
			],
			'generic' => [
				'class' => 'cmsgears\core\common\actions\grid\Generic',
				'config' => [ 'backend' => true ]
			],
			'delete' => [
				'class' => 'cmsgears\core\common\actions\grid\Delete',
				'config' => [ 'backend' => true ]
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

}
