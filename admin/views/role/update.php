<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title	= $coreProperties->getSiteTitle() . ' | Update Role';
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update Role</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-role' ] );?>

		<?= $form->field( $model, 'name' ) ?>
		<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'wrap-icon-picker clearfix' ] ] ) ?>
		<?= $form->field( $model, 'description' )->textarea() ?>
		<?= $form->field( $model, 'adminUrl' ) ?>
		<?= $form->field( $model, 'homeUrl' ) ?>

		<?php if( count( $permissions ) > 0 ) { ?>
		<div class="box-content clearfix">
			<div class="header">Assign Permissions</div>
			<?php
				$modelPermissions	= $model->getPermissionsIdList();

				foreach ( $permissions as $permission ) {

					if( in_array( $permission['id'], $modelPermissions ) ) {
			?>
						<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?= $permission[ 'id' ] ?>" checked /><?= $permission[ 'name' ] ?></span>
			<?php
					}
					else {
			?>
						<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?= $permission[ 'id' ] ?>" /><?= $permission[ 'name' ] ?></span>
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