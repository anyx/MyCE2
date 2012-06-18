<?php

namespace Anyx\CrosswordBundle\Tests\Document;

use Anyx\CrosswordBundle\Tests as BaseTest;

class CrosswordTest extends BaseTest\TestCase
{
    
    public function testSavingCrosswordWordId() {
        //given
        $wordData = array(
                        'text'           => 'hello',
                        'definition'     => 'def',
                        'position'       => array(
                            'x' => 1,
                            'y' => 3
                        )
        );
        
        $word = $this->createDocument(
                                'Word',
                                $wordData,
                                false,
                                false
        );
        
        $crossword = $this->createDocument('Crossword', array(
                           'title'  => 'TestSolutionEventsCrossword',
                           'public' => true,
                           'words'  => array(
                                $word
                            )
            )
        );
        $this->getContainer()->get('anyx.dm')->flush();
        $savedWord = current($crossword->getWords()->toArray());
        //when
        $word2Update = $this->createDocument(
                                'Word',
                                array_merge( $wordData, array(
                                    'id'            => $savedWord->getId(),
                                    'definition'    => 'Updated defintion'
                                )),
                                false,
                                false
        );
        $crossword->updateWords(array($word2Update));
        $this->getContainer()->get('anyx.dm')->flush();
        //then
        $updatedWord = current($crossword->getWords()->toArray());
        $this->assertEquals( $savedWord->getId(), $updatedWord->getId(), 'Id updated word was changed' );
    }
}