<?php
namespace cmsgears\modules\core\admin;

// Yii Imports
use \Yii;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'cmsgears\modules\core\admin\controllers';

    public function init() {

        parent::init();

        $this->setViewPath( '@cmsgears/modules/core/admin/views' );
    }
}

?>