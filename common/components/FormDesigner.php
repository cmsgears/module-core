<?php
namespace cmsgears\core\common\components;

// Yii Imports
use yii\helpers\Html;
use yii\helpers\StringHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\resources\FormField;

/**
 * Default form designer component to generate html for form elements using form and form fields.
 * It supports CMGTools UI, JS and IconLib by default, but can be overriden to support other ui libraries.
 */
class FormDesigner extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// FormDesigner --------------------------

	// Yii Forms

	/**
	 * Generate field html using Yii Form Widget.
	 * @param FormField $field
	 */
	public function getFieldHtml( $form, $model, $config, $key, $field ) {

		switch( $field->type ) {

			case FormField::TYPE_TEXT: {

				return $this->getTextHtml( $form, $model, $config, $key, $field );
			}
			case FormField::TYPE_HIDDEN: {

				return $this->getHiddenHtml( $form, $model, $config, $key, $field );
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
			case FormField::TYPE_RATING : {

				return $this->getRatingHtml( $form, $model, $config, $key, $field );
			}
		}
	}

	protected function getTextHtml( $form, $model, $config, $key, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$fieldHtml = $form->field( $model, $key, $wrapperOptions )->textInput( $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getHiddenHtml( $form, $model, $config, $key, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$fieldHtml		= $form->field( $model, $key, $wrapperOptions )->hiddenInput( $fieldOptions );

		$fieldHtml		= $fieldHtml->label( false );

		return $fieldHtml;
	}

	protected function getPasswordHtml( $form, $model, $config, $key, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$fieldHtml		= $form->field( $model, $key, $wrapperOptions )->passwordInput( $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getTextareaHtml( $form, $model, $config, $key, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$fieldHtml		= $form->field( $model, $key, $wrapperOptions )->textArea( $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getCheckboxGroupHtml( $form, $model, $config, $key, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$items			= isset( $htmlOptions[ 'items' ] ) ? $htmlOptions[ 'items' ] : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$fieldHtml		= $form->field( $model, $key, $wrapperOptions )->checkboxlist( $items, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getRadioGroupHtml( $form, $model, $config, $key, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$items			= isset( $htmlOptions[ 'items' ] ) ? $htmlOptions[ 'items' ] : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$fieldHtml		= $form->field( $model, $key, $wrapperOptions )->radioList( $items, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getSelectHtml( $form, $model, $config, $key, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$items			= isset( $htmlOptions[ 'items' ] ) ? $htmlOptions[ 'items' ] : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$fieldHtml		= $form->field( $model, $key, $wrapperOptions )->dropDownList( $items, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = $fieldHtml->label( $field->label );
		}
		else {

			$fieldHtml = $fieldHtml->label( false );
		}

		return $fieldHtml;
	}

	protected function getRatingHtml( $form, $model, $config, $key, $field ) {

		$class			= isset( $field->htmlOptions[ 'class' ] ) ? $field->htmlOptions[ 'class' ] : 'cmt-rating';
		$readOnly		= isset( $field->htmlOptions[ 'read-only' ] ) ? $field->htmlOptions[ 'read-only' ] : false;
		$disabled		= isset( $field->htmlOptions[ 'disabled' ] ) ? $field->htmlOptions[ 'disabled' ] : false;

		// Prefer config on top of default
		$readOnly		= isset( $config[ 'read-only' ] ) ? $config[ 'read-only' ] : $readOnly;
		$disabled		= isset( $config[ 'disabled' ] ) ? $config[ 'disabled' ] : $disabled;

		// TODO: Get stars and starMessage from field options.
		$stars			= isset( $config[ 'stars' ] ) ? $config[ 'stars' ] : 5;
		$starMessage	= isset( $config[ 'starMessage' ] ) ? $config[ 'starMessage' ] : [ "Poor", "Good", "Very Good", "Perfect", "Excellent" ];
		$value			= $model->$key;

		$fieldName		= StringHelper::baseName( get_class( $model ) ) . '[' . $key . ']';

		if( $readOnly ) {

			$class = "$class read-only";
		}

		if( $disabled ) {

			$class = "$class disabled";
		}

		if( $config[ 'label' ] ) {

			// element-60 will work if form is configured for 40-60 split, else it will behave as normal field
			$ratingHtml	= "<label>$field->label</label><div class=\"element-60 $class\">";
		}
		else {

			$ratingHtml	= "<div class=\"$class\">";
		}

		$ratingHtml .= '<span class="wrap-stars">';

		for( $i = 1; $i <= $stars; $i++ ) {

			if( $value > 0 && $value == $i ) {

				$icon	= "<span star=\"$i\" class=\"star selected\"></span>";
			}
			else {

				$icon	= "<span star=\"$i\" class=\"star\"></span>";
			}

			$ratingHtml	  .= $icon;
		}

		$ratingHtml .= '</span>';

		$ratingHtml .= '<span class="wrap-messages">';

		for( $i = 1; $i <= $stars; $i++ ) {

			$message = $starMessage[ $i - 1 ];

			if( $value > 0 && $value == $i ) {

				$icon	= "<span star-message=\"$i\" class=\"star-message selected\">$message</span>";
			}
			else {

				$icon	= "<span star-message=\"$i\" class=\"star-message\">$message</span>";
			}

			$ratingHtml	  .= $icon;
		}

		$ratingHtml .= '</span>';

		$ratingHtml	.= '<input type="hidden" name="' . $fieldName . '" value="' . $value . '">';

		$ratingHtml	.= "</div>";

		return $ratingHtml;
	}

	// Apix Forms

	/**
	 * Generate field html for CMGTools JS Library.
	 * @param FormField $field
	 */
	public function getApixFieldHtml( $config, $field, $value = null ) {

		switch( $field->type ) {

			case FormField::TYPE_TEXT: {

				return $this->getApixTextHtml( $config, $field, $value );
			}
			case FormField::TYPE_HIDDEN: {

				return $this->getApixHiddenHtml( $config, $field, $value );
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
			case FormField::TYPE_RATING: {

				return $this->getApixRatingHtml( $config, $field, $value );
			}
			case FormField::TYPE_DATE: {

				return $this->getApixDateHtml( $config, $field, $value );
			}
		}
	}

	protected function getApixTextHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$modelName	= $config[ 'modelName' ];
		$fieldHtml	= Html::input( 'text', $modelName . "[$field->name]", $value, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixHiddenHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$modelName	= $config[ 'modelName' ];
		$fieldHtml	= Html::hiddenInput( $modelName . "[$field->name]", $value, $fieldOptions );

		$fieldHtml	= "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixPasswordHtml( $config, $field ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$modelName	= $config[ 'modelName' ];
		$fieldHtml	= Html::passwordInput( $modelName . "[$field->name]", null, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixTextareaHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$modelName	= $config[ 'modelName' ];
		$fieldHtml	= Html::textarea( $modelName . "[$field->name]", $value, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixCheckboxHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$modelName		= $config[ 'modelName' ];
		$fieldHtml		= Html::hiddenInput( $modelName . "[$field->name]", $value );
		$checkboxHtml	= Html::checkbox( "$field->name", $value, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label> <span class='cmt-checkbox'>$checkboxHtml $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "<span class='cmt-checkbox'>$checkboxHtml $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixToggleHtml( $config, $field, $value ) {

		$htmlOptions	= isset( $field->htmlOptions ) ? $field->htmlOptions : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		if( isset( $fieldOptions[ 'class' ] ) ) {

			$fieldOptions[ 'class' ] .= ' cmt-toggle cmt-toggle-round';
		}
		else {

			$fieldOptions[ 'class' ] = 'cmt-toggle cmt-toggle-round';
		}

		$modelName				= $config[ 'modelName' ];
		$id						= $modelName . "_$field->name";
		$htmlOptions[ 'id' ]	= $id;
		$fieldHtml				= Html::hiddenInput( $modelName . "[$field->name]", $value );
		$checkboxHtml			= Html::checkbox( "$field->name", $value, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>
							<span class='cmt-switch cmt-checkbox'>$checkboxHtml <label for='$id'></label> $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "<span class='cmt-switch cmt-checkbox'>$checkboxHtml <label for='$id'></label> $fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixCheckboxGroupHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$items			= isset( $htmlOptions[ 'items' ] ) ? $htmlOptions[ 'items' ] : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		if( isset( $value ) ) {

			$value	= preg_split( "/,/", $value );
		}

		$modelName	= $config[ 'modelName' ];
		$fieldHtml	= Html::checkboxList( $modelName . "[$field->name]", $value, $items, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixRadioHtml( $config, $field, $value ) {

		$htmlOptions	= isset( $field->htmlOptions ) ? $field->htmlOptions : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		if( isset( $value ) && ( $value || strcmp( $value, 'Yes' ) == 0 ) ){

			$value	= true;

			$fieldOptions[ 'value' ]	= $value;
		}
		else {

			$value	= false;
		}

		$modelName	= $config[ 'modelName' ];
		$fieldHtml	= Html::radio( $modelName . "[$field->name]", $value, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixRadioGroupHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$items			= isset( $htmlOptions[ 'items' ] ) ? $htmlOptions[ 'items' ] : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;


		$modelName		= $config[ 'modelName' ];
		$fieldHtml		= Html::radioList( $modelName . "[$field->name]", null, $items, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixSelectHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$items			= isset( $htmlOptions[ 'items' ] ) ? $htmlOptions[ 'items' ] : [];
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		if( isset( $value ) ) {

			$value	= preg_split( "/,/", $value );
		}

		$modelName		= $config[ 'modelName' ];
		$fieldHtml		= Html::dropDownList( $modelName . "[$field->name]", $value, $items, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "$fieldHtml<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	protected function getApixRatingHtml( $config, $field, $value ) {

		$class			= isset( $field->htmlOptions[ 'class' ] ) ? $field->htmlOptions[ 'class' ] : 'cmt-rating';
		$readOnly		= isset( $field->htmlOptions[ 'read-only' ] ) ? $field->htmlOptions[ 'read-only' ] : false;
		$disabled		= isset( $field->htmlOptions[ 'disabled' ] ) ? $field->htmlOptions[ 'disabled' ] : false;

		// Prefer config on top of default
		$readOnly		= isset( $config[ 'read-only' ] ) ? $config[ 'read-only' ] : $readOnly;
		$disabled		= isset( $config[ 'disabled' ] ) ? $config[ 'disabled' ] : $disabled;

		// TODO: Get stars and starMessage from field options.
		$stars			= isset( $config[ 'stars' ] ) ? $config[ 'stars' ] : 5;
		$starMessage	= isset( $config[ 'starMessage' ] ) ? $config[ 'starMessage' ] : [ "Poor", "Good", "Very Good", "Perfect", "Excellent" ];

		$modelName		= $config[ 'modelName' ];
		$fieldName		= $modelName . '[' . $field->name . ']';

		if( $readOnly ) {

			$class = "$class read-only";
		}

		if( $disabled ) {

			$class = "$class disabled";
		}

		if( $config[ 'label' ] ) {

			// element-60 will work if form is configured for 40-60 split, else it will behave as normal field
			$ratingHtml	= "<label>$field->label</label><div class=\"element-60 $class\">";
		}
		else {

			$ratingHtml	= "<div class=\"$class\">";
		}

		$ratingHtml .= '<span class="wrap-stars">';

		for( $i = 1; $i <= $stars; $i++ ) {

			if( $value > 0 && $value == $i ) {

				$icon	= "<span star=\"$i\" class=\"star selected\"></span>";
			}
			else {

				$icon	= "<span star=\"$i\" class=\"star\"></span>";
			}

			$ratingHtml	  .= $icon;
		}

		$ratingHtml .= '</span>';

		$ratingHtml .= '<span class="wrap-messages">';

		for( $i = 1; $i <= $stars; $i++ ) {

			$message = $starMessage[ $i - 1 ];

			if( $value > 0 && $value == $i ) {

				$icon	= "<span star-message=\"$i\" class=\"star-message selected\">$message</span>";
			}
			else {

				$icon	= "<span star-message=\"$i\" class=\"star-message\">$message</span>";
			}

			$ratingHtml	  .= $icon;
		}

		$ratingHtml .= '</span>';

		$ratingHtml	.= '<input type="hidden" name="' . $fieldName . '" value="' . $value . '">';

		$ratingHtml	.= "</div>";

		return $ratingHtml;
	}

	protected function getApixDateHtml( $config, $field, $value ) {

		$htmlOptions	= $field->htmlOptions;
		$wrapperOptions	= isset( $htmlOptions[ 'wrapper' ] ) ? $htmlOptions[ 'wrapper' ] : [ 'class' => 'frm-field' ];
		$fieldOptions	= isset( $htmlOptions[ 'field' ] ) ? $htmlOptions[ 'field' ] : [];
		$fieldOptions	= count( $wrapperOptions ) == 0 && count( $fieldOptions ) == 0 ? $htmlOptions : $fieldOptions;

		$modelName	= $config[ 'modelName' ];
		$fieldHtml	= Html::input( 'text', $modelName . "[$field->name]", null, $fieldOptions );

		if( $config[ 'label' ] ) {

			$fieldHtml = "<label>$field->label</label>
							<span class='frm-icon-element'><i class='icon cmti cmti-calendar'></i>$fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>";
		}
		else {

			$fieldHtml = "<span class='frm-icon-element'><i class='icon cmti cmti-calendar'></i>$fieldHtml</span>
							<span class='error' cmt-error='$field->name'></span>";
		}

		$fieldHtml	= Html::tag( 'div', $fieldHtml, $wrapperOptions );

		return $fieldHtml;
	}

	// HTML Generator

	// TODO: Check more to make compatible with both dynamic and regular forms

	public function getRadioList( $form, $model, $field, $itemlist, $inline = true, $yesNo = false ) {

		$setInline	= null;

		if( $inline ) {

			$setInline	= 'clear-none';
		}

		if( $yesNo ) {

			$itemlist = CoreGlobal::$yesNoMap;
		}

		$template	= "<div class='cmt-choice $setInline clearfix'>{label}<div class='radio-group'>{input}</div><div class='help-block'>\n{hint}\n{error}</div></div>";

		return $form->field( $model, $field, [ 'template' => $template ]	)
					->radioList(
						$itemlist,
						[
							'item' => function( $index, $label, $name, $checked, $value ) {

								$slabel = strtolower( $label );
								$html = "<label class='$slabel'><input ";

								if( $checked ) {

									$html .= 'checked';
								}

								$html .= " type='radio' name='$name' value='$value'><span class='label pad-label'>$label</span></label>";

								return $html;
							}
						]
					);
	}

	public function getCheckboxList( $form, $model, $field, $itemlist, $inline = true ) {

		$setInline	= null;

		if( $inline ) {

			$setInline	= 'clear-none';
		}

		$template	= "<div class='cmt-choice $setInline clearfix'>{label}<div class='checkbox-group'>{input}</div><div class='help-block'>\n{hint}\n{error}</div></div>";

		return $form->field( $model, "$field", [ 'template' => $template ] )
					->checkboxList(
						$itemlist,
						[
							'item' => function( $index, $label, $name, $checked, $value ) {

								$html = "<label id='$label'><input ";
								$html = "<label class='$label'><input ";

								if( $checked ) {

									$html .= 'checked';
								}

								$html .= " type='checkbox' name='$name' value='$value'><span class='label pad-label'>$label</span></label>";

								return $html;
							},
						]
					);
	}

	public function generateMultipleInputHtml( $model, $fieldName, $config = [] ) {

		$defaultField	= isset( $config[ 'defaultField' ] ) ? $config[ 'defaultField' ] : true;
		$label			= isset( $config[ 'label' ] ) ? $config[ 'label' ] : 'Name';
		$placeholder	= isset( $config[ 'placeholder' ] ) ? $config[ 'placeholder' ] : 'Name';
		$modelName		= isset( $config[ 'modelName' ] ) ? $config[ 'modelName' ] : 'Model';
		$addBtnTitle	= isset( $config[ 'addBtnTitle' ] ) ? $config[ 'addBtnTitle' ] : 'Add Field';

		$fields			= $model->$fieldName;

		$fieldHtml		= "<div class='multi-input'><div class='frm-field clear-none clearfix inputs'>";

		if( count( $fields ) == 0 && $defaultField ) {

			$fieldHtml		.= "<div class='clearfix'>
									<label>$label</label>
									<input type='text' placeholder='$placeholder' name='" . $modelName . "[$fieldName][]'>
								</div>";
		}
		else if( is_array( $fields ) ) {

			foreach( $fields as $field ) {

				$fieldHtml	.= "<div class='frm-field relative clearfix'>
										<i class='cmti cmti-close-c icon-delete delete-field'></i>
										<label>$label</label>
										<input type='text' placeholder='$placeholder' name='" . $modelName . "[$fieldName][]' value='$field'>
									</div>";
			}
		}

		$errors = $model->getErrors( $fieldName );
		$errors = join( ",", $errors );

		$fieldHtml	.= "</div><div class='help-block'>$errors</div>
							<div class='frm-field clear-none clearfix'>
								<div class='element-60 right'>
									<a class='link btn-add-input' label='$label' placeholder='$placeholder' model='$modelName' field='$fieldName'>$addBtnTitle</a>
								</div>
							</div>
						</div>";

		return $fieldHtml;
	}

	public function getRatingStars( $stars, $selected, $disabled = false, $class = 'cmt-rating' ) {

		if( $disabled ) {

			$class = "$class disabled";
		}

		$ratingHtml	= "<div class=\"$class\">";

		for( $i = 1; $i <= $stars; $i++ ) {

			if( $selected > 0 && $selected == $i ) {

				$icon	= "<span star=\"$i\" class=\"star selected\"></span>";
			}
			else {

				$icon	= "<span star=\"$i\" class=\"star\"></span>";
			}

			$ratingHtml	  .= $icon;
		}

		$ratingHtml	.= "</div>";

		return $ratingHtml;
	}

	public function getNumberElement( $min, $max, $name = null, $defaultValue = 1 ) {

		$elementHtml	=	"<div class='clearfix max-cols-50 cmt-number-element'>
								<div class='col12x3'><i class='btn btn-min cmti cmti-minus'></i></div>
								<div class='col12x6'><input type='text' value='$defaultValue' name='$name'></div>
								<div class='col12x3'><i class='btn btn-max cmti cmti-plus'></i></div>
								<span class='hidden cmt-min'>$min</span>
								<span class='hidden cmt-max'>$max</span>
							</div>";
	   return $elementHtml;
	}
}
