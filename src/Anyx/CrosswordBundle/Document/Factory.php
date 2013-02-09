<?php

/**
 * 
 */

namespace Anyx\CrosswordBundle\Document;

/**
 * 
 */
use Doctrine\Common\Util\Inflector;
use Doctrine\ODM\MongoDB\DocumentManager;
use Anyx\CrosswordBundle\Document as Document;

/**
 * 
 * @todo validation
 */
class Factory
{

    /**
     *
     * @var type 
     */
    protected $aliases = array();

    /**
     *
     * @var type
     */
    protected $documentManager = null;

    /**
     *
     * @param array $aliases 
     */
    public function __construct(DocumentManager $documentManager, array $aliases = array())
    {

        $this->setDocumentManager($documentManager);

        foreach ($aliases as $alias => $class) {
            $this->addAlias($alias, $class);
        }
    }

    /**
     *
     * @param DocumentManager $documentManager 
     */
    public function setDocumentManager(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     *
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->documentManager;
    }

    /**
     *
     * @param string $alias
     * @param string $class 
     */
    public function addAlias($alias, $class)
    {
        $this->aliases[$alias] = $class;
    }

    /**
     *
     * @param string $class
     * @param array $data
     */
    public function create($class, array $data, $persist = true)
    {

        if (array_key_exists($class, $this->aliases)) {
            $class = $this->aliases[$class];
        }

        $document = $this->setFields(new $class, $data);

        if ($persist) {
            $this->getDocumentManager()->persist($document);
        }

        return $document;
    }

    /**
     *
     * @param type $class
     * @param type $data
     * @return \ArrayObject 
     */
    public function createCollection($class, $data, $persist = true)
    {

        $collection = array();

        foreach ($data as $objectData) {
            $collection[] = $this->create($class, $objectData, false);
        }

        return $collection;
    }

    /**
     *
     */
    public function setFields($document, $data)
    {

        foreach ($data as $field => $value) {
            $method = 'set' . Inflector::classify($field);
            if (!method_exists($document, $method)) {
                throw new \RuntimeException('Method "' . $method . '" not exist from object "' . get_class($document) . '"');
            }

            $document->$method($value);
        }

        return $document;
    }

    /**
     *
     * @param $collection
     * @param $data
     */
    public function updateCollection($collection, $data, $class)
    {
        foreach ($data as $newDocument) {
            $documentExists = false;
            foreach ($collection as &$oldDocument) {
                if ($newDocument->getId() == $oldDocument->getId()) {
                    $oldDocument = $this->setFields($oldDocument, $newDocument);
                    $documentExists = true;
                }
            }
            if (!$documentExists) {
                $collection[] = $this->create($class, $data);
            }
        }

        return $collection;
    }

}