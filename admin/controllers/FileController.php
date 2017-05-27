<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\helpers\Url;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Theme;

class FileController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'fileService' );
		$this->sidebar			= [ 'parent' => 'sidebar-file', 'child' => 'file' ];

		$this->returnUrl		= Url::previous( 'files' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/file/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FileController ------------------------

	public function actionAll() {

		$dataProvider = $this->modelService->getSharedPage();

		return $this->render( 'all', [
				'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate() {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->shared	= true;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

			$this->modelService->saveFile( $model );

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'create', [
				'model' => $model
		]);
	}

	public function actionUpdate( $id ) {

		$model	= $this->modelService->getById( $id );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

			$this->modelService->saveFile( $model );

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'update', [
				'model' => $model
		]);
	}
}
