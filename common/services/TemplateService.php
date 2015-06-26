<?php
namespace cmsgears\core\common\services;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Template;

class TemplateService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return Template::findById( $id );
	}

	public static function getIdNameMap( $type = null ) {

		if( isset( $type ) ) {

			return self::findMap( "id", "name", CoreTables::TABLE_TEMPLATE, [ 'type' => $type ] );
		}
		else {

			return self::findMap( "id", "name", CoreTables::TABLE_TEMPLATE );
		}
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Template(), $config );
	}

	// Create -----------

	public static function create( $template ) {

		$template->save();

		return $template;
	}

	// Update -----------

	public static function update( $template ) {

		$templateToUpdate	= self::findById( $template->id );

		$templateToUpdate->copyForUpdateFrom( $menu, [ 'name', 'description' ] );

		$templateToUpdate->update();

		return $templateToUpdate;
	}

	// Delete -----------

	public static function delete( $template ) {

		$existingTemplate	= self::findById( $template->id );

		// Delete Template
		$existingTemplate->delete();

		return true;
	}
}

?>