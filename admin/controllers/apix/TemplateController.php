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

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * TemplateController provide actions specific to template management.
 *
 * @since 1.0.0
 */
class TemplateController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permissions
		$this->crudPermission 	= CoreGlobal::PERM_CORE;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'templateService' );
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
					'auto-search' => [ 'permission' => $this->crudPermission ],
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'auto-search' => [ 'post' ],
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'auto-search' => [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ],
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateController --------------------

}
