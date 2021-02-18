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
 * OptionController handles the ajax requests specific to Option Model.
 *
 * @since 1.0.0
 */
class OptionController extends \cmsgears\core\frontend\controllers\apix\base\Controller {

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
		$this->modelService = Yii::$app->factory->get( 'optionService' );
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

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// OptionController ----------------------

	public function actionAutoSearch() {

		$name	= Yii::$app->request->post( 'name' );
		$catId	= Yii::$app->request->post( 'type' );

		$data = $this->modelService->searchByNameCategoryId( $name, $catId );

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

	public function actionSuggest() {

		$name	= Yii::$app->request->post( 'name' );
		$cslug	= Yii::$app->request->post( 'cslug' );

		if( empty( $name ) || empty( $cslug ) ) {

			$errors = [ 'name' => 'Please suggest a valid option.' ];

			// Trigger Ajax Success
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		$category = Yii::$app->factory->get( 'categoryService' )->getBySlugType( $cslug, CoreGlobal::TYPE_OPTION_GROUP );

		if( empty( $category ) ) {

			$errors = [ 'name' => 'Please suggest option for valid category.' ];

			// Trigger Ajax Success
			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ), $errors );
		}

		$user = Yii::$app->core->getUser();

		Yii::$app->factory->get( 'userService' )->notifyAdmin( $user, [
			'template' => CoreGlobal::TPL_NOTIFY_SUGGEST_OPTION,
			'adminLink' => "core/optiongroup/option/all?pid={$category->id}",
			'data' => [ 'name' => $name, 'category' => $category ]
		]);

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( 'Thanks for submitting your valuable suggestion.' );
	}

}
