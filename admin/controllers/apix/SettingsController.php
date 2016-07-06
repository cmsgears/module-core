<?php
namespace cmsgears\core\admin\controllers\apix;

use \Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\forms\GenericForm;
use cmsgears\core\common\models\mappers\ModelMeta;

use cmsgears\core\common\utilities\FormUtil;
use cmsgears\core\common\utilities\AjaxUtil;

class SettingsController extends \cmsgears\core\admin\controllers\base\Controller {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	protected $attributeService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission 		= CoreGlobal::PERM_CORE;
		$this->modelService			= Yii::$app->factory->get( 'siteService' );
		$this->attributeService		= Yii::$app->factory->get( 'siteAttributeService' );
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
	                'index'  => [ 'permission' => $this->crudPermission ],
	                'update'  => [ 'permission' => $this->crudPermission ]
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

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SettingsController --------------------

    public function actionIndex( $type ) {

		$settings 		= $this->modelService->getAttributeMapBySlugType( Yii::$app->core->getSiteSlug(), $type );
		$fieldsMap		= FormUtil::fillFromModelAttribute( "config-$type", CoreGlobal::TYPE_SYSTEM, $settings );
		$model			= new GenericForm( [ 'fields' => $fieldsMap ] );

	    $htmlContent	= $this->renderPartial( '@cmsgears/module-core/admin/views/settings/info', [
						        'fieldsMap' => $fieldsMap,
						        'type' => $type,
						        'model' => $model
						    ]);

		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $htmlContent );
    }

	public function actionUpdate( $type ) {

		$settings 		= $this->modelService->getAttributeMapBySlugType( Yii::$app->core->getSiteSlug(), $type );
		$fieldsMap		= FormUtil::fillFromModelAttribute( "config-$type", CoreGlobal::TYPE_SYSTEM, $settings );
		$model			= new GenericForm( [ 'fields' => $fieldsMap ] );

		if( $model->load( Yii::$app->request->post(), "setting$type" ) && $model->validate() ) {

			$settings	= FormUtil::getModelAttributes( $model, $settings );

			$this->attributeService->updateMultiple( $settings, [ 'parent' => Yii::$app->core->site ] );

			$data		= [];

			foreach ( $settings as $key => $value ) {

				$data[]	= $value->getFieldInfo();
			}

			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}

	    return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
    }
}
