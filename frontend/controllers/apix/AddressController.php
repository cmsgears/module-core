<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\entities\ProvinceService;

use cmsgears\core\common\utilities\CodeGenUtil;
use cmsgears\core\common\utilities\AjaxUtil;

class AddressController extends \cmsgears\core\admin\controllers\base\Controller {

	// Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// Instance Methods --------------------------------------------

	// yii\base\Component

	public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
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

	// AddressController

	public function actionProvinceMap() {

		$countryId	= Yii::$app->request->post( 'countryId' );

		if( isset( $countryId ) && $countryId > 0 ) {

			$provinceMap	= ProvinceService::getListByCountryId( $countryId );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $provinceMap );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}

	public function actionProvinceOptions() {

		$countryId	= Yii::$app->request->post( 'countryId' );

		if( isset( $countryId ) && $countryId > 0 ) {

			$provinceOptions	= ProvinceService::getListByCountryId( $countryId );
			$provinceOptions	= CodeGenUtil::generateSelectOptionsIdName( $provinceOptions );

			// Trigger Ajax Success
			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $provinceOptions );
		}

		// Trigger Ajax Failure
        return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NOT_FOUND ) );
	}
}

?>