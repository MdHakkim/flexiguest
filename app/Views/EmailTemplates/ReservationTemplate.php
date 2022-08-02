
<html>
  <body style="width:100%;height:auto;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" >
        <tbody>
          <tr>
            <td align="center">
              <table width="1000" border="0" cellspacing="0" cellpadding="0" >
                <tbody>
                  <tr>
                    <td align="center" style="padding: 0;">
                      <table border="0" width="700" style="border-collapse:collapse;" cellspacing="0" cellpadding="0" >
                        <tbody>
                          <tr>
                            <td><img src="https://keysuite.farnek.com:9021/upload/assets/hotel-logo.png"/></td>
                          </tr>
                          <tr>
                            <td style="padding: 10px 0px;"><h1>Hello <?php echo $data['FULLNAME'];?>,</h1></td>
                          </tr>
                          <?php if($mode!='QR'){ ?>
                          <tr>
                            <td style="padding: 10px 0px;"><h1>Greetings from Hotel!</h1></td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <td>
                              <table style="width:100%">
                                <tbody>
                                  <tr>
                                    <td style="font-size:15px;">
                                    <?php if($mode=='QR'){?>
                                      <h3>You have successfully completed your pre- check in process. Please show the below QR-Code to our friendly reception team in order to collect your apartment key. </h3>
                                    <?php }else{ ?>
                                      <h3>Please find your Reservation details below, download the Flexi Guest App to complete your online check-in and save time on arrival. </h3>
                                      <h3>A valid passport or Emirates ID (for UAE Nationals and Residents) is required upon Check-in. </h3>
                                    <?php } ?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="font-size:16px;">
                                      <table border="0"  cellspacing="0" cellpadding="0" >
                                        <tbody>
                                        <tr style="padding-bottom:6px;">
                                          <td><p>Reservation No</p></td>
                                          <td><p>: <?php echo $data['RESV_NO'];?></p></td>
                                        </tr>
                                        <tr style="padding-bottom:6px;">
                                          <td><p>Guest Name </p></td>
                                          <td><p>: <?php echo $data['FULLNAME'];?></p></td>
                                        </tr>
                                        <tr style="padding-bottom:6px;">
                                          <td><p>Check-in Date</p></td>
                                          <td><p>: <?php echo $data['RESV_ARRIVAL_DT'];?></p></td>
                                        </tr>
                                        <tr style="padding-bottom:6px;">
                                          <td><p>Check-out Date</p></td>
                                          <td><p>: <?php echo $data['RESV_DEPARTURE'];?></p></td>
                                        </tr>
                                        <tr style="padding-bottom:6px;">
                                          <td><p>No. of Apartment Unit</p></td>
                                          <td><p>: <?php echo $data['RESV_NO_F_ROOM'];?></p></td>
                                        </tr>
                                        <tr style="padding-bottom:6px;">
                                          <td><p>Apartment Details</p></td>
                                          <td><p>: <?php echo $data['RM_TY_DESC'];?></p></td>
                                        </tr>
                                        <?php if($mode!='QR'){ ?>
                                        <tr style="padding-bottom:6px;">
                                          <td><p>Click here for Pre-Check-in</p></td>
                                          <td><p>: </p></td>
                                        </tr>
                                        
                                        <tr style="padding-bottom:6px;">
                                          <td colspan="3">
                                            <table style="width:100%">
                                              <tbody>
                                                <tr style="padding-bottom:32px;">
                                                  <td>
                                                    <div style="display: flex;">
                                                      <div style=""><a href="https://play.google.com/store/apps/details?id=com.farnek.FkHospitality"><img src="https://keysuite.farnek.com:9021/upload/assets//play-store.png"></a></div>
                                                      <div style="margin-left: 12px;"><a href="https://play.google.com/store/apps/details?id=com.farnek.FkHospitality"><img src="https://keysuite.farnek.com:9021/upload/assets//app-store.png"></a></div>
                                                      <div style="background: #1f29bd;margin-left: 12px;padding: 2px;text-align: center;width: 160px;color: white;border-radius: 7px;line-height: 2.2;height: 39px;"><a style="color: white;text-decoration: none;" href="https://flexiguest.hitekservices.com/webline/ReservationDetail/<?php echo $data['RESV_ID'];?>">Pre Check-In Web</a></div>
                                                    </div>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                        <?php }else{ ?>
                                          <tr style="padding-bottom:6px;">
                                            <td><div id="output"><img src="https://chart.googleapis.com/chart?cht=qr&chl=<?php echo $data['RESV_ID'];?>&chs=160x160&chld=L|0"/></div></td>
                                          </tr>
                                          <tr>
                                            <td><p>Have a safe journey ahead!</p></td>
                                          </tr>
                                        <?php } ?>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <table style="width:100%">
                                <tbody>
                                  <tr style="padding-bottom:32px;">
                                    <td style="font-size:15px;"><p>Regards,</p></td>
                                  </tr>
                                  <tr style="padding-bottom:32px;">
                                    <td style="font-size:15px;"><p>Hotel</p></td>
                                  </tr>
                                  <tr style="padding-bottom:12px;">
                                    <td style="font-size:15px;"><p>Deposit Policy: For additional services, we will require an advance payment by either cash or credit card.</p></td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" style="background:#5d5c5c;color:white;">
                      <table border="0" width="300" style="text-align: center;" cellspacing="0" cellpadding="0" >
                        <tbody>
                          <tr style="padding-bottom:12px;">
                            <td>
                              <p>Dubai</p>
                            </td>
                          </tr>
                          <tr style="padding-bottom:10px;">
                            <td>United Arab Emirates</td>
                          </tr>
                          <tr style="padding-bottom:32px;">
                            <td>Contact us:</td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
  </body>
</html>
<!-- <script src="<?php //echo base_url('assets/js/jquery-3.6.0.min.js')?>"></script> -->
<!-- <script type="text/javascript" src="<?php //echo base_url('assets/js/jquery.qrcode.js');?>"></script>
<script type="text/javascript" src="<?php //echo base_url('assets/js/qrcode.js');?>"></script> -->
<!-- <script>
  jQuery(function(){
    jQuery('#output').qrcode({
      width: 128,
      height: 128,
      text: "<?php //echo $data['RESV_NO'];?>"
    });
  });
</script> -->

<!-- <!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title></title>

  <style>
  table, td, div, h1, p {font-family: Arial, sans-serif;}
  </style>
  </head>
  <body style="margin:0;padding:0;">
  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
  <tr>
  <td align="center" style="padding:0;">
  <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
  <tr>
  <td align="center" style="padding:40px 0 30px 0;background:#70bbd9;">
  <img src="https://assets.codepen.io/210284/h1.png" alt="" width="300" style="height:auto;display:block;" />
  </td>
  </tr>
  <tr>
  <td style="padding:36px 30px 42px 30px;">
  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
  <tr>
  <td style="padding:0 0 36px 0;color:#153643;">
  <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Creating Email Magic</h1>
  <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempus adipiscing felis, sit amet blandit ipsum volutpat sed. Morbi porttitor, eget accumsan et dictum, nisi libero ultricies ipsum, posuere neque at erat.</p>
  <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="http://www.example.com" style="color:#ee4c50;text-decoration:underline;">In tempus felis blandit</a></p>
  </td>
  </tr>
  <tr>
  <td style="padding:0;">
  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
  <tr>
  <td style="width:260px;padding:0;vertical-align:top;color:#153643;">
  <p style="margin:0 0 25px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><img src="https://assets.codepen.io/210284/left.gif" alt="" width="260" style="height:auto;display:block;" /></p>
  <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempus adipiscing felis, sit amet blandit ipsum volutpat sed. Morbi porttitor, eget accumsan dictum, est nisi libero ultricies ipsum, in posuere mauris neque at erat.</p>
  <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="http://www.example.com" style="color:#ee4c50;text-decoration:underline;">Blandit ipsum volutpat sed</a></p>
  </td>
  <td style="width:20px;padding:0;font-size:0;line-height:0;">&nbsp;</td>
  <td style="width:260px;padding:0;vertical-align:top;color:#153643;">
  <p style="margin:0 0 25px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><img src="https://assets.codepen.io/210284/right.gif" alt="" width="260" style="height:auto;display:block;" /></p>
  <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">Morbi porttitor, eget est accumsan dictum, nisi libero ultricies ipsum, in posuere mauris neque at erat. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempus adipiscing felis, sit amet blandit ipsum volutpat sed.</p>
  <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="http://www.example.com" style="color:#ee4c50;text-decoration:underline;">In tempus felis blandit</a></p>
  </td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  </td>
  </tr>
  <tr>
  <td style="padding:30px;background:#ee4c50;">
  <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
  <tr>
  <td style="padding:0;width:50%;" align="left">
  <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
  &reg; Someone, Somewhere 2021<br/><a href="http://www.example.com" style="color:#ffffff;text-decoration:underline;">Unsubscribe</a>
  </p>
  </td>
  <td style="padding:0;width:50%;" align="right">
  <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
  <tr>
  <td style="padding:0 0 0 10px;width:38px;">
  <a href="http://www.twitter.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/tw_1.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
  </td>
  <td style="padding:0 0 0 10px;width:38px;">
  <a href="http://www.facebook.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/fb_1.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
  </td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  </body>
</html> -->

