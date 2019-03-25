<?php

namespace Loonins\WikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\WikiBundle\Entity\Version;
use Loonins\WikiBundle\Entity\Versions;
use Loonins\WikiBundle\Form\VersionType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Version controller.
 *
 * @Route("/version")
 */
class VersionController extends Controller {

    /**
     * Lists all Version entities.
     *
     * @Route("/", name="version")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsWikiBundle:Version')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Version entity.
     *
     * @Route("/", name="version_create")
     * @Method("POST")
     * @Template("LooninsWikiBundle:Version:new.html.twig")
     */
    public function createAction(Request $request, $art) {
        $entity = new Version();
        $form = $this->createCreateForm($entity, $art);
        $form->handleRequest($request);
//        var_dump($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            $article = $this->getDoctrine()->getManager()->getRepository('LooninsWikiBundle:Article')->find($art);
            //var_dump($article);

            $uploadDir = "uploads/wiki";
//            $file->move($dir, $file->getClientOriginalName());
            $file = $form['attachement']->getData();
            if ($file != null) {
// générer un nom aléatoire et essayer de deviner l'extension (plus sécurisé)
                $extension = $file->guessExtension();
                if (!$extension) {
                    // l'extension n'a pas été trouvée
                    $extension = 'bin';
                }
                $newFilename = rand(1, 99999) . '.' . $extension;
                $file->move($uploadDir, $newFilename);
                $entity->setAttachement($uploadDir . '/' . $newFilename);
            }
            $entity->setVerCreator($user);
            $entity->setVerArticle($article);
//            var_dump($entity->getVerArticle()->getArtTitre());


            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getVerArticle()->getId())));
        }

        return array(
            'entity' => $entity,
            'art' => $art,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Version entity.
     *
     * @param Version $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Version $entity, $art) {
        $form = $this->createForm('Loonins\WikiBundle\Form\VersionType', $entity, array(
            'action' => $this->generateUrl('version_create', array('art' => $art)),
            'method' => 'POST',
                ));

        $form->add('submit', SubmitType::class, array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Version entity.
     *
     * @Route("/new", name="version_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($art) {
        $entity = new Version();
        $form = $this->createCreateForm($entity, $art);
        $article = $this->getDoctrine()->getManager()->getRepository('LooninsWikiBundle:Article')->find($art);
        return array(
            'entity' => $entity,
            'art' => $article,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Version entity.
     *
     * @Route("/{id}", name="version_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Version')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Version entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Version entity.
     *
     * @Route("/{id}/edit", name="version_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Version')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Version entity.');
        }

        $editForm = $this->createEditForm($entity, $entity->getAttachement());
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Version entity.
     *
     * @param Version $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Version $entity, $imgsrc = null) {
        $form = $this->createForm('Loonins\WikiBundle\Form\VersionType', $entity, array(
            'action' => $this->generateUrl('version_update', array('id' => $entity->getId())),
            'method' => 'POST',
                ), $imgsrc);

        $form->add('submit', SubmitType::class, array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing Version entity.
     *
     * @Route("/{id}", name="version_update")
     * @Method("PUT")
     * @Template("LooninsWikiBundle:Version:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsWikiBundle:Version')->find($id);
        $archive = $entity->archivage();
        $old_attachement = $entity->getAttachement();
        $entity->addVersion($archive);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Version entity.');
        }

        $uploadDir = "uploads/wiki";
//            $file->move($dir, $file->getClientOriginalName());
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        $file = $editForm['attachement']->getData();
        if ($file != null) {
// générer un nom aléatoire et essayer de deviner l'extension (plus sécurisé)
            $extension = $file->guessExtension();
            if (!$extension) {
                // l'extension n'a pas été trouvée
                $extension = 'bin';
            }
            $newFilename = rand(1, 99999) . '.' . $extension;
            $file->move($uploadDir, $newFilename);

            $entity->setAttachement($uploadDir . '/' . $newFilename);
        }
        else{
            $entity->setAttachement($old_attachement);
        }
        if ($editForm->isValid()) {
            $em->flush();
//            echo "FLUSHED";
            return $this->redirect($this->generateUrl('article_show', array('id' => $entity->getVerArticle()->getId())));
        }
//        echo "OUT FLUSHED";
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Version entity.
     *
     * @Route("/{id}", name="version_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsWikiBundle:Version')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Version entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('version'));
    }

    /**
     * Creates a form to delete a Version entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('version_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', SubmitType::class, array('label' => 'Supprimer'))
                        ->getForm()
        ;
    }

    /**
     * Lists all Archives entities.
     *
     * @Route("/{id}", name="version_archives")
     * @Method("GET")
     * @Template()
     */
    public function archivesAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsWikiBundle:Version')->find($id);
        $archives = $em->getRepository('LooninsWikiBundle:Versions')->findBy(array('verVersion' => $entity));
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Version entity.');
        }
        return array(
            'entity' => $entity,
            'archives' => $archives,
        );
    }

    /**
     * Lists all Archives entities.
     *
     * @Route("/{id}", name="archive_show")
     * @Method("GET")
     * @Template()
     */
    public function archiveAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsWikiBundle:Versions')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Version entity.');
        }
        return array(
            'entity' => $entity,
            'archive' => $entity,
        );
    }

    /**
     * Restaure an archive.
     *
     * @Route("/{id}", name="archive_restaure")
     * @Method("GET")
     * @Template()
     */
    public function restaureAction($id) {
        $em = $this->getDoctrine()->getManager();
        $backup = $em->getRepository('LooninsWikiBundle:Versions')->find($id);
        $restaure = $backup->restauration();
        $section = isset($restaure['section']) ? $restaure['section'] : null;
        $archive = isset($restaure['archive']) ? $restaure['archive'] : null;
        if (!$section instanceof Version || !$archive instanceof Versions)
            throw $this->createNotFoundException('Impossible de restauer larchive.');
        $section->addVersion($archive);
        $em->remove($backup);
        $em->flush();
        return $this->redirect($this->generateUrl('article_show', array('id' => $section->getVerArticle()->getId())));
    }

}
