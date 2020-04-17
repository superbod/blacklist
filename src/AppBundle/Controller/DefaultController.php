<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlackList;
use AppBundle\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     * @param Request $request
     * @return Response
     */
    public function adminAction(Request $request)
    {
        //if i would have a lot of users in my DB i probably remove dropdown woth users and add search
        // field to fiend user which i want to add to blacklist

        $users = $this->get("doctrine")->getRepository(User::class)->getWhiteList();
        $blacklist = $this->get("doctrine")->getRepository(BlackList::class)->findAll();

        return $this->render('default/admin.html.twig',["list" => $blacklist, "users" => $users]);
    }

    /**
     * @Route("/admin/add_to_blacklist", name="addToBlacklist", methods={"PUT"})
     * @param Request $request
     * @return JsonResponse
     * @throws OptimisticLockException
     */
    public function addToBlackListAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $this->get("black_list_manager")->addUserToBlacklist($data->userId);
        return new JsonResponse(['res' => 1]);
    }

    /**
     * @Route("/admin/remove_from_blacklist", name="removeFromBlacklist", methods={"DELETE"})
     * @param Request $request
     * @return JsonResponse
     * @throws OptimisticLockException
     */
    public function removeFromBlackList(Request $request)
    {
        $data = json_decode($request->getContent());
        $this->get('black_list_manager')->removeUserFromBlacklist($data->userId);
        return new JsonResponse(['res' =>1]);
    }

    /**
     * @Route("/login_social", name="user_login")
     * @param Request $request
     * @return Response
     */
    public function loginSocialAction(Request $request)
    {
        return $this->render('security/login_social.html.twig');
    }
}
