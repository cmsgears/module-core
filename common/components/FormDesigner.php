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

		$fieldOptions	= $field->htmlOptions;

		if( isset( $fieldOptions[ 'items' ] ) ) {

			$items	= $fieldOptions[ 'items' ];

			unset( $fieldOptions[ 'items' ] );

			$fieldHtml 	= $form->field( $model, $key )->checkboxlist( $items, $fieldOptions );
		}
		else {

			$fieldHtml 	= $form->field( $model, $key )->checkboxlist( [ ], $fieldOptions );
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

		$fieldOptions	= $field->htmlOptions;

		if( isset( $fieldOptions[ 'items' ] ) ) {

			$items	= $fieldOptions[ 'items' ];

			unset( $fieldOptions[ 'items' ] );

			$fieldHtml 	= $form->field( $model, $key )->radioList( $items, $fieldOptions );
		}
		else {

			$fieldHtml 	= $form->field( $model, $key )->radioList( [ ], $fieldOptions );
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

		$fieldOptions	= $field->htmlOptions;

		if( isset( $fieldOptions[ 'items' ] ) ) {

			$items	= $fieldOptions[ 'items' ];

			unset( $fieldOptions[ 'items' ] );

			$fieldHtml 	= $form->field( $model, $key )->dropDownList( $items, $fieldOptions );
		}
		else {

			$fieldHtml 	= $form->field( $model, $key )->dropDownList( [ 'Choose Option' ], $fieldOptions );
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
	public function getApixFieldHtml( $form, $config, $field ) {

		switch( $field->type ) {
	
			case FormField::TYPE_TEXT: {
	
				return $this->getApixTextHtml( $form, $config, $field );
			}
			case FormField::TYPE_PASSWORD: {

				return $this->getApixPasswordHtml( $form, $config, $field );
			}
			case FormField::TYPE_TEXTAREA: {

				return $this->getApixTextareaHtml( $form, $config, $field );
			}
			case FormField::TYPE_CHECKBOX: {

				return $this->getApixCheckboxHtml( $form, $config, $field );
			}
			case FormField::TYPE_CHECKBOX_GROUP: {
				
				return $this->getApixCheckboxGroupHtml( $form, $config, $field );
			}
			case FormField::TYPE_RADIO: {

				return $this->getApixRadioHtml( $form, $config, $field );
			}
			case FormField::TYPE_RADIO_GROUP: {
				
				return $this->getApixRadioGroupHtml( $form, $config, $field );
			}
			case FormField::TYPE_SELECT: {

				return $this->getApixSelectHtml( $form, $config, $field );
			}
			case FormField::TYPE_DATE: {

				return $this->getApixDateHtml( $form, $config, $field );
			}
		}
	}

	protected function getApixTextHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= Html::input( 'text', $model . "[$field->name]", null, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixPasswordHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= Html::passwordInput( $model . "[$field->name]", null, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixTextareaHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= Html::textarea( $model . "[$field->name]", null, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixCheckboxHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= Html::checkbox( $model . "[$field->name]", false, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixCheckboxGroupHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= null;

		$fieldOptions	= $field->htmlOptions;

		if( isset( $fieldOptions[ 'items' ] ) ) {

			$items	= $fieldOptions[ 'items' ];

			unset( $fieldOptions[ 'items' ] );

			$fieldHtml 	= Html::checkboxList( $model . "[$field->name]", null, $items, $field->htmlOptions );
		}
		else {

			$fieldHtml 	= Html::checkboxList( $model . "[$field->name]", null, [ ], $field->htmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixRadioHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= Html::radio( $model . "[$field->name]", false, $field->htmlOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixRadioGroupHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= null;

		$fieldOptions	= $field->htmlOptions;

		if( isset( $fieldOptions[ 'items' ] ) ) {

			$items	= $fieldOptions[ 'items' ];

			unset( $fieldOptions[ 'items' ] );

			$fieldHtml 	= Html::radioList( $model . "[$field->name]", null, $items, $field->htmlOptions );
		}
		else {

			$fieldHtml 	= Html::radioList( $model . "[$field->name]", null, [ ], $field->htmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixSelectHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= null;

		$fieldOptions	= $field->htmlOptions;

		if( isset( $fieldOptions[ 'items' ] ) ) {

			$items	= $fieldOptions[ 'items' ];

			unset( $fieldOptions[ 'items' ] );

			$fieldHtml 	= Html::dropDownList( $model . "[$field->name]", null, $items, $field->htmlOptions );
		}
		else {

			$fieldHtml 	= Html::dropDownList( $model . "[$field->name]", null, [ "Choose Option" ], $field->htmlOptions );
		}

		if( $config[ 'label' ] ) {

			$fieldHtml = "<div class='frm-field'><label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}
		else {

			$fieldHtml = "<div class='frm-field'>$fieldHtml<span class='error' cmt-error='$field->name'></span></div>";
		}

		return $fieldHtml;
	}

	protected function getApixDateHtml( $form, $config, $field ) {

		$model		= $config[ 'model' ];
		$fieldHtml 	= Html::input( 'text', $model . "[$field->name]", null, $field->htmlOptions );

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