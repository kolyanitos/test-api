<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidFormException;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ImagesController
 * @package AppBundle\Controller
 * @Annotations\RouteResource("images")
 */
class ImagesController extends FOSRestController implements ClassResourceInterface {

    /**
     * Get a single Image.
     *
     * @ApiDoc(
     *  section="Images",
     *  output = "AppBundle\Document\Image",
     *  statusCodes = {
     *    200 = "Returned when successful",
     *    404 = "Returned when not found"
     *  }
     * )
     *
     * @param string $id the image id
     *
     * @throws NotFoundHttpException when does not exist
     *
     * @return View
     */
    public function getAction($id) {
        $image = $this->getHandler()->get($id);

        $view = $this->view($image);

        return $view;
    }
    /**
     * Images collection
     *
     * @ApiDoc(
     *  section="Images",
     *  output = "array<AppBundle\Document\Image> as results",
     *  filters={
     *      {"name"="filter", "dataType"="string"}
     *  },
     *  statusCodes = {
     *    200 = "Returned when successful",
     *  }
     * )
     *
     * @param Request $request
     * @return View
     */
    public function cgetAction(Request $request) {
        $images = $this->getRepository()->search($request->query->all());
        $view = $this->view($images);

        return $view;
    }

    /**
     * Creates a new Image
     *
     * @ApiDoc(
     *  section="Images",
     *  resource=true,     
     *  input = "AppBundle\Document\Image",
     *  output = "AppBundle\Document\Image",
     *  groups = {"create"},
     *  statusCodes={
     *         201="Returned when a new Image has been successfully created",
     *         400="Returned when the posted data is invalid"
     *     }
     * )
     *
     * @param Request $request
     * @return View
     */
    public function postAction(Request $request) {
        try {
            $this->addRequestImage($request);
            $image = $this->getHandler()->post($request->request->all());

            return $this->view($image, Response::HTTP_CREATED);
        } catch (InvalidFormException $e) {
            return $e->getForm();
        }
    }


    /**
     * Update existing Image from the submitted data
     *
     * @ApiDoc(
     *   section="Images",
     *   resource = true,
     *   input = "AppBundle\Document\Image",
     *   output = "AppBundle\Document\Image",
     *   groups = {"update"},
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param string $id the account id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function patchAction(Request $request, $id) {
        $image = $this->getRepository()->findOneById($id);

        if ($image) {
            try {
                $this->addRequestImage($request);
                $image = $this->getHandler()->patch(
                    $image,
                    $request->request->all()
                );

                $routeOptions = [
                    'id' => $image->getId(),
                    '_format' => $request->get('_format'),
                ];

                return $this->routeRedirectView('get_images', $routeOptions, Response::HTTP_NO_CONTENT);

            } catch (InvalidFormException $e) {
                return $e->getForm();
            }
        }

        return $this->view($image);
    }


    /**
     * Replaces existing Image from the submitted data
     *
     * @ApiDoc(
     *   section="Images",
     *   resource = true,
     *   input = "AppBundle\Document\Image",
     *   output = "AppBundle\Document\Image",
     *   groups = {"update"},
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "Returned when errors",
     *     404 = "Returned when not found"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param string $id the account id
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException when does not exist
     */
    public function putAction(Request $request, $id) {
        $image = $this->getRepository()->findOneById($id);

        if ($image) {
            try {
                $this->addRequestImage($request);
                $image = $this->getHandler()->put(
                    $image,
                    $request->request->all()
                );

                $routeOptions = [
                    'id' => $image->getId(),
                    '_format' => $request->get('_format'),
                ];

                return $this->routeRedirectView('get_images', $routeOptions, Response::HTTP_NO_CONTENT);

            } catch (InvalidFormException $e) {
                return $e->getForm();
            }
        }

        return $this->view($image);
    }


    /**
     * Deletes a specific Image by ID
     *
     * @ApiDoc(
     *  section="Images",
     *  description="Deletes an existing Image",
     *  statusCodes={
     *         204="Returned when an existing Image has been successfully deleted",
     *         404="Returned when trying to delete a non existent Image"
     *     }
     * )
     *
     * @param string $id the account id
     * @return View
     */
    public function deleteAction($id) {
        $image = $this->getRepository()->findOneById($id);

        if ($image) {
            $this->getHandler()->delete($image);

            return new View(null, Response::HTTP_NO_CONTENT);
        }

        return $this->view($image);
    }

    /**
     * Adds image file (if it was sent) to request
     *
     * @param $request
     */
    private function addRequestImage($request) {
        $request->request->set('imageFile', $request->files->get('imageFile'));
    }

    /**
     * Returns the required handler for this controller
     *
     * @return \AppBundle\Handler\ImagesHandler
     */
    private function getHandler() {
        return $this->get('app.handler.images_handler');
    }

    /**
     * @return \AppBundle\Repository\ImageRepository
     */
    private function getRepository() {
        return $this->get('app.repository.image_repository');
    }
}
