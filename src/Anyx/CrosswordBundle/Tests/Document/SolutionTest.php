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
        $this->getContainer()->get('anyx.dm')->flush();
         
        $answer = $this->createDocument(
                        'Answer',
                        array(
                            'wordId'    => $word->getId(),
                            'text'      => 'wrong answer'
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
        ), true, false);
        
        $this->getContainer()->get('anyx.dm')->flush();
        $this->assertEquals( $crossword->getCountSolving(), 1, 'Solving count is not updated' );

        return $solution;
    }
    
    /**
     * @depends testUpdatingCountSolving
     */
    public function testCheckingSolutionCorrectness( $solution ) {
        
        $crossword = $solution->getCrossword();
        
        $this->assertFalse($solution->isCorrect(), 'Wrong checking solution correctness');
        
        $rightAnswers = array();

		$factory = $this->getContainer()->get('anyx.document.factory');

        
		foreach( $crossword->getWords() as $word ) {
            $rightAnswers[]	= $factory->create('Answer', array(
                'wordId'    => $word->getId(),
                'text'      => $word->getText()
            ), false);
		}        
        
        $solution->setAnswers($rightAnswers);

        $this->getContainer()->get('anyx.dm')->flush();
        $this->assertTrue($solution->isCorrect(), 'Wrong checking solution correctness');
    }
}