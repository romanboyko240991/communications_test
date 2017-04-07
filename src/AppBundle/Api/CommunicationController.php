<?php

namespace AppBundle\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Field;

class CommunicationController extends Controller
{
    /**
     * @Route("/save_fields", name="save_fields")
     */
    public function saveFieldsAction(Request $request)
    {
        $fields = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $result = ['success' => false];

        try{
            $user->cleanFields();

            foreach ($fields as $field) {
                $newField = new Field();
                $newField->setField($field->name);
                $em->persist($newField);
                $em->flush($newField);

                $user->addField($newField);
            }

            $em->persist($user);
            $em->flush($user);

            $result['success'] = true;
        }
        catch (\Exception $e) {}

        return new JsonResponse($result);
    }

    /**
     * @Route("/get_user_fields", name="get_user_fields")
     */
    public function getUserFieldsAction()
    {
        $user = $this->getUser();
        $userFields = $user->getFields();

        $result = [];

        foreach ($userFields as $userField) {
            $result[] = [
                'name' => $userField->getField()
            ];
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/delete_user_fields", name="delete_user_fields")
     */
    public function deleteUserFieldsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $result = ['success' => false];

        try{
            $user->cleanFields();

            $em->persist($user);
            $em->flush($user);

            $result['success'] = true;
        }
        catch (\Exception $e) {}

        return new JsonResponse($result);
    }
}
