<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\PostcardSend;
use App\Form\PostcardSendType;
use App\Repository\PostcardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Postcard;


class PostcardController extends AbstractController
{

    /**
     * @Route("/", defaults={"page": "1"}, name="main")
     * @Route("/page/{page}", requirements={"page": "[1-9]\d*"}, name="main_paginated")
     *
     * Главная страница - это основной каталог открыток
     */
    public function homepage(int $page, PostcardRepository $postcardRepository)
    {
        return $this->render('homepage.html.twig', [
            'images' => $postcardRepository->findLatest($page)
        ]);
    }

    /**
     * @Route("/card/{id}", name="one_postcard")
     *
     * Страница одной открытки и форма отправки
     */
    public function card($id, \Swift_Mailer $mailer, Request $request)
    {
        $postcard = $this->getDoctrine()
            ->getRepository(Postcard::class)
            ->find($id);

        if (!$postcard) {
            throw $this->createNotFoundException(
                'No postcard found for id '.$id
            );
        }

        $postcardSend = new PostcardSend(); // это не сущность для сохранения в базу, просто класс для удобства
        $form = $this->createForm(PostcardSendType::class, $postcardSend);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // setFrom зависит от настроек SwiftMailer
            $message = (new \Swift_Message('Вам открытка'))
                ->setFrom(['pudozh_postcards@mail.ru' => 'Pudozh Postcards'])
                ->setTo([$postcardSend->getToEmail() => $postcardSend->getToName()])
                ->setReplyTo($postcardSend->getFromEmail());

            // Прикрепляем открытку
            $image_path = 'images/'.$postcard->getAuthor()->getId().'/'.
                $postcard->getFilename();
            $data['image_src'] = $message->embed(\Swift_Image::fromPath($image_path));
            $data['author'] = $postcard->getAuthor()->getName();
            $data['title'] = $postcard->getTitle();

            // кому, имя получателя
            $data['to_name'] = $postcardSend->getToName();

            // текст открытки
            $data['text'] = $postcardSend->getContent();

            // mail отправителя
            $data['from_email'] = $postcardSend->getFromEmail();

            //имя отправителя
            $data['from_name'] = $postcardSend->getFromName();

            $message->setBody(
                $this->renderView('mail.html.twig', $data),
                'text/html'
            );
            $response = $mailer->send($message);

            if ($response == 1)
                $this->addFlash('success', "Открытка успешно отправлена!");
            else
                $this->addFlash('error', 'Что-то пошло не так: открытка не отправлена. Сообщите нам об ошибке.');

            return $this->redirectToRoute('success');
        }

        return $this->render('card.html.twig',[
            'card'=>$postcard,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/success", name="success")
     */
    public function messageSent()
    {
        return $this->render('base.html.twig');
    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('faq.html.twig');
    }

    /**
     * @Route("/links", name="links")
     */
    public function links()
    {
        return $this->render('ref.html.twig');
    }

    /**
     * @Route("/authors", name="authors")
     */
    public function authors(EntityManagerInterface $em)
    {
        return $this->render('authors.html.twig',[
            'authors' => $em->getRepository(Author::class)->findAll()
        ]);
    }

    /**
     * @Route("/author/{author}", defaults={"page": "1"}, name="author_by_id")
     * @Route("/author/{author}/{page}", requirements={"page": "[1-9]\d*"}, name="author_by_id_paginated")
     */
    public function authorById(Author $author, int $page, PostcardRepository $postcardRepository)
    {
        return $this->render('author.html.twig', [
            'images' => $postcardRepository->findLatestByAuthor($author, $page),
            'author' => $author
        ]);
    }
}