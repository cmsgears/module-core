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

use cmsgears\core\common\utilities\AjaxUtil;

/**
 * CategoryController handles the AJAX requests specific to Category Model.
 *
 * @since 1.0.0
 */
class CategoryController extends \cmsgears\core\frontend\controllers\apix\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Services
		$this->modelService = Yii::$app->factory->get( 'categoryService' );
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
					'suggest' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'auto-search' => [ 'post' ],
					'suggest' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'auto-search' => [ 'class' => 'cmsgears\core\common\actions\content\AutoSearch' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryController --------------------

	public function actionSuggest() {

		$name	= Yii::$app->request->post( 'name' );
		$type	= Yii::$app->request->post( 'type' );

		if( empty( $name ) || empty( $type ) ) {

			$errors = [ 'name' => 'Please suggest a valid category.' ];

			// Trigger Ajax Success
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		$user = Yii::$app->core->getUser();

		Yii::$app->factory->get( 'userService' )->notifyAdmin( $user, [
			'template' => CoreGlobal::TPL_NOTIFY_SUGGEST_CATEGORY,
			'data' => [ 'name' => $name, 'type' => $type ]
		]);

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ) );
	}

}
