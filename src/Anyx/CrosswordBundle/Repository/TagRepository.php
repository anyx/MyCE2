<?php

namespace Anyx\CrosswordBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Anyx\CrosswordBundle\Document;

/**
 * 
 */
class TagRepository extends DocumentRepository
{
    /**
     * 
     * @param array $tagsTexts
     * @param \Anyx\CrosswordBundle\Document\Tag $tag
     * @return array
     */
    public function getOrCreateTagsByText(array $tagsTexts)
    {
        $tagsTexts = array_map(
                        function($tag) {
                            return trim($tag);
                        },
                        $tagsTexts
        );

        $foundTags = $this->getByText($tagsTexts)->toArray();
        $dm = $this->getDocumentManager();
        
        $result = $foundTags;
        
        foreach($tagsTexts as $tagText) {
            $tagExists = false;
            
            foreach($foundTags as $foundTag) {
                if ($foundTag->getText() == $tagText) {
                    $tagExists = true;
                    break;
                }
            }

            if (!$tagExists) {
                $tag = new Document\Tag();
                $tag->setText($tagText);
                $result[] = $tag;
            }
        }
        
        return $result;
    }


    /**
     * 
     * @param array $tags
     */
    public function incrementWeights($tags)
    {
        foreach($tags as $tag) {
            $tag->incWeight();
        }
    }
    
    /**
     * 
     * @param array $tags
     */
    public function decrementWeights($tags)
    {
        foreach($tags as $tag) {
            $tag->decWeight();
        }
    }
    
    /**
     * 
     * @param int $limit
     * @return array
     */
    public function getPopularTags($limit = 20)
    {
        return $this->findBy(array(), array('weight' => 'DESC'), $limit);
    }
    
    /**
     * 
     * @param array $tags
     * @return array
     * @return \Doctrine\MongoDB\LoggableCursor
     */
    public function getByText(array $tags)
    {
        return $this->createQueryBuilder()
                        ->field('text')->in($tags)
                        ->getQuery()
                        ->execute();
    }
    
    /**
     * 
     * @param string $term
     * @param int $limit
     * @return \Doctrine\MongoDB\LoggableCursor
     */
    public function getTagsByTerm($term, $limit = 10)
    {
        return $this->createQueryBuilder()
                        ->field('text')->equals(new \MongoRegex('/.*'.$term.'/i'))
                        ->limit($limit)
                        ->getQuery()
                        ->execute();
    }
}
