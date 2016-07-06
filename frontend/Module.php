<?php
namespace cmsgears\core\frontend;

// Yii Imports
use \Yii;

class Module extends \cmsgears\core\common\base\Module {

    public $controllerNamespace = 'cmsgears\core\frontend\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/module-core/frontend/views' );
    }
}
