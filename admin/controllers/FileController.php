<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\admin\controllers\base\CrudController;

/**
 * FileController provide actions specific to files.
 *
 * @since 1.0.0
 */
class FileController extends CrudController {

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

		// Config
		$this->apixBase	= 'core/file';

		// Services
		$this->modelService = Yii::$app->factory->get( 'fileService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-file', 'child' => 'file' ];

		// Return Url
		$this->returnUrl = Url::previous( 'files' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/file/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Files' ] ],
			'create' => [ [ 'label' => 'Files', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

	public function actionAll( $config = [] ) {

		// Remember return url for crud
		Url::remember( Yii::$app->request->getUrl(), 'files' );

		$dataProvider = $this->modelService->getSharedPage();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => File::$visibilityMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->siteId	= Yii::$app->core->siteId;
		$model->shared	= true;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->saveFile( $model, [ 'admin' => true ] );

			return $this->redirect( 'all' );
		}

		return $this->render( 'create', [
			'model' => $model,
			'visibilityMap' => File::$visibilityMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$model = $this->modelService->getById( $id );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->saveFile( $model, [ 'admin' => true ] );

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'update', [
			'model' => $model,
			'visibilityMap' => File::$visibilityMap
		]);
	}
}
