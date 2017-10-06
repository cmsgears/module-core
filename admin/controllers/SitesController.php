<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

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
		$this->crudPermission	= CoreGlobal::PERM_CORE;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'siteService' );

		$this->themeService		= Yii::$app->factory->get( 'themeService' );

		// Sidebar
		$this->sidebar			= [ 'parent' => 'sidebar-core', 'child' => 'site' ];

		// Return Url
		$this->returnUrl		= Url::previous( 'sites' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/sites/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs		= [
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

	public function actionAll() {

		Url::remember( Yii::$app->request->getUrl(), 'sites' );

		$dataProvider = $this->modelService->getPage();

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider
		]);
	}

	public function actionCreate() {

		$modelClass	= $this->modelService->getModelClass();
		$model		= new $modelClass;
		$avatar		= File::loadFile( $model->avatar, 'Avatar' );
		$banner		= File::loadFile( $model->banner, 'Banner' );

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

			$this->modelService->create( $model, [ 'avatar' => $avatar, 'banner' => $banner ] );

			return $this->redirect( "update?id=$model->id" );
		}

		$themesMap = $this->themeService->getIdNameMap();

		return $this->render( 'create', [
			'model' => $model,
			'avatar' => $avatar,
			'banner' => $banner,
			'themesMap' => $themesMap
		]);
	}

	public function actionUpdate( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			$avatar		= File::loadFile( $model->avatar, 'Avatar' );
			$banner		= File::loadFile( $model->banner, 'Banner' );

			if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

				$this->modelService->update( $model, [ 'avatar' => $avatar, 'banner' => $banner ] );

				return $this->refresh();
			}

			$themesMap = $this->themeService->getIdNameMap();

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

	public function actionDelete( $id ) {

		// Find Model
		$model	= $this->modelService->getById( $id );

		// Delete if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				try {

					$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$themesMap = $this->themeService->getIdNameMap();

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
