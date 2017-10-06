<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use Yii;

/**
 * Entity - It's the parent entity for all the entities.
 */
abstract class Entity extends \yii\db\ActiveRecord {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	/**
	 * It can be used to differentiate multisite models. The model must have siteId column referring to associated site.
	 */
	public static $multiSite = false;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	/**
	 * It can be used by models to pass variables to trait methods before method execution. This will help us to avoid maintaining object state in traits.
	 */
	public $traitParams = [];

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Entity --------------------------------

	// Check whether model already exist
	public function isExisting() {

		return isset( $this->id ) && $this->id > 0;
	}

	/**
	 * The method allows to update a model for selected columns to target model.
	 */
	public function copyForUpdateTo( $toModel, $attributes = [] ) {

		foreach ( $attributes as $attribute ) {

			$toModel->setAttribute( $attribute, $this->getAttribute( $attribute ) );
		}
	}

	/**
	 * The method allows to update a model for selected columns from target model.
	 */
	public function copyForUpdateFrom( $fromModel, $attributes = [] ) {

		foreach ( $attributes as $attribute ) {

			if( $this->hasAttribute( $attribute ) ) {

				$this->setAttribute( $attribute, $fromModel->getAttribute( $attribute ) );
			}
			else {

				$this->$attribute = $fromModel->$attribute;
			}
		}
	}

	public function getMediumText( $attribute, $link = false ) {

		if( strlen( $attribute ) > Yii::$app->core->mediumText ) {

			$attribute	= substr( $attribute, 0, Yii::$app->core->mediumText );

			if( $link ) {

				$attribute	= "$attribute ... $link";
			}
			else {

				$attribute	= "$attribute ...";
			}
		}

		return $attribute;
	}

	public function getClasspath() {

		return get_class( $this );
	}

	public function getNamespace() {

		$name	= get_class( $this );

		return array_slice(explode('\\', $name), 0, -1);
	}

	public function getClassname() {

		$name	= get_class( $this );

		return join( '', array_slice( explode( '\\', $name ), -1 ) );
	}

	public function getAttributeArray( $attributes ) {

		$data = [];

		foreach ( $attributes as $attribute ) {

			$data[ $attribute ] = $this->$attribute;
		}

		return $data;
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// Entity --------------------------------

	// Read - Query -----------

	/**
	 * The method queryWithAll generate query with model relations.
	 */
	public static function queryWithAll( $config = [] ) {

		// query generation
		$query			= static::find();
		$relations		= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [];
		$conditions		= isset( $config[ 'conditions' ] ) ? $config[ 'conditions' ] : null;
		$filters		= isset( $config[ 'filters' ] ) ? $config[ 'filters' ] : null;

		$groups			= isset( $config[ 'groups' ] ) ? $config[ 'groups' ] : null;

		// Relations -----------

		$eager	= false;
		$join	= 'LEFT JOIN';

		foreach ( $relations as $relation ) {

			if( is_array( $relation ) && isset( $relation[ 'relation' ] ) ) {

				$eager	= isset( $relation[ 'eager' ] ) ? $relation[ 'eager' ] : false;
				$join	= isset( $relation[ 'join' ] ) ? $relation[ 'join' ] : 'LEFT JOIN';

				$query->joinWith( $relation[ 'relation' ], $eager, $join );
			}
			else {

				$query->joinWith( $relation );
			}
		}

		// Conditions ----------

		if( isset( $conditions ) ) {

			$query	= $query->andWhere( $conditions );
		}

		// Filters -------------

		if( isset( $filters ) ) {

			foreach ( $filters as $filter ) {

				$query	= $query->andFilterWhere( $filter );
			}
		}

		// Grouping -------------

		if( isset( $groups ) ) {

			foreach ( $groups as $group ) {

				$query	= $query->groupBy( $groups );
			}
		}

		return $query;
	}

	public static function queryWithHasOne( $config = [] ) {

		return static::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Returns row count for the model
	 */
	public static function findCount( $conditions = [] ) {

		return self::find()->where( $conditions )->count();
	}

	// Default Searching - Useful for id based models

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	public static function deleteById( $id ) {

		self::deleteAll( 'id=:id', [ ':id' => $id ] );
	}

}
