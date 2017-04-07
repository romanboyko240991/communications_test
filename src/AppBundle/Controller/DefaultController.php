<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Template
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        return ['privilegy' => $user->getPrivilegy()];
    }

    /**
     * @Template
     * @Route("/communications", name="communications")
     */
    public function communicationAction(Request $request)
    {
        $user = $this->getUser();
        return ['username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()->format('Y-m-d H:i'),
            'privilegy' => $user->getPrivilegy()
        ];
    }

    /**
     * @Template
     * @Route("/settings", name="settings")
     */
    public function settingAction(Request $request){}

    /**
     * @Route("/login_check", name="login_check")
     * @Method("POST")
     */
    public function loginCheckAction(){}
}
