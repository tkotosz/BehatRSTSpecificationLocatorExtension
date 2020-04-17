<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\RST;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser as GherkinParser;
use Doctrine\RST\Nodes\CodeNode;
use Doctrine\RST\Parser as DoctrineRSTParser;

class Parser
{
    /** @var DoctrineRSTParser */
    private $parser;

    /** @var GherkinParser */
    private $gherkinParser;

    public function __construct(DoctrineRSTParser $parser, GherkinParser $gherkinParser)
    {
        $this->parser = $parser;
        $this->gherkinParser = $gherkinParser;
    }

    public function parse(string $input, string $file = null): ?FeatureNode
    {
        $document = $this->parser->parse($input);
        $codeNodes = $document->getNodes(function ($node) {
            return $node instanceof CodeNode;
        });
        $feature = $document->getTitle();

        $text = '';
        foreach ($codeNodes as $node) {
            $text .= $node->getValue() . PHP_EOL;
        }

        return $this->gherkinParser->parse('Feature: ' . $feature . PHP_EOL . $text, $file);
    }
}