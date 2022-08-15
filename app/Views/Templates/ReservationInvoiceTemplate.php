<style>
    .main {
        width: 85%;
        margin: 0 auto;
    }

    .container {
        margin-top: 20px;
    }

    .text-center {
        text-align: center;
    }

    .logo {
        color: cornflowerblue;
        font-family: cursive;
        font-size: 40px;
    }

    .table-1 table,
    .table-2 table {
        width: 100%;
    }

    .font-bold {
        font-weight: bold;
    }

    .table-2 {
        margin-top: 50px;
    }

    .table-2 table {
        border-collapse: collapse;
    }

    .table-2 table th {
        border-top: 2px solid black;
        border-bottom: 2px solid black;
    }

    .table-2 table td,
    .table-2 table th {
        text-align: center;
        padding: 5px;
    }

    .footer {
        margin-top: 50px;
    }
    .border-top-bottom {
        border-top: 2px solid black;
        border-bottom: 2px solid black;
    }
</style>

<div class="main">
    <div class="text-center">
        <!-- <img src="D:\wamp64\www\FlexiGuest\assets\img\farnek.png" height="50px" /> -->
        <!-- <h1 class="logo">FlexiGuest</h1> -->
        <img src="<?= $reservation['branding_logo'] ?>" width="190px;" />
    </div>

    <div class="container">
        <div class="table-1">
            <table>
                <tr>
                    <td colspan="3" class="font-bold">
                        <?= $reservation['CUST_FIRST_NAME'] ?> <?= $reservation['CUST_MIDDLE_NAME'] ?> <?= $reservation['CUST_LAST_NAME'] ?>
                    </td>

                    <td>Room No.</td>
                    <td>:</td>
                    <td><?= $reservation['RESV_ROOM'] ?> (<?= $reservation['RM_DESC'] ?>)</td>
                </tr>

                <tr>
                    <td colspan="3" class="font-bold">
                        <?= $reservation['CUST_ADDRESS_1'] ?> <?= $reservation['CUST_ADDRESS_2'] ?> <?= $reservation['CUST_ADDRESS_3'] ?>
                    </td>

                    <td>Arrival</td>
                    <td>:</td>
                    <td><?= $reservation['RESV_ARRIVAL_DT'] ?></td>
                </tr>

                <tr>
                    <td colspan="3" class="font-bold"><?= $reservation['STATE_NAME'] ?></td>

                    <td>Departure</td>
                    <td>:</td>
                    <td><?= $reservation['RESV_DEPARTURE'] ?></td>
                </tr>

                <tr>
                    <td colspan="3" class="font-bold"><?= $reservation['CITY_NAME'] ?> <?= $reservation['COUNTRY_NAME'] ?></td>


                    <td colspan="3"></td>
                    <!-- <td>Page No.</td>
                    <td>:</td>
                    <td>1 of 2</td> -->
                </tr>

                <tr>
                    <td colspan="6"><br /></td>
                </tr>

                <tr>
                    <td colspan="3"><b>INFORMATION TAX INVOICE</b></td>

                    <td>Cashier No.</td>
                    <td>:</td>
                    <td></td>
                </tr>

                <tr>
                    <td>ConfNo.</td>
                    <td>:</td>
                    <td></td>

                    <td>User ID</td>
                    <td>:</td>
                    <td>CUS<?= $reservation['CUST_ID'] ?></td>
                </tr>

                <tr>
                    <td>Date</td>
                    <td>:</td>
                    <td><?= date('Y-m-d') ?></td>

                    <td>LPO/Voucher No.</td>
                    <td>:</td>
                    <td></td>
                </tr>

                <tr>
                    <td>Folio No. :</td>
                    <td>:</td>
                    <td></td>

                    <td>Tax Invoice No. :</td>
                    <td>:</td>
                    <td></td>
                </tr>

                <tr>
                    <td>Company TRN</td>
                    <td>:</td>
                    <td></td>

                    <td>Property TRN</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="table-2">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Unit Rate</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td><?= $reservation['RESV_ARRIVAL_DT'] ?> - <?= $reservation['RESV_DEPARTURE'] ?></td>
                        <td>Reservation</td>
                        <td><?= $reservation['RESV_RATE'] ?></td>
                        <td>1</td>
                        <td><?= $reservation['RESV_RATE'] ?></td>
                    </tr>

                    <tr>
                        <td>2022-07-01</td>
                        <td>Laudry</td>
                        <td>20</td>
                        <td>1</td>
                        <td>20</td>
                    </tr>

                    <tr>
                        <td>2022-07-10</td>
                        <td>Additional Cleaning</td>
                        <td>100</td>
                        <td>1</td>
                        <td>100</td>
                    </tr>

                    <br/>

                    <tr>
                        <td></td>
                        <td></td>
                        <td colspan="2" class="border-top-bottom"><b>Total</b></td>
                        <td class="border-top-bottom"><b><?= 534.25 ?></b></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td colspan="2"><b>Net</b></td>
                        <td><b><?= 534.25 ?></b></td>
                    </tr>

                    <!-- <tr>
                        <td>19-12-2022</td>
                        <td>Laundry</td>
                        <td>7.00</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>19-12-2022</td>
                        <td>Visa (M)</td>
                        <td></td>
                        <td>23</td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer text-center">
        <p><b>Thank You for your visit and stay safe.</b></p>
    </div>
</div>