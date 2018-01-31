<?php

namespace App\Service\Text;


use App\Service\Text\Processor\TextProcessorInterface;

interface TextProcessorRegistry
{
    function register(TextProcessorInterface $processor);

    function process($method, $text);

}
