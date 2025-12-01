<?php
// Register the Composer autoloader...
require __DIR__ . '/vendor/autoload.php';


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

function generate_qr( $string_to_generate )
{


    $options = new QROptions(
        [
            'eccLevel'   => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'version'    => QRCode::VERSION_AUTO,
        ]
    );

    $out = (new QRCode($options))->render($string_to_generate);

    if (extension_loaded('zlib')) {
        header('Vary: Accept-Encoding');
        header('Content-Encoding: gzip');
        $out = gzencode($out, 9);
    }

    exit($out);

}

header('Content-type: image/svg+xml');

if (isset($_GET['data'])) {
    exit(generate_qr($_GET['data']));
}

exit(generate_qr($_GET['data']));