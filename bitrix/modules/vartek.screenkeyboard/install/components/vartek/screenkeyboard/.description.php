<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$componentDescription = [
    'NAME' => Loc::getMessage('VSK_COMP_NAME'),
    'DESCRIPTION' => Loc::getMessage('VSK_COMP_DESC'),
    'PATH' => [
        'ID' => 'vartek',
        'NAME' => 'Vartek Components'
    ],
];
