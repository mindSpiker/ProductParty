<?php
require_once 'PPFuncts.php';
?><!DOCTYPE:HTML>
<html>
<head>
<title>Settings Setup Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
</style>
</head>
<body>
<center>
<p><strong>Event Settings</strong></p>
<form method="post" action="SaveSettings.php">
Event Name: <input type="text" size="36" maxlength="150" name="eventName" value="<?php echo PP_EVENT_NAME;?>"><br />
Product Name: <input type="text" size="12" maxlength="36" name="productName" value="<?php echo PP_PRODUCT;?>"><br />
Ranks Needed to Allow Reveal: <input type="text" size="12" name="ranksNeeded" value="<?php echo PP_RANKS_NEEDED_TO_REVEAL;?>"><br /> 
<?php 
if (PP_ADMIN_PW == "") {
    echo "<input type=\"hidden\" name=\"oldAdminPW\" value=\"\" />";
} else {
    echo "Old Admin Password: <input type=\"password\" size=\"12\" maxLength=\"36\" name=\"oldAdminPW\"><p />";
}
?>
New Admin Password: <input type="password" size="12" maxLength="36" name="newAdminPW"><p />
<input type="submit" value="Save Settings" name="submit">
</form>
</center>
</body>
</html>
