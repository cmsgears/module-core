<?php
namespace cmsgears\core\admin\controllers\apix\sites;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\utilities\AjaxUtil;

class MemberController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------
	private $userService;
	
	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		// Permission
		$this->crudPermission	= CoreGlobal::PERM_CORE;

		// Services
		$this->userService		= Yii::$app->factory->get( 'userService' );
		$this->modelService		= Yii::$app->factory->get( 'siteMemberService' );
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
					'bulk' => [ 'permission' => $this->crudPermission ],
					'delete' => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'bulk' => [ 'post' ],
					'delete' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	public function actions() {

		return [
			'bulk' => [ 'class' => 'cmsgears\core\common\actions\grid\Bulk' ],
			'delete' => [ 'class' => 'cmsgears\core\common\actions\grid\Delete' ]
		];
	}

	public function actionMember() {

		$name	= Yii::$app->request->post( 'name' );
		$siteId	= Yii::$app->request->get( 'siteId' );

		$data	= [];

		$data			= $this->userService->getByUsername( $name );
		$siteMember		= $this->modelService->findBySiteIdUserId( $siteId, $data->id );

		if( !isset( $siteMember ) ){
			
			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}
		
		$data	= [];
		// Trigger Ajax Success
		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		
	}
	
	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CityController ------------------------

}
