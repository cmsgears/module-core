<?php
namespace cmsgears\modules\core\admin\controllers\apix;

use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\admin\config\AdminGlobalCore;
use cmsgears\modules\cms\common\config\CMSGlobal;

use cmsgears\modules\core\common\models\entities\Config;
use cmsgears\modules\core\common\models\entities\Permission;

use cmsgears\modules\core\admin\services\ConfigService;

use cmsgears\modules\core\admin\controllers\BaseController;

use cmsgears\modules\core\common\utilities\MessageUtil;
use cmsgears\modules\core\common\utilities\AjaxUtil;

class SettingsController extends BaseController {

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'permissions' => [
	                'update'  => Permission::PERM_SETTINGS
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

	public function actionUpdate( $id ) {
			
		$model		= new Config();

		$model->setScenario( "update" );
		
		if( $model->load( Yii::$app->request->post(), "Config" ) ) {
				
			if( ConfigService::update( $model, $id ) ) {
				
				AjaxUtil::generateSuccess( MessageUtil::getMessage( CoreGlobal::MESSAGE_REQUEST ), $model );
			}	
		}
		
		AjaxUtil::generateFailure( MessageUtil::getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>