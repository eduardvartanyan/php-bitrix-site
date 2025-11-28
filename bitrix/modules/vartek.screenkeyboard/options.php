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
        Config::set('mode', $_POST['mode']);
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
        <td width="40%"><?= Loc::getMessage('VSK_SETTINGS_MODE') ?>:</td>
        <td width="60%">
            <select name="mode">
                <option value="kiosk" <?= $settings['mode'] === 'only_kiosk' ? 'selected' : '' ?>><?= Loc::getMessage('VSK_MODE_KIOSK') ?></option>
                <option value="always" <?= $settings['mode'] === 'always' ? 'selected' : '' ?>><?= Loc::getMessage('VSK_MODE_ON') ?></option>
                <option value="off" <?= $settings['mode'] === 'off' ? 'selected' : '' ?>><?= Loc::getMessage('VSK_MODE_OFF') ?></option>
            </select>
        </td>
    </tr>
    <? $tabControl->Buttons([
        'disabled' => $rights<'W',
        'back_url' => (empty($back_url) ? 'settings.php?lang=' . LANG : $back_url)
    ]); ?>
    <?= bitrix_sessid_post() ?>
    <? $tabControl->End(); ?>
</form>
