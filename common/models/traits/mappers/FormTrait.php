<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\mappers\ModelForm;

/**
 * FormTrait can be used to associate forms to relevant models.
 *
 * The model can also support meta trait to save the submitted form values as model
 * attributes. The submitted form values can also be stored in data column using data
 * trait.
 *
 * @since 1.0.0
 */
trait FormTrait {

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

	// FormTrait -----------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelForms() {

		$modelFormTable = ModelForm::tableName();

		return $this->hasMany( ModelForm::class, [ 'parentId' => 'id' ] )
			->where( "$modelFormTable.parentType=:ptype", [ ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelForms() {

		$modelFormTable = ModelForm::tableName();

		return $this->hasMany( ModelForm::class, [ 'parentId' => 'id' ] )
			->where( "$modelFormTable.parentType=:ptype AND $modelFormTable.active=1", [ ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelFormsByType( $type, $active = true ) {

		$modelFormTable = ModelForm::tableName();

		if( $active ) {

			return $this->hasMany( ModelForm::class, [ 'parentId' => 'id' ] )
				->where( "$modelFormTable.parentType=:ptype AND $modelFormTable.type=:type AND $modelFormTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] );
		}

		return $this->hasMany( ModelForm::class, [ 'parentId' => 'id' ] )
			->where( "$modelFormTable.parentType=:ptype AND $modelFormTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getForms() {

		$formTable		= Form::tableName();
		$modelFormTable	= ModelForm::tableName();

		return Form::find()
			->leftJoin( $modelFormTable, "$modelFormTable.modelId=$formTable.id" )
			->where( "$modelFormTable.parentId=:pid AND $modelFormTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveForms() {

		$formTable		= Form::tableName();
		$modelFormTable	= ModelForm::tableName();

		return Form::find()
			->leftJoin( $modelFormTable, "$modelFormTable.modelId=$formTable.id" )
			->where( "$modelFormTable.parentId=:pid AND $modelFormTable.parentType=:ptype AND $modelFormTable.active=1", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getFormsByType( $type, $active = true ) {

		$formTable		= Form::tableName();
		$modelFormTable	= ModelForm::tableName();

		if( $active ) {

			return Form::find()
				->leftJoin( $modelFormTable, "$modelFormTable.modelId=$formTable.id" )
				->where( "$modelFormTable.parentId=:pid AND $modelFormTable.parentType=:ptype AND $modelFormTable.type=:type AND $modelFormTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
				->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] )
				->all();
		}

		return Form::find()
			->leftJoin( $modelFormTable, "$modelFormTable.modelId=$formTable.id" )
			->where( "$modelFormTable.parentId=:pid AND $modelFormTable.parentType=:ptype AND $modelFormTable.type=:type", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type ] )
			->orderBy( [ "$modelFormTable.order" => SORT_DESC, "$modelFormTable.id" => SORT_DESC ] )
			->all();
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// FormTrait -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
