<?php

namespace Anyx\CrosswordBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Anyx\CrosswordBundle\Repository\TagRepository;

class TagsTransformer implements DataTransformerInterface
{
    
    private $tagsRepository;

    /**
     * 
     * @param \Anyx\CrosswordBundle\Repository\TagRepository $tagsRepository
     */
    public function __construct(TagRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * 
     * @return \Anyx\CrosswordBundle\Repository\TagRepository
     */
    public function getTagsRepository()
    {
        return $this->tagsRepository;
    }

    /**
     * 
     * @param array $value
     * @return string
     */
    public function reverseTransform($value)
    {
        return $this->getTagsRepository()->getOrCreateTagsByText($this->getTagsFromString($value));
    }

    /**
     * 
     * @param string $value
     * @return array
     */
    public function transform($tags)
    {
        return implode(',', array_map(
                function($tagObject) {
                    return $tagObject->getText();
                },
                $tags->toArray()
        ));
    }

    /**
     * 
     * @param string $value
     * @return array
     */
    private function getTagsFromString($value)
    {
        return array_filter(
                explode(',', $value),
                function($tag) {
                    return !empty($tag);
                }
        );
    }
}