<?php

namespace Loonins\WikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\WikiBundle\Entity\Article;
use Loonins\WikiBundle\Form\ArticleType;
use FOS\UserBundle\Util\LegacyFormHelper;

/**
 * Article controller.
 *
 * @Route("/article")
 */
class ArticleController extends Controller {

    /**
     * Lists all Article entities.
     *
     * @Route("/", name="article")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('LooninsWikiBundle:Article')->findBy(array(), array('artTitre' => 'asc'));
        $categories = $em->getRepository('LooninsWikiBundle:Categorie')->findBy(array(), array('cat' => 'ASC'));
        $resultat = array();
        foreach ($categories as $cat) {
            $rubriques = $em->getRepository('LooninsWikiBundle:Rubrique')->findByRubCat($cat, array('titre' => 'ASC'));
            $resultat[] = array($cat, $rubriques);
        }
        return array(
            'entities' => $entities,
            'categories' => $categories,
            'catrubs' => $resultat,
        );
    }

    /**
     * Lists all Article entities.
     *
     * @Route("/", name="article")
     * @Method("GET")
     * @Template()
     */
    public function searchAction() {
//        $query = $em->createQueryBuilder();
        $request = $this->get('request');
        $recherche = "";
        foreach ($request as $rq) {
            if ($rq instanceof \Symfony\Component\HttpFoundation\ParameterBag) {
                $param = $rq;
                $arrayParam = $param->all();
                if (isset($arrayParam['rech'])) {
                    $recherche = isset($arrayParam['rech']) ? $arrayParam['rech'] : '';
                }
            }
        }
//        $this->searchDo($rech, $titre_art, $titre_ver, $titre_archive, $content_ver, $content_archive);
//        $param = "%$rech%";
//        $titre_art = $this->mySymfoQuery("a", 'LooninsWikiBundle:Article', "a.artTitre LIKE :rech", $param);
//        $titre_ver = $this->mySymfoQuery("v", 'LooninsWikiBundle:Version', "v.verTitre LIKE :rech", $param);
//        $titre_archive = $this->mySymfoQuery("ar", 'LooninsWikiBundle:Versions', "ar.verTitre LIKE :rech", $param);
//
//        $content_ver = $this->mySymfoQuery("v", 'LooninsWikiBundle:Version', "v.verContent LIKE :rech", $param);
//        $content_archive = $this->mySymfoQuery("ar", 'LooninsWikiBundle:Versions', "ar.verContent LIKE :rech", $param);

        $tab = explode(" ", $recherche);
        $titre_art = array();
        $titre_ver = array();
        $titre_archive = array();
        $content_ver = array();
        $content_archive = array();
        foreach ($tab as $rech) {
            $param = "%$rech%";
            $line = $this->mySymfoQuery("a", 'LooninsWikiBundle:Article', "a.artTitre LIKE :rech", $param);
            !in_array($line, $titre_art) ? $titre_art[] = $line : "";

            $line = $this->mySymfoQuery("v", 'LooninsWikiBundle:Version', "v.verTitre LIKE :rech", $param);
            !in_array($line, $titre_ver) ? $titre_ver[] = $line : "";

            $line = $this->mySymfoQuery("ar", 'LooninsWikiBundle:Versions', "ar.verTitre LIKE :rech", $param);
            !in_array($line, $titre_archive) ? $titre_archive[] = $line : "";

            $line = $this->mySymfoQuery("v", 'LooninsWikiBundle:Version', "v.verContent LIKE :rech", $param);
            !in_array($line, $content_ver) ? $content_ver[] = $line : "";
            
            $line = $this->mySymfoQuery("ar", 'LooninsWikiBundle:Versions', "ar.verContent LIKE :rech", $param);
            !in_array($line, $content_archive) ? $content_archive[] = $line :  "";
        }

        


        $lines = array(
            "titre_art" => $titre_art,
            "titre_ver" => $titre_ver,
            "titre_archive" => $titre_archive,
            "content_ver" => $content_ver,
            "content_archive" => $content_archive
        );
//        die();
        return array(
            'resultats' => $lines,
            'rech' => $recherche,
        );
    }

    /**
     * Lists all Article entities by categorie.
     *
     * @Route("/{id}", name="catlist")
     * @Method("GET")
     * @Template()
     */
    public function rublistAction($id) {
        $em = $this->getDoctrine()->getManager();
//        $categorie = $em->getRepository('LooninsWikiBundle:Categorie')->find($id);
        $categories = $em->getRepository('LooninsWikiBundle:Categorie')->findAll();
        foreach ($categories as $cat) {
            $rubriques = $em->getRepository('LooninsWikiBundle:Rubrique')->findByRubCat($cat);
            $resultat[] = array($cat, $rubriques);
        }

        $rubrique = $em->getRepository('LooninsWikiBundle:Rubrique')->find($id);
        $entities = $em->getRepository('LooninsWikiBundle:Article')->findByArtRub($rubrique);

//        var_dump($entities);
//        var_dump($categories);
//        var_dump($categorie);
//        var_dump($rubrique);
//        die("-");
        return array(
            'entities' => $entities,
            'catrubs' => $resultat,
            'rubrique' => $rubrique,
            'categories' => $categories,
        );
    }

