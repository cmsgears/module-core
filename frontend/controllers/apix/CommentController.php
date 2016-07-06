<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class CommentController extends \cmsgears\core\admin\controllers\base\Controller {

	public $modelName;
	public $modelType;

	/**
	 * Parent Service can be used to find parent to which the model is associated. It can used to check parent ownership for current user using rbac action.
	 */
	public $parentService;

    // Constructor and Initialisation ------------------------------

 	public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
    }

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    public function behaviors() {

        return [
            'rbac' => [
                'class' => Yii::$app->cmgCore->getRbacFilterClass(),
                'actions' => [
                	// rbac actions
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => [ 'post' ],
                    //'update' => [ 'post' ],
                    //'approve' => [ 'post' ],
                    //'spam' => [ 'post' ],
                    //'block' => [ 'post' ],
                    //'trash' => [ 'post' ],
                    //'delete' => [ 'post' ],
                    //'approve-request' => [ 'post' ],
                    //'spam-request' => [ 'post' ],
                    //'delete-request' => [ 'post' ]
                ]
            ]
        ];
    }

	// yii\base\Controller ---------------

    public function actions() {

        return [
            'create' => [
                'class' => 'cmsgears\core\frontend\actions\comment\CreateComment'
            ]
        ];
    }

	// CommentController -----------------

}
