<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 12.05.16
 * Time: 22:52
 *
 * Data fixture for Post entity.
 */

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Tag;

/**
 * Class LoadTaskData.
 * @package BlogBundle\DataFixtures\ORM
 * @author Monika
 */
class LoadPostData implements FixtureInterface
{
    /**
     *
     * Load data.
     *
     * @param ObjectManager $manager Manager
     */
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setTitle('Tytuł');
        $post->setContent(
            'Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.\nLorem ipsum dolor sit
             amet, consectetur adipiscing elit. Morbi ut velocity magna. Etiam vehicula nunc non leo hendrerit commodo. 
             Vestibulum vulputate mauris eget erat congue dapibus imperdiet justo scelerisque. Nulla consectetur tempus 
             nisl vitae viverra. Cras el mauris eget erat congue dapibus imperdiet justo scelerisque. Nulla consectetur 
             tempus nisl vitae viverra. Cras elementum molestie vestibulum. Morbi id quam nisl. Praesent hendrerit, orci
              sed elementum lobortis, justo mauris lacinia libero, non facilisis purus ipsum non mi. Aliquam 
              sollicitudin, augue id vestibulum iaculis, sem lectus convallis nunc, vel scelerisque lorem tortor ac 
              nunc. Donec pharetra eleifend enim vel porta.'
        );
        $post->setCreated(new \DateTime());
        $post->setUpdated($post->getCreated());
        $manager->persist($post);

        $tagPrivate = $manager->getRepository('BlogBundle:Tag')
            ->findOneByName('private');
        $tagImportant = $manager->getRepository('BlogBundle:Tag')
            ->findOneByName('important');
        $post->addTag($tagPrivate);
        $post->addTag($tagImportant);

        $manager->persist($tagPrivate);
        $manager->persist($tagImportant);
        $manager->persist($post);

        $post1 = new Post();
        $post1->setTitle('Tytuł 2');
        $post1->setContent(
            'Morbi ut velocity magna. Etiam vehicula nunc non leo hendrerit commodo. Vestibulum vulputate mauris eget 
            erat congue dapibus imperdiet justo scelerisque. Nulla consectetur tempus nisl vitae viverra. Cras el mauris
             eget erat congue dapibus imperdiet justo scelerisque. Nulla consectetur tempus nisl vitae viverra. 
             Cras elementum molestie vestibulum. Morbi id quam nisl. Praesent hendrerit, orci sed elementum lobortis, 
             justo mauris lacinia libero, non facilisis purus ipsum non mi. Aliquam sollicitudin, augue id vestibulum 
             iaculis, sem lectus convallis nunc, vel scelerisque lorem tortor ac nunc. Donec pharetra eleifend enim vel 
             porta.'
        );
        $post1->setCreated(new \DateTime());
        $post1->setUpdated($post1->getCreated());
        $manager->persist($post1);

        $tagPrivate = $manager->getRepository('BlogBundle:Tag')
            ->findOneByName('private');
        $tagImportant = $manager->getRepository('BlogBundle:Tag')
            ->findOneByName('important');
        $post1->addTag($tagPrivate);
        $post1->addTag($tagImportant);

        $manager->persist($tagPrivate);
        $manager->persist($tagImportant);
        $manager->persist($post1);


        $manager->flush();
    }
}
