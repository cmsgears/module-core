<?php
namespace cmsgears\core\common\utilities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\FormField;
use cmsgears\core\common\models\entities\ModelAttribute;

use cmsgears\core\admin\services\FormService;

class FormUtil {

	// Static Methods ----------------------------------------------

	public static function fillFromModelAttribute( $formSlug, $modelAttributes ) {

		$form 		= FormService::findBySlug( $formSlug );
		$fieldsMap	= [];

		if( isset( $form ) ) {

			$formFields	= $form->getFieldsMap();

			foreach ( $formFields as $key => $formField ) {

				// Convert CheckBox csv to array
				if( $formField->isCheckboxGroup() ) {

					$this->$fieldName	= split("/,/", $formField->value );
				}

				// Ignore passwords
				if( !$formField->isPasswordField() ) {

					$formField->value	= $modelAttributes[ $key ]->value;
				}

				$fieldsMap[ $formField->name ]	= $formField;
			}
		}

		return $fieldsMap;
	}

	public static function getModelAttributes( $model, $settings ) {

		$attributes			= $model->getFormAttributes();
		$fields				= $attributes[ 'fields' ];

		foreach ( $fields as $field ) {

			$fieldName						= $field->name;
			$settings[ $fieldName ]->value	= $model->$fieldName;
		}

		return $settings;
	}

	public static function getFieldsHtml( $form, $model, $config = [] ) {

		$fields 			= $model->fields;
		$fieldsHtml			= '';
		$config[ 'label' ]	= isset( $config[ 'label' ] ) ? $config[ 'label' ] : true;

		foreach ( $fields as $key => $field ) {

			// Convert Json to Array
			if( isset( $field->htmlOptions ) && strlen( $field->htmlOptions ) > 0 ) {

				$field->htmlOptions	= json_decode( $field->htmlOptions, true );
			}
			else {

				$field->htmlOptions	= [];
			}

			$fieldsHtml .= Yii::$app->formDesigner->getFieldHtml( $form, $model, $config, $key, $field );
		}

		return $fieldsHtml;
	}

	public static function getApixFieldsHtml( $form, $model, $config = [] ) {

		$fields 				= $form->fields;
		$fieldsHtml				= '';
		$config[ 'label' ]		= isset( $config[ 'label' ] ) ? $config[ 'label' ] : true;
		$config[ 'modelName' ]	= isset( $config[ 'modelName' ] ) ? $config[ 'modelName' ] : 'GenericForm';

		foreach ( $fields as $key => $field ) {

			// Convert Json to Array
			if( isset( $field->htmlOptions ) && strlen( $field->htmlOptions ) > 0 ) {

				$field->htmlOptions	= json_decode( $field->htmlOptions, true );
			}
			else {

				$field->htmlOptions	= [];
			}

			if( isset( $model ) ) {

				$value		 = $model->fields[ $field->name ]->value; 
				$fieldsHtml .= Yii::$app->formDesigner->getApixFieldHtml( $config, $field, $value );
			}
			else {

				$fieldsHtml .= Yii::$app->formDesigner->getApixFieldHtml( $config, $field );
			}
		}

		return $fieldsHtml;
	}
}

?>