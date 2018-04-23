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

	protected $metaService;

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->crudPermission	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'siteService' );
		$this->metaService		= Yii::$app->factory->get( 'siteMetaService' );
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
					'index'	 => [ 'permission' => $this->crudPermission ],
					'update'  => [ 'permission' => $this->crudPermission ]
				]
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'index'	 => [ 'post' ],
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

		$settings		= $this->modelService->getMetaMapByMetaType( Yii::$app->core->site, $type );
		$fieldsMap		= FormUtil::fillFromModelMeta( "config-$type", CoreGlobal::TYPE_SYSTEM, $settings );
		$model			= new GenericForm( [ 'fields' => $fieldsMap ] );

		$htmlContent	= $this->renderPartial( '@cmsgears/module-core/admin/views/settings/info', [
								'fieldsMap' => $fieldsMap,
								'type' => $type,
								'model' => $model
							]);

		return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $htmlContent );
	}

	public function actionUpdate( $type ) {

		$settings		= $this->modelService->getMetaMapByMetaType( Yii::$app->core->site, $type );

		if( count( $settings )  > 0 ) {
		
			$fieldsMap		= FormUtil::fillFromModelMeta( "config-$type", CoreGlobal::TYPE_SYSTEM, $settings );
			$model			= new GenericForm( [ 'fields' => $fieldsMap ] );

			if( $model->load( Yii::$app->request->post(), "setting$type" ) && $model->validate() ) {

				$settings	= FormUtil::getModelMetas( $model, $settings );

				$this->metaService->updateMultiple( $settings, [ 'parent' => Yii::$app->core->site ] );

				$data		= [];

				foreach ( $settings as $key => $value ) {

					$data[]	= $value->getFieldInfo();
				}

				return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
			}

			return AjaxUtil::generateFailure( Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_REQUEST ) );
			
		}
		else {

			$settings	= Yii::$app->request->post( "setting$type");

			$siteId		= Yii::$app->core->getSiteId();

			$models		= [];
			$data		= [];

 			foreach( $settings as $key => $value ) {

				$model				= new $this->metaService->modelClass;
				$model->modelId		= $siteId;
				$model->name		= $key;
				$model->value		= $value;

				if( $value == '1' ) {

					$model->valueType	= 'flag';
				}
				else {

					$model->valueType	= 'text';
				}

				$model->type		= $type;
				$model->label		= $key;

				if( $model->validate() ) {

					$this->metaService->create( $model );

					$models[] = $model;
				}
			}

			foreach ( $models as $key => $value ) {

				$data[]	= $value->getFieldInfo();
			}

			return AjaxUtil::generateSuccess( Yii::$app->coreMessage->getMessage( CoreGlobal::MESSAGE_REQUEST ), $data );
		}
	}
}
