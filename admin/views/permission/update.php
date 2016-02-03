<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Permission';
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update Permission</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-permission' ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>

		<?php if( count( $roles ) > 0 ) { ?>
		<div class="box-content clearfix">
			<div class="header">Assign Roles</div>
			<?php 
				$modelRoles	= $model->getRolesIdList();
	
				foreach ( $roles as $role ) { 
	
					if( in_array( $role[ 'id' ], $modelRoles ) ) {
			?>		
						<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$role['id']?>" checked /><?=$role['name']?></span>
			<?php 
					}
					else {
			?>
						<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$role['id']?>" /><?=$role['name']?></span>
			<?php
					}
				}
			?>
		</div>
		<?php } ?>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>