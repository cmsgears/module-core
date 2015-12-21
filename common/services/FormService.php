<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Form;

class FormService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Form
	 */
	public static function findById( $id ) {

		return Form::findById( $id );
	}

	/**
	 * @param string $name
	 * @return Form
	 */
    public static function findByName( $name ) {

		return Form::findByName( $name );
    }

	/**
	 * @param string $slug
	 * @return Form
	 */
    public static function findBySlug( $slug ) {

		return Form::findBySlug( $slug );
    }

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Form(), $config );
	}

	// Create -----------

	public static function create( $form ) {

		if( isset( $form->templateId ) && $form->templateId <= 0 ) {

			unset( $form->templateId );
		}

		// Create Form
		$form->save();

		return $form;
	}

	// Update -----------

	public static function update( $form ) {

		if( isset( $form->templateId ) && $form->templateId <= 0 ) {

			unset( $form->templateId );
		}

		$formToUpdate	= self::findById( $form->id );

		$formToUpdate->copyForUpdateFrom( $form, [ 'templateId', 'name', 'description', 'successMessage', 'captcha', 
													'visibility', 'active', 'userMail', 'adminMail', 'options', 'data' ] );

		$formToUpdate->update();

		return $formToUpdate;
	}

	// Delete -----------

	public static function delete( $form ) {

		$existingForm	= self::findById( $form->id );

		// Delete Form
		$existingForm->delete();

		return true;
	}
}

?>