<?php

namespace AppBundle\Form\Handler;

interface FormHandlerInterface
{
    /**
     * @param mixed     $document
     * @param array     $parameters
     * @param string    $method
     * @param array     $options
     * @return mixed
     */
    public function handle($document, array $parameters, $method, array $options);
}