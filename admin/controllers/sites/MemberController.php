<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\admin\controllers\sites;

// Yii Imports
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * MemberController provides actions specific to site members.
 *
 * @since 1.0.0
 */
class MemberController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $roleType;
	protected $superRoleId;

	// Private ----------------

	private $userService;
	private $roleService;

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission = CoreGlobal::PERM_CORE;

		// Config
		$this->apixBase = 'core/sites/member';

		// Services
		$this->modelService	= Yii::$app->factory->get( 'siteMemberService' );

		$this->userService	= Yii::$app->factory->get( 'userService' );
		$this->roleService	= Yii::$app->factory->get( 'roleService' );

		// Sidebar
		$this->sidebar = [ 'parent' => 'sidebar-core', 'child' => 'site' ];

		$this->roleType = 'system';

		$this->superRoleId	= $this->roleService->getBySlugType( 'super-admin', $this->roleType );
		$this->superRoleId	= isset( $this->superRoleId ) ? $this->superRoleId->id : null;

		// Return Url
		$this->returnUrl = Url::previous( 'site-members' );
		$this->returnUrl = isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/sites/members/all' ], true );

		// All Url
		$allUrl = Url::previous( 'sites' );
		$allUrl = isset( $allUrl ) ? $allUrl : Url::toRoute( [ '/core/sites/all' ], true );

		// Breadcrumbs
		$this->breadcrumbs = [
			'base' => [
				[ 'label' => 'Home', 'url' => Url::toRoute( '/dashboard' ) ],
				[ 'label' => 'Sites', 'url' =>  $allUrl ]
			],
			'all' => [ [ 'label' => 'Site Members' ] ],
			'create' => [ [ 'label' => 'Site Members', 'url' => $this->returnUrl ], [ 'label' => 'Add' ] ],
			'update' => [ [ 'label' => 'Site Members', 'url' => $this->returnUrl ], [ 'label' => 'Update' ] ],
			'delete' => [ [ 'label' => 'Site Members', 'url' => $this->returnUrl ], [ 'label' => 'Delete' ] ]
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

		Url::remember( Yii::$app->request->getUrl(), 'site-members' );

		$siteId = Yii::$app->request->get( 'sid' );

		$dataProvider = $this->modelService->getPageBySiteId( $siteId );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'siteId' => $siteId
		]);
	}

	public function actionCreate( $config = [] ) {

		$siteId = Yii::$app->request->get( 'sid' );

		$model = $this->modelService->getModelObject();

		if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

			$this->model = $this->modelService->create( $model, [ 'admin' => true ] );

			return $this->redirect( "all?sid=$siteId" );
		}

		$roleMap = $this->roleService->getIdNameMapByType( $this->roleType );

		unset( $roleMap[ $this->superRoleId ] );

		return $this->render( 'create', [
			'model' => $model,
			'siteId' => $siteId,
			'roleMap' => $roleMap
		]);
	}

	public function actionUpdate( $id, $config = [] ) {

		// Find Model
		$model = $this->modelService->getById( $id );

		// Update if exist
		if( isset( $model ) ) {

			if( $model->load( Yii::$app->request->post(), $model->getClassName() ) && $model->validate() ) {

				$this->model = $this->modelService->update( $model, [ 'admin' => true ] );

				return $this->redirect( $this->returnUrl );
			}

			$roleMap = $this->roleService->getIdNameMapByType( $this->roleType );

			unset( $roleMap[ $this->superRoleId ] );

			// Render view
			return $this->render( 'update', [
				'model' => $model,
				'roleMap' => $roleMap
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

					$this->modelService->delete( $model );

					return $this->redirect( $this->returnUrl );
				}
				catch( Exception $e ) {

					throw new HttpException( 409,  Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_DEPENDENCY )  );
				}
			}

			$roleMap = $this->roleService->getIdNameMapByType( $this->roleType );

			// Render view
			return $this->render( 'delete', [
				'model' => $model,
				'roleMap' => $roleMap
			]);
		}

		// Model not found
		throw new NotFoundHttpException( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

}
