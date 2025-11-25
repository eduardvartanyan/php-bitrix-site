<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) return;
?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="post">
    <?= bitrix_sessid_post() ?>
    <h2><?= Loc::getMessage("VSK_INSTALL_TITLE") ?></h2>
    <p><?= Loc::getMessage("VSK_INSTALL_SUCCESS") ?></p>
    <p><?= Loc::getMessage("VSK_INSTALL_GO_TO_SETTINGS") ?></p>
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="submit" value="<?= Loc::getMessage("VSK_INSTALL_BTN_CONTINUE") ?>">
</form>
