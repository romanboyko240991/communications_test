<?php

namespace AppBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SettingController extends Controller
{
    /**
     * @Route("/get_all_users", name="get_all_users")
     */
    public function getAllUsersAction()
    {
        $result = [];

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll();

            foreach ($users as $user) {
                $result[] = [
                    'name' => $user->getUsername(),
                    'privilegy' => $user->getPrivilegy()
                ];
            }
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/save_privileges", name="save_privileges")
     */
    public function savePrivilegesAction(Request $request)
    {
        $result = ['success' => false];

        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $privileges = json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();
            
            try{
                foreach ($privileges as $username => $privilegy) {
                    $user = $em->getRepository('AppBundle:User')->findOneBy(['username' => $username]);
                    if($user != null) {
                        $user->setPrivilegy($privilegy);
                        $em->persist($user);
                    }
                }

                $em->flush();
                $result['success'] = true;
            }
            catch (\Exception $e) {}
        }

        return new JsonResponse($result);
    }
}
