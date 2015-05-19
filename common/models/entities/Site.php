<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\MetaTrait;

/**
 * Site Entity
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 */
class Site extends NamedCmgEntity {

	use MetaTrait;

	public $metaType	= CoreGlobal::TYPE_SITE;

	// Instance Methods --------------------------------------------

	/**
	 * @return array - list of site Users
	 */
	public function getUsers() {

    	return $this->hasMany( User::className(), [ 'id' => 'memberId' ] )
					->viaTable( CoreTables::TABLE_SITE_MEMBER, [ 'siteId' => 'id' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'name' => 'Name'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_SITE;
	}

	// Site ------------------------------

	/**
	 * @return Site - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	/**
	 * @return Site - by name
	 */
	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}
}

?>