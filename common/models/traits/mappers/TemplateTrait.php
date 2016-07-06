<?php
namespace cmsgears\core\common\models\traits\mappers;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Template;

/**
 * TemplateTrait can be used to assist models supporting templates.
 */
trait TemplateTrait {

	public function getTemplate() {

		return $this->hasOne( Template::className(), [ 'id' => 'templateId' ] );
	}

	public function getTemplateName() {

		$template = $this->template;

		if( isset( $template ) ) {

			return $template->name;
		}

		return '';
	}

	public static function queryWithTemplate( $config = [] ) {

		$config[ 'relations' ]	= [ 'template' ];

		return parent::queryWithAll( $config );
	}
}
