<?php

namespace Opium\OpiumBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class FileController extends FOSRestController
{
    /**
     * photoAction
     *
     * @param string $path
     * @access public
     * @return Response
     *
     * @Rest\View()
     *
     * @ApiDoc()
     */
    public function getFileAction($path)
    {
        $path = urldecode($path);
        $file = $this->get('opium.finder.photo')->get($path);

        return $file;
    }
}
