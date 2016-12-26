<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagesControllerTest extends WebTestCase {

    protected static $image;

    protected static $filesPath;

    /**
     * @var ContainerInterface
     */
    protected static $container;

    protected function log($var) {
        fwrite(STDERR, print_r($var . PHP_EOL, true));
    }

    public static function setUpBeforeClass()
    {
        self::bootKernel();

        self::$container = self::$kernel->getContainer();

        self::$filesPath = self::$container->getParameter('kernel.root_dir') . '/../tests/AppBundle/Resources/files/';

        if (file_exists(self::$filesPath . 'ff_copy.jpg')) {
            rename(self::$filesPath . 'ff_copy.jpg', self::$filesPath . 'ff.jpg');
        }

        copy(self::$filesPath . 'ff.jpg', self::$filesPath . 'ff_copy.jpg');
    }

    public static  function tearDownAfterClass() {}

    /**
     * @group images
     * @covers ImagesController::getAction
     */
    public function testContentTypeJson() {
        $client = static::createClient();

        $client->request('GET', '/api/images/item');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }

    /**
     * @group images
     * @covers ImagesController::postAction
     */
    public function testPostSuccess() {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/images',
            ['tags' => ['tag1', 'tag2', 'tag3']],
            ['imageFile' => new UploadedFile(
                self::$filesPath . 'ff.jpg',
                'ff.jpg',
                null,
                null,
                null,
                true
            )]
        );

        self::$image = json_decode($client->getResponse()->getContent());

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::getAction
     */
    public function testGetNotFound() {
        $client = static::createClient();

        $client->request('GET', '/api/images/item');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::getAction
     * @depends testPostSuccess
     */
    public function testGetSuccess() {
        $client = static::createClient();

        $client->request('GET', '/api/images/' . self::$image->id);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return json_decode($client->getResponse()->getContent());
    }

    /**
     * @group images
     * @covers ImagesController::postAction
     */
    public function testPostInvalid() {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/images',
            ['tags' => ['tag1', 'tag2', 'tag3']],
            []
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::patchAction
     * @depends testPostSuccess
     */
    public function testPatchSuccess() {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/api/images/' . self::$image->id,
            ['tags' => ['tag1']],
            []
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::patchAction
     * @depends testPostSuccess
     */
    public function testPatchInvalid() {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/api/images/' . self::$image->id,
            ['tags' => [['invalid_tag']]],
            []
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::patchAction
     */
    public function testPatchNotFound() {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/api/images/item',
            ['tags' => ['tag1']],
            []
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::putAction
     * @depends testPostSuccess
     */
    public function testPutSuccess() {
        $client = static::createClient();

        $client->request(
            'PUT',
            '/api/images/' . self::$image->id,
            [],
            []
        );

        $image = $this->testGetSuccess();
        $this->assertCount(0, $image->tags, 'Count of tags should be "0"');
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::putAction
     * @depends testPostSuccess
     */
    public function testPutInvalid() {
        $client = static::createClient();

        $client->request(
            'PUT',
            '/api/images/' . self::$image->id,
            ['tags' => [['invalid_tag']]],
            []
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::deleteAction
     * @depends testPostSuccess
     */
    public function testDeleteSuccess() {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/api/images/' . self::$image->id
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::deleteAction
     */
    public function testDeleteNotFound() {
        $client = static::createClient();

        $client->request(
            'DELETE',
            '/api/images/item'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * @group images
     * @covers ImagesController::cgetAction
     */
    public function testGetCollection() {
        $client = static::createClient();

        $client->request(
            'GET',
            '/api/images'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
