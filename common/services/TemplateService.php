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

	public static function findByType( $type ) {

		return Template::findByType( $type );
	}

	public static function findBySlugType( $id, $type ) {

		return Template::findBySlugType( $id, $type );
	}

	public static function getIdNameMap( $options = [] ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_TEMPLATE, $options );
	}

	public static function getIdNameMapByType( $type ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_TEMPLATE, [ 'conditions' => [ 'type' => $type ] ] );
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

		$templateToUpdate->copyForUpdateFrom( $template, [ 'name', 'description', 'layout', 'viewPath', 'adminView', 'frontendView', 'content' ] );

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