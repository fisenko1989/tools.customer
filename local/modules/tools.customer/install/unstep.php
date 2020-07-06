<?php

use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid()) {
    return;
}

if ($ex = $APPLICATION->GetException()) {
    echo CAdminMessage::ShowMessage(
        [
            "TYPE" => "ERROR",
            "MESSAGE" => Loc::getMessage("MODULE_UNINST_ERROR"),
            "DETAILS" => $ex->GetString(),
            "HTML" => true,
        ]
    );
} else {
    echo \CAdminMessage::ShowNote(Loc::getMessage("MODULE_UNINST_OK"));
}

?>
<form action="<?= $APPLICATION->GetCurPage(); ?>">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>">
    <input type="submit" name="" value="<?= Loc::getMessage("MOD_BACK"); ?>">
</form>