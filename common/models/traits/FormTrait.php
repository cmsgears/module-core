<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Form;
use cmsgears\core\common\models\entities\ModelForm;

/**
 * FormTrait can be used to associate forms to relevant models. The model must also support attributes to save the submitted form values.
 */
trait FormTrait {

	public function getModelForms() {

		$parentType	= $this->formType;

		return $this->hasMany( ModelForm::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - ModelForm associated with parent
	 */
	public function getForms() {

    	return $this->hasMany( Form::className(), [ 'id' => 'formId' ] )
					->viaTable( CoreTables::TABLE_MODEL_FORM, [ 'parentId' => 'id' ], function( $query ) {

						$ModelFormTable	= CoreTables::TABLE_MODEL_FORM;

                      	$query->onCondition( [ "$ModelFormTable.parentType" => $this->formType ] );
					});
	}
}

?>