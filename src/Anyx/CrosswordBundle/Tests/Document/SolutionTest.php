<?php

namespace Anyx\CrosswordBundle\Tests\Document;

use Anyx\CrosswordBundle\Tests as BaseTest;

class SolutionTest extends BaseTest\TestCase
{
    
    public function testUpdatingCountSolving() {
        
        $user = $this->createDocument(
                                'User',
                                array(
                                    'username'  => 'testUser',
                                    'email'     => 'tst@email.com',
                                    'enabled'   => true
                                )
        );
        
        $word = $this->createDocument(
                                'Word',
                                array(
                                    'text'           => 'hello',
                                    'definition'     => 'def',
                                ),
                                false,
                                false
        );
        
        $crossword = $this->createDocument('Crossword', array(
                           'title'  => 'TestSolutionEventsCrossword',
                           'public' => true,
                           'owner'  => $user,
                           'words'  => array(
                                $word
                            )
            )
        );
        
        $this->assertEquals( $crossword->getCountSolving(), 0, 'Wrong default solving count' );
        
        $answer = $this->createDocument(
                        'Answer',
                        array(
                            'word'      => $word,
                            'answer'    => 'wrong answer'
                        ),
                        false,
                        false
        );
        
        $solution = $this->createDocument('Solution', array(
            'user'          => $user,
            'crossword'     => $crossword,
            'answers'       => array(
                $answer
            )
        ));
        $this->getContainer()->get('anyx.dm')->flush();
        
        $this->assertEquals( $crossword->getCountSolving(), 1, 'Solving count is not updated' );
    }
}