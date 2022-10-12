

<html>
    <head>
    <title>FlexiGuest</title>
    </head>
    <body>

    <table style='border: solid 1px #ccc;font-family: arial; font-size: 13px; color: #000;' cellspacing='0' cellpadding='20' width='650'>
    <tr>
    <td style='border-bottom:solid 2px #ccc;'>
    <div>
        <img src="<?= brandingLogo() ?>"  width="150" />
    </div>
        <br>
    </td>
    </tr>
    <tr>  <td style='font-family: arial; font-size: 13px; color: #000;'>  <b> Dear&nbsp; <?= $FULL_NAME ?>,
    
    
    </td></tr>
    
    <tr>  <td style='font-family: arial; font-size: 13px; color: #000;'>
    <?= $HEADING ?>
    <p><?php if(($NOTIFICATION_TYPE_ID == 4) && !empty($RESERVATION ))  { echo 'Reservations : '.$RESERVATION; } ?></p>
    <p><?= $NOTIFICATION_TEXT?></p>
    
    <p><?php if(($NOTIFICATION_TYPE_ID == 3 || $NOTIFICATION_TYPE_ID == 4) && $NOTIFICATION_URL != '')  { ?><a href="<?php echo $NOTIFICATION_URL; ?>">Click here</a><?php } ?></p>
    </td></tr>
    <tr>
        <td>
        Best Regards,<br><br>
            <b>FlexiGuest</b>
            <br>
            <br>
        </td>
    </tr>
    <tr>
        <td style='font-family: arial;font-size: 13px;color: #fff;border-top:solid 1px #ccc;padding: 25px 10px 10px 10px;text-align:center;background: #0366ad;'>
        Copyright &copy <?=date('Y')?>	. All rights reserved.
            <br>
            <br>
        </td>
    </tr>
    </table>
    </body>
</html>