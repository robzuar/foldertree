<?php
namespace AppBundle\Imagine\Data;

use Symfony\Component\Form\DataTransformerInterface;
use Imagick;

class PdfTransformer
{
    /**
     * @var \Imagick
     */
    protected $imagick;
    public function __construct(\Imagick $imagick)
    {
        $this->imagick = $imagick;
    }
    /**
     * {@inheritDoc}
     */
    public function apply($absolutePath)
    {
        $info = pathinfo($absolutePath);

        var_dump($info);die();
        if (isset($info['extension']) && false !== strpos(strtolower($info['extension']), 'pdf')) {
            // If it doesn't exists, extract the first page of the PDF
            if (!file_exists("$absolutePath.png")) {
                $this->imagick->readImage($absolutePath.'[0]');
                $this->imagick->setImageFormat('png');
                $this->imagick->writeImage("$absolutePath.png");
                $this->imagick->clear();
            }
            $absolutePath .= '.png';
        }
        return $absolutePath;
    }
}