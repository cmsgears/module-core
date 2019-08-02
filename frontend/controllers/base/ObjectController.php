<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\frontend\controllers\base;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\behaviors\ActivityBehavior;

/**
 * ObjectController provides actions specific to object model.
 *
 * @since 1.0.0
 */
abstract class ObjectController extends \cmsgears\core\frontend\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;
	protected $templateType;

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->type			= CoreGlobal::TYPE_SITE;
		$this->templateType = CoreGlobal::TYPE_SITE;

		// Permission
		$this->crudPermission = CoreGlobal::PERM_USER;

		// Services
		$this->modelService = Yii::$app->factory->get( 'objectService' );

		$this->templateService = Yii::$app->factory->get( 'templateService' );
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
					'index'	 => [ 'permission' => $this->crudPermission ],
					'all'  => [ 'permission' => $this->crudPermission ],
					'create'  => [ 'permission' => $this->crudPermission ],
					'update'  => [ 'permission' => $this->crudPermission ],
					'delete'  => [ 'permission' => $this->crudPermission ],
					'gallery' => [ 'permission' => $this->crudPermission ],
					'data' => [ 'permission' => $this->crudPermission ],
					'attributes' => [ 'permission' => $this->crudPermission ],
					'config' => [ 'permission' => $this->crudPermission ],
					'settings' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'index' => [ 'get', 'post' ],
					'all'  => [ 'get' ],
					'create'  => [ 'get', 'post' ],
					'update'  => [ 'get', 'post' ],
					'delete'  => [ 'get', 'post' ],
					'gallery'  => [ 'get', 'post' ],
					'data'  => [ 'get', 'post' ],
					'attributes'  => [ 'get', 'post' ],
					'config'  => [ 'get', 'post' ],
					'settings'  => [ 'get', 'post' ]
				]
			],
			'activity' => [
				'class' => ActivityBehavior::class,
				'admin' => true,
				'create' => [ 'create' ],
				'update' => [ 'update' ],
				'delete' => [ 'delete' ]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'gallery' => [ 'class' => 'cmsgears\core\common\actions\regular\gallery\Browse' ],
			'data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Form' ],
			'attributes' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Form' ],
			'config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Form' ],
			'settings' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Form' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ObjectController ----------------------

	public function actionAll( $pid = null, $ptype = null ) {

		$modelClass = $this->modelService->getModelClass();

		$dataProvider = null;

		if( isset( $pid ) && isset( $ptype ) ) {

		$this->modelService->getPageByType( $this->type );
		}

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass;

		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= $this->type;

		$avatar	= File::loadFile( null, 'Avatar' );
		$banner	= File::loadFile( null, 'Banner' );
		$video	= File::loadFile( null, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video ] );

			return $this->redirect( 'all' );
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $modelClass::$statusMap,
			'templatesMap' => $templatesMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$avatar	= File::loadFile( $model->avatar, 'Avatar' );
			$banner	= File::loadFile( $model->banner, 'Banner' );
			$video	= File::loadFile( $model->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner, 'video' => $video ] );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'update', [
				'model' => $model,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$statusMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model, [ 'admin' => true ] );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409, Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'delete', [
				'model' => $model,
				'avatar' => $model->avatar,
				'banner' => $model->banner,
				'video' => $model->video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $modelClass::$statusMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
