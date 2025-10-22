// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

function generate_qr($string_to_generate){


    $options  = new QROptions(
            [
                'eccLevel'   => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version'    => QRCode::VERSION_AUTO,
            ]
        );        
        
        return  (new QRCode($options))->render($string_to_generate);

}