    /**
     * Creates a new Article entity.
     *
     * @Route("/", name="article_create")
     * @Method("POST")
     * @Template("LooninsWikiBundle:Article:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Article();
//        $entity->setArtCreator($user);
        $form = $this->createCreateForm($entity);
        // $request = $this->get('request');
        $form->handleRequest($request);
        // $form->bind($request);

//        var_dump($request);
//        var_dump($form);
//        die("------");
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setArtCreator($this->getUser());
            $entity->setArtDel(0);
            $entity->setArtDateCreate(new \DateTime());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Article $entity) {
        // $form = $this->createForm(new ArticleType(), $entity, array(
        //     'action' => $this->generateUrl('article_create'),
        //     'method' => 'POST',
        // ));
        $form = $this->createForm('Loonins\WikiBundle\Form\ArticleType', $entity,array(
            'action' => $this->generateUrl('article_create'),
            'method' => 'POST',
        ));
        $form->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Article entity.
     *
     * @Route("/new", name="article_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Article();
//        $entity->setArtDateCreate(new \DateTime());
        // $user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->getUser();
        $entity->setArtCreator($user);
        $form = $this->createCreateForm($entity);
        // $form->handleRequest($request);
//           var_dump($entity);
//        var_dump($form);
//        die("------");
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}", name="article_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        // $em = $this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsWikiBundle:Article')->find($id);
        $sections = $em->getRepository('LooninsWikiBundle:Version')->findBy(array('verArticle' => $entity));

        $categories = $em->getRepository('LooninsWikiBundle:Categorie')->findAll();
        foreach ($categories as $cat) {
            $rubriques = $em->getRepository('LooninsWikiBundle:Rubrique')->findByRubCat($cat);
            $resultat[] = array($cat, $rubriques);
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'catrubs' => $resultat,
            'sections' => $sections,
            'categories' => $categories,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Article entity.
     *
     * @Route("/{id}/edit", name="article_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Article entity.
     *
     * @param Article $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Article $entity) {
        // $form = $this->createForm(new ArticleType(), $entity, array(
        //     'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
        //     'method' => 'PUT',
        // ));

        $form = $this->createForm('Loonins\WikiBundle\Form\ArticleType', $entity,array(
            'action' => $this->generateUrl('article_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications '));

        return $form;
    }

    /**
     * Edits an existing Article entity.
     *
     * @Route("/{id}", name="article_update")
     * @Method("PUT")
     * @Template("LooninsWikiBundle:Article:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Article')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }




        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        // dump($editForm);
        // die();

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getId())));
            // return $this->redirect($this->generateUrl('article_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Article entity.
     *
     * @Route("/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsWikiBundle:Article')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Article entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('article'));
    }

    /**
     * Creates a form to delete a Article entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        $icon = '<i class="icon icon-remove"></i>';
        //$icon = html_ ($icon);
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('article_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit',  LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer l\'article courant'))
                        ->getForm()
        ;
    }

    private function mySymfoQuery($alias, $entity, $str_where, $param) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
        $query
                ->select($alias)
                ->from($entity, $alias)
                ->where($str_where)
                ->setParameter('rech', $param);
        return $lines = $query->getQuery()->getResult();
    }

    private function searchDo($recherche, $titre_art, $titre_ver, $titre_archive, $content_ver, $content_archive) {
        $tab = explode(" ", $recherche);
        foreach ($tab as $rech) {
            $param = "%$rech%";
            $titre_art[] = $this->mySymfoQuery("a", 'LooninsWikiBundle:Article', "a.artTitre LIKE :rech", $param);
            $titre_ver = $this->mySymfoQuery("v", 'LooninsWikiBundle:Version', "v.verTitre LIKE :rech", $param);
            $titre_archive = $this->mySymfoQuery("ar", 'LooninsWikiBundle:Versions', "ar.verTitre LIKE :rech", $param);

            $content_ver = $this->mySymfoQuery("v", 'LooninsWikiBundle:Version', "v.verContent LIKE :rech", $param);
            $content_archive = $this->mySymfoQuery("ar", 'LooninsWikiBundle:Versions', "ar.verContent LIKE :rech", $param);
        }
    }

}
