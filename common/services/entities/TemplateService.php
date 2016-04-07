<?php
namespace cmsgears\core\common\services\entities;

// Yii Imports
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Template;

class TemplateService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return Template::findById( $id );
	}

	public static function findBySlug( $slug ) {

		return Template::findBySlug( $slug );
	}

	public static function findByType( $type ) {

		return Template::findByType( $type );
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

		$templateToUpdate->copyForUpdateFrom( $template, [ 'name', 'icon', 'description', 'renderer', 'fileRender', 'layout', 'layoutGroup', 'viewPath', 'content' ] );

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