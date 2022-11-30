<html>

<head>
  <style>
    /** Define the margins of your page **/
    @page {
      margin: 65px 0px;
    }

    header {
      position: fixed;
      top: -60px;
      left: 0px;
      right: 0px;
      height: 50px;

      /** Extra personal styles **/
      background-color: #fff;
      color: #000;
      text-align: center;
      line-height: 35px;
    }
    footer {
      position: fixed;
      bottom: -60px;
      left: 0px;
      right: 0px;
      height: 50px;

      /** Extra personal styles **/
      background-color: #ccc;
      color: #000;
      text-align: center;
      line-height: 25px;
    }

    footer .page:after {
      content: counter(page, upper-roman);
    }

    * {
      box-sizing: border-box;
    }


    .row {
      margin-left: -5px;
      margin-right: -5px;
      display: block;
      ;

    }

    .column {
      float: left;
      width: 50%;
      padding: 0px;
    }

    .column.two {
      margin-top: 50px;
    }

    /* Clearfix (clear floats) */
    .row::after {
      content: "";
      clear: both;
      display: table;
    }

    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;

    }

    th,
    td {
      text-align: left;
      padding: 5px;
      line-height: 10px;
    }

    tr:nth-child(even) {
      padding: 10px;
    }
    .header-wrapper{
      width:100%;
      margin:0 auto;
      padding:20px;
      display:block;
      position:relative;
    }
    .header-wrapper-image{
      width:200px; margin:0 auto; padding:30px 10px

    }
    .mb-20{
      margin-bottom:20px;
    }
    .customer_name{
      color:blue;
    }
    .heading_div{
      width:100%;
      margin-top:185px;
      margin-left:-10;
    }
    .thead{
      border-top:1px solid; 
      border-bottom:1px solid;
      padding-top:10px;
      padding-bottom:5px;
      height:10%
    }
    .mt-20{
      margin-top:20px;
    }
    .text-center{
      text-align :center;
      font-size: 13px;
    }
    .main-table
    {
      margin-top:220px;
    }
    .main-tbody{
      padding-top:10px;padding-bottom:10px;height:10%
    }
    .mt-5{
      margin-top:5px;
    }

    .mb-5{
      margin-bottom:5px;
    }
    .text-left
    {
      text-align: left;
    }
    .text-right
    {
      text-align: right;
    }
    .pl-20{
      padding-left: 20px;
    }
    .pt-20{
      padding-top: 20px;
    }
    thead th{
      line-height: 12px;
    }
  </style>
  <title>Task Sheet - <?php echo $HKAT_TASK_SHEET_ID.' - '.$HKTAO_TASK_DATE;?> </title>
  <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
</head>

<body>
  <!-- Define header and footer blocks before your content -->
  <header>
    <div class="header-wrapper" >
    <strong>Housekeeping Task Sheet</strong>
      
      <div class="row">
        <div class="column">
          <table>
            <thead>
              <tr>
              </tr>
            </thead>
            <tbody>
            <tr>
                <td width="30%">
                
                <img src="<?php echo base_url() ?>/assets/img/HITEK.png" width="60%" >
                 <br><br>
                </td>
               

              </tr>
              
              <tr>
                <td><strong>Task Sheet</strong> </td>
                <td>: <?php echo $HKTAO_TASK_DATE.' / '.$HKT_CODE.' / '.$HKAT_TASK_SHEET_ID;?></td>
              </tr>
              <tr>
                <td>Attendant  </td>
                <td>: <?php echo $USR_ID . ' / '. $ATTENDANT_NAME;?></td>

              </tr>
             
              <tr>
                <td><br><strong>Task Details</strong> </td>
              </tr>
              <tr>
                <td>Task Sheet No</td>
                <td>: <?php echo $HKAT_TASK_SHEET_ID;?></td>

              </tr>
              <tr>
                <td>Instructions</td>
                <td>: <?php echo $HKAT_INSTRUCTIONS;?></td>

              </tr>
              <tr>
                <td><br><strong>Room Details</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
        

        <div class="heading_div" >
          <table border="0">
            <thead class="thead" >
              <tr   >
                <th width="6%;" class="text-center">Room<br> No</th>
                <th width="6%;" class="text-center">Room<br> Type</th>
                <th width="6%;" class="text-center">Room<br> Status</th>
                <th  width="6%;" class="text-center">FO<br> Status </th>
                <th width="6%;"  class="text-center">Reservation<br> Status</th>
                <th  width="6%;" class="text-center">Name</th>
                <th  width="6%;" class="text-center">Arrival</th>
                <th width="6%;"  class="text-center">Departure</th>
              </tr>
            </thead>
          </table>
        </div>

      </div>
    </div>
  </header>

  <footer>
    HITEK Services LLC, P.O. Box: 5423 DXB, Farnek Building, Floor 2, Al Quoz, Dubai<br>
    Tel: +971 4 382 4498, Email: info@hitekservices.com, Website: www.hitekservices.com
  </footer>

  <main >
    <table border="0" class="main-table" >
      <thead></thead>
      <tbody class="main-tbody">
        <?php echo $CONTENT; ?>     
        
      </tbody>
    </table>
  </main>
</body>

</html>