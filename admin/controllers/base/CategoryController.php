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

/**
 * CategoryController is base controller for actions specific to category model.
 *
 * @since 1.0.0
 */
abstract class CategoryController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;
	public $type;

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/category' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Config
		$this->title	= CoreGlobal::TYPE_SITE;
		$this->type		= CoreGlobal::TYPE_SITE;

		// Services
		$this->modelService = Yii::$app->factory->get( 'categoryService' );

		// Notes: Configure sidebar and returnUrl exclusively in child classes. We can also change type in child classes.
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryController --------------------

	public function actionAll( $config = [] ) {

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->type	= $this->type;
		$model->siteId	= Yii::$app->core->siteId;
		$model->order	= 0;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [ 'admin' => true ] );

			return $this->redirect( 'all' );
		}

		return $this->render( 'create', [
			'model' => $model
		]);
	}

}
