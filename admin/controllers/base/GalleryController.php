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

use cmsgears\core\common\models\resources\Gallery;

use cmsgears\core\admin\controllers\base\CrudController;

/**
 * GalleryController provide actions specific to gallery management.
 *
 * @since 1.0.0
 */
abstract class GalleryController extends CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $type;
	protected $templateType;

	protected $templateService;

	// For Parent with direct attachment
	protected $parentUrl;
	protected $modelContent;
	protected $parentService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Views
		$this->setViewPath( '@cmsgears/module-core/admin/views/gallery' );

		// Permission
		$this->crudPermission = CoreGlobal::PERM_GALLERY_ADMIN;

		// Config
		$this->templateType	= CoreGlobal::TYPE_GALLERY;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'galleryService' );
		$this->templateService	= Yii::$app->factory->get( 'templateService' );

		// Notes: Configure sidebar and returnUrl exclusively in child classes. We can also change type and templateType in child classes in case gallery is associated with model.
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	public function behaviors() {

		$behaviors	= parent::behaviors();

		$behaviors[ 'rbac' ][ 'actions' ][ 'items' ] = [ 'permission' => $this->crudPermission ];

		$behaviors[ 'verbs' ][ 'actions' ][ 'items' ] = [ 'get' ];

		return $behaviors;
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// GalleryController ---------------------

	public function actionAll( $config = [] ) {

		$dataProvider = $this->modelService->getPageByType( $this->type );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'visibilityMap' => Gallery::$visibilityMap,
			'statusMap' => Gallery::$statusMap
		]);
	}

	public function actionDirect( $pid = null ) {

		if( isset( $pid ) && isset( $this->parentService ) ) {

			$parent = $this->parentService->getById( $pid );
			$type	= $this->parentService->getParentType();
			$model	= $this->modelContent ? $parent->modelContent->gallery : $parent->gallery;

			if( empty( $model ) ) {

				$model = $this->modelService->createByParams([
					'type' => $type, 'status' => Gallery::STATUS_ACTIVE,
					'name' => $parent->name, 'title' => $parent->name,
					'siteId' => Yii::$app->core->siteId
				]);

				if( $this->modelContent ) {

					Yii::$app->factory->get( 'modelContentService' )->linkModel( $parent->modelContent, 'galleryId', $model );
				}
				else {

					$this->parentService->linkModel( $parent, 'galleryId', $model );
				}
			}

			$items = !empty( $model->files ) ? ( count( $model->files ) == 1 ? [ $model->files ] : $model->files ) : [];

			return $this->render( 'items', [
				'parent' => $parent,
				'gallery' => $model,
				'items' => $items
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionCreate( $config = [] ) {

		$model = $this->modelService->getModelObject();

		$model->type	= $this->type;
		$model->siteId	= Yii::$app->core->siteId;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [ 'admin' => true ] );

			return $this->redirect( 'all' );
		}

		$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'visibilityMap' => Gallery::$visibilityMap,
			'statusMap' => Gallery::$statusMap,
			'templatesMap' => $templatesMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update/Render if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'update', [
				'model' => $model,
				'visibilityMap' => Gallery::$visibilityMap,
				'statusMap' => Gallery::$statusMap,
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

			$templatesMap = $this->templateService->getIdNameMapByType( $this->templateType, [ 'default' => true ] );

			return $this->render( 'delete', [
				'model' => $model,
				'visibilityMap' => Gallery::$visibilityMap,
				'statusMap' => Gallery::$statusMap,
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
