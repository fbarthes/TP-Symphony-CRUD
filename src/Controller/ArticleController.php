<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    // récupérer la liste des articles
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $message = '';
        $articles = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }


  // Afficher un article
    #[Route('/showArticle/{id}', name: 'app_showArticle')]
    public function showArticle(Article $article): Response
    {
        if ($article === null) {
            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
  

    // Supprimer un article
    #[Route('/removeArticle/{id}', name: 'app_removeArticle')]
    public function removeArticle(Article $article, ArticleRepository $articleRepository): Response
    {
        $articleRepository->remove($article);
        return $this -> redirectToRoute('app_article');

        return $this->render('article/remove.html.twig', [
            'message' => $message,
        ]);
    } 



      // update un article
      #[Route('/updateArticle/{id}', name: 'app_updateArticle')]
      public function updateArticle(Article $article, Request $request, ArticleRepository $articleRepository): Response
      {
         
        $message = '';
        
        // vérifier la méthod POST du formulaire 
        if ($request->getMethod() === "POST") 
        {
            // récupération des valeurs du formulaire
            $titre = $request->request->get("titre");
            $description = $request->request->get("description");
            $tag = $request->request->get('tag');
    
            // si les champs sont vides, renvoyer message d'erreur
            if ($titre === "" || $description === "" || $tag === "") {
                $message = "les champs ne doivent pas être vide";
            }

            else{
                // création d'un nouvel article et utilisation des méthode de l'entité Article
                $article->setTitre($titre);
                $article->setDescription($description);
                $article->setTag($tag);
                $articleRepository->save($article);
                $message = "l'article a bien été modifié";
                return $this -> redirectToRoute('app_article');
            }
        }
  
          return $this->render('article/update.html.twig', [
              'article' => $article,
          ]);
      }
  
  

    //créer un article
    #[Route('/createArticle', name: 'app_createArticle')]
    public function createArticle(Request $request, ArticleRepository $articleRepository): Response
    {
        $message = '';
        
        // vérifier la méthod POST du formulaire 
        if ($request->getMethod() === "POST") 
        {
            // récupération des valeurs du formulaire
            $titre = $request->request->get("titre");
            $description = $request->request->get("description");
            $tag = $request->request->get('tag');
    
            // si les champs sont vides, renvoyer message d'erreur
            if ($titre === "" || $description === "" || $tag === "") {
                $message = "les champs ne doivent pas être vide";
            }

            else{
                // création d'un nouvel article et utilisation des méthode de l'entité Article
                $article = new Article();
                $article->setTitre($titre);
                $article->setDescription($description);
                $article->setTag($tag);
                $articleRepository->save($article);
                $message="l'article a bien été créé";
                return $this -> redirectToRoute('app_article');
            }
        
        }
    
        return $this->render('article/create.html.twig', [
            'message' => $message,
        ]);

    }

}
