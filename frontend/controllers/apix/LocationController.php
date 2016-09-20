<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\CodeGenUtil;
use cmsgears\core\common\utilities\AjaxUtil;

class LocationController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $provinceService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->provinceService	= Yii::$app->factory->get( 'provinceService' );
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
					// add actions here
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'provinceMap' => [ 'post' ],
					'provinceOptions' => [ 'post' ]
				]
			]
		];
	}

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// LocationController --------------------

	public function actionProvinceMap() {

		$countryId	= Yii::$app->request->post( 'countryId' );

		if( isset( $countryId ) && $countryId > 0 ) {

			$provinceMap	= $this->provinceService->getListByCountryId( $countryId );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $provinceMap );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionProvinceOptions() {

		$countryId	= Yii::$app->request->post( 'countryId' );

		if( isset( $countryId ) && $countryId > 0 ) {

			$provinceOptions	= $this->provinceService->getListByCountryId( $countryId );
			$provinceOptions	= CodeGenUtil::generateSelectOptionsIdName( $provinceOptions );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $provinceOptions );
		}

		// Trigger Ajax Failure
		return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}
