<?php

namespace Anyx\CrosswordBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapCommand extends ContainerAwareCommand
{
    /**
     * 
     */
    protected function configure()
    {
        $this
            ->setName('site:sitemap:generate')
            ->setDescription('Generate sitemap')
            ->addOption('host', null, InputOption::VALUE_REQUIRED, 'Http host', 'http://crosswords-editor.ru/')
        ;
    }
    
    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = new \DOMDocument('1.0', 'UTF-8');
        
        $urlSet = $sitemap->createElement('urlset');
        $sitemap->appendChild($urlSet);
        
        foreach ($this->getCrosswordsLinks($sitemap, $input) as $entry) {
            $urlSet->appendChild($entry);
        }
        foreach($this->getStaticPages($sitemap, $input) as $entry) {
            $urlSet->appendChild($entry);
        }
        
        $path = $this->getContainer()->get('kernel')->getRootDir() . '/../web/sitemap.xml';
        
        file_put_contents($path, $sitemap->saveXML());
    }
    
    /**
     * 
     * @param \DOMDocument $sitemap
     * @return \DOMDocument
     */
    protected function getCrosswordsLinks(\DOMDocument $sitemap, InputInterface $input)
    {
        $router = $this->getContainer()->get('router');
        $crosswordsRepo = $this->getContainer()->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Crossword');
        
        $crosswords = $crosswordsRepo->getPublicCrosswords();
        
        $result = array();
        
        foreach ($crosswords as $crossword) {
            $result[] = $this->createEntry(
                                $sitemap,
                                $input->getOption('host') . $router->generate('crossword_solve', array('id' => $crossword->getId())),
                                $crossword->getUpdatedAt()->format('Y-m-d'),
                                'weekly',
                                '0.8'
            );
        }
        
        return $result;
    }
    
    /**
     * 
     * @param \DOMDocument $sitemap
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return array
     */
    protected function getStaticPages(\DOMDocument $sitemap, InputInterface $input)
    {
        $router = $this->getContainer()->get('router');
        $pages = array('about', 'report', 'news', 'about');
        $lastMod = new \DateTime();
        $result = array();
        foreach($pages as $pageSlug) {
            $result[] = $this->createEntry(
                                $sitemap,
                                $input->getOption('host') . $router->generate('page', array('slug' => $pageSlug)),
                                $lastMod->format('Y-m-d'),
                                'monthly',
                                '0.3'
            );
        }
        
        $result[] = $this->createEntry(
                            $sitemap,
                            $input->getOption('host') . $router->generate('homepage'),
                            $lastMod->format('Y-m-d'),
                            'daily',
                            '0.9'
        );
        
        return $result;
    }
    
    /**
     * 
     * @param \DOMDocument $document
     * @param string $url
     * @param string $lastmod
     * @param string $changefreq
     * @param string $priority
     * @return \DOMElement
     */
    protected function createEntry(\DOMDocument $document, $url, $lastmod, $changefreq, $priority)
    {
        $element = $document->createElement('url');
        
        $element->appendChild($document->createElement('loc', $url));
        $element->appendChild($document->createElement('lastmod', $lastmod));
        $element->appendChild($document->createElement('changefreq', $changefreq));
        $element->appendChild($document->createElement('priority', $priority));
        
        return $element;
    }
}