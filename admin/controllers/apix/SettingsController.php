<?php
namespace cmsgears\core\admin\controllers\apix;

use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Config;

use cmsgears\core\admin\services\ConfigService;

use cmsgears\core\admin\controllers\BaseController;

use cmsgears\core\common\components\MessageDbCore;
use cmsgears\core\common\utilities\AjaxUtil;

class SettingsController extends BaseController {

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'update'  => ['post']
                ]
            ]
        ];
    }
	
	// SettingsController ----------------

	public function actionUpdate( $id ) {

		$config	= Config::findById( $id );
		
		if( isset( $config ) ) {

			$config->setScenario( "update" );

			if( $config->load( Yii::$app->request->post( "Config" ), "" ) ) {

				if( ConfigService::update( $config ) ) {

					AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::MESSAGE_REQUEST ), $config );
				}	
			}

			AjaxUtil::generateFailure( Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_REQUEST ) );
		}
	}
}

?>