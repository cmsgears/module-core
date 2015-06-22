<?php
// Yii Imports
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;

$core	= Yii::$app->cmgCore;
$user	= Yii::$app->user->getIdentity();
?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'identity' ) ) { ?>
	<div class="collapsible-tab has-children" id="sidebar-identity">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf4"><span class="icon-sidebar icon-user"></span></div>
			<div class="colf colf4x3">Users & RBAC</div>
		</div>
		<div class="collapsible-tab-content clear">
			<ul>
				<?php if( $user->isPermitted( 'identity' ) ) { ?>
					<li class='matrix'><?= Html::a( "Access Matrix", ['/cmgcore/permission/matrix'] ) ?></li>
					<li class='role'><?= Html::a( "Roles", ['/cmgcore/role/all'] ) ?></li>
					<li class='permission'><?= Html::a( "Permissions", ['/cmgcore/permission/all'] ) ?></li>
				<?php } ?>
				<?php if( $user->isPermitted( 'identity' ) ) { ?>
					<li class='admin'><?= Html::a( "Admins", ['/cmgcore/user/admins'] ) ?></li>
					<li class='user'><?= Html::a( "Users", ['/cmgcore/user/users'] ) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'core' ) ) { ?>
	<div class="collapsible-tab has-children" id="sidebar-newsletter">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf4"><span class="icon-sidebar icon-newsletter"></span></div>
			<div class="colf colf4x3">Newsletters</div>
		</div>
		<div class="collapsible-tab-content clear">
			<ul>
				<li class='newsletter'><?= Html::a( "Newsletters", ['/cmgcore/newsletter/all'] ) ?></li>
				<li class='member'><?= Html::a( "Members", ['/cmgcore/newsletter/members'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'core' ) ) { ?>
	<div class="collapsible-tab" id="sidebar-gallery">
		<div class="collapsible-tab-header">
			<a href="<?php echo Url::toRoute( ['/cmgcore/gallery/all'] ); ?>">
				<div class="colf colf4"><span class="icon-sidebar icon-settings"></span></div>
				<div class="colf colf4x3">Galleries</div>
			</a>
		</div>
	</div>
<?php } ?>