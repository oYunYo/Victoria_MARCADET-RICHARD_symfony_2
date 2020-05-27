<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordUserType;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/add-user", name="add_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UserType::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $user = $form->getData();
            $image = $form->get('photo')->getData();
            $name = uniqid() . '.' . $image->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $image->move(
                    $this->getParameter('images_directory'),
                    $name
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file uploads
            }

            $user->setPhoto($name);

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPassword()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user');


        } else {
            return $this->render('user/add-user.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/delete/{user}", name="delete_user")
     */

    public function deleteUser(User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('user');
    }

    /**
     * @Route("/set-password", name="set_password")
     */
    public function setPassword(Request $request)
    {
        $form = $this->createForm(PasswordUserType::class, new User());

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

        } else {
            $user = $this->getUser();
            return $this->render('user/set-password.html.twig', [
                'user' => $user
            ]);
        }
    }
}
