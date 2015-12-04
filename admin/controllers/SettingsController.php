<?php
namespace cmsgears\core\admin\controllers;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\GenericForm;

use cmsgears\core\admin\services\SiteService;
use cmsgears\core\admin\services\FormService;
use cmsgears\core\common\services\ModelAttributeService;

use cmsgears\core\common\utilities\FormUtil;

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
					'update' => [ 'permission' => CoreGlobal::PERM_CORE ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
	                'index' => [ 'get' ],
	                'update' => [ 'get', 'post' ]
                ]
            ]
        ];
    }

	// SettingsController ----------------

    public function actionIndex( $type ) {

		$settings 	= SiteService::getAttributeMapBySlugType( Yii::$app->cmgCore->getSiteSlug(), $type );
		$fieldsMap	= FormUtil::fillFromModelAttribute( "config-$type", $settings );

	    return $this->render( 'index', [
	        'fieldsMap' => $fieldsMap,
	        'type' => $type
	    ]);
    }

	public function actionUpdate( $type ) {

		$settings 	= SiteService::getAttributeMapBySlugType( Yii::$app->cmgCore->getSiteSlug(), $type );
		$fieldsMap	= FormUtil::fillFromModelAttribute( "config-$type", $settings );
		$model		= new GenericForm( [ 'fields' => $fieldsMap ] );

		if( $model->load( Yii::$app->request->post(), 'GenericForm' ) && $model->validate() ) {

			$settings	= FormUtil::getModelAttributes( $model, $settings );

			ModelAttributeService::updateAll( $settings );

			return $this->redirect( "index?type=$type" );
		}

	    return $this->render('update', [
	        'model' => $model,
	        'type' => $type
	    ]);
    }
}

?>