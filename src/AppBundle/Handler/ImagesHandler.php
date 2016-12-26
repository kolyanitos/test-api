<?php

namespace AppBundle\Handler;

use AppBundle\Document\Image;
use AppBundle\Form\Handler\FormHandlerInterface;
use AppBundle\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ImagesHandler implements HandlerInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $formHandler;

    /**
     * @var ImageRepository
     */
    private $repository;

    public function __construct(
        FormHandlerInterface $formHandler,
        ImageRepository $imageRepository
    )
    {
        $this->formHandler = $formHandler;
        $this->repository = $imageRepository;
    }

    /**
     * @param array                 $parameters
     * @param array                 $options
     * @return Image
     */
    public function post(array $parameters, array $options = [])
    {
        $image = $this->formHandler->handle(
            new Image(),
            $parameters,
            Request::METHOD_POST,
            $options
        );

        $this->repository->add($image);

        return $image;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get($id)
    {
        if ($id === null) {
            throw new BadRequestHttpException('Image ID was not specified.');
        }

        return $this->repository->findOneById($id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return mixed
     */
    public function search($limit = 10, $offset = 0)
    {
        return $this->repository->findAllForUser($this->user)->slice($offset, $limit);
    }
    

    /**
     * @param  Image     $image
     * @param  array                $parameters
     * @param  array                $options
     * @return mixed
     */
    public function patch($image, array $parameters, array $options = [])
    {
        $image = $this->formHandler->handle(
            $image,
            $parameters,
            Request::METHOD_PATCH,
            $options
        );

        $this->repository->save($image);

        return $image;
    }


    /**
     * @param  Image     $image
     * @param  array     $parameters
     * @param  array     $options
     * @return mixed
     */
    public function put($image, array $parameters, array $options = [])
    {
        $image = $this->formHandler->handle(
            $image,
            $parameters,
            Request::METHOD_PUT,
            $options
        );

        $this->repository->save($image);

        return $image;
    }
    

    /**
     * @param mixed $resource
     * @return bool
     */
    public function delete($resource)
    {
        $this->repository->delete($resource);

        return true;
    }
}
