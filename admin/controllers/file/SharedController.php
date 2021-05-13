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

use cmsgears\core\common\models\resources\File;

/**
 * SharedController provide actions specific to files.
 *
 * @since 1.0.0
 */
class SharedController extends \cmsgears\core\admin\controllers\base\CrudController {

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
		$this->sidebar = [ 'parent' => 'sidebar-file', 'child' => 'sfile' ];

		// Return Url
		$this->returnUrl = Url::previous( 'sfiles' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/file/shared/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Shared Files' ] ],
			'create' => [ [ 'label' => 'Shared Files', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Shared Files', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Shared Files', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SharedController ----------------------

	public function actionAll( $config = [] ) {

		// Remember return url for crud
		Url::remember( Yii::$app->request->getUrl(), 'sfiles' );

		$dataProvider = $this->modelService->getSharedPage();

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => File::$filterVisibilityMap,
			'typeMap' => File::$typeMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->shared = true;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->saveFile( $model, [ 'admin' => true ] );

			if( $this->model ) {

				return $this->redirect( 'all' );
			}
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
