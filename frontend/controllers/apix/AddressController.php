<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\services\ProvinceService;

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

	public function actionProvinceMap( $countryid ) {

		$provinceMap	= ProvinceService::getListByCountryId( $countryid );

		return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $provinceMap );
	}

	public function actionProvinceOptions( $countryid ) {

		$provinceOptions	= ProvinceService::getListByCountryId( $countryid );
		$provinceOptions	= CodeGenUtil::generateSelectOptionsIdName( $provinceList );		

		return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $provinceOptions );
	}
}

?>