
<html>
  <body style="width:100%;height:auto;">
      <table width="100%" border="1" cellspacing="0" cellpadding="0" style='border: solid 1px #5c636a8c;font-family: arial; font-size: 13px; color: #000;' cellspacing='0' cellpadding='20' width='650' >
        <tbody>
          <tr>
            <td align="center">
              <table >
                <tbody>                 
                        <tr>
                        <td style='border-bottom:solid 1px #5c636a8c;'>
                          <img src="<?= brandingLogo() ?>"  width="150" />
                            <br>
                        </td>
                        </tr>
                         
                          <tr>
                            <td style="padding: 15px ;"><h3>Hello <?php echo $data['FULLNAME'];?>,</h3></td>
                          </tr>
                          <?php if($mode!='QR' ){ ?>
                          <tr>
                            <td style="padding: 15px"><h3>Greetings from Hotel!</h3></td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <td>
                              <table style="width:100%">
                                <tbody>
                                  <tr>
                                    <td style="font-size:14px;">
                                    <?php if($mode=='QR' && $status == 0){?>
                                      <h3>You have successfully completed your pre- check in process. Please show the below QR-Code to our friendly reception team in order to collect your apartment key. </h3>
                                    <?php }else if($mode=='QR' && $status == 1) {?>
                                      <h3>It is our pleasure to welcome you to Hotel</h3>
                                      <h3>We strive to provide you with a clean and comfortable apartment, providing all the comforts of home, supported with a dedicated, friendly team of hospitality professionals, who are on standby to address all of your concerns and requests.</h3>
                                      <h3>We trust your stay will be an enjoyable one and look forward to serving you during your visit to Dubai. </h3>
                                    <?php } else{?>
                                      <h3>Please find your Reservation details below, download the Flexi Guest App to complete your online check-in and save time on arrival. </h3>
                                      <h3>A valid passport or Emirates ID (for UAE Nationals and Residents) is required upon Check-in. </h3>
                                    <?php } ?>
                                    </td>
                                    <br>
                                  </tr>
                                  <?php if($status != 1) { ?>
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
                                          <td><p>: <?php echo $data['RM_TY_DESC'];?></p><br></td>
                                          
                                        </tr>
                                        <?php if($mode!='QR'){ ?>                                        
                                        
                                        <tr style="padding-bottom:6px;">
                                          <td colspan="3">
                                            <table style="width:100%">
                                              <tbody>
                                                <tr style="padding-bottom:32px;">
                                                  <td><br>
                                                    <div style="display: flex;">
                                                      <div style="float:left"><a href="https://play.google.com/store/apps/details?id=com.farnek.FkHospitality"><img src="https://keysuite.farnek.com:9021/upload/assets//play-store.png"></a></div>
                                                      <div style="margin-left: 12px;float:left"><a href="https://play.google.com/store/apps/details?id=com.farnek.FkHospitality"><img src="https://keysuite.farnek.com:9021/upload/assets//app-store.png"></a></div>
                                                      <div style="background: #1f29bd;margin-left: 12px;padding: 2px;text-align: center;width: 160px;color: white;border-radius: 7px;line-height: 2.2;height: 39px;"><a style="color: white;text-decoration: none;" href="https://flexiguest.hitekservices.com/webline/ReservationDetail/<?php echo $data['RESV_ID'];?>">Pre Check-In Web</a></div>
                                                    </div>
                                                  </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                        <?php } ?>                                        
                                          <tr style="padding-bottom:6px;">
                                            <td><br><p>Please show the below QR-Code to our friendly reception team. </p><div id="output"><img src="https://chart.googleapis.com/chart?cht=qr&chl=<?php echo $data['RESV_ID'];?>&chs=160x160&chld=L|0"/></div></td>
                                          </tr>
                                          <tr>
                                            <td><h3>Have a safe journey ahead!</h3></td>
                                          </tr>
                                        <?php  ?>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <table style="width:100%">
                                <tbody>
                                  <tr style="padding-bottom:20px;">
                                    <td><p style="font-size:15px;"><strong>Regards,</strong></p></td>
                                  </tr>
                                  <tr style="padding-bottom:32px;">
                                    <td ><p style="font-size:15px;"><strong>Hotel</strong></p></td>
                                  </tr>
                                  <tr style="padding-bottom:13px;">
                                    <td><p  style="font-size:13px;">Deposit Policy: For additional services, we will require an advance payment by either cash or credit card.</p></td>
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

                  <td style='font-family: arial;font-size: 13px;color: #fff;border-top:solid 1px #ccc;padding: 25px 10px 10px 10px;text-align:center;background: #0366ad;'>

                      Copyright &copy; <?php echo date('Y')?>. All rights reserved.

                      <br>

                      <br>

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