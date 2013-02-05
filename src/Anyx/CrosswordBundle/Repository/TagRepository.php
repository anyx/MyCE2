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
     * @param array $tags
     */
    public function incrementWeights(array $tags)
    {
        $em = $this->getDocumentManager();
        
        $existTags = array();
        $tagObjects = $this->getByText($tags);

        foreach($tagObjects as $tag) {
            $tag->incWeight();
            $existTags[] = $tag->getText();
        }
        
        foreach(array_diff($tags, $existTags) as $newTag) {
            $tag = new Document\Tag();
            $tag->setText($newTag);
            $tag->incWeight();
            
            $em->persist($tag);
        }
    }
    
    /**
     * 
     * @param array $tags
     */
    public function decrementWeights(array $tags)
    {
        $tagObjects = $this->getByText($tags);
        foreach($tagObjects as $tag) {
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
     */
    public function getByText(array $tags)
    {
        return $this->createQueryBuilder()
                        ->field('text')->in($tags)
                        ->getQuery()
                        ->execute();
    }
}
