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

	public function registerChildAssets( $view ) {

		// register child theme assets from config
		$themeChilds	= $this->childs;

		foreach ( $themeChilds as $child ) {

			$child = Yii::createObject( $child );

			$child->registerAssets( $view );
		}
	}
}