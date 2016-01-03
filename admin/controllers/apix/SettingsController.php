<?php
namespace cmsgears\core\admin\controllers\apix;

use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelMeta;
use cmsgears\core\common\models\forms\GenericForm;

use cmsgears\core\common\services\ModelAttributeService;
use cmsgears\core\admin\services\SiteService;

use cmsgears\core\common\utilities\FormUtil;
use cmsgears\core\common\utilities\AjaxUtil;

class SettingsController extends \cmsgears\core\admin\controllers\base\Controller {

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	// yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
	                'index'  => [ 'permission' => CoreGlobal::PERM_CORE ],
	                'update'  => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index'  => [ 'post' ],
	                'update'  => [ 'post' ]
                ]
            ]
        ];
    }

	// SettingsController ----------------

    public function actionIndex( $type ) {

		$settings 		= SiteService::getAttributeMapBySlugType( Yii::$app->cmgCore->getSiteSlug(), $type );
		$fieldsMap		= FormUtil::fillFromModelAttribute( "config-$type", $settings );
		$model			= new GenericForm( [ 'fields' => $fieldsMap ] );

	    $htmlContent	= $this->renderPartial( '@cmsgears/module-core/admin/views/settings/info', [
						        'fieldsMap' => $fieldsMap,
						        'type' => $type,
						        'model' => $model
						    ]);

		return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $htmlContent );
    }

	public function actionUpdate( $type ) {

		$settings 		= SiteService::getAttributeMapBySlugType( Yii::$app->cmgCore->getSiteSlug(), $type );
		$fieldsMap		= FormUtil::fillFromModelAttribute( "config-$type", $settings );
		$model			= new GenericForm( [ 'fields' => $fieldsMap ] );

		if( $model->load( Yii::$app->request->post(), "setting$type" ) && $model->validate() ) {

			$settings	= FormUtil::getModelAttributes( $model, $settings );

			ModelAttributeService::updateAll( $settings );

			$data		= [];

			foreach ( $settings as $key => $value ) {
				
				$data[]	= $value->getFieldInfo();
			}
			
			return AjaxUtil::generateSuccess( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

	    return AjaxUtil::generateFailure( Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
    }
}

?>