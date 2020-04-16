<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\RST;

use Doctrine\RST\Nodes\CodeNode;
use Doctrine\RST\Parser as DoctrineRSTParser;

class Parser
{
    /** @var DoctrineRSTParser */
    private $parser;

    public function __construct(DoctrineRSTParser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(string $content): string
    {
        $document = $this->parser->parse($content);
        $codeNodes = $document->getNodes(function ($node) {
            return $node instanceof CodeNode;
        });
        $feature = $document->getTitle();

        $text = '';
        foreach ($codeNodes as $node) {
            $text .= $node->getValue() . PHP_EOL;
        }

        return 'Feature: ' . $feature . PHP_EOL . $text;
    }
}