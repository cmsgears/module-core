<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Permission';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
	<h1>Delete Permission</h1>
		<?php $form = ActiveForm::begin( ['id' => 'frm-permission-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly'=>'true' ] ) ?>
    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly'=>'true' ] ) ?>

		<h4>Mapped Roles</h4>
		<?php 
			$permissionRoles	= $model->getRolesIdList();

			foreach ( $roles as $role ) { 

				if( in_array( $role['id'], $permissionRoles ) ) {
		?>		
					<span class="box-half"><input type="checkbox" name="roles" value="<?=$role['id']?>" checked readonly /><?=$role['name']?></span>
		<?php 
				}
				else {
		?>
					<span class="box-half"><input type="checkbox" name="roles" value="<?=$role['id']?>" readonly /><?=$role['name']?></span>
		<?php
				}
			}
		?>
		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/cmgcore/permission/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", 2 );
</script>