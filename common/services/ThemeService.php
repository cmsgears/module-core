<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\Theme;

class ThemeService extends \cmsgears\core\common\services\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Activity
	 */
	public static function findById( $id ) {

		return Theme::findById( $id );
	}

	public static function getIdNameMap( $config = [] ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_THEME, $config );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new Theme(), $config );
	}

	// Create -----------

	public static function create( $theme ) {

		// Create Theme
		$theme->save();

		return $theme;
	}

	// Update -----------

	public static function update( $theme ) {

		$themeToUpdate	= self::findById( $theme->id );

		$themeToUpdate->copyForUpdateFrom( $theme, [ 'name', 'description', 'basePath', 'renderer' ] );

		$themeToUpdate->update();

		return $themeToUpdate;
	}

	// Delete -----------

	public static function delete( $theme ) {

		$existingTheme	= self::findById( $theme->id );

		// Delete Theme
		$existingTheme->delete();

		return true;
	}
}

?>