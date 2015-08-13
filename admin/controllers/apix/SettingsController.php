<?php
namespace cmsgears\core\admin\controllers\apix;

use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelMeta;

use cmsgears\core\admin\services\SiteService;

use cmsgears\core\admin\controllers\BaseController;

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

	public function actionUpdate() {

		$meta	= new ModelMeta();

		$meta->setScenario( "update" );

		if( $meta->load( Yii::$app->request->post(), "ModelMeta" ) ) {

			if( SiteService::updateMeta( $meta ) ) {

				return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $meta );
			}
		}

		return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
	}
}

?>