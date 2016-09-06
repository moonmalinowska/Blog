<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 06.05.16
 * Time: 16:40
 *
 * User entity.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest.
 * @package BlogBundle\Test
 * @author Monika Malinowska
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * Test Index.
     *
     */
    public function testIndex()
    {
        /** @var mixed $client */
        $client = static::createClient();

        /** @var mixed $crawler */
        $crawler = $client->request('GET', '/hello/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
}
