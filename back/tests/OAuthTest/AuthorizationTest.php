<?php

namespace App\Tests\OAuthTest;

use OAuth2\OAuth2;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class AuthorizationTest extends WebTestCase
{
    /** @var RouterInterface */
    private $router;

    /** @var KernelBrowser */
    private $client;

    public function setUp()
    {
        $this->client = self::createClient();
        $this->router = self::$container->get(RouterInterface::class);
    }

    public function testAuthorization()
    {
        $url        = $this->router->generate('fos_oauth_server_token');
        $parameters = [
            'client_id'     => '1_3xi2g7gu5m4gc84880wow0448w4w4goo8o4goc4s88w88s0ww',
            'client_secret' => '3toqncrwntessw080gkgswow8g8k884kscs4ww8ss4ggw4ow0g',
            'response_type' => OAuth2::RESPONSE_TYPE_ACCESS_TOKEN,
            'grant_type'    => OAuth2::GRANT_TYPE_USER_CREDENTIALS,
            'username'      => 'test',
            'password'      => 'test',
        ];

        $this->client->request(Request::METHOD_POST, $url, $parameters);
        $response = $this->client->getResponse();
        $content  = \json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $url = $this->router->generate('get_test');

        $this->client->request(Request::METHOD_GET, $url, [], [], [
            'HTTP_AUTHORIZATION' => $content['access_token'],
        ]);
        $response = $this->client->getResponse();
        $content = \json_decode($response->getContent(), true);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
        $this->assertEquals('Test Access Denied.', $content['message']);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $content['code']);
    }
}
