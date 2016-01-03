<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\models\entities\FormField;

/**
 * Default form designer component to generate html for form elements using form and form fields.
 * It supports CMGTools UI, JS and IconLib by default, but can be overriden to support other ui libraries. 
 */
class FormDesigner extends Component {

	/**
	 * Generate field html using Yii Form Widget.
	 * @param FormField $field
	 */
	public function getFieldHtml( $form, $model, $config, $key, $field ) {

		switch( $field->type ) {
	
			case FormField::TYPE_TEXT: {
	
				return $this->getTextHtml( $form, $model, $config, $key, $field );
			}
			case FormField::TYPE_PASSWORD: {

				return $this->getPasswordHtml( $form, $model, $config, $key, $field );
			}
			case FormField::TYPE_TEXTAREA: {

				return $this->getTextareaHtml( $form, $model, $config, $key, $field );
			}
			case FormField::TYPE_CHECKBOX: {

				return $form->field( $model, $key )->checkbox( $field->htmlOptions );
			}
			case FormField::TYPE_TOGGLE: {

				return $form->field( $model, $key, [ 'class' => 'switch' ] )->checkbox( $field->htmlOptions );
			}
			case FormField::TYPE_CHECKBOX_GROUP: {
				
				return $this->getCheckboxGroupHtml( $form, $model, $config, $key, $field );
			}
			case FormField::TYPE_RADIO: {

				return $form->field( $model, $key )->radio( $field->htmlOptions );
			}
			case FormField::TYPE_RADIO_GROUP: {
				
				return $this->getRadioGroupHtml( $form, $model, $config, $key, $field );
			}
			case FormField::TYPE_SELECT: {

				return $this->getSelectHtml( $form, $model, $config, $key, $field );
			}
		}
	}

