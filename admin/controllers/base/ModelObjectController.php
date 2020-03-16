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
 * ModelObjectController provides actions specific to model objects.
 *
 * @since 1.0.0
 */
abstract class ModelObjectController extends Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	public $title;

	// Protected --------------

	protected $type;

	protected $parentService;

	protected $objectService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/mobject' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		$this->modelService = Yii::$app->factory->get( 'modelObjectService' );

		$this->objectService = Yii::$app->factory->get( 'objectService' );
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
					'delete' => [ 'permission' => $this->crudPermission ],
					'review' => [ 'permission' => $this->crudPermission ],
					'gallery' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all' => [ 'get' ],
					'create' => [ 'get', 'post' ],
					'update' => [ 'get', 'post' ],
					'delete' => [ 'get', 'post' ],
					'review' => [ 'get', 'post' ],
					'gallery' => [ 'get' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'gallery' => [
				'class' => 'cmsgears\core\common\actions\regular\gallery\Browse',
				'modelService' => $this->objectService
			]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ModelObjectController -----------------

	public function actionAll( $pid ) {

		$objectClass = $this->objectService->getModelClass();

		$parent		= $this->parentService->getById( $pid );
		$parentType	= $this->parentService->getParentType();

		if( isset( $parent ) ) {

			$dataProvider = $this->modelService->getPageByParent( $parent->id, $parentType );

			return $this->render( 'all', [
				'dataProvider' => $dataProvider,
				'parent' => $parent,
				'statusMap' => $objectClass::$statusMap,
				'visibilityMap' => $objectClass::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $pid ) {

		$parent = $this->parentService->getById( $pid );

		if( isset( $parent ) ) {

			$objectClass	= $this->objectService->getModelClass();
			$parentType		= $this->parentService->getParentType();

			$object	= new $objectClass;
			$model	= $this->modelService->getModelObject();

			$object->siteId	= Yii::$app->core->siteId;
			$object->type	= $this->type;

			$avatar	= File::loadFile( null, 'Avatar' );
			$banner	= File::loadFile( null, 'Banner' );
			$video	= File::loadFile( null, 'Video' );

			if( $object->load( Yii::$app->request->post(), $object->getClassName() ) && $object->validate() ) {

				$this->model = $this->modelService->createWithParent( $object, [
					'parentId' => $parent->id, 'parentType' => $parentType, 'type' => $this->type,
					'avatar' => $avatar, 'banner' => $banner, 'video' => $video
				]);

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'create', [
				'object' => $object,
				'model' => $model,
				'parent' => $parent,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'statusMap' => $objectClass::$statusMap,
				'visibilityMap' => $objectClass::$visibilityMap
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

			$objectClass = $this->objectService->getModelClass();

			$object = $this->objectService->getById( $model->modelId );

			$avatar	= File::loadFile( $object->avatar, 'Avatar' );
			$banner	= File::loadFile( $object->banner, 'Banner' );
			$video	= File::loadFile( $object->video, 'Video' );

			if( $object->load( Yii::$app->request->post(), $object->getClassName() ) && $object->validate() ) {

				$this->objectService->update( $object, [
					'admin' => true,
					'avatar' => $avatar, 'banner' => $banner, 'video' => $video
				]);

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'update', [
				'object' => $object,
				'model' => $model,
				'parent' => $parent,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'statusMap' => $objectClass::$statusMap,
				'visibilityMap' => $objectClass::$visibilityMap
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

			$modelClass = $this->modelService->getModelClass();

			$object = $this->objectService->getById( $model->modelId );

			if( $object->load( Yii::$app->request->post(), $object->getClassName() ) && $object->validate() ) {

				$this->model = $model;

				// Delete Object and Mappings
				$this->objectService->delete( $object, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			return $this->render( 'delete', [
				'object' => $object,
				'model' => $model,
				'parent' => $parent,
				'avatar' => $object->avatar,
				'banner' => $object->banner,
				'video' => $object->video,
				'statusMap' => $objectClass::$statusMap,
				'visibilityMap' => $objectClass::$visibilityMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
