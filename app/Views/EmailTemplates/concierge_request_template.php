<html>

<body>
    <div style="width: 30%; margin: 0 auto; padding: 50px">
        <div>
            <img src="<?= brandingLogo() ?>" />
        </div>

        <div style="margin-top: 10px;">
            <h1>Alert! Concierge Request.</h1>
        </div>

        <div style="margin-top: 50px;">
            <h1>Hello <?= $to_name ?>,</h1>
        </div>

        <div style="font-size: 20px">
            <p>
                <b>Offer Title: </b> <?= $concierge_offer['CO_TITLE'] ?>
            </p>
            <p>
                <b>Guest Name: </b> <?= $concierge_request['CR_GUEST_NAME'] ?>
            <p>
                <b>Guest Email: </b> <?= $concierge_request['CR_GUEST_EMAIL'] ?>
            </p>
            <p>
                <b>Guest Phone: </b> <?= $concierge_request['CR_GUEST_PHONE'] ?>
            </p>
            <p>
                <b>Quantity: </b> <?= $concierge_request['CR_QUANTITY'] ?>
            </p>
            <p>
                <b>PREFERRED DATE: </b> <?= $concierge_request['CR_PREFERRED_DATE'] ?>
            </p>
            <p>
                <b>Net Amount: </b> <?= $concierge_request['CR_NET_AMOUNT'] ?>
            </p>
            <p>
                <b>Tax Amount: </b> <?= $concierge_request['CR_TAX_AMOUNT'] ?>
            </p>
            <p>
                <b>Total Amount: </b> <?= $concierge_request['CR_TOTAL_AMOUNT'] ?>
            </p>
        </div>

        <div style="margin-top: 100px; font-size: 20px">
            Regards,
        </div>

        <div style="font-size: 20px">
            Hotel
        </div>
    </div>
</body>

</html>