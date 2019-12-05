<?php
preg_match("/.*htdocs\/(.*)\/view.*/", $_SERVER["SCRIPT_FILENAME"], $matches);
$server_location = $matches[1];
?>
<script>
  var server_location = "<?=$server_location?>";
</script>