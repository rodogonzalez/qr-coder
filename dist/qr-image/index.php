<?php
// Register the Composer autoloader...
require __DIR__ . '/vendor/autoload.php';
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\{QRCodeOutputException, QRMarkupSVG};



class SVGConvert extends QRMarkupSVG{

	protected function header():string{
		[$width, $height] = $this->getOutputDimensions();

		// we need to specify the "width" and "height" attributes so that Imagick knows the output size
		$header = sprintf(
			'<svg xmlns="http://www.w3.org/2000/svg" class="qr-svg %1$s" viewBox="%2$s" preserveAspectRatio="%3$s" width="%5$s" height="%6$s">%4$s',
			$this->options->cssClass,
			$this->getViewBox(),
			$this->options->svgPreserveAspectRatio,
			$this->options->eol,
			($width * $this->scale), // use the scale option to modify the size
			($height * $this->scale),
		);

		if($this->options->svgAddXmlHeader){
			$header = sprintf('<?xml version="1.0" encoding="UTF-8"?>%s%s', $this->options->eol, $header);
		}

		return $header;
	}

	public function dump(string|null $file = null):string{
		$base64 = $this->options->outputBase64;
		// we don't want the SVG in base64
		$this->options->outputBase64 = false;

		$svg = $this->createMarkup($file !== null);

		// now convert the output
		$im = new Imagick;
		$im->readImageBlob($svg);
		$im->setImageFormat($this->options->imagickFormat);

		if($this->options->quality > -1){
			$im->setImageCompressionQuality(max(0, min(100, $this->options->quality)));
		}

		$imageData = $im->getImageBlob();

		$im->clear();
		$this->saveToFile($imageData, $file);

		if($base64){
			// use finfo to guess the mime type
			$mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($imageData);

			if($mime === false){
				throw new QRCodeOutputException('unable to detect mime type');
			}

			$imageData = $this->toBase64DataURI($imageData, $mime);
		}

		return $imageData;
	}

}


// SVG from the basic example
$options = new QROptions;

$options->version              = 7;
$options->outputInterface      = SVGConvert::class;
$options->imagickFormat        = 'png32';
$options->scale                = 20;
$options->outputBase64         = false;
$options->drawLightModules     = true;
$options->svgUseFillAttributes = false;
$options->drawCircularModules  = true;
$options->circleRadius         = 0.4;
$options->connectPaths         = true;
$options->keepAsSquare         = [
	QRMatrix::M_FINDER_DARK,
	QRMatrix::M_FINDER_DOT,
	QRMatrix::M_ALIGNMENT_DARK,
];
$options->svgDefs              = '
	<linearGradient id="rainbow" x1="1" y2="1">
		<stop stop-color="#e2453c" offset="0"/>
		<stop stop-color="#e07e39" offset="0.2"/>
		<stop stop-color="#e5d667" offset="0.4"/>
		<stop stop-color="#51b95b" offset="0.6"/>
		<stop stop-color="#1e72b7" offset="0.8"/>
		<stop stop-color="#6f5ba7" offset="1"/>
	</linearGradient>
	<style><![CDATA[
		.dark{fill: url(#rainbow);}
		.light{fill: #eee;}
		svg{ width: 530px; height: 530px; }
	]]></style>';


// render the SVG and convert to the desired ImageMagick format
$image = (new QRCode($options))->render('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

header('Content-type: image/png');

echo $image;



exit();
/*
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Data\QRMatrix;

function generate_qr( $string_to_generate )
{

  
        $options = new QROptions(
            [
                'eccLevel'        => QRCode::ECC_L,
                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                'version'         => QRCode::VERSION_AUTO,

            ]
        );
  
  
    return (new QRCode($options))->render($string_to_generate);

}

header('Content-type: image/png');


if (isset($_GET['data'])) {

    exit(generate_qr($_GET['data']));

}

exit(generate_qr($_GET['data']));
*/