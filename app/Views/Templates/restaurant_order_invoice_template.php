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

    .border-top-bottom {
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
</style>

<div class="main">
    <div class="text-center">
        <!-- <img src="D:\wamp64\www\FlexiGuest\assets\img\farnek.png" height="50px" /> -->
        <h1 class="logo">FlexiGuest</h1>
    </div>

    <div class="container">
        <div class="table-1">
            <table>
                <tr>
                    <td colspan="3" class="font-bold">
                        <?= $data['CUST_FIRST_NAME'] ?> <?= $data['CUST_MIDDLE_NAME'] ?> <?= $data['CUST_LAST_NAME'] ?>
                    </td>

                    <td>Room No.</td>
                    <td>:</td>
                    <td><?= $data['RM_NO'] ?> (<?= $data['RM_DESC'] ?>)</td>
                </tr>

                <tr>
                    <td colspan="3" class="font-bold">
                        <?= $data['CUST_ADDRESS_1'] ?> <?= $data['CUST_ADDRESS_2'] ?> <?= $data['CUST_ADDRESS_3'] ?>
                    </td>

                    <td>Arrival</td>
                    <td>:</td>
                    <td><?= $data['RESV_ARRIVAL_DT'] ?></td>
                </tr>

                <tr>
                    <td colspan="3" class="font-bold"><?= $data['STATE_NAME'] ?></td>

                    <td>Departure</td>
                    <td>:</td>
                    <td><?= $data['RESV_DEPARTURE'] ?></td>
                </tr>

                <tr>
                    <td colspan="3" class="font-bold"><?= $data['CITY_NAME'] ?> <?= $data['COUNTRY_NAME'] ?></td>


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
                    <td>CUS<?= $data['CUST_ID'] ?></td>
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

                <?php
                if ($data['RO_PAYMENT_METHOD'] == 'Credit/Debit card' && !empty($data['transaction_id'])) {
                ?>
                    <tr>
                        <td>Payment Method</td>
                        <td>:</td>
                        <td><?= $data['RO_PAYMENT_METHOD'] ?></td>

                        <td>Transaction #</td>
                        <td>:</td>
                        <td><?= $data['transaction_id'] ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>

        <div class="table-2">
            <table>
                <thead>
                    <tr>
                        <th>Sr#</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Unit Rate</th>
                        <th>Qty</th>
                        <th>Amount (AED)</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $row_count = 1;
                    foreach ($data['order_details'] as $order_detail) {
                    ?>
                        <tr>
                            <td><?= $row_count++ ?></td>
                            <td><?= date_create($data['RO_CREATED_AT'])->format('Y-m-d') ?></td>
                            <td><?= $order_detail['MI_ITEM'] ?></td>
                            <td><?= $order_detail['MI_PRICE'] - ($order_detail['MI_PRICE'] * 0.05) ?></td>
                            <td><?= $order_detail['ROD_QUANTITY'] ?></td>
                            <td><?= ($order_detail['MI_PRICE'] - ($order_detail['MI_PRICE'] * 0.05)) * $order_detail['ROD_QUANTITY'] ?></td>
                        </tr>
                    <?php
                    }
                    ?>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Net</b></td>
                        <td><b><?= $data['RO_TOTAL_PAYABLE'] - ($data['RO_TOTAL_PAYABLE'] * 0.05) ?></b></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>Tax (5%)</b></td>
                        <td><b><?= $data['RO_TOTAL_PAYABLE'] * 0.05 ?></b></td>
                    </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="border-top-bottom"><b>Total</b></td>
                        <td class="border-top-bottom"><b><?= $data['RO_TOTAL_PAYABLE'] ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer text-center">
        <p><b>Thank You for your visit and stay safe.</b></p>
    </div>
</div>