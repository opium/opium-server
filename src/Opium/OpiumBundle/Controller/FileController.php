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
     * @Rest\View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc()
     */
    public function getFileAction($slug)
    {
        $file = $this->get('opium.repository.photo')->findOneBySlug($slug);
        $neighbour = $file->getParent()->getChildren();
        $index = $neighbour->indexOf($file);

        $previous = $neighbour[$index - 1];
        $next = $neighbour[$index + 1];

        $file->setPrevious($previous)
            ->setNext($next);

        $file->setPosition(['lat' => 59.91, 'lng' => 10.75]);

        return $file;
    }
}
