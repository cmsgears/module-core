<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Role';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Role</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-role-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'disabled'=>'true' ] ) ?>
    	<?= $form->field( $model, 'description' )->textarea( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'homeUrl' )->textInput( [ 'disabled'=>'true' ] ) ?>

		<h4>Mapped Permissions</h4>
		<?php 

			$rolePermissions	= $model->getPermissionsIdList();

			foreach ( $permissions as $permission ) { 

				if( in_array( $permission['id'], $rolePermissions ) ) {
		?>		
					<span class="box-half"><input type="checkbox" name="permissions" value="<?=$permission['id']?>" checked disabled /><?=$permission['name']?></span>
		<?php 
				}
				else {
		?>
					<span class="box-half"><input type="checkbox" name="permissions" value="<?=$permission['id']?>" disabled /><?=$permission['name']?></span>
		<?php
				}
			}
		?>
		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/cmgcore/role/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", 1 );
</script>