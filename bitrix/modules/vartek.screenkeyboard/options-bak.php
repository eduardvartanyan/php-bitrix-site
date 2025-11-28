<?php

use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Vartek\ScreenKeyboard\Config;

$moduleId = 'vartek.screenkeyboard';

try {
    Loader::includeModule($moduleId);
} catch (LoaderException $e) { return; }

/**
 * @global CMain $APPLICATION
 * @global string $REQUEST_METHOD
 **/

$rights = $APPLICATION->GetGroupRight($moduleId);

if ($REQUEST_METHOD === 'POST' && check_bitrix_sessid()) {
    try {
        Config::set('auto_enable', $_POST['auto_enable'] === 'Y' ? 'Y' : 'N');
        Config::set('theme', $_POST['theme']);
        Config::set('position', $_POST['position']);
        Config::set('animation', $_POST['animation'] === 'Y' ? 'Y' : 'N');
        LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . urlencode($moduleId) . '&lang=' . urlencode(LANGUAGE_ID) . '&saved=Y');
    } catch (ArgumentOutOfRangeException $e) {
        echo '<div class="adm-info-message-wrap adm-info-message-red">
            <div class="adm-info-message">'
            .htmlspecialcharsbx(Loc::getMessage('VSK_SAVE_ERROR_MESSAGE')).
            '</div>
          </div>';
    }
}

$settings = Config::all();

$aTabs = [
    [
        'DIV' => 'edit1',
        'TAB' => Loc::getMessage('VSK_OPT_TAB_OPTIONS'),
        'ICON' => '',
        'TITLE' => Loc::getMessage('LOCATION_OPT_TAB_OPTIONS'),
    ]
];
$tabControl = new CAdminTabControl('tabControl', $aTabs);
?>

<? $tabControl->Begin(); ?>
<form method="post" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= $moduleId ?>&lang=<?= LANGUAGE_ID ?>">
    <? $tabControl->BeginNextTab(); ?>
    <tr>
        <td width="40%"><?= Loc::getMessage('VSK_SETTINGS_AUTO_ENABLE') ?>:</td>
        <td width="60%"><input type="checkbox" name="auto_enable" value="Y" <?= $settings['auto_enable'] === 'Y' ? 'checked' : '' ?>></td>
    </tr>
    <tr>
        <td><?= Loc::getMessage('VSK_SETTINGS_THEME') ?>:</td>
        <td>
            <select name="theme">
                <option value="light" <?= $settings['theme'] === 'light' ? 'selected' : '' ?>>Light</option>
                <option value="dark" <?= $settings['theme'] === 'dark' ? 'selected' : '' ?>>Dark</option>
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
        <td><input type="checkbox" name="animation" value="Y" <?= $settings['animation'] === 'Y' ? 'checked' : '' ?>></td>
    </tr>
    <? $tabControl->Buttons([
        'disabled' => $rights<'W',
        'back_url' => (empty($back_url) ? 'settings.php?lang=' . LANG : $back_url)
    ]); ?>
    <?= bitrix_sessid_post() ?>
    <? $tabControl->End(); ?>
</form>
