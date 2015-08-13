<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\MetaTrait;

/**
 * Site Entity
 *
 * @property integer $id
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
     * @inheritdoc
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
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_SITE;
	}

	// Site ------------------------------

}

?>