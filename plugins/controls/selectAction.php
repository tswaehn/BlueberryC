
<?php

  
?>

<h3>Raspi Tools</h3>

<div id="controls_item">
<a href="<?php postToMe('reboot_raspi'); ?>">reboot Raspi</a> 
<br>
reboots the raspi immediatly and will take a while</li>
</div>

<div id="controls_item">
<a href="<?php postToMe('shutdown_raspi'); ?>">shutdown Raspi</a> 
<br>
shutdown of the raspi immediatly, will take a while </li>
</div>


<h3>Logging Tools</h3>

<div id="controls_item">
<a href="<?php postToMe('dmesg'); ?>">dmesg</a>
<br>
shows messages regarding to devices attached to the raspi
</div>

<div id="controls_item">
<a href="<?php postToMe('messages'); ?>">messages</a>
<br>
shows linux messages /var/log/messages
</div>

<div id="controls_item">
<a href="<?php postToMe('syslog'); ?>">syslog</a>
<br>
shows linux system messages /var/log/syslog
</div>

