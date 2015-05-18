<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelMessage Entity
 *
 * @property int $localeId
 * @property int $parentId
 * @property string $parentType
 * @property string $name
 * @property string $value
 */
class ModelMessage extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Locale - parent locale.
	 */
	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'localeId', 'parentId', 'parentType', 'name', 'value' ], 'required' ],
            [ [ 'parentType' ], 'string', 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'localeId' => 'Locale',
			'parentId' => 'Parent',
			'parentType' => 'Parent Type',
			'name' => 'Name',
			'value' => 'Value'
		];
	}

	// ModelMessage ----------------------

	/**
	 * Validates to ensure that only one message exist with one name for a particular locale.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameLocaleId( $this->parentId, $this->parentType, $this->name, $this->localeId ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one message exist with one name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingMessage = self::findByNameLocaleId( $this->parentId, $this->parentType, $this->name, $this->localeId );

			if( isset( $existingMessage ) && $existingMessage->id != $this->id && 
				strcmp( $existingMessage->name, $this->name ) == 0 && $existingMessage->localeId == $this->localeId 
				&& $existingMessage->parentId == $this->parentId && $existingMessage->parentType == $this->parentType ) {
	
				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {
		
		return CoreTables::TABLE_LOCALE_MESSAGE;
	}

	// ModelMessage ----------------------

	/**
	 * @param int $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return ModelMessage - by name and locale id
	 */
	public static function findByNameLocaleId( $parentId, $parentType, $name, $localeId ) {

		return self::find()->where( 'localeId=:id AND name=:name AND parentId=:pid AND parentType=:type' )
							->addParams( [ ':id' => $localeId, ':name' => $name, ':pid' => $parentId, ':type' => $type ] )
							->one();
	}

	/**
	 * @param int $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return boolean - check whether message exist by name and locale id
	 */
	public static function isExistByNameLocaleId( $parentId, $parentType, $name, $localeId ) {

		return isset( self::findByNameLocaleId( $parentId, $parentType, $name, $localeId ) );
	}
}

?>