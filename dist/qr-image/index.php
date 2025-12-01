<?php 
// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRGdImageWEBP;


function generate_qr($string_to_generate){


    $options  = new QROptions(
            [
                'eccLevel'   => QRCode::ECC_L,
                'outputType' => QRGdImageWEBP::class,
                

                'version'    => QRCode::VERSION_AUTO,
            ]
        );
        
        
        $gdImage = new QRCode($options)->render($string_to_generate);

        //$gdImage = (new QRCode($options))->render($data);

        // do stuff with the GdImage instance...
        $size = imagesx($gdImage);

        header('Content-type: image/jpeg');

        imagejpeg($gdImage);
        imagedestroy($gdImage);

}

//header('content-type: image/svg+xml');


if (isset($_GET['data'])){

    exit (generate_qr($_GET['data']));

}

exit (generate_qr($_GET['data']));