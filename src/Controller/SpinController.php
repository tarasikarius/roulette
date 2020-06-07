<?php

namespace App\Controller;

use App\Manager\SpinManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SpinController extends AbstractFOSRestController
{
    private $spinManager;

    public function __construct(SpinManager $spinManager)
    {
        $this->spinManager = $spinManager;
    }

    /**
     * @Route("/spins", methods={"POST"})
     * @Rest\View(serializerGroups={"spin:view"})
     */
    public function postSpin(Request $request)
    {
        return $this->spinManager->makeSpin(json_decode($request->getContent(), true));
    }
}
