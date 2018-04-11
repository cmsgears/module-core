<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\base\form;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\controllers\base\Controller;

/**
 * FieldController provides actions specific to form field model.
 *
 * @since 1.0.0
 */
class FieldController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $formService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->setViewPath( '@cmsgears/module-core/admin/views/form/field' );

		$this->crudPermission		= CoreGlobal::PERM_CORE;

		// Services
		$this->modelService			= Yii::$app->factory->get( 'formFieldService' );
		$this->formService			= Yii::$app->factory->get( 'formService' );

		// Return Url
		$this->returnUrl		= Url::previous( 'formFields' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/forms/config/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs		= [
			'all' => [ [ 'label' => 'Form Field' ] ],
			'create' => [ [ 'label' => 'Form Field', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Form Field', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Form Field', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
		];

		// Note: Set returnUrl and sidebar in child classes.
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
					'all'  => [ 'permission' => $this->crudPermission ],
					'create'  => [ 'permission' => $this->crudPermission ],
					'update'  => [ 'permission' => $this->crudPermission ],
					'delete'  => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'all'	=> [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// FieldController -----------------------

	public function actionAll( $fid ) {

		Url::remember( Yii::$app->request->getUrl(), 'formFields' );

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = $this->modelService->getPageByFormId( $fid );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'formId' => $fid,
			'typeMap' => $modelClass::$typeMap
		]);
	}

	public function actionCreate( $fid ) {

		$modelClass		= $this->modelService->getModelClass();
		$model			= new $modelClass;
		$model->formId	= $fid;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( $this->returnUrl );
		}

		return $this->render( 'create', [
			'model' => $model,
			'formId' => $fid,
			'typeMap' => $modelClass::$typeMap
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$modelClass	= $this->modelService->getModelClass();
		$model		= $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->modelService->update( $model );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'model' => $model,
				'formId' => $model->formId,
				'typeMap' => $modelClass::$typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id ) {

		// Find Model
		$modelClass	= $this->modelService->getModelClass();
		$model		= $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->modelService->delete( $model );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'delete', [
				'model' => $model,
				'formId' => $model->formId,
				'typeMap' => $modelClass::$typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
