<?php

namespace App\Controller;

use App\Entity\CategoryPost;
use App\Entity\Post;
use App\Repository\CategoryPostRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/post')]
class PostController extends AbstractController
{
    private $entityManager;
    private $postRepository;

    public function __construct(EntityManagerInterface $entityManager, PostRepository $postRepository) {
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }
    
    #[Route('/', name: 'app_post')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/show/{slug}', name: 'app_post_show')]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig',[
            'post' => $post
        ]);
    }

    #[Route('/delete/{slug}', name: 'app_post_delete')]
    public function delete(Post $post): Response
    {
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_post');
    }

    #[Route('/edit/{slug}', name: 'app_post_edit')]
    public function edit(Post $post, Request $request): Response
    {
        $randomPostfix = $this->getRandom();

        $newTitle = $post->getTitle() . ' edit ' . $randomPostfix;
        $post->setTitle($newTitle);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        $urlRedirect = $request->headers->get('referer');
        if(!$urlRedirect) {
            $urlRedirect = $this->generateUrl('app_post');
        }

        return $this->redirect($urlRedirect);
    }

    #[Route('/create', name: 'app_post_create')]
    public function create(CategoryPostRepository $categoryPostRepository): Response
    {
        $randomPostfix = $this->getRandom();
        $slugger = new AsciiSlugger();
        
        $categoryName = 'News ' . $randomPostfix;
        $slugCategoryPost = $slugger->slug($categoryName)->lower();
        
        $categoryPost = new CategoryPost();
        $categoryPost->setName($categoryName);
        $categoryPost->setSlug($slugCategoryPost);

        $randomCategory = $categoryPostRepository->findRandomCategoryPost();

        $title = 'News ' . $randomPostfix;
        $slugPostTitle = $slugger->slug($title)->lower();
        $description = 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Blanditiis veniam consequatur consequuntur atque ad iure nesciunt, pariatur ratione consectetur officia ut quis, cupiditate quibusdam et quae, beatae animi? Quia, laboriosam. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Blanditiis veniam consequatur consequuntur atque ad iure nesciunt, pariatur ratione consectetur officia ut quis, cupiditate quibusdam et quae, beatae animi? Quia, laboriosam.';

        $post = new Post();
        $post->setTitle($title);
        $post->setSlug($slugPostTitle);
        $post->setDescription($description);
        $post->setCategoryPost($randomCategory[0]);

        $this->entityManager->persist($categoryPost);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_post');
    }

    private function getRandom(): int
    {
        return rand(1,1000);
    }
}
