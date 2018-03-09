<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\resources;

// CMG Imports
use cmsgears\core\common\models\entities\Template;

/**
 * TemplateTrait can be used to assist models supporting templates.
 */
trait TemplateTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// TemplateTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getTemplate() {

		return $this->hasOne( Template::class, [ 'id' => 'templateId' ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getTemplateName() {

		$template = $this->template;

		if( isset( $template ) ) {

			return $template->name;
		}

		return '';
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// TemplateTrait -------------------------

	// Read - Query -----------

	public static function queryWithTemplate( $config = [] ) {

		$config[ 'relations' ]	= [ 'template' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
