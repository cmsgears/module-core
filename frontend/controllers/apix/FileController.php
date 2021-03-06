<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class FileController extends \cmsgears\core\frontend\controllers\apix\base\Controller {

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

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
					'file-handler' => [ 'permission' => CoreGlobal::PERM_USER ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'file-handler' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'file-handler' => [ 'class' => 'cmsgears\core\common\actions\file\FileHandler' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

}
