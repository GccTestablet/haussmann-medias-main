<?php

declare(strict_types=1);

namespace App\Tools\Parser;

class HTMLDomParser
{
    private readonly \DOMDocument $dom;

    public function __construct()
    {
        $this->dom = new \DOMDocument();
    }

    /**
     * @param array<string, string> $attributes
     *
     * @throws \DOMException
     */
    public function createElement(string $name, array $attributes = [], string $value = ''): \DOMElement
    {
        $element = $this->dom->createElement($name, $value);
        foreach ($attributes as $attribute => $attributeValue) {
            $element->setAttribute($attribute, $attributeValue);
        }

        return $element;
    }

    /**
     * @param array<\DOMElement|string> $elements
     */
    public function appendTo(\DOMElement $domElement, array $elements): void
    {
        $domElement->append(...$elements);
    }

    public function asString(\DOMElement $domElement): string
    {
        return \simplexml_import_dom($domElement)?->asXML();
    }
}
