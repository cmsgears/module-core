<?php
namespace cmsgears\core\common\services;

// Yii Imports
use yii\helpers\ArrayHelper;

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

	public static function findBySlugType( $slug, $type ) {

		return Template::findBySlugType( $slug, $type );
	}

	public static function getIdNameMap( $options = [] ) {

		$map = self::findMap( 'id', 'name', CoreTables::TABLE_TEMPLATE, $options );

		if( isset( $options[ 'default' ] ) && $options[ 'default' ] ) {

			unset( $options[ 'default' ] );

			$map = ArrayHelper::merge( [ '0' => 'Choose Template' ], $map );
		}

		return $map;
	}

	public static function getIdNameMapByType( $type, $options = [] ) {

		$options[ 'conditions' ][ 'type' ] = $type;

		return self::getIdNameMap( $options );
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

		$templateToUpdate->copyForUpdateFrom( $template, [ 'name', 'icon', 'description', 'renderer', 'renderFile', 'layout', 'viewPath', 'content' ] );

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