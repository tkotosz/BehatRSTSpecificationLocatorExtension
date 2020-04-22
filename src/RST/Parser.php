<?php

namespace Bex\Behat\BehatRSTSpecificationLocatorExtension\RST;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser as GherkinParser;
use Doctrine\RST\Nodes\CodeNode;
use Doctrine\RST\Nodes\QuoteNode;
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
        $mainDocument = $this->parser->parse($input);

        // collect documents (content of quote notes counts as an embedded document)
        $documents = [$mainDocument];
        foreach ($mainDocument->getNodes() as $node) {
            if ($node instanceof QuoteNode) {
                $documents[] = $node->getValue();
            }
        }

        // collect all code node
        $gherkinNodes = [];
        foreach ($documents as $document) {
            $documentGherkinNodes = $document->getNodes(function ($node) {
                return ($node instanceof CodeNode) && ($node->getLanguage() === 'gherkin');
            });

            $gherkinNodes = array_merge($gherkinNodes, $documentGherkinNodes);
        }

        $feature = $mainDocument->getTitle();

        $text = '';
        foreach ($gherkinNodes as $node) {
            $text .= $node->getValue() . PHP_EOL;
        }

        return $this->gherkinParser->parse('Feature: ' . $feature . PHP_EOL . $text, $file);
    }
}