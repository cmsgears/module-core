<?php
namespace cmsgears\core\common\behaviors;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\validators\UniqueSlugValidator;

class UniqueSluggableBehavior extends \yii\behaviors\SluggableBehavior {

	public $ensureUnique = true;

	/** It might append numbers at the end of slug in some situations where like query return models,
	 *  but much faster than Yii method which iterate and executes n queries.
	 */
    protected function makeUnique( $slug ) {

        $uniqueSlug	= $slug;

        if( !$this->validateSlug( $uniqueSlug ) ) {

			$model			= $this->owner;
			$targetClass 	= get_class( $model );
			$attribute		= $this->slugAttribute;

			$query 		= $targetClass::find();
			$nextNum	= $query->where( "$attribute LIKE '$uniqueSlug%'" )->count();

			$uniqueSlug	= "$uniqueSlug-$nextNum";
        }

        return $uniqueSlug;
    }

    /**
     * @inheritdoc
     */
	protected function validateSlug( $slug ) {

        $validator = Yii::createObject(array_merge(
            [
                'class' => UniqueSlugValidator::className(),
            ],
            $this->uniqueValidator
        ));

        $model = clone $this->owner;
        $model->clearErrors();
        $model->{$this->slugAttribute} = $slug;

        $validator->validateAttribute( $model, $this->slugAttribute );

        return !$model->hasErrors();
    }
}
