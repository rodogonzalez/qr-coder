<?php
// Register the Composer autoloader...
require __DIR__ . '/vendor/autoload.php';


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRMarkupSVG;
function generate_qr( $string_to_generate )
{


    $options_old = new QROptions(
        [
            'eccLevel'   => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'version'    => QRCode::VERSION_AUTO,
        ]
    );

    $options = new QROptions;

    $options->version              = 7;
    $options->outputInterface      = QRMarkupSVG::class;
    $options->outputBase64         = false;
    // if set to false, the light modules won't be rendered
    $options->drawLightModules     = true;
    $options->svgUseFillAttributes = false;
    // draw the modules as circles isntead of squares
    $options->drawCircularModules  = true;
    $options->circleRadius         = 0.4;
    // connect paths
    $options->connectPaths         = true;

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