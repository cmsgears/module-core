<?php
namespace cmsgears\core\admin\controllers\base;

// Yii Imports
use \Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Template;

abstract class TemplateController extends CrudController {

    // Variables ---------------------------------------------------

    // Globals ----------------

    // Public -----------------

    // Protected --------------

    protected $type;

    // Private ----------------

    // Constructor and Initialisation ------------------------------

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-core/admin/views/template' );

        $this->crudPermission 	= CoreGlobal::PERM_CORE;
        $this->modelService		= Yii::$app->factory->get( 'templateService' );

        // Notes: Configure type, sidebar and returnUrl exclusively in child classes.
    }

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    // yii\base\Controller ----

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // TemplateController --------------------

    public function actionAll() {

        $dataProvider = $this->modelService->getPageByType( $this->type );

        return $this->render( 'all', [

            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate() {

        $modelClass		= $this->modelService->getModelClass();
        $model			= new $modelClass;
        $model->type 	= $this->type;

        if( $model->load( Yii::$app->request->post(), $model->getClassName() )  && $model->validate() ) {

            $this->modelService->create( $model );

            return $this->redirect( $this->returnUrl );
        }

        return $this->render( 'create', [
            'model' => $model
        ]);
    }
}
