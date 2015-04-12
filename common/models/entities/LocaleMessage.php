<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * LocaleMessage Entity
 *
 * @property integer $id
 * @property integer $localeId 
 * @property short $type
 * @property integer $parentId 
 * @property string $key
 * @property string $value
 */
class LocaleMessage extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Locale - parent Locale.
	 */
	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'localeId', 'type', 'key', 'value' ], 'required' ],
            [ [ 'id', 'parentId' ], 'safe' ],
            [ 'key', 'alphanumhyphenspace' ],
            [ 'key', 'length', 'min'=>1, 'max'=>100 ],
            [ 'key', 'validateKeyCreate', 'on' => [ 'create' ] ],
            [ 'key', 'validateKeyUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'locale' => 'Locale',
			'key' => 'Key',
			'value' => 'Value'
		];
	}

	// Config ----------------------------
	
	/**
	 * Validates to ensure that only one message exist with one key.
	 */
    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			if( isset( $this->parentId ) ) {

	            if( self::isExistByLocaleIdKeyParentId( $this->localeId, $this->key, $this->parentId, $this->type ) ) {

					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
	            }
			}
			else {

	            if( self::isExistByLocaleIdKey( $this->localeId, $this->key ) ) {
	
					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
	            }
			}
        }
    }

	/**
	 * Validates to ensure that only one message exist with one key.
	 */
    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			if( isset( $this->parentId ) ) {

				$existingMessage = self::findByLocaleIdKeyParentId( $this->localeId, $this->key, $this->parentId, $this->type );

				if( isset( $existingMessage ) && $existingMessage->id != $this->id && 
					strcmp( $existingMessage->key, $this->key ) == 0 && $existingMessage->localeId == $this->localeId 
					&& $existingMessage->parentId == $this->parentId && $existingMessage->type == $this->type ) {
	
					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
				}
			}
			else {

				$existingMessage = self::findByLocaleIdKey( $this->localeId, $this->key );
	
				if( isset( $existingMessage ) && $existingMessage->id != $this->id && 
					strcmp( $existingMessage->key, $this->key ) == 0 && $existingMessage->localeId == $this->localeId ) {
	
					$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
				}
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {
		
		return CoreTables::TABLE_LOCALE_MESSAGE;
	}

	// LocaleMessage

	public static function findById( $id ) {

		return Role::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByLocale( $locale ) {

		return self::find()->where( 'localeId=:id', [ ':id' => $locale->id ] )->one();
	}

	public static function findByLocaleId( $localeId ) {

		return self::find()->where( 'localeId=:id', [ ':id' => $localeId ] )->one();
	}

	public static function findByLocaleKey( $locale, $key ) {

		return self::find()->where( [ 'localeId=:id', 'key=:key' ] )
							->addParams( [ ':id' => $locale->id, ':key' => $key ] )
							->one();
	}

	public static function findByLocaleIdKey( $localeId, $key ) {

		return self::find()->where( [ 'localeId=:id', 'key=:key' ] )
							->addParams( [ ':id' => $localeId, ':key' => $key ] )
							->one();
	}

	public static function findByLocaleIdKeyParentId( $localeId, $key, $parentId, $type ) {

		return self::find()->where( [ 'localeId=:id', 'key=:key', 'parentId=:pid', 'type=:type' ] )
							->addParams( [ ':id' => $localeId, ':key' => $key, ':pid' => $parentId, ':type' => $type ] )
							->one();
	}

	public static function isExistByLocaleIdKey( $localeId, $key ) {

		return isset( self::findByLocaleIdKey( $localeId, $key ) );
	}

	public static function isExistByLocaleIdKeyParentId( $localeId, $key, $parentId, $type ) {

		return isset( self::findByLocaleIdKeyParentId( $localeId, $key, $parentId, $type ) );
	}
}

?>