	protected function getTextHtml( $form, $model, $config, $key, $field ) {

		$fieldHtml = $form->field( $model, $key )->textInput( $field->htmlOptions );
		
		if( $config[ 'label' ] ) {
			
			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {
	
			$fieldHtml = $fieldHtml->label( false );
		}
		
		return $fieldHtml;
	}

	protected function getPasswordHtml( $form, $model, $config, $key, $field ) {

		$fieldHtml = $form->field( $model, $key )->passwordInput( $field->htmlOptions );

		if( $config[ 'label' ] ) {
			
			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}
		
		return $fieldHtml;
	}

	protected function getTextareaHtml( $form, $model, $config, $key, $field ) {

		$fieldHtml = $form->field( $model, $key )->textArea( $field->htmlOptions );

		if( $config[ 'label' ] ) {
			
			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}
		
		return $fieldHtml;
	}

	protected function getCheckboxGroupHtml( $form, $model, $config, $key, $field ) {

		$fieldhtmlOptions	= $field->htmlOptions;

		if( isset( $fieldhtmlOptions[ 'items' ] ) ) {

			$items	= $fieldhtmlOptions[ 'items' ];

			unset( $fieldhtmlOptions[ 'items' ] );

			$fieldHtml 	= $form->field( $model, $key )->checkboxlist( $items, $fieldhtmlOptions );
		}
		else {

			$fieldHtml 	= $form->field( $model, $key )->checkboxlist( [ ], $fieldhtmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getRadioGroupHtml( $form, $model, $config, $key, $field ) {

		$fieldhtmlOptions	= $field->htmlOptions;

		if( isset( $fieldhtmlOptions[ 'items' ] ) ) {

			$items	= $fieldhtmlOptions[ 'items' ];

			unset( $fieldhtmlOptions[ 'items' ] );

			$fieldHtml 	= $form->field( $model, $key )->radioList( $items, $fieldhtmlOptions );
		}
		else {

			$fieldHtml 	= $form->field( $model, $key )->radioList( [ ], $fieldhtmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getSelectHtml( $form, $model, $config, $key, $field ) {

		$fieldhtmlOptions	= $field->htmlOptions;

		if( isset( $fieldhtmlOptions[ 'items' ] ) ) {

			$items	= $fieldhtmlOptions[ 'items' ];

			unset( $fieldhtmlOptions[ 'items' ] );

			$fieldHtml 	= $form->field( $model, $key )->dropDownList( $items, $fieldhtmlOptions );
		}
		else {

			$fieldHtml 	= $form->field( $model, $key )->dropDownList( [ 'Choose Option' ], $fieldhtmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	/**
	 * Generate field html for CMGTools JS Library.
	 * @param FormField $field
	 */
	public function getApixFieldHtml( $config, $field, $value = null ) {

		switch( $field->type ) {
	
			case FormField::TYPE_TEXT: {
	
				return $this->getApixTextHtml( $config, $field, $value );
			}
			case FormField::TYPE_PASSWORD: {

				return $this->getApixPasswordHtml( $config, $field );
			}
			case FormField::TYPE_TEXTAREA: {

				return $this->getApixTextareaHtml( $config, $field, $value );
			}
			case FormField::TYPE_CHECKBOX: {

				return $this->getApixCheckboxHtml( $config, $field, $value );
			}
			case FormField::TYPE_TOGGLE: {

				return $this->getApixToggleHtml( $config, $field, $value );
			}
			case FormField::TYPE_CHECKBOX_GROUP: {
				
				return $this->getApixCheckboxGroupHtml( $config, $field, $value );
			}
			case FormField::TYPE_RADIO: {

				return $this->getApixRadioHtml( $config, $field, $value );
			}
			case FormField::TYPE_RADIO_GROUP: {
				
				return $this->getApixRadioGroupHtml( $config, $field, $value );
			}
			case FormField::TYPE_SELECT: {

				return $this->getApixSelectHtml( $config, $field, $value );
			}
			case FormField::TYPE_DATE: {

				return $this->getApixDateHtml( $config, $field, $value );
			}
		}
	}

	protected function getApixTextHtml( $config, $field, $value ) {

		$modelName	= $config[ 'modelName' ];
		$fieldHtml 	= Html::input( 'text', $modelName . "[$field->name]", $value, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixPasswordHtml( $config, $field ) {

		$modelName	= $config[ 'modelName' ];
		$fieldHtml 	= Html::passwordInput( $modelName . "[$field->name]", null, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixTextareaHtml( $config, $field, $value ) {

		$modelName	= $config[ 'modelName' ];
		$fieldHtml 	= Html::textarea( $modelName . "[$field->name]", $value, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixCheckboxHtml( $config, $field, $value ) {

		$modelName		= $config[ 'modelName' ];
		$fieldHtml	 	= Html::hiddenInput( $modelName . "[$field->name]", $value );
		$checkboxHtml 	= Html::checkbox( "$field->name", $value, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'>
							<label>$field->label</label>
							<span class='cmt-checkbox'>$checkboxHtml $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>
						</div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>
							<span class='cmt-checkbox'>$checkboxHtml $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>
						</div>";
		}

		return $fieldHtml;
	}

	protected function getApixToggleHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;

		if( !isset( $htmlOptions ) ) {

			$htmlOptions	= [];
		}

		if( isset( $htmlOptions[ 'class' ] ) ) {

			$htmlOptions[ 'class' ] .= ' cmt-toggle cmt-toggle-round';
		}
		else {

			$htmlOptions[ 'class' ] = 'cmt-toggle cmt-toggle-round';
		}

		$modelName				= $config[ 'modelName' ];
		$id						= $modelName . "_$field->name";
		$htmlOptions[ 'id' ]	= $id;
		$fieldHtml	 			= Html::hiddenInput( $modelName . "[$field->name]", $value );
		$checkboxHtml 			= Html::checkbox( "$field->name", $value, $htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'>
							<label>$field->label</label>
							<span class='cmt-switch cmt-checkbox'>$checkboxHtml <label for='$id'></label> $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>
						</div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>
							<span class='cmt-switch cmt-checkbox'>$checkboxHtml <label for='$id'></label> $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>
						</div>";
		}

		return $fieldHtml;
	}

	protected function getApixCheckboxGroupHtml( $config, $field, $value ) {
			
		if( isset( $value ) ) {
			
			$value	= preg_split( "/,/", $value );
		} 
		
		$modelName	= $config[ 'modelName' ];
		$fieldHtml 	= null;

		$fieldhtmlOptions	= $field->htmlOptions;

		if( isset( $fieldhtmlOptions[ 'items' ] ) ) {

			$items	= $fieldhtmlOptions[ 'items' ];

			unset( $fieldhtmlOptions[ 'items' ] );

			$fieldHtml 	= Html::checkboxList( $modelName . "[$field->name]", $value, $items, $field->htmlOptions );
		}
		else {

			$fieldHtml 	= Html::checkboxList( $modelName . "[$field->name]", null, [ ], $field->htmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixRadioHtml( $config, $field, $value ) {
		
		$htmlOptions	= $field->htmlOptions;
		
		if( isset( $value ) && ( $value || strcmp( $value, 'Yes' ) == 0 ) ){

			if( !isset( $htmlOptions ) ) {
				
				$htmlOptions	= [];
			}
			
			$htmlOptions[ 'value' ]	= $value;
			$value					= true;
		}
		else {
			
			$value	= false;
		}

		$modelName	= $config[ 'modelName' ];
		$fieldHtml 	= Html::radio( $modelName . "[$field->name]", $value, $htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixRadioGroupHtml( $config, $field, $value ) {

		$modelName			= $config[ 'modelName' ];
		$fieldHtml 			= null;
		$fieldhtmlOptions	= $field->htmlOptions;

		if( isset( $fieldhtmlOptions[ 'items' ] ) ) {

			$items	= $fieldhtmlOptions[ 'items' ];

			unset( $fieldhtmlOptions[ 'items' ] );

			$fieldHtml 	= Html::radioList( $modelName . "[$field->name]", null, $items, $field->htmlOptions );
		}
		else {

			$fieldHtml 	= Html::radioList( $modelName . "[$field->name]", null, [ ], $field->htmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixSelectHtml( $config, $field, $value ) {

		if( isset( $value ) ) {
			
			$value	= preg_split( "/,/", $value );
		}

		$modelName			= $config[ 'modelName' ];
		$fieldHtml 			= null;
		$fieldhtmlOptions	= $field->htmlOptions;

		if( isset( $fieldhtmlOptions[ 'items' ] ) ) {

			$items	= $fieldhtmlOptions[ 'items' ];

			unset( $fieldhtmlOptions[ 'items' ] );

			$fieldHtml 	= Html::dropDownList( $modelName . "[$field->name]", $value, $items, $field->htmlOptions );
		}
		else {

			$fieldHtml 	= Html::dropDownList( $modelName . "[$field->name]", null, [ "Choose Option" ], $field->htmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixDateHtml( $config, $field, $value ) {

		$modelName	= $config[ 'modelName' ];
		$fieldHtml 	= Html::input( 'text', $modelName . "[$field->name]", null, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'>
							<label>$field->label</label>
							<span class='frm-icon-element'><i class='icon cmti cmti-calendar'></i>$fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>
						</div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>
							<span class='frm-icon-element'><i class='icon cmti cmti-calendar'></i>$fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>
						</div>";
		}

		return $fieldHtml;
	}
}

?>