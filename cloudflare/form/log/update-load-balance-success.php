<html>
    <body>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    </body>
</html>

<?php

    echo "<script>

        swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Your data has been saved',
            type: 'success'
        }).then(function() {
            window.location = '../../load-balance.php';
        });
    </script>";



    // This is in the PHP file and sends a Javascript alert to the client


    //header('Location: alert.php');

?>