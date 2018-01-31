<?php

namespace App\Service\Text\Processor;


interface TextProcessorInterface
{
    function getMethods();

    function process($method, $text);
}