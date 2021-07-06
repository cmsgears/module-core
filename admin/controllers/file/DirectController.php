<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\file;

// Yii Imports
use Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * DirectController provide actions specific to files.
 *
 * @since 1.0.0
 */
class DirectController extends \cmsgears\core\admin\controllers\base\CrudController {

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
		$this->sidebar = [ 'parent' => 'sidebar-file', 'child' => 'dfile' ];

		// Return Url
		$this->returnUrl = Url::previous( 'dfiles' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/file/direct/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Direct Files' ] ],
			'update' => [ [ 'label' => 'Direct Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// DirectController ----------------------

	public function actionAll( $config = [] ) {

		// Remember return url for crud
		Url::remember( Yii::$app->request->getUrl(), 'dfiles' );

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getDirectPage();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => $modelClass::$visibilityMap,
			'filterVisibilityMap' => $modelClass::$filterVisibilityMap,
			'typeMap' => $modelClass::$typeMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = $this->modelService->getById( $id );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->saveFile( $model, [ 'admin' => true ] );

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'update', [
			'model' => $model,
			'visibilityMap' => $modelClass::$visibilityMap
		]);
	}

}
