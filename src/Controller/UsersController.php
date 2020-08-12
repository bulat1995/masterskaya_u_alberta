<?php
/**
* Контроллер для работы с пользователями системы
*
*/

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\User;
use App\Form\UserType;


use Symfony\Component\HttpFoundation\RedirectResponse;
/**
* @Route("/lk/")
*/
class UsersController extends AbstractController
{
    /**
    * Отображение всех пользователей системы
    * @Route("users/", name="users.list")
    */
    public function list(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $em = $this->getDoctrine()->getManager();
       $users = $em->getRepository(User::class)->findAll();
        return $this->render('lk/users/list.html.twig',[
            'users'=>$users,
        ]);
    }

    /**
    * Редактирование пользователя по его идентификатору
    * @Route("users/{id}", name="user.show")
    */
    public function edit(Request $request, User $user,EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('success','Данные пользователя '.$user->getUsername().' изменены');
            return $this->redirectToRoute('users.list');
        }

        return $this->render('lk/users/edit.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user,
        ]);
    }

    /**
    * Удаление пользователя по его идентификатору
    * @Route("user/{id}/delete", name="users.delete", requirements={"id":"\d+"})
    */
    public function delete(Request $request, User $user,EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        if(in_array('ROLE_SUPER_ADMIN',$user->getRoles()))
        {
            $this->addFlash('error','Невозможно удалить пользователя '.$user->getUsername().' с правами администратора');
            return $this->redirectToRoute('users.list');
        }
        else{
            $this->addFlash('success','Пользователь '.$user->getUsername().' удален');
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('users.list');
        }

    }
}
