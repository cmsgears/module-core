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
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\Gallery;

/**
 * GalleryController provide actions specific to gallery management.
 *
 * @since 1.0.0
 */
class GalleryController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;

	protected $templateService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_GALLERY_ADMIN;

		// Config
		$this->type		= CoreGlobal::TYPE_SITE;
		$this->apixBase	= 'core/gallery';

		// Services
		$this->modelService = Yii::$app->factory->get( 'galleryService' );

		$this->templateService = Yii::$app->factory->get( 'templateService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-file', 'child' => 'gallery' ];

		// Return Url
		$this->returnUrl = Url::previous( 'galleries' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/gallery/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Galleries' ] ],
			'create' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ],
			'items' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Items' ] ],
			'data' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Data' ] ],
			'attributes' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Attributes' ] ],
			'config' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Config' ] ],
			'settings' => [ [ 'label' => 'Galleries', 'url' => $this->returnUrl ], [ 'label' => 'Settings' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors	= parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'items' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'data' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'attributes' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'config' ] = [ 'permission' => $this->crudPermission ];
		$behaviors[ 'rbac' ][ 'actions' ][ 'settings' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'items' ] = [ 'get' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'data' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'attributes' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'config' ] = [ 'get', 'post' ];
		$behaviors[ 'verbs' ][ 'actions' ][ 'settings' ] = [ 'get', 'post' ];

		return $behaviors;
	}

	// yii\base\Controller ----

	public function actions() {

		$actions = parent::actions();

		$actions[ 'data' ] = [ 'class' => 'cmsgears\core\common\actions\data\data\Form' ];
		$actions[ 'attributes' ] = [ 'class' => 'cmsgears\core\common\actions\data\attributes\Form' ];
		$actions[ 'config' ] = [ 'class' => 'cmsgears\core\common\actions\data\config\Form' ];
		$actions[ 'settings' ] = [ 'class' => 'cmsgears\core\common\actions\data\setting\Form' ];

		return $actions;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

	public function actionAll( $config = [] ) {

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => Gallery::$visibilityMap,
			'statusMap' => Gallery::$subStatusMap,
			'filterStatusMap' => Gallery::$filterSubStatusMap
		]);
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->siteId	= Yii::$app->core->siteId;
		$model->type	= $this->type;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [ 'admin' => true ] );

			if( $this->model ) {

				return $this->redirect( 'all' );
			}
		}

		$templatesMap = $this->templateService->getIdNameMapByType( CoreGlobal::TYPE_GALLERY, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'visibilityMap' => Gallery::$visibilityMap,
			'statusMap' => Gallery::$subStatusMap,
			'templatesMap' => $templatesMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			$template = $model->template;

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [
					'admin' => true, 'oldTemplate' => $template
				]);

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( CoreGlobal::TYPE_GALLERY, [ 'default' => true ] );

			return $this->render( 'update', [
				'model' => $model,
				'visibilityMap' => Gallery::$visibilityMap,
				'statusMap' => Gallery::$subStatusMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) ) {

				$this->model = $this->modelService->delete( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( CoreGlobal::TYPE_GALLERY, [ 'default' => true ] );

			return $this->render( 'delete', [
				'model' => $model,
				'visibilityMap' => Gallery::$visibilityMap,
				'statusMap' => Gallery::$subStatusMap,
				'templatesMap' => $templatesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionItems( $id ) {

		// Find Model
		$gallery = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $gallery ) ) {

			return $this->render( 'items', [
				'gallery' => $gallery,
				'items' => $gallery->files
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
