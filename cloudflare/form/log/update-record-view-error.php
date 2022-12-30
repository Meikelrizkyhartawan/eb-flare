<html>
    <body>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    </body>
</html>

<?php

    echo "<script>
        swal.fire({
            icon: 'error',
            title: 'Failed',
            text: 'Your data failed to saved',
            type: 'success'
        }).then(function() {
            window.location = '../../main-view.php';
        });
    </script>";




    // This is in the PHP file and sends a Javascript alert to the client


    //header('Location: alert.php');

?>