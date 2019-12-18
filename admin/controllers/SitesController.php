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

use cmsgears\core\common\models\resources\File;

/**
 * SitesController provides actions specific to site model.
 *
 * @since 1.0.0
 */
class SitesController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $themeService;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Config
		$this->apixBase	= 'core/sites';

		// Services
		$this->modelService	= Yii::$app->factory->get( 'siteService' );

		$this->themeService	= Yii::$app->factory->get( 'themeService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-core', 'child' => 'site' ];

		// Return Url
		$this->returnUrl = Url::previous( 'sites' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/sites/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ]
			],
			'all' => [ [ 'label' => 'Sites' ] ],
			'create' => [ [ 'label' => 'Sites', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Sites', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Sites', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
		];
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SitesController------------------------

	public function actionAll( $config = [] ) {

		Url::remember( Yii::$app->request->getUrl(), 'sites' );

		$dataProvider = $this->modelService->getPage();

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate( $config = [] ) {

		$model	= $this->modelService->getModelObject();
		$avatar	= File::loadFile( $model->avatar, 'Avatar' );
		$banner	= File::loadFile( $model->banner, 'Banner' );

		$model->primary = true;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner ] );

			return $this->redirect( 'all' );
		}

		$themesMap = $this->themeService->getIdNameMap( [ 'default' => true ] );

		return $this->render( 'create', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'themesMap' => $themesMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			$avatar = File::loadFile( $model->avatar, 'Avatar' );
			$banner = File::loadFile( $model->banner, 'Banner' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true, 'avatar' => $avatar, 'banner' => $banner ] );

				return $this->redirect( $this->returnUrl );
			}

			$themesMap = $this->themeService->getIdNameMap( [ 'default' => true ] );

			// Render view
			return $this->render( 'update', [
				'model' => $model,
				'avatar' => $avatar,
				'banner' => $banner,
				'themesMap' => $themesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionDelete( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->model = $model;

					$this->modelService->delete( $model, [ 'admin' => true ] );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$themesMap = $this->themeService->getIdNameMap( [ 'default' => true ] );

			// Render view
			return $this->render( 'delete', [
				'model' => $model,
				'avatar' => $model->avatar,
				'banner' => $model->banner,
				'themesMap' => $themesMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
