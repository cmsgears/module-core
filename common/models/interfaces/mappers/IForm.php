<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\interfaces\mappers;

/**
 * The IForm declare the methods implemented by FormTrait. It can be implemented
 * by entities, resources and models which need multiple forms.
 *
 * @since 1.0.0
 */
interface IForm {

	/**
	 * Return all the form mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelForm[]
	 */
	public function getModelForms();

	/**
	 * Return all the active form mappings associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\mappers\ModelForm[]
	 */
	public function getActiveModelForms();

	/**
	 * Return the form mappings associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\mappers\ModelForm[]
	 */
	public function getModelFormsByType( $type, $active = true );

	/**
	 * Return all the forms associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Form[]
	 */
	public function getForms();

	/**
	 * Return all the active forms associated with the parent.
	 *
	 * @return \cmsgears\core\common\models\resources\Form[]
	 */
	public function getActiveForms();

	/**
	 * Return forms associated with the parent for given mapping type.
	 *
	 * @param string $type
	 * @param boolean $active
	 * @return \cmsgears\core\common\models\resources\Form[]
	 */
	public function getFormsByType( $type, $active = true );
}
