<?php
namespace cmsgears\modules\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;
use cmsgears\modules\core\admin\config\AdminGlobalCore;

use cmsgears\modules\core\common\models\entities\Permission;
use cmsgears\modules\core\common\models\entities\Config;

use cmsgears\modules\core\admin\services\OptionService;
use cmsgears\modules\core\admin\services\ConfigService;

use common\utilities\MessageUtil;

class SettingsController extends BaseController {

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
	}

	public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'permissions' => [
	                'index'  => Permission::PERM_SETTINGS,
					'update' => Permission::PERM_SETTINGS
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => ['get'],
	                'update' => ['get']
                ]
            ]
        ];
    }

    public function actionIndex( $type ) {

		$settingType	= OptionService::findByCategoryNameKey( CoreGlobal::CATEGORY_CONFIG_TYPE, ucfirst( $type ) );
		$settings		= null;
		
		if( isset( $settingType ) ) {

    		$settings 	= ConfigService::findByType( $settingType->getValue() );
		}
		
	    return $this->render('index', [
	        'settings' => $settings,
	        'type' => $type
	    ]);
    }

	public function actionUpdate( $type ) {
		
		$settingType	= OptionService::findByCategoryNameKey( CoreGlobal::CATEGORY_CONFIG_TYPE, ucfirst( $type ) );
    	$settings		= null;

		if( isset( $settingType ) ) {

    		$settings 	= ConfigService::findByType( $settingType->getValue() );
		}
		
	    return $this->render('update', [
	        'settings' => $settings,
	        'type' => $type
	    ]);
    }
}
?>