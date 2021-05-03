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
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

/**
 * ObjectDataController provides actions specific to object model.
 *
 * @since 1.0.0
 */
abstract class ObjectDataController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $shared;

	protected $minStatusMap;

	protected $type;
	protected $templateType;

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Config
		$this->minStatusMap	= true;
		$this->type			= CoreGlobal::TYPE_SITE;
		$this->templateType = CoreGlobal::TYPE_SITE;

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Services
		$this->modelService = Yii::$app->factory->get( 'objectDataService' );

		$this->templateService = Yii::$app->factory->get( 'templateService' );

		// Notes: Configure sidebar and returnUrl exclusively in child classes. We can also change type and templateType in child classes.
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors = parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'gallery' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'data' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'attributes' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'config' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'settings' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'gallery' ] = [ 'get' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'data' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'attributes' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'config' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'settings' ] = [ 'get', 'post' ];

		return $behaviors;
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'gallery' => [ 'class' => 'cmsgears\core\common\actions\gallery\Manage' ],
			'data' => [ 'class' => 'cmsgears\core\common\actions\data\data\Form' ],
			'attributes' => [ 'class' => 'cmsgears\core\common\actions\data\attribute\Form' ],
			'config' => [ 'class' => 'cmsgears\core\common\actions\data\config\Form' ],
			'settings' => [ 'class' => 'cmsgears\core\common\actions\data\setting\Form' ]
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ObjectDataController ------------------

	public function actionAll( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();
		$modelTable = $this->modelService->getModelTable();

		$config[ 'conditions' ][ "$modelTable.userId" ] = null;
		$config[ 'conditions' ][ "$modelTable.shared" ] = $this->shared;

		$dataProvider = $this->modelService->getPageByType( $this->type, $config );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $this->minStatusMap ? $modelClass::$minStatusMap : $modelClass::$subStatusMap,
			'filterStatusMap' => $this->minStatusMap ? $modelClass::$filterMinStatusMap : $modelClass::$filterSubStatusMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		$model = new $modelClass;

		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= $this->type;
		$model->shared	= $this->shared;

		$avatar		= File::loadFile( null, 'Avatar' );
		$banner		= File::loadFile( null, 'Banner' );
		$mbanner	= File::loadFile( null, 'MobileBanner' );
		$video		= File::loadFile( null, 'Video' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->add( $model, [
				'admin' => true, 'avatar' => $avatar, 'banner' => $banner,
				'mbanner' => $mbanner, 'video' => $video
			]);

			if( $this->model ) {

				return $this->redirect( 'all' );
			}
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		$statusMap = $this->minStatusMap ? $modelClass::$minStatusMap : $modelClass::$subStatusMap;

		return $this->render( 'create', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'video' => $video,
			'visibilityMap' => $modelClass::$visibilityMap,
			'statusMap' => $statusMap,
			'templatesMap' => $templatesMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		$modelClass = $this->modelService->getModelClass();

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$template = $model->template;

			$avatar		= File::loadFile( $model->avatar, 'Avatar' );
			$banner		= File::loadFile( $model->banner, 'Banner' );
			$mbanner	= File::loadFile( $model->mobileBanner, 'MobileBanner' );
			$video		= File::loadFile( $model->video, 'Video' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'oldTemplate' => $template,
					'avatar' => $avatar, 'banner' => $banner,
					'mbanner' => $mbanner, 'video' => $video
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			$statusMap = $this->minStatusMap ? $modelClass::$minStatusMap : $modelClass::$subStatusMap;

			return $this->render( 'update', [
				'model' => $model,
				'avatar' => $avatar,
				'banner' => $banner,
				'video' => $video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $statusMap,
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

			$statusMap = $this->minStatusMap ? $modelClass::$minStatusMap : $modelClass::$subStatusMap;

			return $this->render( 'delete', [
				'model' => $model,
				'avatar' => $model->avatar,
				'banner' => $model->banner,
				'mbanner' => $model->mobileBanner,
				'video' => $model->video,
				'visibilityMap' => $modelClass::$visibilityMap,
				'statusMap' => $statusMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
