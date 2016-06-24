<?php
namespace cmsgears\core\common\models\traits\mappers;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\mappers\ModelForm;

/**
 * FormTrait can be used to associate forms to relevant models. The model must also support attributes to save the submitted form values.
 */
trait FormTrait {

	public function getModelForms() {

		return $this->hasMany( ModelForm::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}

	/**
	 * @return array - ModelForm associated with parent
	 */
	public function getForms() {

    	return $this->hasMany( Form::className(), [ 'id' => 'formId' ] )
					->viaTable( CoreTables::TABLE_MODEL_FORM, [ 'parentId' => 'id' ], function( $query ) {

						$ModelFormTable	= CoreTables::TABLE_MODEL_FORM;

                      	$query->onCondition( [ "$ModelFormTable.parentType" => $this->parentType ] );
					});
	}
}

?>