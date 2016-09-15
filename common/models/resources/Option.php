<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * Option Entity
 *
 * @property long $id
 * @property long $categoryId
 * @property string $name
 * @property string $value
 * @property string $icon
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 */
class Option extends \cmsgears\core\common\models\base\Resource {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    // Protected --------------

    // Variables -----------------------------

    // Public -----------------

    public $mParentType	= CoreGlobal::TYPE_OPTION;

    // Protected --------------

    // Private ----------------

    // Traits ------------------------------------------------------

    use DataTrait;

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii interfaces ------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    // yii\base\Model ---------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'value', 'htmlOptions', 'content', 'data' ], 'safe' ],
            [ 'categoryId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'name', 'icon' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'categoryId', 'name' ], 'unique', 'targetAttribute' => [ 'categoryId', 'name' ] ]
        ];

        // trim if required
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'value', 'icon' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'categoryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CATEGORY ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE ),
            'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
            'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
            'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Validators ----------------------------

    // Option --------------------------------

    /**
     * @return Category - The parent category.
     */
    public function getCategory() {

        return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] );
    }

    // Static Methods ----------------------------------------------

    // Yii parent classes --------------------

    // yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_OPTION;
    }

    // CMG parent classes --------------------

    // Option --------------------------------

    // Read - Query -----------

    public static function queryWithHasOne( $config = [] ) {

        $relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'category' ];
        $config[ 'relations' ]	= $relations;

        return parent::queryWithAll( $config );
    }

    public static function queryWithCategory( $config = [] ) {

        $config[ 'relations' ]	= [ 'category' ];

        return parent::queryWithAll( $config );
    }

    // Read - Find ------------

    /**
     * @return Option - by category id
     */
    public static function findByCategoryId( $categoryId ) {

        return self::find()->where( 'categoryId=:id', [ ':id' => $categoryId ] )->all();
    }

    /**
     * @return Option - by category slug and type
     */
    public static function findByCategorySlugType( $categorySlug, $categoryType ) {

        $categoryTable = CoreTables::TABLE_CATEGORY;

        return self::queryWithCategory( [ 'conditions' => [ "$categoryTable.slug" => $categorySlug, "$categoryTable.type" => $categoryType ] ] )->all();
    }

    /**
     * @return Option - by name and category id
     */
    public static function findByNameCategoryId( $name, $categoryId ) {

        $optionTable = CoreTables::TABLE_OPTION;

        return self::find()->where( "$optionTable.name=:name AND categoryId=:id", [ ':name' => $name, ':id' => $categoryId ] )->one();
    }

    /**
     * @return boolean - check whether option exist by name and category id
     */
    public static function isExistByNameCategoryId( $name, $categoryId ) {

        $option = self::findByNameCategoryId( $name, $categoryId );

        return isset( $option );
    }

    // Create -----------------

    // Update -----------------

    // Delete -----------------

    /**
     * Delete Option - by category id
     */
    public static function deleteByCategoryId( $categoryId ) {

        return self::deleteAll( 'categoryId=:id', [ ':id' => $categoryId ] );
    }
}
