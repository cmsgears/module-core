<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\admin\services\SiteService;

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
	                'index'  => [ 'permission' => CoreGlobal::PERM_CORE ],
					'update' => [ 'permission' => CoreGlobal::PERM_CORE ]
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
	
	// SettingsController ----------------

    public function actionIndex( $type ) {

		$settings 	= SiteService::getMetaByNameType( Yii::$app->cmgCore->getSiteName(), $type );

	    return $this->render('index', [
	        'settings' => $settings,
	        'type' => $type
	    ]);
    }

	public function actionUpdate( $type ) {

		$settings 	= SiteService::getMetaByNameType( Yii::$app->cmgCore->getSiteName(), $type );

	    return $this->render('update', [
	        'settings' => $settings,
	        'type' => $type
	    ]);
    }
}

?>