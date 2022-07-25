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
      line-height: 15px;
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
      margin-top:230px;
      margin-left:0;
    }
    .thead{
      border-top:1px solid; 
      border-bottom:1px solid;
      padding-top:10px;
      padding-bottom:10px;
      height:10%
    }
    .mt-20{
      margin-top:20px;
    }
    .text-center{
      text-align :center;
    }
    .main-table
    {
      margin-top:370px;
    }
    .main-tbody{
      padding-top:10px;padding-bottom:10px;height:10%
    }
  </style>
</head>

<body>
  <!-- Define header and footer blocks before your content -->
  <header>
    <div class="header-wrapper" >
      <div class="header-wrapper-image">
        <img src="<?php echo base_url() ?>/assets/img/farnek.png" width="100%"></th>
      </div>
      <div class="row">
        <div class="column">
          <table>
            <thead>
              <tr>
                <th></th>
                <th></th>

              </tr>
            </thead>
            <tbody>
              <tr class="mb-20" >
                <td colspan="2"><span class="customer_name">Mr. Youssef Mussaid M Aleshaiwi<br><br>
                    Saudi Arabia</td>

              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td colspan="2"><strong>TAX PRO-FORMA INVOICE</strong><br><br></td>

              </tr>
              <tr>
                <td>Conf. No. </td>
                <td>: 29917</td>

              </tr>
              <tr>
                <td>Date </td>
                <td>: 18/07/22</td>

              </tr>
              <tr>
                <td>Folio No. </td>
                <td>: 29917</td>

              </tr>
              <tr>
                <td>Company TRN </td>
                <td>: 29917</td>

              </tr>
            </tbody>
          </table>
        </div>
        <div class="column">
          <table>
            <thead>
              <tr>
                <th></th>
                <th></th>

              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Room No.</td>
                <td>: 130</td>

              </tr>
              <tr>
                <td>Arrival</td>
                <td>: 19/09/21</td>

              </tr>
              <tr>
                <td>Departure</td>
                <td>: 24/09/21</td>
              </tr>
              <tr>
                <td>Cashier No.</td>
                <td>: 20</td>
              </tr>
              <tr>
                <td>User ID</td>
                <td>: AAMIRS,E</td>
              </tr>
              <tr>
                <td>LPO/Voucher No. </td>
                <td>:</td>
              </tr>
              <tr>
                <td>Tax Invoice No </td>
                <td>:</td>
              </tr>
              <tr>
                <td>Property TRN </td>
                <td>: 100565666300003</td>
              </tr>

            </tbody>
          </table>

        </div>


        <div class="heading_div" >

          <table border="0">
            <thead class="thead" >

              <tr class="mt-20 mb-20" >
                <th width="10%;" height="10%" class="text-center" style="padding:20px !important">Date</th>
                <th width="10%;" height="10%" class="text-center">Text</th>
                <th width="10%;" height="10%"></th>
                <th width="10%;" height="10%" class="text-center">Charges AED </th>
                <th width="10%;" height="10%" class="text-center" >Credit AED</th>
              </tr>
            </thead>


          </table>
        </div>

      </div>
    </div>
  </header>

  <footer>
    Farnek Services LLC, P.O. Box: 5423 DXB,Farnek Building, Floor 3, Al Quoz, Dubai<br>
    Tel: +971 (04) 3824400,Fax: +971 (04) 3397456 Email: expovillage@farnek.com
  </footer>

  <main >
    <table border="0" class="main-table" >
      <thead></thead>
      <tbody class="main-tbody" >

        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th >Room Charge </th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr></tr><div style="margin-top:370px;margin-bottom:5px; page-break-after:always"></div></tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr></tr><div style="margin-top:370px;margin-bottom:5px; page-break-after:always"></div></tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr></tr><div style="margin-top:370px;margin-bottom:5px; page-break-after:always"></div></tr>

        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">9/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>
        <tr style="margin-top:5px;margin-bottom:5px; ">
          <th class="text-center">19/09/21</th>
          <th class="text-center">Room Charge</th>
          <th></th>
          <th class="text-center">412.88 </th>
          <th class="text-center">0.00</th>
        </tr>



      </tbody>
    </table>
  </main>
</body>

</html>