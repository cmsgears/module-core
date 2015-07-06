<?php
namespace cmsgears\core\common\base;

use \Yii;

class Theme extends \yii\base\Theme {

	public $childs		= [];

    public function init() {

        parent::init();

		// The path for images directly accessed using the img tag 
		Yii::setAlias( "@images", "@web/images" );
    }
}