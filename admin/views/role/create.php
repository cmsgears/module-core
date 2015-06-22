<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Role';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-identity';
$this->params['sidebar-child'] 	= 'role';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Role</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-role-create', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>
    	<?= $form->field( $model, 'homeUrl' ) ?>
		<h4>Assign Permissions</h4>
		<?php foreach ( $permissions as $permission ) { ?>
			<span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?=$permission['id']?>" /><?=$permission['name']?></span>
		<?php } ?>
		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/cmgcore/role/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>