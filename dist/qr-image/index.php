<?php
// Register the Composer autoloader...
require __DIR__ . '/vendor/autoload.php';


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;

function generate_qr( $string_to_generate )
{


    $options = new QROptions(
        [
            'eccLevel'        => QRCode::ECC_L,
            //'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'version'         => QRCode::VERSION_AUTO,
            'outputInterface' => QRGdImagePNG::class
        ]
    );

    return (new QRCode($options))->render($string_to_generate);

}

header('Content-type: image/png');


if (isset($_GET['data'])) {

    exit(generate_qr($_GET['data']));

}

exit(generate_qr($_GET['data']));