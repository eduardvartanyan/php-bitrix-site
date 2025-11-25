<?php

use Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) return;
?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="post">
    <?= bitrix_sessid_post() ?>
    <h2><?= Loc::getMessage('VSK_UNINSTALL_TITLE') ?></h2>
    <p><?= Loc::getMessage('VSK_UNINSTALL_CONFIRM') ?></p>
    <label>
        <input type="checkbox" name="savedata" value="Y" checked>
        <?= Loc::getMessage('VSK_UNINSTALL_SAVE_DATA') ?>
    </label>
    <br><br>
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="submit" name="inst" value="<?= Loc::getMessage('VSK_UNINSTALL_BTN_CONTINUE') ?>">
</form>
