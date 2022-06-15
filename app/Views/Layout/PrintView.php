<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?=base_url('assets')?>/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <?=$this->renderSection("titleRender")?>
    <?= $this->include('Layout/PrintHeaderScript') ?>

<body>

    <div class="invoice-print p-5">
        <?=$this->renderSection("contentRender")?>
    </div>

    <?= $this->include('Layout/PrintFooterScript') ?>

    <?= $this->renderSection("script") ?>

</body>

</html>