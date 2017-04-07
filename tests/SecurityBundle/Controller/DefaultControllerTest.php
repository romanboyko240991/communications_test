<?php

namespace SecurityBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\HttpFoundation\Cookie;

class DefaultControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->assertRegExp('/\/login$/', $this->client->getResponse()->headers->get('location'));

        $this->assertContains('Username', $this->client->getResponse()->getContent());
        $this->assertContains('Password', $this->client->getResponse()->getContent());
    }

    public function testSecuredSettings()
    {
        $this->logIn();

        $crawler = $this->client->request('GET', '/settings');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Search by username")')->count());
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $em = $this->getDoctrine()->getManager();
        $repo  = $em->getRepository('AppBundle:User');
        $user = $repo->loadUserByUsername('adminuser');

        if (!$user) {
            throw new UsernameNotFoundException("User not found");
        } else {
            $token = new UsernamePasswordToken($user, null, "main", $user->getRoles());
            $this->get("security.context")->setToken($token);

            $request = $this->get("request");
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            $session->set('_security_main', serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $this->client->getCookieJar()->set($cookie);
        }
    }

    public function testSecuredCommunication()
    {
        $crawler = $this->client->request('GET', '/communications');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Tags")')->count());
    }
}
