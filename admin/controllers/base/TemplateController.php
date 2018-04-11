<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * TemplateController provide actions specific to template management.
 *
 * @since 1.0.0
 */
abstract class TemplateController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/template' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService = Yii::$app->factory->get( 'templateService' );

		// Notes: Configure type, sidebar and returnUrl exclusively in child classes.
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors = parent::behaviors();

		$behaviors[ 'activity' ] = [
			'class' => ActivityBehavior::class,
			'admin' => true,
			'create' => [ 'create' ],
			'update' => [ 'update' ],
			'delete' => [ 'delete' ]
		];

		return $behaviors;
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// TemplateController --------------------

	public function actionAll( $config = [] ) {

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'type' => $this->type
		]);
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->type	= $this->type;
		$model->siteId	= Yii::$app->core->siteId;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [ 'admin' => true ] );

			return $this->redirect( 'all' );
		}

		return $this->render( 'create', [
			'model' => $model
		]);
	}

}
