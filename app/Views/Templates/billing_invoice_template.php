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
        <!-- <h1 class="logo">FlexiGuest</h1> -->
        <img src="<?= $branding_logo ?>" width="190px;" />
    </div>

    <div class="container">
        <div class="table-1">
            <table>
                <tr>
                    <td colspan="3" class="font-bold">
                        <?= $data['CUST_FIRST_NAME'] ?> <?= $data['CUST_LAST_NAME'] ?>
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
            </table>
        </div>

        <?php if (!empty($data['debited_transactions'])) : ?>
            <div class="table-2">
                <table>
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Reference</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Amount (AED)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $row_count = 1;
                        $total_amount = 0;
                        ?>
                        <?php foreach ($data['debited_transactions'] as $transaction) : ?>
                            <tr>
                                <td><?= $row_count++ ?></td>
                                <td><?= date_create($transaction['RTR_CREATED_AT'])->format('Y-m-d') ?></td>
                                <td><?= $transaction['TR_CD_CODE'] ?></td>
                                <td><?= $transaction['TR_CD_DESC'] ?></td>
                                <td><?= $transaction['RTR_REFERENCE'] ?></td>
                                <td><?= $transaction['RTR_AMOUNT'] ?></td>
                                <td><?= $transaction['RTR_QUANTITY'] ?></td>
                                <td><?= $transaction['RTR_AMOUNT'] * $transaction['RTR_QUANTITY'] ?></td>

                                <?php
                                $total_amount += $transaction['RTR_AMOUNT'] * $transaction['RTR_QUANTITY'];
                                ?>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Net</b></td>
                            <td><b><?= $total_amount - ($total_amount * 0.05) ?></b></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b>Tax (5%)</b></td>
                            <td><b><?= $total_amount * 0.05 ?></b></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="border-top-bottom"><b>Total</b></td>
                            <td class="border-top-bottom"><b><?= $total_amount ?></b></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        <?php endif ?>

        <?php if (!empty($data['credited_transactions'])) : ?>
            <div class="table-2">
                <table>
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Date</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Reference</th>
                            <th>Rate</th>
                            <th>Quantity</th>
                            <th>Amount (AED)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $row_count = 1;
                        $total_credited_amount = 0;
                        ?>
                        <?php foreach ($data['credited_transactions'] as $transaction) : ?>
                            <tr>
                                <td><?= $row_count++ ?></td>
                                <td><?= date_create($transaction['RTR_CREATED_AT'])->format('Y-m-d') ?></td>
                                <td><?= $transaction['PYM_TXN_CODE'] ?></td>
                                <td><?= $transaction['PYM_DESC'] ?></td>
                                <td><?= $transaction['RTR_REFERENCE'] ?></td>
                                <td></td>
                                <td><?= $transaction['RTR_QUANTITY'] ?></td>
                                <td><?= abs($transaction['RTR_AMOUNT']) ?></td>

                                <?php
                                $total_credited_amount += abs($transaction['RTR_AMOUNT']);
                                ?>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="border-top-bottom"><b>Total</b></td>
                            <td class="border-top-bottom"><b><?= $total_credited_amount ?></b></td>
                        </tr>

                        <tr>
                            <td colspan="8"></td>
                        </tr>
                        <tr>
                            <td colspan="8"></td>
                        </tr>
                        <tr>
                            <td colspan="8"></td>
                        </tr>
                        <tr>
                            <td colspan="8"></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="border-top-bottom"><b>Pending Amount</b></td>
                            <td class="border-top-bottom"><b><?= $total_amount - $total_credited_amount ?></b></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>

    <div class="footer text-center">
        <p><b>Thank You for your visit and stay safe.</b></p>
    </div>
</div>