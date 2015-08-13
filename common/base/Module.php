<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

class Module extends \yii\base\Module {

	public $config	= [];

	public function getSidebarHtml() {

		return '';
	}

	public function getDashboardHtml() {

		return '';
	}
}

?>