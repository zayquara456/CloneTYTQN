<?php
if (!defined('CMS_SYSTEM')) die();

$content = "<table cellspacing=\"1\" width=\"100%\">";
$content .= '<tr><td><input id="him_auto" onclick="setMethod(0);" type="radio" name="viet_method">'._AUTO."</td></tr>";
$content .= '<tr><td><input id="him_telex" onclick="setMethod(1);" type="radio" name="viet_method">'._TELEX."</td></tr>";
$content .= '<tr><td><input id="him_vni" onclick="setMethod(2);" type="radio" name="viet_method">'._VNI."</td></tr>";
$content .= '<tr><td><input id="him_viqr" onclick="setMethod(3);" type="radio" name="viet_method">'._VIQR."</td></tr>";
$content .= '<tr><td><input id="him_viqr2" onclick="setMethod(4);" type="radio" name="viet_method">'._VIQR2."</td></tr>";
$content .= '<tr><td><input id="him_off" onclick="setMethod(-1);" type="radio" name="viet_method">'._OFF."</td></tr>";
$content .= '<tr><td><hr></td></tr>'."";
$content .= '<tr><td><input id="him_ckspell" onclick="setSpell(this);" type="checkbox" name="viet_method">'._CKSPELL."</td></tr>";
$content .= '<tr><td><input id="him_daucu" onclick="setDauCu(this);" type="checkbox" name="viet_method">'._OLDACCENT."</td></tr>";
$content .= "</table>";
?>