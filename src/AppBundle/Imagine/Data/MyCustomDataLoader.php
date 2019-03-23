<?php

namespace AppBundle\Imagine\Data;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Imagine\Image\ImagineInterface;

use Liip\ImagineBundle\Binary\Loader\LoaderInterface;


class MyCustomDataLoader implements LoaderInterface
{
    /**
     * @var Imagine\Image\ImagineInterface
     */
    private $imagine;

    /**
     * @var array
     */
    private $formats;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var
     */
    private $container;

    /**
     * Constructs
     *
     * @param ImagineInterface  $imagine
     * @param array             $formats
     * @param string            $rootPath
     */
    public function __construct(ImagineInterface $imagine, $formats, $rootPath, $container)
    {
        $this->imagine = $imagine;
        $this->formats = $formats;
        $this->rootPath = realpath($rootPath);
        $this->container = $container;
    }

    /**
     * @param string $path
     *
     * @return Imagine\Image\ImageInterface
     */
    public function find($path)
    {
        if (false !== strpos($path, '/../') || 0 === strpos($path, '../')) {
            throw new NotFoundHttpException(sprintf("Source file was searched with '%s' out side of the defined root path", $path));
        }

        $parametrofolder = $this->container->get('service_container')->getParameter('directorio_principal');

        $absolutePath = $parametrofolder.'/'.ltrim($path, '/');
        $info = pathinfo($absolutePath);

        //var_dump($info);die();

        if (isset($info['extension']) && strpos(strtolower($info['extension']), 'pdf') !== false ) {
            //If it doesn't exists, extract first page of the PDF to png
            if (!file_exists("$absolutePath.png")) {
                $img = new \Imagick ( $absolutePath.'[0]' );
                $img->setImageFormat( "png" );
                $img->writeImages($absolutePath.'.png', true);
            }
            //finally update $absolutePath and $info
            $absolutePath .= '.png';
            $info = pathinfo($absolutePath);
        }


        return $this->imagine->open($absolutePath);
    }
}