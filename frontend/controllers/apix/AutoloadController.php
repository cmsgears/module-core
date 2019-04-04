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
use cmsgears\core\common\utilities\AjaxUtil;

// CRE Imports
use century\core\common\config\CoreGlobal;

class AutoloadController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		return [
			'rbac' => [
				'class' => Yii::$app->core->getRbacFilterClass(),
				'actions' => [
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'widget' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AutoloadController --------------------

	public function actionWidget() {

		$request = Yii::$app->request->post();

		$id		= $request[ 'widgetId' ];
		$widget = $request[ 'widgetClass' ];

		$widgetHtml = $widget::widget( [ 'wrap' => false, 'autoload' => false ] );

		$data = [ 'widgetId' => $id, 'widgetHtml' => $widgetHtml ];

		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
	}

}
