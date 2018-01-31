<?php

namespace App\Service\Text\Processor;


class DefaultTextProcessor implements TextProcessorInterface
{

    function getMethods()
    {
        return [
            'stripTags',
            'removeSpaces',
            'replaceSpacesToEol',
            'htmlspecialchars',
            'removeSymbols',
            'toNumber',
        ];
    }

    function process($method, $text)
    {
        switch ($method) {
            case 'stripTags':
                return strip_tags($text);
                break;
            case 'removeSpaces':
                return preg_replace('/\s/', '', $text);
                break;
            case 'replaceSpacesToEol':
                return preg_replace('/\s/', PHP_EOL, $text);
                break;
            case 'htmlspecialchars':
                return htmlspecialchars($text);
                break;
            case 'removeSymbols':
                return preg_replace('/[\[.,\/!@#$%&*()\]]/', '', $text);
                break;
            case 'toNumber':
                if (preg_match('/\d+/', $text, $matches)) {
                    return $matches[0];
                } else {
                    return '';
                }
                break;
        }
        throw new \BadMethodCallException("Method DefaultTextProcessor::'{$method}' not implemented");
    }
}
