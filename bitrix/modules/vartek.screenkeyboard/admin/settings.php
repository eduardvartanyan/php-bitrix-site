<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Vartek\ScreenKeyboard\Config;

if (!check_bitrix_sessid()) return;

Loc::loadMessages(__FILE__);

$moduleId = 'vartek.screenkeyboard';

if ($REQUEST_METHOD === 'POST' && strlen($Update) > 0 && check_bitrix_sessid()) {
    Option::set($moduleId, 'auto_enable', $_POST['auto_enable'] === 'Y' ? 'Y' : 'N');
    Option::set($moduleId, 'theme', $_POST['theme']);
    Option::set($moduleId, 'position', $_POST['position']);
    Option::set($moduleId, 'animation', $_POST['animation'] === 'Y' ? 'Y' : 'N');

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . urlencode($moduleId) . '&lang=' . urlencode(LANGUAGE_ID) . '&saved=Y');
}

$settings = Config::all();
?>

<form method="post" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= $moduleId ?>&lang=<?= LANGUAGE_ID ?>">
    <?=bitrix_sessid_post()?>
    <table class="adm-detail-content-table edit-table">
        <tr class="heading">
            <td colspan="2"><?= Loc::getMessage('VSK_SETTINGS_MAIN') ?></td>
        </tr>
        <tr>
            <td width="40%"><?= Loc::getMessage('VSK_SETTINGS_AUTO_ENABLE') ?>:</td>
            <td width="60%">
                <input type="checkbox" name="auto_enable" value="Y" <?= $settings['auto_enable'] === 'Y' ? 'checked' : '' ?>>
            </td>
        </tr>
        <tr>
            <td><?= Loc::getMessage('VSK_SETTINGS_THEME') ?>:</td>
            <td>
                <select name="theme">
                    <option value="light" <?= $settings['theme'] === 'light' ? 'selected' : '' ?>>Light</option>
                    <option value="dark" <?= $settings['theme'] === 'dark' ? 'selected' : '' ?>>Dark</option>
                    <option value="custom" <?= $settings['theme'] === 'custom' ? 'selected' : '' ?>>Custom</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?= Loc::getMessage('VSK_SETTINGS_POSITION') ?>:</td>
            <td>
                <select name="position">
                    <option value="bottom" <?= $settings['position'] === 'bottom' ? 'selected' : '' ?>><?= Loc::getMessage('VSK_POS_BOTTOM') ?></option>
                    <option value="fixed" <?= $settings['position'] === 'fixed' ? 'selected' : '' ?>><?= Loc::getMessage('VSK_POS_FIXED') ?></option>
                    <option value="absolute" <?= $settings['position'] === 'absolute' ? 'selected' : '' ?>><?= Loc::getMessage('VSK_POS_ABSOLUTE') ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?= Loc::getMessage('VSK_SETTINGS_ANIMATION') ?>:</td>
            <td>
                <input type="checkbox" name="animation" value="Y" <?= $settings['animation'] === 'Y' ? 'checked' : '' ?>>
            </td>
        </tr>
    </table>
    <input type="submit" name="Update" value="<?= Loc::getMessage('VSK_SETTINGS_SAVE') ?>" class="adm-btn-save">
</form>
