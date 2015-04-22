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
			<div class="colf colf4x3">Users & Roles</div>
		</div>
		<div class="collapsible-tab-content clear">
			<ul>
				<?php if( $user->isPermitted( 'identity-rbac' ) ) { ?>
					<li><?= Html::a( "Access Matrix", ['/cmgcore/permission/matrix'] ) ?></li>
					<li><?= Html::a( "Roles", ['/cmgcore/role/all'] ) ?></li>
					<li><?= Html::a( "Permissions", ['/cmgcore/permission/all'] ) ?></li>
				<?php } ?>
				<?php if( $user->isPermitted( 'identity-user' ) ) { ?>
					<li><?= Html::a( "Users", ['/cmgcore/user/all'] ) ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'newsletter' ) ) { ?>
	<div class="collapsible-tab has-children" id="sidebar-newsletter">
		<div class="collapsible-tab-header clearfix">
			<div class="colf colf4"><span class="icon-sidebar icon-newsletter"></span></div>
			<div class="colf colf4x3">Newsletters</div>
		</div>
		<div class="collapsible-tab-content clear">
			<ul>
				<li><?= Html::a( "Newsletters", ['/cmgcore/newsletter/all'] ) ?></li>
				<li><?= Html::a( "Members", ['/cmgcore/newsletter/members'] ) ?></li>
			</ul>
		</div>
	</div>
<?php } ?>

<?php if( $core->hasModule( 'cmgcore' ) && $user->isPermitted( 'settings' ) ) { ?>
	<div class="collapsible-tab" id="sidebar-setting">
		<div class="collapsible-tab-header">
			<a href="<?php echo Url::toRoute( ['/cmgcore/settings/index?type=core'] ); ?>">
				<div class="colf colf4"><span class="icon-sidebar icon-settings"></span></div>
				<div class="colf colf4x3">Settings</div>
			</a>
		</div>
	</div>
<?php } ?>