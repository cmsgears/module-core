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

use cmsgears\core\common\models\resources\File;

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
		$this->setViewPath( '@cmsgears/module-core/admin/views/mfile' );

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

			$dataProvider = $this->modelService->getPageByParent( $parent->id, $parentType );

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'parent' => $parent,
				'visibilityMap' => File::$visibilityMap,
				'typeMap' => File::$typeMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid ) {

		$parent = $this->parentService->getById( $pid );

		if( isset( $parent ) ) {

			$fileClass	= $this->fileService->getModelClass();
			$parentType	= $this->parentService->getParentType();

			$file = new $fileClass;

			$file->backend	= true;
			$file->frontend	= false;
			$file->shared	= false;

			if( $file->load( Yii::$app->request->post(), $file->getClassName() ) && $file->validate() ) {

				$this->model = $this->modelService->createWithParent( $file, [ 'parentId' => $parent->id, 'parentType' => $parentType ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'create', [
				'file' => $file,
				'parent' => $parent,
				'visibilityMap' => File::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionUpdate( $id, $pid ) {

		$model		= $this->modelService->getById( $id );
		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $model ) && isset( $parent ) && $model->isParentValid( $parent->id, $parentType ) ) {

			$file = $this->fileService->getById( $model->modelId );

			if( $file->load( Yii::$app->request->post(), $file->getClassName() ) && $file->validate() ) {

				$this->fileService->saveFile( $file, [ 'admin' => true ] );

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'file' => $file,
				'model' => $model,
				'parent' => $parent,
				'visibilityMap' => File::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $pid ) {

		$model		= $this->modelService->getById( $id );
		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $model ) && isset( $parent ) && $model->isParentValid( $parent->id, $parentType ) ) {

			$file = $this->fileService->getById( $model->modelId );

			if( $file->load( Yii::$app->request->post(), $file->getClassName() ) && $file->validate() ) {

				$this->model = $model;

				// Delete File and Mappings
				$this->fileService->delete( $file, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'delete', [
				'file' => $file,
				'model' => $model,
				'parent' => $parent,
				'visibilityMap' => File::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
