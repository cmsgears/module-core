<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class LocaleMessage extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'locale', 'type', 'key', 'value' ], 'required' ],
            [ 'parent', 'safe' ],
            [ 'key', 'alphanumhyphenspace' ],
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

	// Config

    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByLocaleIdKey( $this->localeId, $this->key ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingMessage = self::findByLocaleIdKey( $this->localeId, $this->key );

			if( isset( $existingMessage ) && $existingMessage->id != $this->id && 
				strcmp( $existingMessage->key, $this->key ) == 0 && $existingMessage->localeId == $this->localeId ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
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

		return self::find()->where( [ 'id' => $id ] )->one();
	}

	public static function findByLocale( $locale ) {

		return self::find()->where( [ 'localeId' => $locale->id ] )->one();
	}

	public static function findByLocaleId( $localeId ) {

		return self::find()->where( 'localeId=:id', [ ':id' => $localeId ] )->one();
	}

	public static function findByLocaleKey( $locale, $key ) {

		return self::find()->where( 'localeId=:id', [ ':id' => $locale->id ] )->andWhere( 'key=:id', [ ':id' => $key ] )->one();
	}

	public static function findByLocaleIdKey( $localeId, $key ) {

		return self::find()->where( 'localeId=:id', [ ':id' => $localeId ] )->andWhere( 'key=:id', [ ':id' => $key ] )->one();
	}
	
	public static function isExistByLocaleIdKey( $localeId, $key ) {

		return isset( self::findByLocaleIdKey( $localeId, $key ) );
	}
}

?>