<?php 
$notifier = new MNotifier(); 
$notifier->notif_list();
$hide_notif = $notifier->sum_notif > 0 ? null : "hide";
?>
<li class="grey">
		<a href="#" title="Retour" id="call_go_back"><span><i class="fa fa-reply icon-only bigger-150"></i> </span></a>
</li>
<li id="zone_notif" class="purple <?php echo  $hide_notif ?>">
	<a data-toggle="dropdown" class="dropdown-toggle" href="#">
		<i class="ace-icon fa fa-bell icon-animated-bell"></i>
		<span id="sum_notif" class="badge badge-important">
			<?php echo  $notifier->sum_notif ?>
		</span>
	</a>

	<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
		<li class="dropdown-content">
			<ul class="dropdown-menu dropdown-navbar navbar-pink " id="notif_ul">

				<?php echo $notifier->list_notif ?>
				
			</ul>
		</ul>

	</li>


	

