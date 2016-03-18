<?php
namespace cmsgears\core\frontend\controllers\apix;

// Yii Imports
use Yii;
use yii\filters\VerbFilter;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\ModelComment;

use cmsgears\core\frontend\actions\comment\CreateTrait;

use cmsgears\core\frontend\services\ModelCommentService;

use cmsgears\core\common\utilities\AjaxUtil;

class CommentController extends \cmsgears\core\admin\controllers\base\Controller {

    protected $scenario = null;

    // Constructor and Initialisation ------------------------------

    public function __construct( $id, $module, $config = [] ) {

        parent::__construct( $id, $module, $config );
    }

    // Instance Methods --------------------------------------------

    // yii\base\Component

    public function behaviors() {

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => [ 'post' ]
                ]
            ]
        ];
    }

    use CreateTrait;
}

?>