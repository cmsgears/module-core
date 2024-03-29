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
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelFileController provides actions specific to model files.
 *
 * @since 1.0.0
 */
abstract class ModelFileController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	// Protected --------------

	protected $parentService;

	protected $fileService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/model-file' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		$this->modelService = Yii::$app->factory->get( 'modelFileService' );

		$this->fileService = Yii::$app->factory->get( 'fileService' );
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
					'index' => [ 'permission' => $this->crudPermission ],
					'all' => [ 'permission' => $this->crudPermission ],
					'create' => [ 'permission' => $this->crudPermission ],
					'update' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
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

	// ModelFileController -------------------

	public function actionAll( $pid ) {

		$parent = $this->parentService->getById( $pid );

		$parentType	= $this->parentService->getParentType();

		if( isset( $parent ) ) {

			$fileClass = $this->fileService->getModelClass();

			$dataProvider = $this->modelService->getPageByParent( $parent->id, $parentType );

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'parent' => $parent,
				'visibilityMap' => $fileClass::$visibilityMap,
				'filterVisibilityMap' => $fileClass::$filterVisibilityMap,
				'typeMap' => $fileClass::$typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid ) {

		$parent = $this->parentService->getById( $pid );

		if( isset( $parent ) ) {

			$modelClass = $this->modelService->getModelClass();
			$fileClass	= $this->fileService->getModelClass();
			$parentType	= $this->parentService->getParentType();

			$model	= new $modelClass;
			$file	= new $fileClass;

			$file->shared = false;

			$model->active = true;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
				$file->load( Yii::$app->request->post(), $file->getClassName() ) && $file->validate() ) {

				$this->model = $this->modelService->createWithParent( $file, [
					'parentId' => $parent->id, 'parentType' => $parentType,
					'model' => $model
				]);

				if( $this->model ) {

					return $this->redirect( $this->returnUrl );
				}
			}

			return $this->render( 'create', [
				'model' => $model,
				'file' => $file,
				'parent' => $parent,
				'visibilityMap' => $fileClass::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id, $pid ) {

		$fileClass = $this->fileService->getModelClass();

		$model		= $this->modelService->getById( $id );
		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $model ) && isset( $parent ) && $model->isParentValid( $parent->id, $parentType ) ) {

			$file = $this->fileService->getById( $model->modelId );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
				$file->load( Yii::$app->request->post(), $file->getClassName() ) &&
				$model->validate() && $file->validate() ) {

				$model->type = $file->type;

				$this->fileService->saveFile( $file, [ 'admin' => true ] );

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'model' => $model,
				'file' => $file,
				'parent' => $parent,
				'visibilityMap' => $fileClass::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $pid ) {

		$fileClass = $this->fileService->getModelClass();

		$model		= $this->modelService->getById( $id );
		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $model ) && isset( $parent ) && $model->isParentValid( $parent->id, $parentType ) ) {

			$file = $this->fileService->getById( $model->modelId );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) &&
				$file->load( Yii::$app->request->post(), $file->getClassName() ) &&
				$model->validate() && $file->validate() ) {

				$this->model = $model;

				// Delete File and Mappings
				$this->fileService->delete( $file, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'delete', [
				'model' => $model,
				'file' => $file,
				'parent' => $parent,
				'visibilityMap' => $fileClass::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
