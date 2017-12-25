<?php
namespace cmsgears\core\admin\controllers\sites;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\File;

class MemberController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------
	protected $roleType;
	protected $roleSuperAdmin;
	
	// Private ----------------

	private $themeService;
	private $userService;
	private $roleService;
	
	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission	= CoreGlobal::PERM_CORE;

		// Services
		$this->modelService		= Yii::$app->factory->get( 'siteMemberService' );
		$this->userService		= Yii::$app->factory->get( 'userService' );
		$this->roleService			= Yii::$app->factory->get( 'roleService' );

		$this->themeService		= Yii::$app->factory->get( 'themeService' );

		// Sidebar
		$this->sidebar			= [ 'parent' => 'sidebar-core', 'child' => 'site' ];
		
		$this->roleSuperAdmin		= $this->roleService->getBySlug( 'super-admin', true );
		$this->roleSuperAdmin		= isset( $this->roleSuperAdmin ) ? $this->roleSuperAdmin->id : null;

		// Return Url
		$this->returnUrl		= Url::previous( 'sitesMember' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/sites/members/all' ], true );
		$this->roleType =		'system';
		// Breadcrumbs
		$this->breadcrumbs		= [
			'all' => [ [ 'label' => 'Site Member' ] ],
			'create' => [ [ 'label' => 'Site member', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Site member', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Site member', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
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

		Url::remember( Yii::$app->request->getUrl(), 'sitesMember' );
		$siteId = Yii::$app->request->get("id");

		$dataProvider = $this->modelService->getSiteMemberBySiteId( $siteId );

		return $this->render( 'all', [
			 'dataProvider' => $dataProvider,
			'siteId' => $siteId
			
		]);
	}

	public function actionCreate() {

		$siteId = Yii::$app->request->get("siteId");

		$modelClass	= $this->modelService->getModelClass();
		$model		= new $modelClass;
	
		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$user = $this->userService->getById( $model->userId );
			
			$hello = $this->modelService->create( $user, [ 'roleId' => $model->roleId, 'siteId' => $model->siteId ] );
			
			return $this->redirect( "all?id=$model->siteId" );
		}
		
		$roleMap	= $this->roleService->getIdNameMapByType( $this->roleType );
		unset( $roleMap[ $this->roleSuperAdmin ] );
		
		return $this->render( 'create', [
			'model' => $model,
			'siteId' => $siteId,
			'roleMap' => $roleMap
		]);
	}

	public function actionUpdate( $id ) {

		$siteId = Yii::$app->request->get("siteId");
		
		// Find Model
		$model	= $this->modelService->getById( $id );
		
		// Update if exist
		if( isset( $model ) ) {

			$user = $this->userService->getById( $model->userId );
			
			if( $model->load( Yii::$app->request->post(), $model->getClassName() )	&& $model->validate() ) {

				$this->modelService->update( $model, [ ] );

				return $this->refresh();
			}

			$roleMap	= $this->roleService->getIdNameMapByType( $this->roleType );
			unset( $roleMap[ $this->roleSuperAdmin ] );
		
			// Render view
			return $this->render( 'update', [
				'model' => $model,
				'user'	=> $user,
				'siteId' => $siteId,
				'roleMap' => $roleMap
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
