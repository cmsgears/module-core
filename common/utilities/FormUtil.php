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

			$fieldHtml	= null;
	
			if( isset( $field->options ) ) {
	
				$field->options	= json_decode( $field->options, true );
			}
			else {
	
				$field->options	= [];
			}

			switch( $field->type ) {

				case FormField::TYPE_TEXT: {

					$fieldHtml = $form->field( $model, $key )->textInput( $field->options );
					
					if( $config[ 'label' ] ) {
						
						$fieldHtml = $fieldHtml->label( $field->label );
					}
					else {

						$fieldHtml = $fieldHtml->label( false );
					}
					
					break;
				}
				case FormField::TYPE_PASSWORD: {

					$fieldHtml = $form->field( $model, $key )->passwordInput( $field->options );

					if( $config[ 'label' ] ) {
						
						$fieldHtml = $fieldHtml->label( $field->label );
					}
					else {

						$fieldHtml = $fieldHtml->label( false );
					}

					break;
				}
				case FormField::TYPE_TEXTAREA: {

					$fieldHtml = $form->field( $model, $key )->textArea( $field->options );

					if( $config[ 'label' ] ) {
						
						$fieldHtml = $fieldHtml->label( $field->label );
					}
					else {

						$fieldHtml = $fieldHtml->label( false );
					}

					break;
				}
				case FormField::TYPE_CHECKBOX: {

					$fieldHtml = $form->field( $model, $key )->checkbox( $field->options );

					break;
				}
				case FormField::TYPE_SELECT: {

					if( isset( $field->options[ 'options' ] ) ) {

						$fieldOptions	= $field->options;
						$options		= $fieldOptions[ 'options' ];

						unset( $fieldOptions[ 'options' ] );
	
						$fieldHtml 	= $form->field( $model, $key )->dropDownList( $options, $fieldOptions );

						if( $config[ 'label' ] ) {
							
							$fieldHtml = $fieldHtml->label( $field->label );
						}
						else {
	
							$fieldHtml = $fieldHtml->label( false );
						}
					}

					break;
				}
			}

			$fieldsHtml .= $fieldHtml;
		}

		return $fieldsHtml;
	}
}

?>