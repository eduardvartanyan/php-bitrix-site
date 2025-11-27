<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;
?>
    <div id="sk-keyboard-container"></div>
    <script>BX.message({VSK_TEMPLATE_FOLDER: "<?=$templateFolder?>"});</script>
<?php
Asset::getInstance()->addCss($templateFolder . '/style.css');