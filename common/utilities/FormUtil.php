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
			if( isset( $field->options ) && strlen( $field->options ) > 0 ) {

				$field->options	= json_decode( $field->options, true );
			}
			else {

				$field->options	= [];
			}

			$fieldsHtml .= Yii::$app->formDesigner->getFieldHtml( $form, $model, $config, $key, $field );
		}

		return $fieldsHtml;
	}

	public static function getApixFieldsHtml( $form, $config = [] ) {

		$fields 			= $form->fields;
		$fieldsHtml			= '';
		$config[ 'label' ]	= isset( $config[ 'label' ] ) ? $config[ 'label' ] : true;
		$config[ 'model' ]	= isset( $config[ 'model' ] ) ? $config[ 'model' ] : 'GenericForm';

		foreach ( $fields as $key => $field ) {

			// Convert Json to Array
			if( isset( $field->options ) && strlen( $field->options ) > 0 ) {

				$field->options	= json_decode( $field->options, true );
			}
			else {

				$field->options	= [];
			}

			$fieldsHtml .= Yii::$app->formDesigner->getApixFieldHtml( $form, $config, $field );
		}

		return $fieldsHtml;
	}
}

?>