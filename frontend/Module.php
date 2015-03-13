<?php
namespace cmsgears\modules\core\frontend;

// Yii Imports
use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'cmsgears\modules\core\frontend\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/modules/core/frontend/views' );
    }
}

?>