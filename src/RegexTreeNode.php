<?php

namespace Rosem\Route;

use function count;
use function mb_strlen;
use function mb_substr;

class RegexTreeNode
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var self[]
     */
    protected $nodes = [];

    public function __construct(string $prefix = '', array $nodes = [])
    {
        $this->prefix = $prefix;
        $this->nodes = $nodes;
    }

    public function clear(): void
    {
        $this->__construct();
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function hasNodes(): bool
    {
        return (bool)count($this->nodes);
    }

    public function getNodes(): array {
        return $this->nodes;
    }

    public function addRegex(string $regex): void
    {
        $patternLength = mb_strlen($regex);
        $matchLength = 0;

        foreach ($this->nodes as $index => &$node) {
            $nodePrefix = $node->getPrefix();
            $nodePrefixLength = mb_strlen($nodePrefix);
            $end = min($patternLength, $nodePrefixLength);
            $groups = 0;
            $ignoreMatchLength = 0;

            for ($matchLength = 0; $matchLength < $end; ++$matchLength) {
                if ($regex[$matchLength] !== $nodePrefix[$matchLength]) {
                    break;
                }

                if ('(' === $regex[$matchLength] || '(' === $nodePrefix[$matchLength]) {
                    ++$groups;
                } elseif (')' === $regex[$matchLength] || ')' === $nodePrefix[$matchLength]) {
                    --$groups;
                }

                if ($groups > 0) {
                    ++$ignoreMatchLength;
                } elseif ((isset($regex[$matchLength + 1]) && '?' === $regex[$matchLength + 1])
                    || (isset($nodePrefix[$matchLength + 1]) && '?' === $nodePrefix[$matchLength + 1])
                    || '/' === $nodePrefix[$matchLength]
                    || '/' === $regex[$matchLength]
                ) {
                    ++$ignoreMatchLength;
                } else {
                    $ignoreMatchLength = 0;
                }
            }

            $matchLength -= $ignoreMatchLength;

            if ($matchLength) {
                if ($matchLength !== $nodePrefixLength) {
                    $newPrefix = mb_substr($nodePrefix, 0, $matchLength);
                    $newChild = mb_substr($nodePrefix, $matchLength);
                    $node = new self($newPrefix, [new self($newChild, $node->getNodes())]);
                } elseif (!$node->hasNodes()) {
                    $node->addRegex('');
                }

                $node->addRegex(mb_substr($regex, $matchLength));

                break;
            }
        }

        unset($node);

        if (!$matchLength) {
            $this->nodes[] = new self($regex);
        }
    }

    public function getRegex(): string
    {
        $regex = $this->prefix;

        if ($this->hasNodes()) {
            $regex .= '(?';

            foreach ($this->nodes as $node) {
                $regex .= '|' . $node->getRegex();
            }

            $regex .= ')';
        }

        return $regex;
    }
}
