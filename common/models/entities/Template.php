<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Template Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $layout
 * @property string $view
 */
class Template extends NamedCmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'name', 'type' ], 'required' ],
            [ [ 'id', 'description', 'layout', 'view' ], 'safe' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'layout' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LAYOUT ),
			'view' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VIEW )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_TEMPLATE;
	}

	// Template --------------------------

	/**
	 * @return array - Template by type
	 */
	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	/**
	 * @return array - Template available for pages
	 */
	public static function findForPages() {

		return self::find()->where( 'type=:type', [ ':type' => self::TYPE_PAGE ] )->all();
	}

	/**
	 * @return array - Template available for widgets
	 */
	public static function findForWidgets() {

		return self::find()->where( 'type=:type', [ ':type' => self::TYPE_WIDGET ] )->all();
	}
}

?>