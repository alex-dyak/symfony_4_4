<?php

namespace App\RestController;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
//use FOS\RestBundle\Controller\Annotations as Rest;

class TestController extends AbstractFOSRestController
{
    /**
     *
     * @IsGranted("ROLE_TEST", statusCode=403, message="Test Access Denied.")
     *
     * @param Request $request
     */
    public function getTestAction(Request $request)
    {
        return;
    }
}