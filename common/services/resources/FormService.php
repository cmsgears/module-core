<?php
namespace cmsgears\core\common\services\resources;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\mappers\ModelForm;

class FormService extends \cmsgears\core\common\services\base\Service {

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

		// Create Form
		$form->save();

		return $form;
	}

	// Update -----------

	public static function update( $form ) {

		$formToUpdate	= self::findById( $form->id );

		$formToUpdate->copyForUpdateFrom( $form, [ 'templateId', 'name', 'description', 'successMessage', 'captcha',
													'visibility', 'active', 'userMail', 'adminMail', 'htmlOptions', 'data' ] );

		$formToUpdate->update();

		return $formToUpdate;
	}

	// Delete -----------

	public static function delete( $form ) {

		// Find existing form
		$existingForm	= self::findById( $form->id );

		// Delete dependency
		ModelForm::deleteByFormId( $form->id );

		// Delete Form
		$existingForm->delete();

		return true;
	}
}

?>