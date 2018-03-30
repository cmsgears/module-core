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
			->where( "$modelFormTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelForms() {

		$modelFormTable = ModelForm::tableName();

		return $this->hasMany( ModelForm::class, [ 'parentId' => 'id' ] )
			->where( "$modelFormTable.parentType='$this->modelType' AND $modelFormTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelFormsByType( $type, $active = true ) {

		$modelFormTable = ModelForm::tableName();

		return $this->hasOne( ModelForm::class, [ 'parentId' => 'id' ] )
			->where( "$modelFormTable.parentType=:ptype AND $modelFormTable.type=:type AND $modelFormTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getForms() {

		$modelFormTable = ModelForm::tableName();

		return $this->hasMany( Form::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFormTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelFormTable ) {

					$query->onCondition( [ "$modelFormTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveForms() {

		$modelFormTable = ModelForm::tableName();

		return $this->hasMany( Form::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFormTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelFormTable ) {

					$query->onCondition( [ "$modelFormTable.parentType" => $this->modelType, "$modelFormTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getFormsByType( $type, $active = true ) {

		$modelFormTable = ModelForm::tableName();

		return $this->hasMany( Form::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelFormTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelFormTable ) {

					$query->onCondition( [ "$modelFormTable.parentType" => $this->modelType, "$modelFormTable.type" => $type, "$modelFormTable.active" => $active ] );
				}
			)->all();
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
