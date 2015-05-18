<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * Newsletter Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $description
 * @property longtext $content
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $lastSentAt
 */
class Newsletter extends NamedCmgEntity {

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description', 'content' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'createdAt', 'modifiedAt', 'lastSentAt' ], 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'name' => 'Name',
			'description' => 'Description',
			'content' => 'Content'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_NEWSLETTER;
	}

	// Newsletter ------------------------

}

?>