<?php
namespace cmsgears\core\admin\controllers\country;

// Yii Imports
use \Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\base\Exception;
use yii\web\HttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class ProvinceController extends \cmsgears\core\admin\controllers\base\CrudController {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

 	public function init() {

        parent::init();

		$this->crudPermission 	= CoreGlobal::PERM_CORE;
		$this->modelService		= Yii::$app->factory->get( 'provinceService' );
		$this->sidebar 			= [ 'parent' => 'sidebar-core', 'child' => 'country' ];

		$this->returnUrl		= Url::previous( 'provinces' );
		$this->returnUrl		= isset( $this->returnUrl ) ? $this->returnUrl : Url::toRoute( [ '/core/country/province/all' ], true );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Controller ----

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// ProvinceController --------------------

	public function actionAll( $cid ) {

		$dataProvider	= $this->modelService->getPage( [ 'conditions' => [ 'countryId' => $cid ] ] );

		Url::remember( [ "country/province/all?cid=$cid" ], 'provinces' );

		return $this->render( 'all', [
			'dataProvider' => $dataProvider,
			'countryId' => $cid
		]);
	}

	public function actionCreate( $cid ) {

		$modelClass			= $this->modelService->getModelClass();
		$model				= new $modelClass;
		$model->countryId	= $cid;

		if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

			$this->modelService->create( $model );

			return $this->redirect( $this->returnUrl );
		}

    	return $this->render( 'create', [
    		'model' => $model
    	]);
	}
}

?>