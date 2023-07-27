
<!-- One Signal -->

<?php 
if (isset($_SESSION['login']) ||  $isMobile) {

    if ($_SESSION['utype'] == '0' ||  $isMobile) {
            $uid = $_SESSION['uid'];
?>

      <script type="text/javascript">var uid = "<?= $uid ?>";</script>
        <script src='https://cdn.onesignal.com/sdks/OneSignalSDK.js' async=''></script>
       <script src='/includes/one-signal.js'></script>

<?php
    }
}
?>
<!-- End One Signal -- >