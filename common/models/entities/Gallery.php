<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Query;
use yii\web\IdentityInterface;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\FileTrait;
use cmsgears\core\common\models\traits\MetaTrait;

/**
 * Gallery Entity - The primary class.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class Gallery extends NamedCmgEntity {

	use FileTrait;

	public $fileType	= CoreGlobal::TYPE_GALLERY;

	use MetaTrait;

	public $metaType	= CoreGlobal::TYPE_GALLERY;

	// Instance Methods --------------------------------------------

	// yii\base\Model ---------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
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
			'name' => 'Name',
			'description' => 'Description'
		];
	}
	
	// Gallery ----------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ----------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_GALLERY;
	}

	// Gallery ----------------------------

}

?>