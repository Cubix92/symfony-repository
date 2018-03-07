<?php
namespace AppBundle\Form\DataTransformer;

use AppBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagsToTextTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms an object to a string.
     *
     * @param  ArrayCollection $tagsAsArray
     * @return string
     */
    public function transform($tagsAsArray)
    {
        $tags = '';

        foreach($tagsAsArray->toArray() as $tag) {
            $tags .= $tag->getName() . ', ';
        }

        return rtrim($tags, ', ');
    }

    /**
     * Transforms a string to an ArrayCollection.
     *
     * @param  string $tagsAsString
     * @return array
     * @throws TransformationFailedException.
     */
    public function reverseTransform($tagsAsString)
    {
        $tags = array();
        $tagsArray = explode(', ', $tagsAsString);

        foreach ((array)$tagsArray as $tagName) {
            $tag = $this->manager->getRepository('AppBundle:Tag')->findOneByName($tagName);
            if (!$tag) {
                $tag = new Tag();
                $tag->setName($tagName);
            }

            $tags[] = $tag;
        }

        return $tags;
    }
}