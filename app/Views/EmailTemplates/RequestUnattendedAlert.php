<html>

<body>
    <div style="width: 30%; margin: 0 auto; padding: 50px">
        <div>
            <img src="https://keysuite.farnek.com:9021/upload/assets/hotel-logo.png" />
        </div>

        <div style="margin-top: 10px;">
            <h1>Alert! Unattended Request.</h1>
        </div>

        <div style="margin-top: 50px;">
            <h1>Hello <?= $to_name ?>,</h1>
        </div>

        <div style="font-size: 20px">
            The request of a '<?= $order_detail['PR_NAME'] ?>' is not attended yet.
            <br/>
            <b>Request# <?= $order_detail['LAOD_ID'] ?></b>
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