<?php
/** Adminer - Compact database management
 *
 * @link https://www.adminer.org/
 * @author Jakub Vrana, https://www.vrana.cz/
 * @copyright 2007 Jakub Vrana
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 * @version 4.8.1
 */
function adminer_errors($Cc, $Ec) {
	return !!preg_match('~^(Trying to access array offset on value of type null|Undefined array key)~', $Ec);
}

error_reporting(6135);
set_error_handler('adminer_errors', E_WARNING);
$ad=!preg_match('~^(unsafe_raw)?$~', ini_get("filter.default"));
if ($ad||ini_get("filter.default_flags")) {
	foreach (['_GET', '_POST', '_COOKIE', '_SERVER'] as $X) {
		$Ii=filter_input_array(constant("INPUT$X"), FILTER_UNSAFE_RAW);
		if ($Ii)
			$$X=$Ii;
	}
}
if (function_exists("mb_internal_encoding"))
	mb_internal_encoding("8bit");
function connection() {
	global $g;

	return $g;
}

function adminer() {
	global $b;

	return $b;
}

function version() {
	global $ia;

	return $ia;
}

function idf_unescape($v) {
	if (!preg_match('~^[`\'"]~', $v))
		return $v;
	$qe=substr($v, -1);

	return str_replace($qe.$qe, $qe, substr($v, 1, -1));
}

function escape_string($X) {
	return substr(q($X), 1, -1);
}

function number($X) {
	return preg_replace('~[^0-9]+~', '', $X);
}

function number_type() {
	return '((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';
}

function remove_slashes($tg, $ad=false) {
	if (function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()) {
		while (list($z, $X)=each($tg)) {
			foreach ($X as $he=>$W) {
				unset($tg[$z][$he]);
				if (is_array($W)) {
					$tg[$z][stripslashes($he)]=$W;
					$tg[]=&$tg[$z][stripslashes($he)];
				} else$tg[$z][stripslashes($he)]=($ad?$W:stripslashes($W));
			}
		}
	}
}

function bracket_escape($v, $Na=false) {
	static $ui=[':'=>':1', ']'=>':2', '['=>':3', '"'=>':4'];

	return strtr($v, ($Na?array_flip($ui):$ui));
}

function min_version($Zi, $De="", $h=null) {
	global $g;
	if (!$h)
		$h=$g;
	$nh=$h->server_info;
	if ($De&&preg_match('~([\d.]+)-MariaDB~', $nh, $C)) {
		$nh=$C[1];
		$Zi=$De;
	}

	return (version_compare($nh, $Zi)>=0);
}

function charset($g) {
	return (min_version("5.5.3", 0, $g)?"utf8mb4":"utf8");
}

function script($yh, $ti="\n") {
	return "<script".nonce().">$yh</script>$ti";
}

function script_src($Ni) {
	return "<script src='".h($Ni)."'".nonce()."></script>\n";
}

function nonce() {
	return ' nonce="'.get_nonce().'"';
}

function target_blank() {
	return ' target="_blank" rel="noreferrer noopener"';
}

function h($P) {
	return str_replace("\0", "&#0;", htmlspecialchars($P, ENT_QUOTES, 'utf-8'));
}

function nl_br($P) {
	return str_replace("\n", "<br>", $P);
}

function checkbox($D, $Y, $db, $me="", $uf="", $hb="", $ne="") {
	$I="<input type='checkbox' name='$D' value='".h($Y)."'".($db?" checked":"").($ne?" aria-labelledby='$ne'":"").">".($uf?script("qsl('input').onclick = function () { $uf };", ""):"");

	return ($me!=""||$hb?"<label".($hb?" class='$hb'":"").">$I".h($me)."</label>":$I);
}

function optionlist($_f, $gh=null, $Ri=false) {
	$I="";
	foreach ($_f as $he=>$W) {
		$Af=[$he=>$W];
		if (is_array($W)) {
			$I.='<optgroup label="'.h($he).'">';
			$Af=$W;
		}
		foreach ($Af as $z=>$X)
			$I.='<option'.($Ri||is_string($z)?' value="'.h($z).'"':'').(($Ri||is_string($z)?(string)$z:$X)===$gh?' selected':'').'>'.h($X);
		if (is_array($W))
			$I.='</optgroup>';
	}

	return $I;
}

function html_select($D, $_f, $Y="", $tf=true, $ne="") {
	if ($tf)
		return "<select name='".h($D)."'".($ne?" aria-labelledby='$ne'":"").">".optionlist($_f, $Y)."</select>".(is_string($tf)?script("qsl('select').onchange = function () { $tf };", ""):"");
	$I="";
	foreach ($_f as $z=>$X)
		$I.="<label><input type='radio' name='".h($D)."' value='".h($z)."'".($z==$Y?" checked":"").">".h($X)."</label>";

	return $I;
}

function select_input($Ia, $_f, $Y="", $tf="", $fg="") {
	$Yh=($_f?"select":"input");

	return "<$Yh$Ia".($_f?"><option value=''>$fg".optionlist($_f, $Y, true)."</select>":" size='10' value='".h($Y)."' placeholder='$fg'>").($tf?script("qsl('$Yh').onchange = $tf;", ""):"");
}

function confirm($Ne="", $hh="qsl('input')") {
	return script("$hh.onclick = function () { return confirm('".($Ne?js_escape($Ne):lang(0))."'); };", "");
}

function print_fieldset($u, $ve, $cj=false) {
	echo "<fieldset><legend>", "<a href='#fieldset-$u'>$ve</a>", script("qsl('a').onclick = partial(toggle, 'fieldset-$u');", ""), "</legend>", "<div id='fieldset-$u'".($cj?"":" class='hidden'").">\n";
}

function bold($Ua, $hb="") {
	return ($Ua?" class='active $hb'":($hb?" class='$hb'":""));
}

function odd($I=' class="odd"') {
	static $t=0;
	if (!$I)
		$t=-1;

	return ($t++%2?$I:'');
}

function js_escape($P) {
	return addcslashes($P, "\r\n'\\/");
}

function json_row($z, $X=null) {
	static $bd=true;
	if ($bd)
		echo "{";
	if ($z!="") {
		echo ($bd?"":",")."\n\t\"".addcslashes($z, "\r\n\t\"\\/").'": '.($X!==null?'"'.addcslashes($X, "\r\n\"\\/").'"':'null');
		$bd=false;
	} else {
		echo "\n}\n";
		$bd=true;
	}
}

function ini_bool($Ud) {
	$X=ini_get($Ud);

	return (preg_match('~^(on|true|yes)$~i', $X)||(int)$X);
}

function sid() {
	static $I;
	if ($I===null)
		$I=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));

	return $I;
}

function set_password($Yi, $M, $V, $F) {
	$_SESSION["pwds"][$Yi][$M][$V]=($_COOKIE["adminer_key"]&&is_string($F)?[encrypt_string($F, $_COOKIE["adminer_key"])]:$F);
}

function get_password() {
	$I=get_session("pwds");
	if (is_array($I))
		$I=($_COOKIE["adminer_key"]?decrypt_string($I[0], $_COOKIE["adminer_key"]):false);

	return $I;
}

function q($P) {
	global $g;

	return $g->quote($P);
}

function get_vals($G, $d=0) {
	global $g;
	$I=[];
	$H=$g->query($G);
	if (is_object($H)) {
		while ($J=$H->fetch_row())
			$I[]=$J[$d];
	}

	return $I;
}

function get_key_vals($G, $h=null, $qh=true) {
	global $g;
	if (!is_object($h))
		$h=$g;
	$I=[];
	$H=$h->query($G);
	if (is_object($H)) {
		while ($J=$H->fetch_row()) {
			if ($qh)
				$I[$J[0]]=$J[1]; else$I[]=$J[0];
		}
	}

	return $I;
}

function get_rows($G, $h=null, $n="<p class='error'>") {
	global $g;
	$yb=(is_object($h)?$h:$g);
	$I=[];
	$H=$yb->query($G);
	if (is_object($H)) {
		while ($J=$H->fetch_assoc())
			$I[]=$J;
	} elseif (!$H&&!is_object($h)&&$n&&defined("PAGE_HEADER"))
		echo $n.error()."\n";

	return $I;
}

function unique_array($J, $x) {
	foreach ($x as $w) {
		if (preg_match("~PRIMARY|UNIQUE~", $w["type"])) {
			$I=[];
			foreach ($w["columns"] as $z) {
				if (!isset($J[$z]))
					continue
					2;
				$I[$z]=$J[$z];
			}

			return $I;
		}
	}
}

function escape_key($z) {
	if (preg_match('(^([\w(]+)('.str_replace("_", ".*", preg_quote(idf_escape("_"))).')([ \w)]+)$)', $z, $C))
		return $C[1].idf_escape(idf_unescape($C[2])).$C[3];

	return idf_escape($z);
}

function where($Z, $p=[]) {
	global $g, $y;
	$I=[];
	foreach ((array)$Z["where"] as $z=>$X) {
		$z=bracket_escape($z, 1);
		$d=escape_key($z);
		$I[]=$d.($y=="sql"&&is_numeric($X)&&preg_match('~\.~', $X)?" LIKE ".q($X):($y=="mssql"?" LIKE ".q(preg_replace('~[_%[]~', '[\0]', $X)):" = ".unconvert_field($p[$z], q($X))));
		if ($y=="sql"&&preg_match('~char|text~', $p[$z]["type"])&&preg_match("~[^ -@]~", $X))
			$I[]="$d = ".q($X)." COLLATE ".charset($g)."_bin";
	}
	foreach ((array)$Z["null"] as $z)
		$I[]=escape_key($z)." IS NULL";

	return implode(" AND ", $I);
}

function where_check($X, $p=[]) {
	parse_str($X, $bb);
	remove_slashes([&$bb]);

	return where($bb, $p);
}

function where_link($t, $d, $Y, $wf="=") {
	return "&where%5B$t%5D%5Bcol%5D=".urlencode($d)."&where%5B$t%5D%5Bop%5D=".urlencode(($Y!==null?$wf:"IS NULL"))."&where%5B$t%5D%5Bval%5D=".urlencode($Y);
}

function convert_fields($e, $p, $L=[]) {
	$I="";
	foreach ($e as $z=>$X) {
		if ($L&&!in_array(idf_escape($z), $L))
			continue;
		$Ga=convert_field($p[$z]);
		if ($Ga)
			$I.=", $Ga AS ".idf_escape($z);
	}

	return $I;
}

function cookie($D, $Y, $ye=2592000) {
	global $ba;

	return header("Set-Cookie: $D=".urlencode($Y).($ye?"; expires=".gmdate("D, d M Y H:i:s", time()+$ye)." GMT":"")."; path=".preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]).($ba?"; secure":"")."; HttpOnly; SameSite=lax", false);
}

function restart_session() {
	if (!ini_bool("session.use_cookies"))
		session_start();
}

function stop_session($gd=false) {
	$Qi=ini_bool("session.use_cookies");
	if (!$Qi||$gd) {
		session_write_close();
		if ($Qi&&@ini_set("session.use_cookies", false)===false)
			session_start();
	}
}

function&get_session($z) {
	return $_SESSION[$z][DRIVER][SERVER][$_GET["username"]];
}

function set_session($z, $X) {
	$_SESSION[$z][DRIVER][SERVER][$_GET["username"]]=$X;
}

function auth_url($Yi, $M, $V, $l=null) {
	global $kc;
	preg_match('~([^?]*)\??(.*)~', remove_from_uri(implode("|", array_keys($kc))."|username|".($l!==null?"db|":"").session_name()), $C);

	return "$C[1]?".(sid()?SID."&":"").($Yi!="server"||$M!=""?urlencode($Yi)."=".urlencode($M)."&":"")."username=".urlencode($V).($l!=""?"&db=".urlencode($l):"").($C[2]?"&$C[2]":"");
}

function is_ajax() {
	return ($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");
}

function redirect($B, $Ne=null) {
	if ($Ne!==null) {
		restart_session();
		$_SESSION["messages"][preg_replace('~^[^?]*~', '', ($B!==null?$B:$_SERVER["REQUEST_URI"]))][]=$Ne;
	}
	if ($B!==null) {
		if ($B=="")
			$B=".";
		header("Location: $B");
		exit;
	}
}

function query_redirect($G, $B, $Ne, $Dg=true, $Jc=true, $Tc=false, $gi="") {
	global $g, $n, $b;
	if ($Jc) {
		$Fh=microtime(true);
		$Tc=!$g->query($G);
		$gi=format_time($Fh);
	}
	$Ah="";
	if ($G)
		$Ah=$b->messageQuery($G, $gi, $Tc);
	if ($Tc) {
		$n=error().$Ah.script("messagesPrint();");

		return false;
	}
	if ($Dg)
		redirect($B, $Ne.$Ah);

	return true;
}

function queries($G) {
	global $g;
	static $yg=[];
	static $Fh;
	if (!$Fh)
		$Fh=microtime(true);
	if ($G===null)
		return [implode("\n", $yg), format_time($Fh)];
	$yg[]=(preg_match('~;$~', $G)?"DELIMITER ;;\n$G;\nDELIMITER ":$G).";";

	return $g->query($G);
}

function apply_queries($G, $S, $Fc='table') {
	foreach ($S as $Q) {
		if (!queries("$G ".$Fc($Q)))
			return false;
	}

	return true;
}

function queries_redirect($B, $Ne, $Dg) {
	[$yg, $gi]=queries(null);

	return query_redirect($yg, $B, $Ne, $Dg, false, !$Dg, $gi);
}

function format_time($Fh) {
	return lang(1, max(0, microtime(true)-$Fh));
}

function relative_uri() {
	return str_replace(":", "%3a", preg_replace('~^[^?]*/([^?]*)~', '\1', $_SERVER["REQUEST_URI"]));
}

function remove_from_uri($Qf="") {
	return substr(preg_replace("~(?<=[?&])($Qf".(SID?"":"|".session_name()).")=[^&]*&~", '', relative_uri()."&"), 0, -1);
}

function pagination($E, $Pb) {
	return " ".($E==$Pb?$E+1:'<a href="'.h(remove_from_uri("page").($E?"&page=$E".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($E+1)."</a>");
}

function get_file($z, $Xb=false) {
	$Zc=$_FILES[$z];
	if (!$Zc)
		return null;
	foreach ($Zc as $z=>$X)
		$Zc[$z]=(array)$X;
	$I='';
	foreach ($Zc["error"] as $z=>$n) {
		if ($n)
			return $n;
		$D=$Zc["name"][$z];
		$oi=$Zc["tmp_name"][$z];
		$Db=file_get_contents($Xb&&preg_match('~\.gz$~', $D)?"compress.zlib://$oi":$oi);
		if ($Xb) {
			$Fh=substr($Db, 0, 3);
			if (function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~", $Fh, $Jg))
				$Db=iconv("utf-16", "utf-8", $Db); elseif ($Fh=="\xEF\xBB\xBF")
				$Db=substr($Db, 3);
			$I.=$Db."\n\n";
		} else$I.=$Db;
	}

	return $I;
}

function upload_error($n) {
	$Ke=($n==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);

	return ($n?lang(2).($Ke?" ".lang(3, $Ke):""):lang(4));
}

function repeat_pattern($cg, $we) {
	return str_repeat("$cg{0,65535}", $we/65535)."$cg{0,".($we%65535)."}";
}

function is_utf8($X) {
	return (preg_match('~~u', $X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~', $X));
}

function shorten_utf8($P, $we=80, $Mh="") {
	if (!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]", $we).")($)?)u", $P, $C))
		preg_match("(^(".repeat_pattern("[\t\r\n -~]", $we).")($)?)", $P, $C);

	return h($C[1]).$Mh.(isset($C[2])?"":"<i>…</i>");
}

function format_number($X) {
	return strtr(number_format($X, 0, ".", lang(5)), preg_split('~~u', lang(6), -1, PREG_SPLIT_NO_EMPTY));
}

function friendly_url($X) {
	return preg_replace('~[^a-z0-9_]~i', '-', $X);
}

function hidden_fields($tg, $Jd=[], $lg='') {
	$I=false;
	foreach ($tg as $z=>$X) {
		if (!in_array($z, $Jd)) {
			if (is_array($X))
				hidden_fields($X, [], $z); else {
				$I=true;
				echo '<input type="hidden" name="'.h($lg?$lg."[$z]":$z).'" value="'.h($X).'">';
			}
		}
	}

	return $I;
}

function hidden_fields_get() {
	echo(sid()?'<input type="hidden" name="'.session_name().'" value="'.h(session_id()).'">':''), (SERVER!==null?'<input type="hidden" name="'.DRIVER.'" value="'.h(SERVER).'">':""), '<input type="hidden" name="username" value="'.h($_GET["username"]).'">';
}

function table_status1($Q, $Uc=false) {
	$I=table_status($Q, $Uc);

	return ($I?$I:["Name"=>$Q]);
}

function column_foreign_keys($Q) {
	global $b;
	$I=[];
	foreach ($b->foreignKeys($Q) as $r) {
		foreach ($r["source"] as $X)
			$I[$X][]=$r;
	}

	return $I;
}

function enum_input($T, $Ia, $o, $Y, $zc=null) {
	global $b;
	preg_match_all("~'((?:[^']|'')*)'~", $o["length"], $Fe);
	$I=($zc!==null?"<label><input type='$T'$Ia value='$zc'".((is_array($Y)?in_array($zc, $Y):$Y===0)?" checked":"")."><i>".lang(7)."</i></label>":"");
	foreach ($Fe[1] as $t=>$X) {
		$X=stripcslashes(str_replace("''", "'", $X));
		$db=(is_int($Y)?$Y==$t+1:(is_array($Y)?in_array($t+1, $Y):$Y===$X));
		$I.=" <label><input type='$T'$Ia value='".($t+1)."'".($db?' checked':'').'>'.h($b->editVal($X, $o)).'</label>';
	}

	return $I;
}

function input($o, $Y, $s) {
	global $U, $b, $y;
	$D=h(bracket_escape($o["field"]));
	echo "<td class='function'>";
	if (is_array($Y)&&!$s) {
		$Ea=[$Y];
		if (version_compare(PHP_VERSION, 5.4)>=0)
			$Ea[]=JSON_PRETTY_PRINT;
		$Y=call_user_func_array('json_encode', $Ea);
		$s="json";
	}
	$Ng=($y=="mssql"&&$o["auto_increment"]);
	if ($Ng&&!$_POST["save"])
		$s=null;
	$pd=(isset($_GET["select"])||$Ng?["orig"=>lang(8)]:[])+$b->editFunctions($o);
	$Ia=" name='fields[$D]'";
	if ($o["type"]=="enum")
		echo h($pd[""])."<td>".$b->editInput($_GET["edit"], $o, $Ia, $Y); else {
		$zd=(in_array($s, $pd)||isset($pd[$s]));
		echo (count($pd)>1?"<select name='function[$D]'>".optionlist($pd, $s===null||$zd?$s:"")."</select>".on_help("getTarget(event).value.replace(/^SQL\$/, '')", 1).script("qsl('select').onchange = functionChange;", ""):h(reset($pd))).'<td>';
		$Wd=$b->editInput($_GET["edit"], $o, $Ia, $Y);
		if ($Wd!="")
			echo $Wd; elseif (preg_match('~bool~', $o["type"]))
			echo "<input type='hidden'$Ia value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i', $Y)?" checked='checked'":"")."$Ia value='1'>";
		elseif ($o["type"]=="set") {
			preg_match_all("~'((?:[^']|'')*)'~", $o["length"], $Fe);
			foreach ($Fe[1] as $t=>$X) {
				$X=stripcslashes(str_replace("''", "'", $X));
				$db=(is_int($Y)?($Y>>$t)&1:in_array($X, explode(",", $Y), true));
				echo " <label><input type='checkbox' name='fields[$D][$t]' value='".(1<<$t)."'".($db?' checked':'').">".h($b->editVal($X, $o)).'</label>';
			}
		} elseif (preg_match('~blob|bytea|raw|file~', $o["type"])&&ini_bool("file_uploads"))
			echo "<input type='file' name='fields-$D'>";
		elseif (($ei=preg_match('~text|lob|memo~i', $o["type"]))||preg_match("~\n~", $Y)) {
			if ($ei&&$y!="sqlite")
				$Ia.=" cols='50' rows='12'"; else {
				$K=min(12, substr_count($Y, "\n")+1);
				$Ia.=" cols='30' rows='$K'".($K==1?" style='height: 1.2em;'":"");
			}
			echo "<textarea$Ia>".h($Y).'</textarea>';
		} elseif ($s=="json"||preg_match('~^jsonb?$~', $o["type"]))
			echo "<textarea$Ia cols='50' rows='12' class='jush-js'>".h($Y).'</textarea>';
		else {
			$Me=(!preg_match('~int~', $o["type"])&&preg_match('~^(\d+)(,(\d+))?$~', $o["length"], $C)?((preg_match("~binary~", $o["type"])?2:1)*$C[1]+($C[3]?1:0)+($C[2]&&!$o["unsigned"]?1:0)):($U[$o["type"]]?$U[$o["type"]]+($o["unsigned"]?0:1):0));
			if ($y=='sql'&&min_version(5.6)&&preg_match('~time~', $o["type"]))
				$Me+=7;
			echo "<input".((!$zd||$s==="")&&preg_match('~(?<!o)int(?!er)~', $o["type"])&&!preg_match('~\[\]~', $o["full_type"])?" type='number'":"")." value='".h($Y)."'".($Me?" data-maxlength='$Me'":"").(preg_match('~char|binary~', $o["type"])&&$Me>20?" size='40'":"")."$Ia>";
		}
		echo $b->editHint($_GET["edit"], $o, $Y);
		$bd=0;
		foreach ($pd as $z=>$X) {
			if ($z===""||!$X)
				break;
			$bd++;
		}
		if ($bd)
			echo script("mixin(qsl('td'), {onchange: partial(skipOriginal, $bd), oninput: function () { this.onchange(); }});");
	}
}

function process_input($o) {
	global $b, $m;
	$v=bracket_escape($o["field"]);
	$s=$_POST["function"][$v];
	$Y=$_POST["fields"][$v];
	if ($o["type"]=="enum") {
		if ($Y==-1)
			return false;
		if ($Y=="")
			return "NULL";

		return +$Y;
	}
	if ($o["auto_increment"]&&$Y=="")
		return null;
	if ($s=="orig")
		return (preg_match('~^CURRENT_TIMESTAMP~i', $o["on_update"])?idf_escape($o["field"]):false);
	if ($s=="NULL")
		return "NULL";
	if ($o["type"]=="set")
		return array_sum((array)$Y);
	if ($s=="json") {
		$s="";
		$Y=json_decode($Y, true);
		if (!is_array($Y))
			return false;

		return $Y;
	}
	if (preg_match('~blob|bytea|raw|file~', $o["type"])&&ini_bool("file_uploads")) {
		$Zc=get_file("fields-$v");
		if (!is_string($Zc))
			return false;

		return $m->quoteBinary($Zc);
	}

	return $b->processInput($o, $Y, $s);
}

function fields_from_edit() {
	global $m;
	$I=[];
	foreach ((array)$_POST["field_keys"] as $z=>$X) {
		if ($X!="") {
			$X=bracket_escape($X);
			$_POST["function"][$X]=$_POST["field_funs"][$z];
			$_POST["fields"][$X]=$_POST["field_vals"][$z];
		}
	}
	foreach ((array)$_POST["fields"] as $z=>$X) {
		$D=bracket_escape($z, 1);
		$I[$D]=["field"=>$D, "privileges"=>["insert"=>1, "update"=>1], "null"=>1, "auto_increment"=>($z==$m->primary),];
	}

	return $I;
}

function search_tables() {
	global $b, $g;
	$_GET["where"][0]["val"]=$_POST["query"];
	$jh="<ul>\n";
	foreach (table_status('', true) as $Q=>$R) {
		$D=$b->tableName($R);
		if (isset($R["Engine"])&&$D!=""&&(!$_POST["tables"]||in_array($Q, $_POST["tables"]))) {
			$H=$g->query("SELECT".limit("1 FROM ".table($Q), " WHERE ".implode(" AND ", $b->selectSearchProcess(fields($Q), [])), 1));
			if (!$H||$H->fetch_row()) {
				$pg="<a href='".h(ME."select=".urlencode($Q)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$D</a>";
				echo "$jh<li>".($H?$pg:"<p class='error'>$pg: ".error())."\n";
				$jh="";
			}
		}
	}
	echo ($jh?"<p class='message'>".lang(9):"</ul>")."\n";
}

function dump_headers($Hd, $Ve=false) {
	global $b;
	$I=$b->dumpHeaders($Hd, $Ve);
	$Mf=$_POST["output"];
	if ($Mf!="text")
		header("Content-Disposition: attachment; filename=".$b->dumpFilename($Hd).".$I".($Mf!="file"&&preg_match('~^[0-9a-z]+$~', $Mf)?".$Mf":""));
	session_write_close();
	ob_flush();
	flush();

	return $I;
}

function dump_csv($J) {
	foreach ($J as $z=>$X) {
		if (preg_match('~["\n,;\t]|^0|\.\d*0$~', $X)||$X==="")
			$J[$z]='"'.str_replace('"', '""', $X).'"';
	}
	echo implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")), $J)."\r\n";
}

function apply_sql_function($s, $d) {
	return ($s?($s=="unixepoch"?"DATETIME($d, '$s')":($s=="count distinct"?"COUNT(DISTINCT ":strtoupper("$s("))."$d)"):$d);
}

function get_temp_dir() {
	$I=ini_get("upload_tmp_dir");
	if (!$I) {
		if (function_exists('sys_get_temp_dir'))
			$I=sys_get_temp_dir(); else {
			$q=@tempnam("", "");
			if (!$q)
				return false;
			$I=dirname($q);
			unlink($q);
		}
	}

	return $I;
}

function file_open_lock($q) {
	$nd=@fopen($q, "r+");
	if (!$nd) {
		$nd=@fopen($q, "w");
		if (!$nd)
			return;
		chmod($q, 0660);
	}
	flock($nd, LOCK_EX);

	return $nd;
}

function file_write_unlock($nd, $Rb) {
	rewind($nd);
	fwrite($nd, $Rb);
	ftruncate($nd, strlen($Rb));
	flock($nd, LOCK_UN);
	fclose($nd);
}

function password_file($i) {
	$q=get_temp_dir()."/adminer.key";
	$I=@file_get_contents($q);
	if ($I||!$i)
		return $I;
	$nd=@fopen($q, "w");
	if ($nd) {
		chmod($q, 0660);
		$I=rand_string();
		fwrite($nd, $I);
		fclose($nd);
	}

	return $I;
}

function rand_string() {
	return md5(uniqid(mt_rand(), true));
}

function select_value($X, $A, $o, $fi) {
	global $b;
	if (is_array($X)) {
		$I="";
		foreach ($X as $he=>$W)
			$I.="<tr>".($X!=array_values($X)?"<th>".h($he):"")."<td>".select_value($W, $A, $o, $fi);

		return "<table cellspacing='0'>$I</table>";
	}
	if (!$A)
		$A=$b->selectLink($X, $o);
	if ($A===null) {
		if (is_mail($X))
			$A="mailto:$X";
		if (is_url($X))
			$A=$X;
	}
	$I=$b->editVal($X, $o);
	if ($I!==null) {
		if (!is_utf8($I))
			$I="\0"; elseif ($fi!=""&&is_shortable($o))
			$I=shorten_utf8($I, max(0, +$fi));
		else$I=h($I);
	}

	return $b->selectVal($I, $A, $o, $X);
}

function is_mail($wc) {
	$Ha='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
	$jc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
	$cg="$Ha+(\\.$Ha+)*@($jc?\\.)+$jc";

	return is_string($wc)&&preg_match("(^$cg(,\\s*$cg)*\$)i", $wc);
}

function is_url($P) {
	$jc='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';

	return preg_match("~^(https?)://($jc?\\.)+$jc(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i", $P);
}

function is_shortable($o) {
	return preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~', $o["type"]);
}

function count_rows($Q, $Z, $ce, $sd) {
	global $y;
	$G=" FROM ".table($Q).($Z?" WHERE ".implode(" AND ", $Z):"");

	return ($ce&&($y=="sql"||count($sd)==1)?"SELECT COUNT(DISTINCT ".implode(", ", $sd).")$G":"SELECT COUNT(*)".($ce?" FROM (SELECT 1$G GROUP BY ".implode(", ", $sd).") x":$G));
}

function slow_query($G) {
	global $b, $qi, $m;
	$l=$b->database();
	$hi=$b->queryTimeout();
	$vh=$m->slowQuery($G, $hi);
	if (!$vh&&support("kill")&&is_object($h=connect())&&($l==""||$h->select_db($l))) {
		$ke=$h->result(connection_id());
		echo '<script', nonce(), '>
var timeout = setTimeout(function () {
	ajax(\'', js_escape(ME), 'script=kill\', function () {
	}, \'kill=', $ke, '&token=', $qi, '\');
}, ', 1000*$hi, ');
</script>
';
	} else$h=null;
	ob_flush();
	flush();
	$I=@get_key_vals(($vh?$vh:$G), $h, false);
	if ($h) {
		echo script("clearTimeout(timeout);");
		ob_flush();
		flush();
	}

	return $I;
}

function get_token() {
	$Ag=rand(1, 1e6);

	return ($Ag^$_SESSION["token"]).":$Ag";
}

function verify_token() {
	[$qi, $Ag]=explode(":", $_POST["token"]);

	return ($Ag^$_SESSION["token"])==$qi;
}

function lzw_decompress($Ra) {
	$gc=256;
	$Sa=8;
	$jb=[];
	$Pg=0;
	$Qg=0;
	for ($t=0; $t<strlen($Ra); $t++) {
		$Pg=($Pg<<8)+ord($Ra[$t]);
		$Qg+=8;
		if ($Qg>=$Sa) {
			$Qg-=$Sa;
			$jb[]=$Pg>>$Qg;
			$Pg&=(1<<$Qg)-1;
			$gc++;
			if ($gc>>$Sa)
				$Sa++;
		}
	}
	$fc=range("\0", "\xFF");
	$I="";
	foreach ($jb as $t=>$ib) {
		$vc=$fc[$ib];
		if (!isset($vc))
			$vc=$nj.$nj[0];
		$I.=$vc;
		if ($t)
			$fc[]=$nj.$vc[0];
		$nj=$vc;
	}

	return $I;
}

function on_help($rb, $sh=0) {
	return script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $rb, $sh) }, onmouseout: helpMouseout});", "");
}

function edit_form($Q, $p, $J, $Li) {
	global $b, $y, $qi, $n;
	$Rh=$b->tableName(table_status1($Q, true));
	page_header(($Li?lang(10):lang(11)), $n, ["select"=>[$Q, $Rh]], $Rh);
	$b->editRowPrint($Q, $p, $J, $Li);
	if ($J===false)
		echo "<p class='error'>".lang(12)."\n";
	echo '<form action="" method="post" enctype="multipart/form-data" id="form">
';
	if (!$p)
		echo "<p class='error'>".lang(13)."\n"; else {
		echo "<table cellspacing='0' class='layout'>".script("qsl('table').onkeydown = editingKeydown;");
		foreach ($p as $D=>$o) {
			echo "<tr><th>".$b->fieldName($o);
			$Yb=$_GET["set"][bracket_escape($D)];
			if ($Yb===null) {
				$Yb=$o["default"];
				if ($o["type"]=="bit"&&preg_match("~^b'([01]*)'\$~", $Yb, $Jg))
					$Yb=$Jg[1];
			}
			$Y=($J!==null?($J[$D]!=""&&$y=="sql"&&preg_match("~enum|set~", $o["type"])?(is_array($J[$D])?array_sum($J[$D]):+$J[$D]):(is_bool($J[$D])?+$J[$D]:$J[$D])):(!$Li&&$o["auto_increment"]?"":(isset($_GET["select"])?false:$Yb)));
			if (!$_POST["save"]&&is_string($Y))
				$Y=$b->editVal($Y, $o);
			$s=($_POST["save"]?(string)$_POST["function"][$D]:($Li&&preg_match('~^CURRENT_TIMESTAMP~i', $o["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));
			if (!$_POST&&!$Li&&$Y==$o["default"]&&preg_match('~^[\w.]+\(~', $Y))
				$s="SQL";
			if (preg_match("~time~", $o["type"])&&preg_match('~^CURRENT_TIMESTAMP~i', $Y)) {
				$Y="";
				$s="now";
			}
			input($o, $Y, $s);
			echo "\n";
		}
		if (!support("table"))
			echo "<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]", $b->editFunctions(["null"=>isset($_GET["select"])]))."<td><input name='field_vals[]'>"."\n";
		echo "</table>\n";
	}
	echo "<p>\n";
	if ($p) {
		echo "<input type='submit' value='".lang(14)."'>\n";
		if (!isset($_GET["select"])) {
			echo "<input type='submit' name='insert' value='".($Li?lang(15):lang(16))."' title='Ctrl+Shift+Enter'>\n", ($Li?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".lang(17)."…', this); };"):"");
		}
	}
	echo($Li?"<input type='submit' name='delete' value='".lang(18)."'>".confirm()."\n":($_POST||!$p?"":script("focus(qsa('td', qs('#form'))[1].firstChild);")));
	if (isset($_GET["select"]))
		hidden_fields(["check"=>(array)$_POST["check"], "clone"=>$_POST["clone"], "all"=>$_POST["all"]]);
	echo '<input type="hidden" name="referer" value="', h(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"]), '">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="', $qi, '">
</form>
';
}

if (isset($_GET["file"])) {
	if ($_SERVER["HTTP_IF_MODIFIED_SINCE"]) {
		header("HTTP/1.1 304 Not Modified");
		exit;
	}
	header("Expires: ".gmdate("D, d M Y H:i:s", time()+365*24*60*60)." GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: immutable");
	if ($_GET["file"]=="favicon.ico") {
		header("Content-Type: image/x-icon");
		echo lzw_decompress("\0\0\0` \0�\0\n @\0�C��\"\0`E�Q����?�tvM'�Jd�d\\�b0\0�\"��fӈ��s5����A�XPaJ�0���8�#R�T��z`�#.��c�X��Ȁ?�-\0�Im?�.�M��\0ȯ(̉��/(%�\0");
	} elseif ($_GET["file"]=="default.css") {
		header("Content-Type: text/css; charset=utf-8");
		echo lzw_decompress("\n1̇�ٌ�l7��B1�4vb0��fs���n2B�ѱ٘�n:�#(�b.\rDc)��a7E����l�ñ��i1̎s���-4��f�	��i7�����t4���y�Zf4��i�AT�VV��f:Ϧ,:1�Qݼ�b2`�#�>:7G�1���s��L�XD*bv<܌#�e@�:4�!fo���t:<��咾�o��\ni���',�a_�:�i�Bv�|N�4.5Nf�i�vp�h��l��֚�O����= �OFQ��k\$��i����d2T�p��6�����-�Z�����6����h:�a�,����2�#8А�#��6n����J��h�t�����4O42��ok��*r���@p@�!������?�6��r[��L���:2B�j�!Hb��P�=!1V�\"��0��\nS���D7��Dڛ�C!�!��Gʌ� �+�=tC�.C��:+��=�������%�c�1MR/�EȒ4���2�䱠�`�8(�ӹ[W��=�yS�b�=�-ܹBS+ɯ�����@pL4Yd��q�����6�3Ĭ��Ac܌�Ψ�k�[&>���Z�pkm]�u-c:���Nt�δpҝ��8�=�#��[.��ޯ�~���m�y�PP�|I֛���Q�9v[�Q��\n��r�'g�+��T�2��V��z�4��8��(	�Ey*#j�2]��R����)��[N�R\$�<>:�>\$;�>��\r���H��T�\nw�N �wأ��<��Gw����\\Y�_�Rt^�>�\r}��S\rz�4=�\nL�%J��\",Z�8����i�0u�?�����s3#�ى�:���㽖��E]x���s^8��K^��*0��w����~���:��i���v2w����^7���7�c��u+U%�{P�*4̼�LX./!��1C��qx!H��Fd��L���Ġ�`6��5��f��Ć�=H�l �V1��\0a2�;��6����_ه�\0&�Z�S�d)KE'��n��[X��\0ZɊ�F[P�ޘ@��!��Y�,`�\"ڷ��0Ee9yF>��9b����F5:���\0}Ĵ��(\$����37H��� M�A��6R��{Mq�7G��C�C�m2�(�Ct>[�-t�/&C�]�etG�̬4@r>���<�Sq�/���Q�hm���������L��#��K�|���6fKP�\r%t��V=\"�SH\$�} ��)w�,W\0F��u@�b�9�\rr�2�#�D��X���yOI�>��n��Ǣ%���'��_��t\rτz�\\1�hl�]Q5Mp6k���qh�\$�H~�|��!*4����`S���S t�PP\\g��7�\n-�:袪p����l�B���7Өc�(wO0\\:��w���p4���{T��jO�6HÊ�r���q\n��%%�y']\$��a�Z�.fc�q*-�FW��k��z���j���lg�:�\$\"�N�\r#�d�Â���sc�̠��\"j�\r�����Ւ�Ph�1/��DA)���[�kn�p76�Y��R{�M�P���@\n-�a�6��[�zJH,�dl�B�h�o�����+�#Dr^�^��e��E��� ĜaP���JG�z��t�2�X�����V�����ȳ��B_%K=E��b弾�§kU(.!ܮ8����I.@�K�xn���:�P�32��m�H		C*�:v�T�\nR�����0u�����ҧ]�����P/�JQd�{L�޳:Y��2b��T ��3�4���c�V=���L4��r�!�B�Y�6��MeL������i�o�9< G��ƕЙMhm^�U�N����Tr5HiM�/�n�흳T��[-<__�3/Xr(<���������uҖGNX20�\r\$^��:'9�O��;�k����f��N'a����b�,�V��1��HI!%6@��\$�EGڜ�1�(mU��rս���`��iN+Ü�)���0l��f0��[U��V��-:I^��\$�s�b\re��ug�h�~9�߈�b�����f�+0�� hXrݬ�!\$�e,�w+����3��_�A�k��\nk�r�ʛcuWdY�\\�={.�č���g��p8�t\rRZ�v�J:�>��Y|+�@����C�t\r��jt��6��%�?��ǎ�>�/�����9F`ו��v~K�����R�W��z��lm�wL�9Y�*q�x�z��Se�ݛ����~�D�����x���ɟi7�2���Oݻ��_{��53��t���_��z�3�d)�C��\$?KӪP�%��T&��&\0P�NA�^�~���p� �Ϝ���\r\$�����b*+D6궦ψ��J\$(�ol��h&��KBS>���;z��x�oz>��o�Z�\nʋ[�v���Ȝ��2�OxِV�0f�����2Bl�bk�6Zk�hXcd�0*�KT�H=��π�p0�lV����\r���n�m��)(�(�:#����E��:C�C���\r�G\ré0��i����:`Z1Q\n:��\r\0���q���:`�-�M#}1;����q�#|�S���hl�D�\0fiDp�L��``����0y��1���\r�=�MQ\\��%oq��\0��1�21�1�� ���ќbi:��\r�/Ѣ� `)��0��@���I1�N�C�����O��Z��1���q1 ����,�\rdI�Ǧv�j�1 t�B���⁒0:�0��1�A2V���0���%�fi3!&Q�Rc%�q&w%��\r��V�#���Qw`�% ���m*r��y&i�+r{*��(rg(�#(2�(��)R@i�-�� ���1\"\0��R���.e.r��,�ry(2�C��b�!Bޏ3%ҵ,R�1��&��t��b�a\rL��-3�����\0��Bp�1�94�O'R�3*��=\$�[�^iI;/3i�5�&�}17�# ѹ8��\"�7��8�9*�23�!�!1\\\0�8��rk9�;S�23��ړ*�:q]5S<��#3�83�#e�=�>~9S螳�r�)��T*a�@і�bes���:-���*;,�ؙ3!i���LҲ�#1 �+n� �*��@�3i7�1���_�F�S;3�F�\rA��3�>�x:� \r�0��@�-�/��w��7��S�J3� �.F�\$O�B���%4�+t�'g�Lq\rJt�J��M2\r��7��T@���)ⓣd��2�P>ΰ��Fi಴�\nr\0��b�k(�D���KQ����1�\"2t����P�\r��,\$KCt�5��#��)��P#Pi.�U2�C�~�\"�");
	} elseif ($_GET["file"]=="functions.js") {
		header("Content-Type: text/javascript; charset=utf-8");
		echo lzw_decompress("f:��gCI��\n8��3)��7���81��x:\nOg#)��r7\n\"��`�|2�gSi�H)N�S��\r��\"0��@�)�`(\$s6O!��V/=��' T4�=��iS��6IO�G#�X�VC��s��Z1.�hp8,�[�H�~Cz���2�l�c3���s���I�b�4\n�F8T��I���U*fz��r0�E����y���f�Y.:��I��(�c��΋!�_l��^�^(��N{S��)r�q�Y��l٦3�3�\n�+G���y���i���xV3w�uh�^r����a۔���c��\r���(.��Ch�<\r)�ѣ�`�7���43'm5���\n�P�:2�P����q ���C�}ī�����38�B�0�hR��r(�0��b\\0�Hr44��B�!�p�\$�rZZ�2܉.Ƀ(\\�5�|\nC(�\"��P���.��N�RT�Γ��>�HN��8HP�\\�7Jp~���2%��OC�1�.��C8·H��*�j����S(�/��6KU����<2�pOI���`���ⳈdO�H��5�-��4��pX25-Ң�ۈ�z7��\"(�P�\\32:]U����߅!]�<�A�ۤ���iڰ�l\r�\0v��#J8��wm��ɤ�<�ɠ��%m;p#�`X�D���iZ��N0����9��占��`��wJ�D��2�9t��*��y��NiIh\\9����:����xﭵyl*�Ȉ��Y�����8�W��?���ޛ3���!\"6�n[��\r�*\$�Ƨ�nzx�9\r�|*3ףp�ﻶ�:(p\\;��mz���9����8N���j2����\r�H�H&��(�z��7i�k� ����c��e���t���2:SH�Ƞ�/)�x�@��t�ri9����8����yҷ���V�+^Wڦ��kZ�Y�l�ʣ���4��Ƌ������\\E�{�7\0�p���D��i�-T����0l�%=���˃9(�5�\n\n�n,4�\0�a}܃.��Rs\02B\\�b1�S�\0003,�XPHJsp�d�K� CA!�2*W����2\$�+�f^\n�1����zE� Iv�\\�2��.*A���E(d���b��܄��9����Dh�&��?�H�s�Q�2�x~nÁJ�T2�&��eR���G�Q��Tw�ݑ��P���\\�)6�����sh\\3�\0R	�'\r+*;R�H�.�!�[�'~�%t< �p�K#�!�l���Le����,���&�\$	��`��CX��ӆ0֭����:M�h	�ڜG��!&3�D�<!�23��?h�J�e ��h�\r�m���Ni�������N�Hl7��v��WI�.��-�5֧ey�\rEJ\ni*�\$@�RU0,\$U�E����ªu)@(t�SJk�p!�~���d`�>��\n�;#\rp9�jɹ�]&Nc(r���TQU��S��\08n`��y�b���L�O5��,��>���x���f䴒���+��\"�I�{kM�[\r%�[	�e�a�1! ���Ԯ�F@�b)R��72��0�\nW���L�ܜҮtd�+���0wgl�0n@��ɢ�i�M��\nA�M5n�\$E�ױN��l�����%�1 A������k�r�iFB���ol,muNx-�_�֤C( ��f�l\r1p[9x(i�BҖ��zQl��8C�	��XU Tb��I�`�p+V\0��;�Cb��X�+ϒ�s��]H��[�k�x�G*�]�awn�!�6�����mS�I��K�~/�ӥ7��eeN��S�/;d�A�>}l~��� �%^�f�آpڜDE��a��t\nx=�kЎ�*d���T����j2��j��\n��� ,�e=��M84���a�j@�T�s���nf��\n�6�\rd��0���Y�'%ԓ��~	�Ҩ�<���AH�G��8���΃\$z��{���u2*��a��>�(w�K.bP�{��o��´�z�#�2�8=�8>���A,�e���+�C�x�*���-b=m���,�a��lzk���\$W�,�m�Ji�ʧ���+���0�[��.R�sK���X��ZL��2�`�(�C�vZ������\$�׹,�D?H��NxX��)��M��\$�,��*\nѣ\$<q�şh!��S����xsA!�:�K��}�������R��A2k�X�p\n<�����l���3�����VV�}�g&Yݍ!�+�;<�Y��YE3r�َ��C�o5����ճ�kk�����ۣ��t��U���)�[����}��u��l�:D��+Ϗ _o��h140���0��b�K�㬒�����lG��#��������|Ud�IK���7�^��@��O\0H��Hi�6\r����\\cg\0���2�B�*e��\n��	�zr�!�nWz&� {H��'\$X �w@�8�DGr*���H�'p#�Į���\nd���,���,�;g~�\0�#����E��\r�I`��'��%E�.�]`�Л��%&��m��\r��%4S�v�#\n��fH\$%�-�#���qB�����Q-�c2���&���]�� �qh\r�l]�s���h�7�n#����-�jE�Fr�l&d����z�F6����\"���|���s@����z)0rpڏ\0�X\0���|DL<!��o�*�D�{.B<E���0nB(� �|\r\n�^���� h�!���r\$��(^�~����/p�q��B��O����,\\��#RR��%���d�Hj�`����̭ V� bS�d�i�E���oh�r<i/k\$-�\$o��+�ŋ��l��O�&evƒ�i�jMPA'u'���( M(h/+��WD�So�.n�.�n���(�(\"���h�&p��/�/1D̊�j娸E��&⦀�,'l\$/.,�d���W�bbO3�B�sH�:J`!�.���������,F��7(��Կ��1�l�s �Ҏ���Ţq�X\r����~R鰱`�Ҟ�Y*�:R��rJ��%L�+n�\"��\r��͇H!qb�2�Li�%����Wj#9��ObE.I:�6�7\0�6+�%�.����a7E8VS�?(DG�ӳB�%;���/<�����\r ��>�M��@���H�Ds��Z[tH�Enx(���R�x��@��GkjW�>���#T/8�c8�Q0��_�IIGII�!���YEd�E�^�td�th�`DV!C�8��\r���b�3�!3�@�33N}�ZB�3	�3�30��M(�>��}�\\�t�f�f���I\r���337 X�\"td�,\nbtNO`P�;�ܕҭ���\$\n����Zѭ5U5WU�^ho���t�PM/5K4Ej�KQ&53GX�Xx)�<5D��\r�V�\n�r�5b܀\\J\">��1S\r[-��Du�\r���)00�Y��ˢ�k{\n��#��\r�^��|�uܻU�_n�U4�U�~Yt�\rI��@䏳�R �3:�uePMS�0T�wW�X���D��KOU����;U�\n�OY��Y�Q,M[\0�_�D���W��J*�\rg(]�\r\"ZC��6u�+�Y��Y6ô�0�q�(��8}��3AX3T�h9j�j�f�Mt�PJbqMP5>������Y�k%&\\�1d��E4� �Yn���\$<�U]Ӊ1�mbֶ�^�����\"NV��p��p��eM���W�ܢ�\\�)\n �\nf7\n�2��r8��=Ek7tV����7P��L��a6��v@'�6i��j&>��;��`��a	\0pڨ(�J��)�\\��n��Ĭm\0��2��eqJ��P��t��fj��\"[\0����X,<\\������+md��~�����s%o��mn�),ׄ�ԇ�\r4��8\r����mE�H]�����HW�M0D�߀��~�ˁ�K��E}����|f�^���\r>�-z]2s�xD�d[s�t�S��\0Qf-K`���t���wT�9��Z��	�\nB�9 Nb��<�B�I5o�oJ�p��JNd��\r�hލ��2�\"�x�HC�ݍ�:���9Yn16��zr+z���\\�����m ��T ���@Y2lQ<2O+�%��.Ӄh�0A���Z��2R��1��/�hH\r�X��aNB&� �M@�[x��ʮ���8&L�V͜v�*�j�ۚGH��\\ٮ	���&s�\0Q��\\\"�b��	��\rBs��w��	���BN`�7�Co(���\nè���1�9�*E� �S��U�0U� t�'|�m���?h[�\$.#�5	 �	p��yB�@R�]���@|��{���P\0x�/� w�%�EsBd���CU�~O׷�P�@X�]����Z3��1��{�eLY���ڐ�\\�(*R`�	�\n������QCF�*�����霬�p�X|`N���\$�[���@�U������Z�`Zd\"\\\"����)��I�:�t��oD�\0[�����-���g���*`hu%�,����I�7ī�H�m�6�}��N�ͳ\$�M�UYf&1����e]pz���I��m�G/� �w �!�\\#5�4I�d�E�hq���Ѭk�x|�k�qD�b�z?���>���:��[�L�ƬZ�X��:�������j�w5	�Y��0 ���\$\0C��dSg����{�@�\n`�	���C ���M�����# t}x�N����{�۰)��C��FKZ�j��\0PFY�B�pFk��0<�>�D<JE��g\r�.�2��8�U@*�5fk��JD���4��TDU76�/��@��K+���J�����@�=��WIOD�85M��N�\$R�\0�5�\r��_���E���I�ϳN�l���y\\����qU��Q���\n@���ۺ�p���P۱�7ԽN\r�R{*�qm�\$\0R��ԓ���q�È+U@�B��Of*�Cˬ�MC��`_ ���˵N��T�5٦C׻� ��\\W�e&_X�_؍h���B�3���%�FW���|�Gޛ'�[�ł����V��#^\r��GR����P��Fg�����Yi ���z\n��+�^/�������\\�6��b�dmh��@q���Ah�),J��W��cm�em]�ӏe�kZb0�����Y�]ym��f�e�B;���O��w�apDW�����{�\0��-2/bN�sֽ޾Ra�Ϯh&qt\n\"�i�Rm�hz�e����FS7��PP�䖤��:B����sm��Y d���7}3?*�t����lT�}�~�����=c������	��3�;T�L�5*	�~#�A����s�x-7��f5`�#\"N�b��G����@�e�[�����s����-��M6��qq� h�e5�\0Ң���*�b�IS���Fή9}�p�-��`{��ɖkP�0T<��Z9�0<՚\r��;!��g�\r\nK�\n��\0��*�\nb7(�_�@,�e2\r�]�K�+\0��p C\\Ѣ,0�^�MЧ����@�;X\r��?\$\r�j�+�/��B��P�����J{\"a�6�䉜�|�\n\0��\\5���	156�� .�[�Uد\0d��8Y�:!���=��X.�uC����!S���o�p�B���7��ů�Rh�\\h�E=�y:< :u��2�80�si��TsB�@\$ ��@�u	�Q���.��T0M\\/�d+ƃ\n��=��d���A���)\r@@�h3���8.eZa|.�7�Yk�c���'D#��Y�@X�q�=M��44�B AM��dU\"�Hw4�(>��8���C�?e_`��X:�A9ø���p�G��Gy6��F�Xr��l�1��ػ�B�Å9Rz��hB�{����\0��^��-�0�%D�5F\"\"�����i�`��nAf� \"tDZ\"_�V\$��!/�D�ᚆ������٦�̀F,25�j�T��y\0�N�x\r�Yl��#��Eq\n��B2�\n��6���4���!/�\n��Q��*�;)bR�Z0\0�CDo�˞�48������e�\n�S%\\�PIk��(0��u/��G������\\�}�4Fp��G�_�G?)g�ot��[v��\0��?b�;��`(�ی�NS)\n�x=��+@��7��j�0��,�1Åz����>0��Gc��L�VX�����%����Q+���o�F���ܶ�>Q-�c���l����w��z5G��@(h�c�H��r?��Nb�@�������lx3�U`�rw���U���t�8�=�l#���l�䨉8�E\"����O6\n��1e�`\\hKf�V/зPaYK�O�� ��x�	�Oj���r7�F;��B����̒��>�Ц�V\rĖ�|�'J�z����#�PB��Y5\0NC�^\n~LrR��[̟Rì�g�eZ\0x�^�i<Q�/)�%@ʐ��fB�Hf�{%P�\"\"���@���)���DE(iM2�S�*�y�S�\"���e̒1��ט\n4`ʩ>��Q*��y�n����T�u�����~%�+W��XK���Q�[ʔ��l�PYy#D٬D<�FL���@�6']Ƌ��\rF�`�!�%\n�0�c���˩%c8WrpG�.T�Do�UL2�*�|\$�:�Xt5�XY�I�p#� �^\n��:�#D�@�1\r*�K7�@D\0��C�C�xBh�EnK�,1\"�*y[�#!�י�ٙ���l_�/��x�\0���5�Z��4\0005J�h\"2���%Y���a�a1S�O�4��%ni��P��ߴq�_ʽ6���~��I\\���d���d������D�����3g^��@^6����_�HD�.ksL��@��Ɉ�n�I���~�\r�b�@�Ӏ�N�t\0s���]:u��X�b@^�1\0���2?�T��6dLNe��+�\0�:�Ё�l��z6q=̺x���N6��O,%@s�0\n�\\)�L<�C�|���P��b����A>I���\"	��^K4��gIX�i@P�jE�&/1@�f�	�N�x0coaߧ����,C'�y#6F@�Р��H0�{z3t�|cXMJ.*B�)ZDQ���\0��T-v�X�a*��,*�<b���#xј�d�P��KG8�� y�K	\\#=�)�gȑh�&�8])�C�\nô��9�z�W\\�g�M 7��!��������,��9���\$T\"�,��%.F!˚ A�-�����-�g��\0002R>KE�'�U�_I���9�˼�j(�Q��@�@�4/�7���'J.�RT�\0]KS�D���Ap5�\r�H0!�´e	d@Rҝ�ิ�9�S�;7�H�B�bx�J��_�vi�U`@���SAM��X��G�Xi��U*��������'��:V�WJv�D���N'\$�zh\$d_y���Z]����Y���8ؔ���]�P�*h���֧e;��pe��\$k�w��*7N�DTx_�ԧ�Gi�&P�Ԇ�t͆�b�\\E�H\$i�E\"cr��0l�?>��C(�W@3���22a���I����{�B`�ڳiŸGo^6E\r��G�M�p1i�I��X�\0003�2�K�����zl&ֆ�'IL�\\�\"�7�>�j(>�j�FG_��& 10I�A31=h q\0�F����ķ��_�J���ԳVΖ��܆q�՚��	��(/�dOC�_sm�<g�x\0��\"��\n@EkH\0�J���8�(���km[����S4�\nY40��+L\n������#Bӫb��%R֖��׭��R:�<\$!ۥr�;���	%|ʨ�(�|�H�\0�������]�cҡ=0��Z�\"\"=�X��)�f�N��6V}F��=[���ৢhu�-��\0t��bW~��Q��iJ���L�5׭q#kb���Wn���Q�T�!���e�nc�S�[+ִE�<-��a]Ń��Yb�\n\nJ~�|JɃ8� �Lp����o� �N�ܨ�J.��ŃS��2c9�j�y�-`a\0��*�ֈ@\0+��mg��6�1��Me\0��Q �_�}!I��GL�f)�X�o,�Shx�\0000\"h�+L�M�� �ј��Z	j�\0���/��\$��>u*�Z9��Z�e��+J����tz������R�Kԯ���Dy���q�0C�-f��m����BI�|��HB��sQl�X��.����|�c���[��ZhZ��l���x�@'��ml�KrQ�26��]�ҷn�d[��񎩇d���\"GJ9u��B�o��Zߖ�a��n@��n�lW|*gX�\nn2�F�|x`Dk��uPP�!Q\rr��`W/���	1�[-o,71bUs����N�7����Gq�.\\Q\"CCT\"�����*?u�ts�����]�٩Pz[�[YFϹ��FD3�\"����]�u۝)wz�:#���Iiw��pɛ��{�o�0n��;��\\�x���\0q��m���&�~��7����9[�H�qdL�O�2�v�|B�t��\\Ƥ�Hd���H�\" ��N\n\0��G�g�F��F�}\"�&QEK��{}\ryǎ��rכt������7�Nuó[A�gh;S�.Ҡ���¥|y��[Ն_b�Ȩ�!+R��ZX�@0N����P���%�jD�¯z	���[�U\"�{e�8��>�EL4Jн�0����7 ��d�� �Q^`0`�����]c�<g@��hy8��p.ef\n��eh��aX����mS��jBژQ\"�\r���K3�=>ǪAX�[,,\"'<���%�a��Ӵ��.\$�\0�%\0�sV���p�M\$�@j���>���}Ve�\$@�̈́#���(3:�`�U�Y��u�������@�V#E�G/��XD\$�h��av��xS\"]k18a�я�9dJROӊs�`EJ����Uo�m{l�B8���(\n}ei�b��, �;�N��͇�Q�\\�ǸI5yR�\$!>\\ʉ�g�uj*?n�M�޲h��\r%���U(d��N�d#}�pA:����-\\�A�*�4�2I���\r�֣�� 0h@\\Ե��8�3�rq]���d8\"�Q����ƙ:c��y�4	�ᑚda�Π6>U�A����:��@�2���\$�eh2���F��əN�+���\r�Ԁ(�Ar��d*�\0[�#cj����>!(�S���L�e�T��M	9\0W:�BD���3J���_@s��rue������ +�'B��}\"B\"�z2��r��l�xF[�L�˲Ea9��cdb��^,�UC=/2�����/\$�C�#��8�}D���6�`^;6B0U7�_=	,�1�j1V[�.	H9(1���ҏLz�C�	�\$.A�fh㖫����DrY	�H�e~o�r19��م\\�߄P�)\"�Q��,�e��L��w0�\0������;w�X�ǝ���qo���~�����>9�>}��dc�\0��g��f��q�&9���-�J#����3^4m/���\0\0006��n8��>䈴.ӗ��cph��������_A@[��7�|9\$pMh�>���5�K���E=h��A�t�^�V�	�\"�	c�B;���i��QҠt����@,\n�)���s�`����;�4����I������y��-�0yeʨ�U��B�v��3H�P�G�5��s|��\r���\$0����1��l3��(*oF~PK��.�,'�J/�Ӳ�t���d�:��n�\n��j��Y�z�(����w���Z�#Z�	Io�@1�λ\$��=VWz�	n�B�a���A��q�@��I�p	@�5Ӗ�lH{U��oX��f��ӿ\\z��.���,-\\ڗ^y n^���Bq����zX㉡�\$�*J72�D4.����!�M0��D��F����G��L�m�c*m�cI��5Ɍ�^�t���jl�7替S�Q��.i����h��L�ڱB6Ԅh�&�J��l\\��We�c�f%kj�� �p�R=��i�@.��(�2�klHUW\"�o�j���p!S5��pL'`\0�O *�Q3X��lJ\08\n�\r���*�a��떞��r�`<�&�XBh�8!x��&�Bht�\$���]�n߆���cL��[Ƶ�d��<`���\0���ς�aw�O%;���BC��Q�\r̭�����p����PQ�Z���Z�Au=N&�ia\n�mK6I}��n	��t\nd)����bp��\"��g'�0�7�u�&@�7�8X�N��x������\$B��ZB/�M�gB�i��ѧ�\\�m�mI�Ā��;5=#&4����P�Ս����q�A��\\�,q�cޟ\nc�B�����w\0BgjD�@;�=0m�k��\rĲ�`��'5���k-�{��\0�_�Mu����2��׆����q����>)9�W\n�d+��ԧ�G\r��n4���O�:5���8��1�:Κ?��(yGgWK�\r�7����m5.��e�H�hJ�Ak#��L�..�\\�=��U�Є����:�>7�W+^yD���b��G��OZ�4�r�(|x���Pr��,y���8qaܩO2��k�n��#p2��ǈ�ؔ.��c��U�c����łj�\$��8Ĭ~��7ZR:�׆8�9Ψw(a�L�%�-,��쿌#�f�%8��|�c������%X�W�\n}6��H����˞��#�&J,'z�M�M�����ຑ܆� ���/y6YQ���ںdәd����:����E��p2g�g�/�,����Ո'8�^;�UWN�����{�OC�����z�iKX��ڔN�dG�RCJY����i���y#>zS�MUc�������RORԾ�0�)�0��]:=Ϟ�t�����'\$�s�rF���67	=\$B��!qs	1\"���v��%��I�l<�b!ۮ6(Cd-�^<H`~2�K��zK�ٜ�Ա���y,qA�*�\0}��C�pb�\\�S�5����'(����|�M����W��5;\$5�T|��;k���t���@��;9�)��;i�.�;���_����F�=�D�M`H���\0�	 N @�%w��d��Pb�\$H|k�[��dCI!:l��,���<��u�t���NeϝW^�w�'6���D��f�u �ihI�Z:��~��ϣ�r���z�3�+�uoC�s2�b�ua�X��wWK�	HԶ27>�W���y����M�J��rpT��L��|`f��:���A�t�d|i��[w��j���W� 7���au�����e��A5�Q' ʐ\0��3�Ҿ\$����\rk)�a;���H=��֐~�IG�I�<���\"���I1'蠙�Gcm\0P\n�w��#�>���xB\"��Em|��2�\$}<3P�YX�go�d߶�<�����qE\"`���4�g�8r�]\n����:��qVb�T��m���9K&ғĤ�m�7)@��Qz���=��ߵű�H\n���}O�i}�\r٣.��v��p�JW&�u�55�0	�5��P�I��\n�����l\0O5*=��	�P-���H\0�f�%��tぺ*�S:�tϛ���?�ȂH����q4��K���@�Ԭ�܂.O(����Z�\$���]���o��n�z�A�!�t85<W�R2[�8���n5\$I��浕Z����]'}ET\n�����.��&�7��V�@�_�D�o��&J6��4i�j\$��EL���u��t����+I�Т���أ~�S�SZTX���PYz��\"\$V�_]�M(��7���������t_��S�����/��t���Ă���mH�:\0�5�- _Z'#���1�P��,�}(��~�\0��!Җ`-�P\ne�y (����`9O��!��;5�\n�\$�{������UA��7��!���[� �Y���F�濴�����>�8&����!CL���H����(�\0'Ǐ2��d\r%�;�k抐4��_O�>�5���@D�Ҽ��\0V�A�6' AY�����S�����rԾ�4�+h@b��������O�M\0���r̛�@�\rJ��m0\08�O���;k�Ӡ���A(6�|	`8 �\0��&��E�V��\0V�����wk�N��K����xdp���s�AL��A�X�k���u\0�����t �Ԣ�.�>(N��K'fld�A���?++��N��~������k�����PR\0��x������ʑ���BK]�bU��\\̛���d\0S@��Q��͉�b�\0\0b���\0_\\�@\nN���O�A��Pf��������ԏAj ��M4<�9���+�����`S�� ����w3T���7�X���T!\0e�PAI�b 1!\0��4���'� @�!�8\0��/���!:K�,�CAS�X�f�e��M��.:��:��t������._�d����81v`�B\"��!.^�*��N.^��\n�&\r(��.����O0��@��P��nj���ڗ#������&��rH�<��� �!��3��(i @�Aa��{� ¬#�S���6𨘶F@�����Y[O��(��.��/�B�����)L02B؈�-�ƀ��qp��J<�.Б\0\n��\0��/@8C�4P��\r	P�)��F���\$q.]�\"B#��	�#\\��84\$�s:.(*Oi>�|#T'`�Bu�a/���C��T�Ka�X8�`p�����\0`�\0");
	} elseif ($_GET["file"]=="jush.js") {
		header("Content-Type: text/javascript; charset=utf-8");
		echo lzw_decompress("v0��F����==��FS	��_6MƳ���r:�E�CI��o:�C��Xc��\r�؄J(:=�E���a28�x�?�'�i�SANN���xs�NB��Vl0���S	��Ul�(D|҄��P��>�E�㩶yHch��-3Eb�� �b��pE�p�9.����~\n�?Kb�iw|�`��d.�x8EN��!��2��3���\r���Y���y6GFmY�8o7\n\r�0��\0�Dbc�!�Q7Шd8���~��N)�Eг`�Ns��`�S)�O���/�<�x�9�o�����3n��2�!r�:;�+�9�CȨ���\n<�`��b�\\�?�`�4\r#`�<�Be�B#�N ��\r.D`��j�4���p�ar��㢺�>�8�\$�c��1�c���c����{n7����A�N�RLi\r1���!�(�j´�+��62�X�8+����.\r����!x���h�'��6S�\0R����O�\n��1(W0���7q��:N�E:68n+��մ5_(�s�\r��/m�6P�@�EQ���9\n�V-���\"�.:�J��8we�q�|؇�X�]��Y X�e�zW�� �7��Z1��hQf��u�j�4Z{p\\AU�J<��k��@�ɍ��@�}&���L7U�wuYh��2��@�u� P�7�A�h����3Û��XEͅZ�]�l�@Mplv�)� ��HW���y>�Y�-�Y��/�������hC�[*��F�#~�!�`�\r#0P�C˝�f������\\���^�%B<�\\�f�ޱ�����&/�O��L\\jF��jZ�1�\\:ƴ>�N��XaF�A�������f�h{\"s\n�64������?�8�^p�\"띰�ȸ\\�e(�P�N��q[g��r�&�}Ph���W��*��r_s�P�h���\n���om������#���.�\0@�pdW �\$Һ�Q۽Tl0� ��HdH�)��ۏ��)P���H�g��U����B�e\r�t:��\0)\"�t�,�����[�(D�O\nR8!�Ƭ֚��lA�V��4�h��Sq<��@}���gK�]���]�=90��'����wA<����a�~��W��D|A���2�X�U2��yŊ��=�p)�\0P	�s��n�3�r�f\0�F���v��G��I@�%���+��_I`����\r.��N���KI�[�ʖSJ���aUf�Sz���M��%��\"Q|9��Bc�a�q\0�8�#�<a��:z1Uf��>�Z�l������e5#U@iUG��n�%Ұs���;gxL�pP�?B��Q�\\�b��龒Q�=7�:��ݡQ�\r:�t�:y(� �\n�d)���\n�X;����CaA�\r���P�GH�!���@�9\n\nAl~H���V\ns��ի�Ư�bBr���������3�\r�P�%�ф\r}b/�Α\$�5�P�C�\"w�B_��U�gAt��夅�^Q��U���j���Bvh졄4�)��+�)<�j^�<L��4U*���Bg�����*n�ʖ�-����	9O\$��طzyM�3�\\9���.o�����E(i������7	tߚ�-&�\nj!\r��y�y�D1g���]��yR�7\"������~����)TZ0E9M�YZtXe!�f�@�{Ȭyl	8�;���R{��8�Į�e�+UL�'�F�1���8PE5-	�_!�7��[2�J��;�HR��ǹ�8p痲݇@��0,ծpsK0\r�4��\$sJ���4�DZ��I��'\$cL�R��MpY&����i�z3G�zҚJ%��P�-��[�/x�T�{p��z�C�v���:�V'�\\��KJa��M�&���Ӿ\"�e�o^Q+h^��iT��1�OR�l�,5[ݘ\$��)��jLƁU`�S�`Z^�|��r�=��n登��TU	1Hyk��t+\0v�D�\r	<��ƙ��jG���t�*3%k�YܲT*�|\"C��lhE�(�\r�8r��{��0����D�_��.6и�;����rBj�O'ۜ���>\$��`^6��9�#����4X��mh8:��c��0��;�/ԉ����;�\\'(��t�'+�����̷�^�]��N�v��#�,�v���O�i�ϖ�>��<S�A\\�\\��!�3*tl`�u�\0p'�7�P�9�bs�{�v�{��7�\"{��r�a�(�^��E����g��/���U�9g���/��`�\nL\n�)���(A�a�\" ���	�&�P��@O\n師0�(M&�FJ'�! �0�<�H�������*�|��*�OZ�m*n/b�/�������.��o\0��dn�)����i�:R���P2�m�\0/v�OX���Fʳψ���\"�����0�0�����0b��gj��\$�n�0}�	�@�=MƂ0n�P�/p�ot������.�̽�g\0�)o�\n0���\rF����b�i��o}\n�̯�	NQ�'�x�Fa�J���L������\r��\r����0��'��d	oep��4D��ʐ�q(~�� �\r�E��pr�QVFH�l��Kj���N&�j!�H`�_bh\r1���n!�Ɏ�z�����\\��\r���`V_k��\"\\ׂ'V��\0ʾ`AC������V�`\r%�����\r����k@N����B�횙� �!�\n�\0Z�6�\$d��,%�%la�H�\n�#�S\$!\$@��2���I\$r�{!��J�2H�ZM\\��hb,�'||cj~g�r�`�ļ�\$���+�A1�E���� <�L��\$�Y%-FD��d�L焳��\n@�bVf�;2_(��L�п��<%@ڜ,\"�d��N�er�\0�`��Z��4�'ld9-�#`��Ŗ����j6�ƣ�v���N�͐f��@܆�&�B\$�(�Z&���278I ��P\rk\\���2`�\rdLb@E��2`P( B'�����0�&��{���:��dB�1�^؉*\r\0c<K�|�5sZ�`���O3�5=@�5�C>@�W*	=\0N<g�6s67Sm7u?	{<&L�.3~D��\rŚ�x��),r�in�/��O\0o{0k�]3>m��1\0�I@�9T34+ԙ@e�GFMC�\rE3�Etm!�#1�D @�H(��n ��<g,V`R]@����3Cr7s~�GI�i@\0v��5\rV�'������P��\r�\$<b�%(�Dd��PW����b�fO �x\0�} ��lb�&�vj4�LS��ִԶ5&dsF M�4��\".H�M0�1uL�\"��/J`�{�����xǐYu*\"U.I53Q�3Q��J��g��5�s���&jь��u�٭ЪGQMTmGB�tl-c�*��\r��Z7���*hs/RUV����B�Nˈ�����Ԋ�i�Lk�.���t�龩�rYi���-S��3�\\�T�OM^�G>�ZQj���\"���i��MsS�S\$Ib	f���u����:�SB|i��Y¦��8	v�#�D�4`��.��^�H�M�_ռ�u��U�z`Z�J	e��@Ce��a�\"m�b�6ԯJR���T�?ԣXMZ��І��p����Qv�j�jV�{���C�\r��7�Tʞ� ��5{P��]�\r�?Q�AA������2񾠓V)Ji��-N99f�l Jm��;u�@�<F�Ѡ�e�j��Ħ�I�<+CW@�����Z�l�1�<2�iF�7`KG�~L&+N��YtWH飑w	����l��s'g��q+L�zbiz���Ţ�.Њ�zW�� �zd�W����(�y)v�E4,\0�\"d��\$B�{��!)1U�5bp#�}m=��@�w�	P\0�\r�����`O|���	�ɍ����Y��JՂ�E��Ou�_�\n`F`�}M�.#1��f�*�ա��  �z�uc���� xf�8kZR�s2ʂ-���Z2�+�ʷ�(�sU�cD�ѷ���X!��u�&-vP�ر\0'L�X �L����o	��>�Վ�\r@�P�\rxF��E��ȭ�%����=5N֜��?�7�N�Å�w�`�hX�98 �����q��z��d%6̂t�/������L��l��,�Ka�N~�����,�'�ǀM\rf9�w��!x��x[�ϑ�G�8;�xA��-I�&5\$�D\$���%��xѬ���´���]����&o�-3�9�L��z���y6�;u�zZ ��8�_�ɐx\0D?�X7����y�OY.#3�8��ǀ�e�Q�=؀*��G�wm ���Y�����]YOY�F���)�z#\$e��)�/�z?�z;����^��F�Zg�����������`^�e����#�������?��e��M��3u�偃0�>�\"?��@חXv�\"������*Ԣ\r6v~��OV~�&ר�^g���đٞ�'��f6:-Z~��O6;zx��;&!�+{9M�ٳd� \r,9���W��ݭ:�\r�ٜ��@睂+��]��-�[g��ۇ[s�[i��i�q��y��x�+�|7�{7�|w�}����E��W��Wk�|J؁��xm��q xwyj���#��e��(�������ߞþ��� {��ڏ�y���M���@��ɂ��Y�(g͚-��������J(���@�;�y�#S���Y��p@�%�s��o�9;�������+��	�;����ZNٯº��� k�V��u�[�x��|q��ON?���	�`u��6�|�|X����س|O�x!�:���ϗY]�����c���\r�h�9n�������8'������\rS.1��USȸ��X��+��z]ɵ��?����C�\r��\\����\$�`��)U�|ˤ|Ѩx'՜����<�̙e�|�ͳ����L���M�y�(ۧ�l�к�O]{Ѿ�FD���}�yu��Ē�,XL\\�x��;U��Wt�v��\\OxWJ9Ȓ�R5�WiMi[�K��f(\0�dĚ�迩�\r�M����7�;��������6�KʦI�\r���xv\r�V3���ɱ.��R������|��^2�^0߾\$�Q��[�D��ܣ�>1'^X~t�1\"6L���+��A��e�����I��~����@����pM>�m<��SK��-H���T76�SMfg�=��GPʰ�P�\r��>�����2Sb\$�C[���(�)��%Q#G`u��Gwp\rk�Ke�zhj��zi(��rO�������T=�7���~�4\"ef�~�d���V�Z���U�-�b'V�J�Z7���)T��8.<�RM�\$�����'�by�\n5����_��w����U�`ei޿J�b�g�u�S��?��`���+��� M�g�7`���\0�_�-���_��?�F�\0����X���[��J�8&~D#��{P���4ܗ��\"�\0��������@ғ��\0F ?*��^��w�О:���u��3xK�^�w���߯�y[Ԟ(���#�/zr_�g��?�\0?�1wMR&M���?�St�T]ݴG�:I����)��B�� v����1�<�t��6�:�W{���x:=��ޚ��:�!!\0x�����q&��0}z\"]��o�z���j�w�����6��J�P۞[\\ }��`S�\0�qHM�/7B��P���]FT��8S5�/I�\r�\n ��O�0aQ\n�>�2�j�;=ڬ�dA=�p�VL)X�\n¦`e\$�TƦQJ��k�7�*O�� .����ġ�\r���\$#p�WT>!��v|��}�נ.%��,;�������f*?�焘��\0��pD��! ��#:MRc��B/06���	7@\0V�vg����hZ\nR\"@��F	����+ʚ�E�I�\n8&2�bX�PĬ�ͤ=h[���+�ʉ\r:��F�\0:*��\r}#��!\"�c;hŦ/0��ޒ�Ej�����]�Z�����\0�@iW_���h�;�V��Rb��P%!��b]SB����Ul	����r��\r�-\0��\"�Q=�Ih����	 F���L��FxR�э@�\0*�j5���k\0�0'�	@El�O���H�Cx�@\"G41�`ϼP(G91��\0��\"f:Qʍ�@�`'�>7�Ȏ�d�����R41�>�rI�H�Gt\n�R�H	��bҏ��71���f�h)D��8�B`���(�V<Q�8c? 2���E�4j\0�9��\r�͐�@�\0'F�D��,�!��H�=�*��E�(���?Ѫ&xd_H�ǢE�6�~�u��G\0R�X��Z~P'U=���@����l+A�\n�h�IiƔ���PG�Z`\$�P������.�;�E�\0�}� ��Q�����%���jA�W�إ\$�!��3r1� {Ӊ%i=IfK�!�e\$���8�0!�h#\\�HF|�i8�tl\$���l����l�i*(�G���L	 �\$��x�.�q\"�Wzs{8d`&�W��\0&E����15�jW�b��ć��V�R����-#{\0�Xi���g*��7�VF3�`妏�p@��#7�	�0��[Ү���[�éh˖\\�o{���T���]��Ŧᑀ8l`f@�reh��\n��W2�*@\0�`K(�L�̷\0vT��\0�c'L����:�� 0��@L1�T0b��h�W�|\\�-���DN��\ns3��\"����`Ǣ�肒�2��&��\r�U+�^��R�eS�n�i0�u˚b	J����2s��p�s^n<���♱�Fl�a�\0���\0�mA2�`|؟6	��nr���\0Dټ��7�&m�ߧ-)���\\���݌\n=���;*���b��蓈�T��y7c��|o�/����:���t�P�<��Y:��K�&C��'G/�@��Q�*�8�v�/��&���W�6p.\0�u3����Bq:(eOP�p	�駲���\r���0�(ac>�N�|��	�t��\n6v�_��e�;y���6f���gQ;y�β[S�	��g�ǰ�O�ud�dH�H�=�Z\r�'���qC*�)����g��E�O�� \"��!k�('�`�\nkhT��*�s��5R�E�a\n#�!1�����\0�;��S�iȼ@(�l���I� �v\r�nj~��63��Έ�I:h����\n.��2pl�9Bt�0\$b��p+�ǀ*�tJ����s�JQ8;4P(��ҧѶ!��.Ppk@�)6�5��!�(��\n+��{`=��H,Ɂ\\Ѵ�4�\"[�C���1���-���luo��4�[���E�%�\"��w] �(� ʏTe��)�K�A�E={ \n�`;?���-�G�5I���.%�����q%E���s���gF��s	�����K�G��n4i/,�i0�u�x)73�Szg���V[��h�Dp'�L<TM��jP*o�≴�\nH���\n�4�M-W�N�A/@�8mH��Rp�t�p�V�=h*0��	�1;\0uG��T6�@s�\0)�6��ƣT�\\�(\"���U,�C:��5i�K�l���ۧ�E*�\"�r����.@jR�J�Q��/��L@�SZ���P�)(jj�J������L*���\0���\r�-��Q*�Qڜg��9�~P@���H���\n-e�\0�Qw%^ ET�< 2H�@޴�e�\0� e#;��I�T�l���+A+C*�Y���h/�D\\�!鬚8�»3�AЙ��E��E�/}0t�J|���1Qm��n%(�p��!\n��±U�)\rsEX���5u%B- ��w]�*��E�)<+��qyV�@�mFH ���BN#�]�YQ1��:��V#�\$������<&�X������x��t�@]G��Զ��j)-@�q��L\nc�I�Y?qC�\r�v(@��X\0Ov�<�R�3X���Q�J����9�9�lxCuīd�� vT�Zkl\r�J��\\o�&?�o6E�q������\r���'3��ɪ�J�6�'Y@�6�FZ50�V�T�y���C`\0��VS!���&�6�6���rD�f`ꛨJvqz���F�����@�ݵ��҅Z.\$kXkJ�\\�\"�\"�֝i��:�E���\roX�\0>P��P�mi]\0�����aV��=���I6�����jK3���Z�Q�m�E���b�0:�32�V4N6����!�l�^ڦ�@h�hU��>:�	��E�>j�����0g�\\|�Sh�7y�ބ�\$��,5aė7&��:[WX4��q� ���J���ׂ�c8!�H���VD�Ď�+�D�:����9,DUa!�X\$��Я�ڋG�܌�B�t9-+o�t��L��}ĭ�qK��x6&��%x��tR�����\"�π�R�IWA`c���}l6��~�*�0vk�p���6��8z+�q�X��w*�E��IN�����*qPKFO\0�,�(��|�����k *YF5���;�<6�@�QU�\"��\rb�OAXÎv��v�)H��o`ST�pbj1+ŋ�e��� ʀQx8@�����5\\Q�,���ĉN��ޘb#Y�H��p1����kB�8N�o�X3,#Uک�'�\"�销�eeH#z��q^rG[��:�\r�m�ng����5��V�]��-(�W�0���~kh\\��Z��`��l����k �o�j�W�!�.�hF���[t�A�w�e�M૫��3!����nK_SF�j���-S�[r�̀w��0^�h�f�-����?���X�5�/������IY �V7�a�d �8�bq��b�n\n1YR�vT���,�+!����N�T��2I�߷�����������K`K\"�����O)\nY��4!}K�^����D@��na�\$@� ��\$A��j����\\�D[=�	bHp�SOAG�ho!F@l�U��`Xn\$\\�͈_��˘`���HB��]�2���\"z0i1�\\�����w�.�fy޻K)����� p�0���X�S>1	*,]��\r\"���<cQ��\$t��q��.��	<��+t,�]L�!�{�g���X��\$��6v����� ����%G�H������E����X��*��0ۊ)q�nC�)I���\"�����툳�`�KF����@�d�5��A��p�{�\\���pɾN�r�'�S(+5�Њ+�\"�Ā�U0�iː����!nM��brK���6ú�r���|a����@�x|��ka�9WR4\"?�5��p�ۓ��k�rĘ����ߒ����7Hp��5�YpW���G#�rʶAWD+`��=�\"�}�@H�\\�p���Ѐ�ߋ�)C3�!�sO:)��_F/\r4���<A��\nn�/T�3f7P1�6����OYлϲ���q��;�؁���a�XtS<��9�nws�x@1Ξxs�?��3Ş@���54��o�ȃ0����pR\0���������yq��L&S^:��Q�>\\4OIn��Z�n��v�3�3�+P��L(�������.x�\$�«C��Cn�A�k�c:L�6���r�w���h����nr�Z��=�=j�ђ���6}M�G�u~�3���bg4���s6s�Q��#:�3g~v3���<�+�<���a}ϧ=�e�8�'n)ӞcC�z��4L=h��{i����J�^~��wg�D�jL���^����=6ΧN�Ӕ����\\��D���N���E�?h�:S�*>��+�u�hh҅�W�E1j�x����t�'�t�[��wS���9��T��[�,�j�v����t��A#T���枂9��j�K-��ޠ���Y�i�Qe?��4Ӟ���_Wz����@JkWY�h��pu����j|z4���	�i��m�	�O5�\0>�|�9�ז��轠��gVy��u���=}gs_���V�sծ{�k�@r�^���(�w����H'��a�=i��N�4����_{�6�tϨ��ϗe�[�h-��Ul?J��0O\0^�Hl�\0.��Z������xu���\"<	�/7���� ���i:��\nǠ���;��!�3���_0�`�\0H`���2\0��H�#h�[�P<��עg����m@~�(��\0ߵk�Y�v���#>���\nz\n�@�Q�\n(�G��\n����'k����5�n�5ۨ�@_`Ї_l�1���wp�P�w���\0��c��oEl{�ݾ�7����o0����Ibϝ�n�z����﷛� ���{�8�w�=��|�/y�3a�߼#xq����@��ka�!�\08d�m��R[wvǋRGp8���v�\$Z���m��t��������������ǽ����u�o�p�`2��m|;#x�m�n�~;��V�E�������3O�\r�,~o�w[��N��}�� �cly��O����;��?�~�^j\"�Wz�:�'xW��.�	�u�(��Ý�q��<g��v�hWq��\\;ߟ8��)M\\��5vڷx=h�i�b-���|b���py�DЕHh\rce��y7�p��x��G�@D=� ����1��!4Ra\r�9�!\0'�Y����@>iS>����o��o��fsO 9�.����\"�F��l��20��E!Q���ːD9d�BW4��\0��y`RoF>F�a��0�����0	�2�<�I�P'�\\���I�\0\$��\n R�aU�.�sЄ��\"���1І�e�Y砢�Z�q��1�|��#�G!�P�P\0|�H�Fnp>W�:��`YP%�ď�\n�a8��P>�����`]��4�`<�r\0�Î������z�4����8�����4�`m�h:�Ϊ�HD���j�+p>*����8�ՠ0�8�A��:���с�]w�ú�z>9\n+�������:����ii�PoG0���1��)�Z�ږ�n�����eR֖��g�M�����gs�LC�r�8Ѐ�!�����3R)��0�0��s�I��J�VPpK\n|9e[���ˑ��D0����z4ϑ�o������,N8n��s�#{蓷z3�>�BS�\";�e5VD0���[\$7z0������=8�	T 3���Q�'R������n��L�yŋ��'�\0o��,��\0:[}(���|���X�>xvqW�?tB�E1wG;�!�݋5΀|�0��JI@��#���uņI��\\p8�!'�]߮��l-�l�S�B��,ӗ���]��1�ԕH��N�8%%�	��/�;�FGS���h�\\ل�c�t����2|�W�\$t��<�h�O��+#�B�aN1��{��y�w���2�\\Z&)�d�b'��,Xxm�~�H��@:d	>=-��lK��܏�J�\0���́�@�rϥ�@\"�(A����Z�7�h>����\\����#>���\0��Xr�Y��Yxŝ�q=:��Թ�\rl�o�m�gb��������D_�Tx�C���0.��y��R]�_���Z�ǻW�I��G��	Mɪ(��|@\0SO��s� {��@k}��FXS�b8��=��_����l�\0�=�g��{�H��yG���� s�_�J\$hk�F�q������d4ω����'���>vϏ��!_7�Vq��@1z�uSe��jKdyu���S�.�2�\"�{��K���?�s��˦h��R�d��`:y����Gھ\nQ�����ow��'��hS��>���L�X}��e���G��@9��퟈�W�|��Ϲ�@�_��uZ=��,���!}���\0�I@��#��\"�'�Y`��\\?��p��,G����ל_��'�G����	�T��#�o��H\r��\"���o�}��?��O鼔7�|'���=8�M��Q�y�a�H�?��߮� ���\0���bUd�67���I O����\"-�2_�0�\r�?�������hO׿�t\0\0002�~�° 4���K,��oh��	Pc���z`@��\"�����H; ,=��'S�.b��S����Cc���욌�R,~��X�@ '��8Z0�&�(np<pȣ�32(��.@R3��@^\r�+�@�,���\$	ϟ��E���t�B,���⪀ʰh\r�><6]#���;��C�.Ҏ����8�P�3��;@��L,+>���p(#�-�f1�z���,8�ߠ��ƐP�:9����R�۳����)e\0ڢR��!�\nr{��e����GA@*��n�D��6��������N�\r�R���8QK�0��颽��>PN���IQ=r<�;&��f�NGJ;�UA�����A�P�&������`�����);��!�s\0���p�p\r�����n(��@�%&	S�dY����uC�,��8O�#�����o���R�v,��#�|7�\"Cp����B�`�j�X3�~R�@��v�����9B#���@\n�0�>T�����-�5��/�=� ���E����\n��d\"!�;��p*n��Z�\08/�jX�\r��>F	Pϐe>��O��L����O0�\0�)�k���㦃[	��ϳ���'L��	����1 1\0��C�1T�`����Rʐz�Ě����p��������< .�>�5��\0���>� Bnˊ<\"he�>к�î��s�!�H�{ܐ�!\r�\r�\"��|��>R�1d���\"U@�D6����3���>o\r����v�L:K�2�+�0쾁�>��\0�� ���B�{!r*H��y;�`8\0��د��d����\r�0���2A����?��+�\0�Å\0A����wS��l����\r[ԡ�6�co�=����0�z/J+�ꆌ�W[��~C0��e�30HQP�DPY�}�4#YD���p)	�|�@���&�-��/F�	�T�	����aH5�#��H.�A>��0;.���Y�ġ	�*�D2�=3�	pBnuDw\n�!�z�C�Q \0��HQ4D�*��7\0�J��%ıp�uD�(�O=!�>�u,7��1��TM��+�3�1:\"P�����RQ?���P���+�11= �M\$Z��lT7�,Nq%E!�S�2�&��U*>GDS&����ozh8881\\:��Z0h���T �C+#ʱA%��D!\0�����XDA�3\0�!\\�#�h���9b��T�!d�����Y�j2��S����\nA+ͽ��H�wD`�(AB*��+%�E��X.ˠB�#��ȿ��&��Xe�Eo�\"��|�r��8�W�2�@8Da�|�������N�h����J8[�۳����W�z�{Z\"L\0�\0��Ȇ8�x�۶X@�� �E����h;�af��1��;n��hZ3�E����0|� 옑��A���t�B,~�W�8^�Ǡ׃��<2/	�8�+��۔���O+�%P#ή\n?�߉?��e˔�O\\]�7(#��D۾�(!c)�N����MF�E�#DX�g�)�0�A�\0�:�rB��``  ��Q��H>!\rB��\0��V%ce�HFH��m2�B�2I����`#���D>���n\n:L���9C���0��\0��x(ޏ�(\n����L�\"G�\n@���`[���\ni'\0��)������y)&��(p\0�N�	�\"��N:8��.\r!��'4|ל~����ʀ���\"�c��Dlt����0c��5kQQר+�Z��Gk�!F��c�4��Rx@�&>z=��\$(?���(\n쀨>�	�ҵ���Cqی��t-}�G,t�GW �xq�Hf�b\0�\0z���T9zwЅ�Dmn'�ccb�H\0z���3�!����� H��Hz׀�Iy\",�-�\0�\"<�2���'�#H`�d-�#cl�jĞ`��i(�_���dgȎ�ǂ*�j\r�\0�>� 6���6�2�kj�<�Cq��9�Đ��I\r\$C�AI\$x\r�H��7�8 ܀Z�pZrR����_�U\0�l\r��IR�Xi\0<����r�~�x�S��%��^�%j@^��T3�3ɀGH�z��&\$�(��q\0��f&8+�\rɗ%�2hC�x���I��lbɀ�(h�S�Y&��B������`�f��x�v�n.L+��/\"=I�0�d�\$4�7r����A���(4�2gJ(D��=F�����(����-'Ġ�XG�2�9Z=���,��r`);x\"��8;��>�&�����',�@��2�pl���:0�lI��\rr�JD���������hA�z22p�`O2h��8H��Ąwt�BF���g`7���2{�,Kl���߰%C%�om���������+X����41򹸎\n�2p��	ZB!�=V�ܨ�Ȁ�+H6���*��\0�k���%<� �K',3�r�I�;��8\0Z�+Eܭ�`������+l����W+�Yҵ-t��f�b�Q��_-Ӏޅ�+�� 95�LjJ.Gʩ,\\��ԅ.\$�2�J�\\�-��1�-c���ˇ.l�f�xBqK�,d��ˀ�8�A�Ko-��������3K��r��/|����/\\�r���,��HϤ�!�Y�1�0�@�.�&|����+��J\0�0P3J�-ZQ�	�\r&����\n�L�*���j�ĉ|�����#Ծ�\"˺���A��/���8�)1#�7\$\"�6\n>\n���7L�1���h9�\0�B�Z�d�#�b:\0+A���22��'̕\nt���̜�O��2lʳ.L��HC\0��2���+L�\\��r�Kk+���˳.ꌒ�;(Dƀ���1s����d�s9�����P4�쌜��@�.���A��nhJ�1�3�K�0��3J\$\0��2�Lk3��Q�;3��n\0\0�,�sI�@��u/VA�1���UM�<�Le4D�2��V�% �Ap\nȬ2��35���A-��T�u5�3�۹1+fL~�\n���	��->�� �ҡM�4XL�S��dٲ�͟*\\�@ͨ��Y�k����SDM�5 Xf����D�s���Us%	�̱p+K�6��/���ݒ�8X�ނ=K�6pH����%�3�ͫ7l�I�K0���L��D��u���`��P\r��SO͙&(;�L@��ψN>S��2��8(���`J�E��r�F	2��SE��M��M��\$q�E��\$�ã/I\$\\���ID�\"��\n䱺�w.t�S	���ђP��#\nW��-\0Cҵ�:j�R��^S���8;d�`���5Ԫ�aʖ��E��+(Xr�M�;��3�;���B,��*1&����2X�S���)<� �L9;�RSN����gIs+��ӰK�<��s�LY-Z�:A<���OO*��2v�W7��+|���˻<T���9�h����y\$<��#ρ;����v�\$��O�\0� �,Hk��-���Ϛ\r����ϣ;���O�>�����7>��3@O{.4�pO�?T�b���.�.~O�4��S���>1SS��*4�Pȣ�>�����3�\0�W�>��2��><���P?4��@��t\nN����A�xp��%=P@��C�@�R�˟?x��\n���0N�w�O?�TJC@��#�	.d���M��t�&=�\\�4��A��:L����\$���N��:��\r��I'���A�rግ;\r�/��C���B�Ӯ�i>L��7:9�����|�C\$��)�����z@�tl�:>��C�\n�Bi0G��,\0�FD%p)�o\0����\n>��`)QZI�KG�%M\0#\0�D���Q.H�'\$�E\n �\$ܐ%4I�D�3o�:L�\$��m ��0�	�B�\\(����8��通�h��D��C�sDX4TK���{��x�`\n�,��\nE��:�p\n�'��>��o\0���tI��` -\0�D��/��KP�`/���H�\$\n=���>��U�FP0���UG}4B\$?E����%�T�WD} *�H0�T�\0t������\"!o\0�E�7��R.���tfRFu!ԐD�\n�\0�F-4V�QH�%4��0uN\0�D�QRuE�	)��I\n�&Q�m�)ǚ�m �#\\����D��(\$̓x4��WFM&ԜR5H�%q��[F�+���IF \nT�R3D�L�o���y4TQ/E��[ў<�t^��F��)Q��+4�Q�I�#���IF�'TiѪX��!ѱF�*�nR�>�5�p��Km+�s��������I���R�E�+ԩ��M\0��(R�?�+HҀ�J�\"T�D���\$���	4wQ�}Tz\0�G�8|�x���R��6�R�	4XR6\n�4y�mN��Q�NM�&R�H&�2Q/�7#�қ�{�'�ҍ,|����\n�	.�\0�>�{�o#1D�;��?U��ҕJ�9�*����j����F�N��щJ� #�~%-?C���L�3�@EP�{`>Q�Ȕ��%O�)4�R%I�@��%,�\"���I�<�����\$ԉTP>�\n�\0QP5D��kOF�TY�<�o�Q�=T�\0��x	5�D�,�0?�i�?x�  �mE}>�|����[��\0����&RL���H�S9�G�I��1䀖��M4V�H�oT-S�)Q�G�F [��TQRjN��#x]N(�U�8\nuU\n?5,TmԞ?����?��@�U\n�u-��R�9��U/S \nU3�IESt�QYJu.�Q��F�o\$&���i	��KPC�6�>�5�G\0uR��u)U'R�0�Ѐ�DuIU�J@	��:�V8*�Rf%&�\\�R��MU9R��fUAU[T�UQSe[��\0�KeZUa��Uh��mS<���,R�s�`&Tj@��G�!\\x�^�0>��\0&��p�΂Q�Q�)T�U�Ps�@%\0�W�	`\$��(1�Q?�\$C�Qp\n�O�J��X�#��V7X�u;�!YB��S�c��+V����#MU�W�H��U�R�ǅU-+��VmY}\\���OK�M��\$�S�eToV���HT��!!<{�R��ZA5�R�!=3U��(�{@*Ratz\0)Q�P5H؏���հ�N5+���P�[��9�V%\"����\n����G�SL�����9�����l����\rV�ؤ�[�ou�UIY�R_T�Y�p5O֧\\�q`�U�[�Bu'Uw\\mRU�ԭ\\Es5�K\\���V�\\�S�{�AZ%O��\$��F���>�5E�WVm`��Wd]& \$�Ό����!R�Z}ԅ]}v5���ZUg��Q^y` �!^=F��R�^�v�U�Kex@+��r5�#�@?=�u�Γs���ץY�N�sS!^c�5�\$.�u`��\0�XE~1�9��J�UZ�@�#1_[�4J�2�\n�\$VI�4n�\0�?�4a�R�!U~)&��B>t�R�I�0��_EkTUS��|��Uk_�8�&��E��(‘?�@���J�5���JU�BQT}HV��j��Qx\ne�VsU=���V�N�4ղؗ\\x����R34�G�D\":	KQ�>�[�\r�Y_�#!�#][j<6خX	���c���#KL}>`'\0��5�X�cU�[\0��(���Wt|t�R]p�/�]H2I�QO��1�S�Qj�Z����H���m���)d�^SXCY\r�tu@J�p��%��M������?�UQ�\n�=R�ar:ԿE���-G�\0\$��d���]�meh*��Q�Wt��c��`��A�Y=S\r���	m-���=Mw�H�]J�\"䴏������f�\"�{#9Te����M�c��N�I����D������U�6��g��2��ݝ�e�a�L��Q&&uT�X�51Y�>����S�֊Q#�I���j�\0����W�P��?ub5FU�Ln�)V5R�@��\$!%o��P��'��E�U��P�-����B�p\n�F\$�S4�t�UF|{�q�ȓ0���Umjs�������\$�ڛj��c�ڐ��֫��aZI5X��j�26��&>v��\n\r)2�_k�G��TJ��eQ-c�Z�VM�ֽ�z>�]�a�c��c��`t��H��j�6��+k�M�\0�>���##3l=�'���^6�\0�èv�Z9Se��\"���bΡ�B>�)�/T�=�9\0�`P�\$\0�]�/0ڪ��䵏�k-�6��{k���[�F\r|�SѿJ��MQ�D=�/�WX���V�a�'���a�to��l冶�Xj}C@\"�KP����om�3\0#HV���v��~�{���?gx	n|[�?U��[r�h��G�`�3#Gk%L��\0�I�`C�D��	 \"\0��ŧ��#cN�6�ڹf���zێ�;Ѥ�eeF�7�/N\r:��Q�G�9	\$��I�ռ��]��T��WGs��dW�M�I����f�Bc�ۤ����!#cnu&(�S�_�w��Sf�&T�Z:��0C�S�LN`ܳYj=��>Ų��Z!=�rV]g��	ӣr���Xl��-.�U�'uJuJ\0�s�J�'W%���\\>?�B��V�j4���J}I/-ҝrRL�S�3\0,Rgqӭ��Tf>�1��\0�_���\\V8��Z�t��c耆�<^\\�ll�j\0���T�]C��w�ΓzI��ZwN���pVW�jv�Y�>�2�	o\$|U�W�L%{toX3_���R�J5~6\"��Zl}�`�kc����eR=^UԎ��1�ѽw7e�d��v��b�=��\0�f��,��m�)��Gp��-Ӽ�)9L���>|�� \"�@���5�`�:��\0�,��t@��x���l�J���b�6������a��A\0ػAR�[A���0\$qo�A��S��@���<@�y��\"as.����V^��讥^�����\0��H���[H@�bK����)z�\r����=��^�z�B\0�����N�o<̇t<�x�\0ڬ0*R��I{��^�E�:�{KՐ�1E�0��Y����/��c��\"\0��4���F�7'���\n�0��`U�T��?MP���l��4��r(	��Z�|���&��t\"I����L�w+�m}����Wi\r>�U__u��63�y[�8�T-��V�}�x��_~�%�7��{jM�o_�E�����~]�P\$�J�CaXG�9�\0007Ń5�A#�\0.���\r˴��_������%����\n�\r#<M�x�J���|��2�\0��;o�^a+F���笀Lk��;�_���#��M\\����pr@��õ�����OR���~z��A�NE�Y�O	(1N׉�R��8��C�����n?O)��1�A�Do\0�\r�Ǣ?�kJ��\"�,�OF��a����-b�6]PS�)ƙ�5xC�=@j����L�����L�:\"胻Ί�l#���B�k��������@��N��:�>�|B����9�	���:N��\$��S� �CB:j6����ΉJk��uK�_�W�͢ØI�=@Tv��\n0^o�\\�Ӡ?/��&u�.��_��\r��C��+��c�~�J�b�6���e\0�y�ѡ\0wx�h��8j%S���VH@N'�\\ۯ��N�`n\r��u�n�K�qU�B�+�f>G��\r���=@G���d���\n�)��FO� hʷ��ÈfC�ɅX|��I�]��3auy�Ui^�9y�\no^rt\r8��͇#����N	V��Y�;�c*�%V�<��#�h9r�\rxc�v(\ra���(xja�`g�0�V̼���Q��x(���glհ{��gh`sW<Kj�'�;)�Gnq\$�p�+�Ɍ_��d��^& ���D�x�!b�v�!EjPV�'����(�=�b�\r�\"�b��L�\0���bt�\n>J���1;�����ۈ�4^s�Q�p`�fr`7���x��E<l���	8s��'PT��ֺ�˃��z_�T[>��:��`�1.���;7�@��[��>��6!�*\$`��\0���`,�������@����?�m�>�>\0�LCǸ�R��n��/+�`;C����\0�*�<F���+���q M���;1�K\n�:b�3j1��l�:c>�Y���h���ގ�#�;���3ֺ�8�5�:�\\��\0XH���a�����M1�\\�L[YC��vN��\0+\0��t#�\$�����!@*�l��	F�dhd���F���&��Ƙf�)=��0��4�x\0004ED�6K��䢣���\0�nN�];q�4sj-�=-8���\0�sǨ���D�f5p4����J�^���'Ӕ[��H^�NR F�Kw�z�� ��E����gF|!�c���o�db����x�\0�-��6�,E��_���3u�p ��/�wz�(��ex�Ra�H�Y�ce��5�9d\0�0@2@Ґ�Y�fey��Y�cMו�h����[�ez\rv\\0�e���\\�cʃ��[�ue��NY`��ۖ�]9h姗~^Yqe���]�qe_|6!���u�`�f��J�{�7��M{�Yه��j�e��C��S6\0DuasFL}�\$ȇ�(��Mb���Ƥ,0Buί���т2�gxFљ{�a�n:i\rPj�e��r�r��G�BY��M+q��iY�d˙�`0��,>6�fo�0���o�� �Xf����\0�V�L!��f��l��6� �/��1e��\0�>kbf�\r�!�uf�<%�(r˛�a&	����Y��!���mBg=@��\r�; \r�5phI�9bm�\$BYˋ���g�x�#�@QEO��m9���0\"���!�t���ˉ��Ї�O* ���\0��>%�\$�o�rN&s9�f��4���g��~jM�f�wy�g�y�\\`X1y5x����^z�_,& k���|����1x��A�6� \n�o蔻�&x��gg�{r�?緛�-����|t�3�����}gHgK�9����J�<C�C��1��9�7��g����h6!0H���cdy�f��DA;��9�T���0��\0�p�����!� 6^�.�S²?���E(P�Έ .���5��h���EPJv��.���+�\$�5��>P+�?~��g�6\r��h��p�z(�W��`��\"y���:�FadŬ�6:��f��i\0����A;�e�����^��w�f� >y�����`-\r����\0�hr\r�r�8i\"_�	����9�CI��fXˈ2���\"�Ţ����h�L~�\"���%V�:!%��xy�izyg�vx�]���}qg����Zi��|��`�+ _�g�����٣������譞6PA�ʀ\$�=�9�����h��|p��������!��.�!�����i�^���iˢ�8zVC����Z\"����(�����9�U)��!DgU\0�j��?`��4�LTo@�B����N�a�{�r�:\n̟�E��8æ&=�E�*Z:\n?��g���̊��h��.����N�5(�S�h��i2�*c�f�@����7��z\"�|��rP�.ǀ�L8T'��k���:(�q2&��ED�2~���ر�����9���v���8������@��^X=X`��qZ��Q�֮`9j�5^���@竸�n�qv����3����(I6�j�dT���\\� ��3�,��h�k�3�(�3���P�u�V�|\0阮U�k;��JQ���.��	:J\r��1��n�BI\r\0ɬh@��?�N�\nsh���\"��;�r~7O�\$��(�5�R���	�ʽj����FYF��ܔ��~�x޾�f��\"�vۓo��˨��º#��a�����P���<��h�-3麝/G�x����n�i@\"�G�?��,�Zp�xX`v�4X������[�I��7�åXc	��!�b�}�j�_��9�5qti�6f������ٞ5���Fƹ�iѱ�pX'�2��r���0�ƺ��D,#G�U2��؏�I��\rl(�� �챣��=�A�a�쩳-8�dbS����4~���H;���0�6��b��{��޺R���s3z�����N�ބ��`�ˆ+���4<�^a�y���	}r���y������k�&4@��?~���cE����@�LS@���z^�qqN��</H�j^sC�`��sbgGy����^\n�N�\n:G�N}�c\n���� +���=�p�1��N�TB[d������Ћ��ܹ�`�n�oj;�jěwh����c9��p̡[y4���05�͋N��+ο��`Xda��/zn*�P�����#t�赸~�9W�	�V��~=�#��n)����	2��;�j:��J�k�C�!>x��5��==�2���.��|�'���[��'�;��v�������������;:SA	�&�[�me���n������˵���<��6ma�=Y.神��:g����腀����;�I߻x�[��I�J\0�~�zaY������wT\\`��V\n�~P)�zJ�������Q@��[�{rʉ�D�B�v��|i-�E��K�;^n�{���:Nh;���2��ƀp�Ѵ6����罘9�9����X�hQ�~���iA�@D �j���}�ozLV���ѳ~���	8B?�#F}F�Td����e��zc��F���g�7Η���� 6�#.E£����£��S�.J3��5��Kɥ�J���;���n5��:yS��C�voս.�{��	d\\0�?W\0!)�'����Eg�;�+��\0�Y�Nt�bp+��c�����\0�B=\"�c�T�:B������c��������P�I��D��V0��!ROl�O�N~aF�|%�ߺ�����)O��	�W�o����Q�w��:ٟl�0h@:���օ8�Q�&�[�n�F��p,�æ�@��JT�w�9��(���<�{�ƐO\r�	���ڂ\$m�/HnP\$o^�U��\"���{Ė�<.���n�q8\r�\0;�n������硟�+�޳3��n{�D\$7�,Ez7\0��l!{��8��x҂�.s8�PA�Fx�r����Qۮ���1̅�p+@�d��9OP5�lK�/�����\\m����s�q���v�Q�/���	�!���z�7�o��Eǆ�:q�V�5�?G�HO��O�\$�l��+�,�\r;�����~�Ač錳�{�`7|��Ă���r'��Ji\rc+�|�#+<&қ�<W,��>��^�P�&n�Jh�e�%d������C�i�zX�A�'D�>��Έ�Ek���@�B�w(�.��\n99A�hN�c�kN��d`���p`��%2���3H��b2&�<�9�R(���t�TH�	�z��'�� �o���>4?�\rZ�w�ӂ��4�`��Ї鍆��N���Ӏ�'-I����0(S�r�w,�����K�r��'-2Hlo-�U����_�'W#'/��H֟���j6�̉�����ȫ��\0�<������j1�E�Q�T�T���r�Bcm�16�͈g٫:w6ͯ�h@1�I:������2�p�L/����w�:�ő���K<��E<��J�76Ӏ�s�.̲sZ��/\$�AsEyϜ�r�r:w?Չ�!�?���Ǚ�Z��M�9�՝\0��1?ARͦ%�7>�M�ARr}s��r)\\t-8=����ЎU��,WOCsՆ��#w�5��ERlM*�D��1��>]��gK��V�\n�\\���s�܇8͹seͧ9��so�~����w4x�����f@���D��9����6��\0	@.���@�9\0�C;K��y+�J��٥��u<\\�`�c{Ӌ�E�>�y��J=l����/�-�7����Z46�uC5��P�Ω�RV�������ʳlV��aNx�`մ?U�7(HP�}jV�J�zNQJ�S����s-gQ!a�V�_SwR�O�3am�ZXwZ�o�'�wa���O�oZ���!�[\n<�Z��O�Ҷ'��Omo�[��a�=Q��>�:��T�\n����\0�=��m�j��AT�R�bu(�I���:��\$v�W�����u�S�\\V8��v�\\���g!Mж�u��_�&�is�\\C�R�VM�]tX�T7\\UoT��o_ԯݛS?a�l�S�-LutZGe���i`	}XZ�i}Q�yW[i��T��Yo���(ZE\\�}nٍi�f��ڋ��W�d�%T�pu3u�T�f5)v��]�UR3VEY]�X�\n�^��VqS�S�}X�iGf��v>�S��v�JMQ��vڕ�����\\�g]�QYE��ݵ#1V�l5U�EK]��\0���S��U?\\�BwS�U�7���mZ�V5\\��Wf��է[�eUr�{G\\��U��,�����W�[]x��V�j5mT�V�j�~u7�\0�V�U��'t��w?ms�����5V��vݏq}����u-Uq�]ݗc]�W���]Tt:�f�M�k���e]�[-p}^�I[�XD���Y�V�d���O]	seN����Z�WY�[�t��V?�3�ǵ�M���ݙ`��t^w�d�:qT�L�@@>]�j\rF�qv��-Lv�G�Kwi�LwIPMo��ǹMgv���[��Uss��~	���w:B�A���NE�{�!-��d���o\0��}&����hX��A��5�%٣fzL�H�5d�� Y�_%�v�ә!m��]������%������=B�>E [#^}�hYF�a���>{�gS���p[�F���Da�6n�����x9��8L�I㈫N�a=�S�@�bPk�.��N��H��l\0��:���2#�Θ;��v�O}�9ik]	&�{�� �����2|a��&�������Q��������)��oف�Ǹ:�&.\0�5q\0J�L��64hy�3�ޢ���a�ރ��Iz��O�����ﮈ\"�yB�ʳ{�3�%�5r(m������x.7r�b%���^�e�M���2�\0x��!�b}.��Y6\$qS��\"^|xE����a�������Xǡ5�9��'T�R	�c9���W�1���AΔP��؏h6'�o�-���p��T(\nn\r�Ő��1���R�RUg�������x��Pe#��*��kT<�<�>b;��\0�����gL�.�<k�Zv������z���8~��y7�Y��ȁ��7w��Odn�>�<���E�3��wS�ۆ�@��� o�W�1����Һ�z�e�޽��1��z�\0f=��c㊤g��{��>n�p\0���Α:H�Bn�6F��B�r�W=��C>M.1~@3�G�9�8�q<S�|�Y�8QP��`L[���qz�۫P���N�<{_-ٮ�d�O��d-�NB7��4��B�N��.V���9ƨ�Q�3��{IcP\$���h��<R yy��?��G��:n�����g����;Ah!����&��+>�ˀ�;M�ˌ�	������6S�N��ڌ=#����`�T�#+�n�;��r,�����X|#��\r�#���?\n�D>�|V�S����eϗ~J�m99��\ns�{S|r],~�˹��� �q�I�?\"|w���%|�j�\0rE�,kSn�����qƕ�d8B.��1����\"��/|���؃]�������E�Ϝ�N�l����x��I��� Ic�Ÿ.|\$8D��F������P�K��3��\\j��xU��C/��җ�A{������e�����������ܾ�����\rp�U\n�՟Wlo­Y�{����`]'���s���/|�o����3���r��}��;��[�n��������O�M7���ߣؼq��q(��_l�q�s�N��y������;�i�g�t����:�����ՙ�qk�����{���?z��������Mȗ�o��'�j������c�y�߄���g��gk�w��f8�Vc�7fA��Y���+Kx�=�gKAk�T,95rd�+�G����ٯ����[��%��A�w柞�����7���ଅ�%��{�m��8%_��m��q��V�˨_���%�!�E���i�~���h��~��C�߭~���%�������_�������rLkD�y����~�?p1O!?��v�\\��Pm�\"��<������E�6� �E��V����zk����9�z����~�/��պ��!Q�>��O��Nm��3r�� F��l���e;�M�߷���Ͻ�_a��!~C��f����b}3� K�f���. 	��}.����DX	i5�|��?��=\0��?�?��?��@��Õ��fu~a�^��n��y�Q;�q�����)�s�S�,\"G�\nu%��U�Y�AKl\n��B�I�86VCcO\0�`}.x���,-N�@~��T�G����'��d�J�����y1�zl��æf�g����AB�a�!��M\\<�gʃ�z4ƿ��@/��C�Â�@�	�Qq���)��x��/�.7inD�#=��� *79c�F���d2(��.�V��3����\$g`�A᧋rl|�m����b��/�qE���ô!�bU@��9i�;pp�d���פ=�1�y�x�x�	�=�v=��(v��s_��Bo�ɂ�ց#�K\r n����\\�# �f�PX�u-3&�	��J&,F�(9��v�0�&@khZ�y�g�Cԋ�z ��Á�hi=�s9T�� eT>g��3�d�tF��2b&:��\0�P���B��-�Q��8~�LS�M���ڷcg���Th'�f(���\$�.E���VL����A�I���ߌ���r���g�\r���0�����T��1P`1�d�����\r�4���=6@F���� F���=�ɂ6�A���>�N�AV�	���(\$�A/������;����?�g�f^	�\n�&�KO��n�{]���g˛�8�c��ў���Ϸ�����\n��7L����t:�Ѡ�hF�VO\r��J�)b�(\"OB�m�	o��\$]T�SH�Z^��K����w�\\[A9('�لcۑ���b0���� K�����srB�x\n�*Ba�z6o�\ry&tX1p'���^�M��<�Cg�`�4�8GH��zd?gX��.@,�7w��۞:+�TiUX16��L��s�:�\r�L�6�����f�r\r`�t��67~g�x�gH9�J��O=-\$�4?r٪4����O���:��z��{��D`����21�F�ܵ��(D�M��;����&����́��ڭ��U>�I�6��c���߸@\r/�/��ԕ��_H��\n7z�� ������7�a�ɻ[9D�'����}B��O�R��ݟ�B#s��]z!(D���@L^��	��x��@o��u�O����D���!�e`\na�k>�0`����-*���8E�Z6=f��%����c㛰�K=���F�\r���Sh�yN�[v*v�\r���@�#߸퉁�Ah*�L\$���A�A\\�����%�*	��p�\r*==8�\$W�\r� [��Jx0y��Z�+&Y�HA~A\n,\\(��p�!F����<6S�&IP`6Xz�+�df�\r��J£���i�s�+�&5��/rE���M^\$R(R�Q��Ew3��lH*m\0Bq�a��r��LB����Q��z6~l���B��\rI®G��XٸXVbs�mB�H�����c�_K�\$p�-:8��Nj:�х��-#�F�	\0�aiB�s\\�)�<.�!��\\��N��bIw8�͹t���PjW�`���y\0��&0�i?���Ҕ:�Ia)=��C�,a&�M�apƃ\$�I�IFc���\0!���Y�xa)~�C1�P�ZL3T�j�C\0y����`�\\�W��\\t\$�2�\n�+a�\0aKb���\n��]�C@��?I\r�HヮKs%�N����^���9CL/��=%ۨ�h��:?&P��EY�>5���n[Gْ�%V��*�w<����gJ�]�*�wd�]�B�5^�֢�OQ>%�s{�ԅ畫;�W����z�Gi���*��Rn��G9�E����,(u*��Ւ×��X�s��R���:�5�;��)�R���N���vK�(�R��M���b����_�{�F<<3�:%��HV�YS\n�%L+{�o.>Z(�Qk���N�!��,�:rH}nR�NkI		��[���ӧg��֤;mYҳ�g�%�9V~-J_��g�����\\�ɮ�Q\n��!�t�\\UY-tZn��d:B��ʽ�*�]')t���w���ɫ[BUm*�r4�ؖ�*yv���vZ�չ+GH��Zn�P�܅|\nT� %#\\�AX\0}5b+w�r�Xwܲ1u��%Cg=I��v`�cr�e�0`..<���h�+�H̝^\\j�yF��%�]�B�\0��r��+�>�%Zx�� �%C.����`Vn�1KS���k\r���X|��[�;�6H	U@�D:޻Mj	Ε��?��]ڤ��b�A+��G�\0thxb��L`���64Mޛ��Y#�hfD=e��w=�c�+H��:�.%��^\$�DZrAzj�fLl�7�o�����\0��-���Ed�މyz'V ��Ӟ�W�	Z��K�+�d(A�fy�P?�xR�^h���'���A\0���:p\r�d(V�����d�t	S�FcHȟ��]r�r�CHY	X_�/f���ͽ 4 7e�6D�{,�����<<Z^��j\"	�\n+ƀM�Y9��A�(<Pl�lp	�,>Ѐ�{E9�&�Gh�h{(���Agg8�(@�jT�n�g�Z��Ű�J����x�����@ic��Ջ�(p�'oJ0MnĀ�&���\r'\0Ց��\rq�F�4���)��cL���_�oJ�}5��c�o���|6�m�}Q���4Q��b����[�x�m( �&�@�;�+򘥮��f|I����R�48� {	`���k`u�r`��W㸱`\"��)fI\n��;�8Zj���g�~��AΈ�!j��%��T��E\\�\r3E�j�j��FXZ	��Ay�kH��Xd��gCQ�����΀�0�d����������t�	��zk�`@\0001\0n����H��\0�4\0g&.�\0���\0O(��P@\r��E�\0l\0��X��\r��E��8�x���@�ԋ�\0��^���z@E���\0�.�^��Qq\"�����Y��D_p&���3\0mZ.Pp�\r�Eϋ��s��v\"����0�`��w���,���_�`\rc���/�]x�q���3\0q�.p��q���\0002�_�i���ъ��E�\0a�1�b��wJ \0l\0�1,`��1y\0�9#?0T^��q��\$F6���/\$d�����FD�yJ0b��\0	��W��\0�.�c�{c E�\0s�3l]@\rb�F�\"\0�2�`����\"�7���/�\0������a	^04e��Q{c<�ь�j/_��ѐc\0001��*28BA��\0000�xƔiؾ1��F�5�0ljH���\"�F�30\\_��q�\0�f��T�l_0т�BEČ#3�]���s�ƽ���64_X�1�\0ƽ����d`��`\r�S�_JMV/f����1\0005I6tf���4F����34f����F-���6�d��\"��4�k��\$h�±�#E�̌�\0�6�_01�c@F���/d]X�Q�#G\n���5�g�q��EF\n�m\\�Dn��q��YFv�1/4`��q���4�=�8b�q|�\0004���3�mX�1��e��\0��.�\\��Q�cI�	��.7�\\x�`\"��\0i^3�(籒��\"�Ev4l_��q��\$F���oȾ�\r#UE䍩^9�t�������.�\0�3|r��1�\0����69l^x�ѼPF-�]\n0�v��Qy\"�G��2,sx�Qq#�F+�\0�/Di��q}���8�[6,j��\0cm�o��N5�eh�Qv��GL��H<T_�Q��?Fɋ�..\$f���y�E��C2�l��1s#�E�D�loh�Ѳ�j����8�e�ű�b�F!���9�`x�q�����C�7�hx�٣�Ŏ��7�^x���K<�h���	,u�鱑�G)��;lu��#�Eߎ��<�k���b���\0sR.�w�ֱ�#z�~�w�2|x(����\0001�'�:�v�\0001��G挿�?|`������� .2�X��#�G��8K�@<z�1��ƹ�\"9|j�����	G��/�6�q�����G��s�7�/\0001�b��ߍ��:|�8�Q�#~F��W�4�g���#<F\r�� �2��X�Q�#�Fv�k�7�x�1�#��Ǝ��@�rh�����F���Z;�f��rc�y��!\r	�_x�1�\"�H1���0Tw�ٲc\rF�1 \n8d�X�r���Ԍ��2Db���{d4H��rA<~��1�dBHI�[J?������q�~�k�0�t���#�F\r�#�0\\h��\r�G����Ett���c7�U��!�=D_���cN�\0�y�6a��� Fg��!v1�q��1��KǇ���@�e��ѳcGo��\n/���Ʋ�E��\"�3t`���#cH���<�c��q���F�%�?Tb蹱�d)��� r0����qc�E���>3\$tyQң���E�Cl`9)�VFH�MJ7�f���\$HHQ�� ;�ri�7#F��-F�H�Q�#\0G��!�1�^��&4�vG&��7�g�ృ\$\0G�\rr/�d�R�(��s6@���'RA�Ǭ������&�����g\0k z=�|Hٱ������^J�]��sd��,�\$�1����<cqǦ���J�_���b�G��QvJ���ر��H5��F�p��Ic��[���@�r���vH�%��3D����c<I\$�M.d��r1c=F���.4�c��2b�G.��!�L|{X�ѳ�{I��NF�dx�qsc��ݍ�#�E�a)��#�G����J�m�.��\$=Gh�AN=�s��ŤE͑G�G\\a1�0��H���F.tg8�ä[����Idn���8�F����.T����F3�E�6riq��sF���6�x�r���L�=nFT��od��>�-�3�|�2\$�0��= �:�xc�H�I\"NP\$b��Q�\$F�� �DĂ�����}F�%�?�(����G�3\$�O\$^x�2T������0���R���#�D�:��E�|i/2��XG����8���-�\$H�v���=d�� ��`���:lax�����I���:�X�RJ����R�mx�J#\nGG�9!N���{cI���&�I���R=��I\r��&j:�8��g#�H��'3�_x��b��H}��>7����c��ُ\"&K<x��2���H���\"6@db�뱭e;�)�!�.�]�/�d���m*f6,v��ɪ����L��(q��AI8�7d�9Ttc����UL�X��%H��I*z:�|IXqs��-�B���q^(�R��aq(~e���9J�U�+-eq*nT��>�\$�ѫer��α�p\n�ռ�\$es+�V��I���b��eq:�#]�cc�7r\n�f,gY��TC�%��	�}�\0���\\*�EWP�a�:�E�,&W��p)���xl�M���3\0t\0�/Iip�D'\0	k\$T��F��]f��dM�ȀK\$���H(@�ɔ��(�z�nWҤ�_�Mݔ*�\0�e�lF�^H	W*B���ZPe��֘��R/�dRRʅ\0Ku�,yH)�\"S�XI'��Z�=�L�R�3����\n�'�[k��6@;}R���I����_�)�w�[�� �\n���n����ʓbBr�l,\$v����԰����H����\\���s*����.Qt�B��d�b���@�?3�S�`a@�K�\\.����~�f���)����,?|&ӶK���Z9.�X�+S��|����\0Pʼ��E���e�/�\0V��^K�\0\n-	:��Sز)ת�0j�9TX��B���K\"�ů��²,2�'�2����P,�x���p���Kꗪ����\"�D�#TV��D��1�Ao;ؕ�/9TH%V`WJ<9��aeʰ�K/V^/�Q���\nB�Z\"9���XүM~\$�5����\$0d�I�U���2�^X\n�*�E7I\nV3���+�a��Ii��N�KK�g0�a���z*�V���#bJyMҦe��Z� �V���`����U1�C��.\rF��-j�&LU�p�9s�鹊+Q&1��Rm��ӱgZ���	,.XryZ첰0���3�2�A1�ւ�e�N������(?Al ��,N�ue��\$|r��_%��E05E}�\$���X2�%�Z�e �\n\";<9a�h㶥�a]���8���*�u����L����dR��0����+�Qm.�,G����M��_�2�e�dB��ݸ,�S�2��>U���԰�4vl�~e2��2�eĵ�Yg2nf�=��\$�%��ٖ�Ffa�)����fTƶ�G���g2�W,[����X>)t�A]���R*�&Z��6j2|��\0��(�p	�9� ��uҪ�?��`n��-lZn�!H9����zL��9VLϹy��ݢZ�JhR��g�EfL�U��~`4�Y���x)\$B�QR#ÕS������,6i#�Y��,;C��r��i�&�X��]��\nw54�K�x�\n*&��T���W�������+SлqNc�y��IW��\0W5c��ɫ��&+����Vr�)����Kg����?� ����|�gR���hR�%K��)Z#�5�,ֵ�k�漻`��l:��LsC�[M�UB�6ld�ѓJ������1nl:���j���Lߖ�\0�h� *)�p/��ާ5\\�<9��V��/��ޫ�hT�dj��rMbx\n�]R��W�R� MaU�3=��`0�o��,Z���l��}��m�월�l����mL�S6�\\�tΙ���L���\\�%�J���K��7oѩ��ef�M���oC�Y��v慭NV�4=R��sJ������*h���hn��-m��4��4�y��H�M��|��is�U=����A\$ڭ�i�ϙ������>����p�p��Qf������q,��5s�UL���8}ݬ�٪���#�XH�����I����9U�8�c:�I���f����7�kl�5}��f�LY���N2ް�}&�	i���c,�I�3���R��6r�؉�3b��͍��6>lXY��f�L�)+�S,ى�*�el���U\"ed��\"Z��ږ�6�ZD�E9��%�΂�Y9rmt�E��'.M�[4��^��ɷ�;M�w�5���9���a��v+70l����d%��<��3�_<�lN���(�v+7YRl΅Ӫ]�.��4�I��)��=փN�T�]۹'U^�?�S���7�XC�ũӨ�1�u�9�E�ߙ�k�L;���Nh���S�qNXk;1[����LgpV�B�1_����gs����;�Rl��E���N�T�8�w,���s��1�Pxr�q���3���(��;�Z��	yӾ'{O	_���r�ȪMg|�I��92eL���f�O\rY��nk��u���SN�v9Vk�	�3ǧ.̛v9zyd�)����N�Y�&s\$���jd'6͔�Q<�V��)�e�+���:�ج�Yjt���p�u<��ʖ��3�]qM��Y:9X�S��gI�Ý*�m���C����v�G���R@�֯�jT�=��:�e���(\0_Vn�,?p�	3�'Π���������\r�����|\"�i��gT�n��P皤�\nӔ�q,�Sf�.Y��Q A��A�,Z��eS���sE���\r��v�T��Q�Z�\"p�I�s�UAϛ\0��vZ�}�r��K�tf�P�f9疮�{��^J���ς�������\n0%��NGګ*~l�D.���Ke��6�[,�%����O՘�-�~쵕����j��RO;��@	˨en�b_�%sK�Ŝ����Y����Y�0���L�W���jr�Ր��φ��!B����Pv��fwګ�����M�R2�2�z�4r�h;�#M@�}�\0�|��M�\0�=ځ=��f�-!�6p��g[P4��������C�[5:��\r�Ct��àu@�ۺ<��if��Nu��n[�!u8j{&9Ku�FQlR�i�(�C��A�䮙s4��\0Y��;f�B<�{�嘼R_I�~��6��|MWTA�]4�e@J�e�P|[���r5*���OΠ�Bt�)��%�-\0P�j�m	u�s�}И��Bi^��*��z�0YK.�`[�Y�2��Ы�|�XB�����(?З�.\$�l���,��X�D��\n��j�OD�->_<���֝��\0�������s�h\\����ea\\�\0��e䑙Y�`���7U�\"e��CYT���zt:V9P�_���a�ЕF�;݀\0M�����2�e��HC���Z�?�V��'����}c�Y�a�脬��?Qh8	�0�Q�CM`����6��,���J�eZ�Z\"G�W��u��u\r�>49�K���I%L����V9����։��Z�{VEO�X;�����o�agP�\$\n�RX@}!-Si��R���qz�	��ITH.���\nk\n��\ndϮ�T����>�\n���?�E�`��5D+f�?#z��IZ�7T[��Qs#�D���\$���P���I�	�3��*�:�9YI��H���H��X�0�D�!u7J��m��YB}E������简��r�8Q��\n}'P�S�	Q���������\$��`R�)^��(O�P\0�aK����m�3��\$H.��X�����)�V��`���9 �.�Y��18���eU��`X�9���	����\\Lc�j�IE N鍫��6�W�D�XB�	Z�:�|Ϥ:	E-P-�&���)����*���l�)P�u��y|R���Lh�.p���_*�QA��@ �?,Ƨ�Y��)t�ч�<��P*���j�VuQ�:2\0�L�?J����,TPHL���E%���\0��yP(Y�JZ���TH�X\r	�Q4�hO�;\\�vV�#��T�Ww��\\`��Oҡ��?�JR2��=�F��]����I5TMjI�9�,(ƤDv|t�)��Wy-�]z��e���a,pQ6\$�I-g=%�S�W#�TP�ܐ��)�T&]���X15j��B8���V�ӥ\n�em y���h�*������d�4ς�bd!0��gR�J\\� �Mt��1R\n\n���x�����.�_��u�+Ƽ�;���*4�θ)]�\\�l�(m\"�Q�nT���(*\0�`�1H�@2	6h��Y�c���H_���f�?��a��7=KKde�t�H��2\0/\0�62@b~��`�\0.��\0�v�) !~��JPĝT������������O�{t��\0005���/ீ\r���J^��0�a!�)�8�%KޘPP4��~�H����������\r+�Lb��/24)���GK�e0�e��S1�B�	-0jf���S�wLΙ�i�d ����L��\r1�h�ȩ�S ��MJJ�ht�)��+?L��e5n���|FH��MN��5�j�ɩ�SH��L���4�=T���D��Mn��6Zm@I@S`�)'���7f�z��Sz�x~OU1k����SF��MOU4�p�٣2\0000���7�6�k�#xSl�'K�7�7\nl���xSu�LR7�7�st��xS}�GM7�8*qt�#xS��OM\"7�8�u��)�ӏ\0����9�r�)�Sr��2��;���)��7��Nj�m/�x��ӿ�sNڞ:jy4���S��gO:1�=\ncT���Sͧ����;�{��Sȧ/ORH\r=�tT��Iݧ�O���\\zx4��S�M���>j|T�i�S���O�����~��\$l���O����}t��٧�O��z��*�%�]PP���vU\"��ݧ�K��@\no�j�H�;P�>��1���Fd�P.5Bظ��\r��3�uB�<�L#�<�QPE�Cʁu*\n�ۨyPN��l���\r�6��?K��mBZi�j�H��O2�}1J����M�_M��mD����&�K��Q6��Fzv���6ӹ��Qj��;j��j)�*����mEʌ�9Fd��Qv5eG�ɵd�Ԅ�EM\0+�D�\"j)SD�QҤpZf��Ƃ�mR&��H��U�ہ%�{Rv0m0z��䧟Lƥ@��'���ER�?eJ�>�ԝ��M���I����YT���R�/�Bʕ.�UT��YRΡ�L:�jNԅ��R���L��5ji&,��O�mJD�5,�9����Q����1�hTf��N����ޥQ�'��7��Lih��\rcjԝ��Sz�u��\0n�Ժ�g���9�@c��\rT�%L��A�fT��MT9uQ\n��)��U��S��uD:���j�U	��ƨ�Pږq�*�EڪKSb�l\\ڤ�F���ŪGTz�gJ��H�SF�	\"��Q:�1����;���RꦵL*~EߪoTҦ\\z������:���]Sꕱ����B��U�^J�uR*kE��	��T�Qt��R�g2��Uj��V\$��_��S��mPH�U\\��T��[Uʫ5Jhٵ\\��Up�����V�7a_*����=R�>\0I*����V��X:hU8j�T�KZ��\\:��)j�T��8��	�WZ�Ub��J8�R�=Y�UV�U��R��\\:��-j��ѫiV.��[z��Ҫ��-�{T���Z��uoj�U��3 ��[���>����E �%\\���h#bՅ��WZ�-\\���C�����W>��]ںg4#����KTr��Zʤwj��\$��z�-Rj��tj�U*��W��tp\n�4����'�N�M����xU��X32[x�+���\$B�US*��q�UͪqXZ�}S���x���@�-W\n5�XZ�Յ���J��U2�=\\����F+��V�0]XX�U����0����-VJ��+�/�����Zʮ5sj��D��U޲%b�ɵ������V�%Y�^u@d�բ��W�愔�ŲRk&���YR��\\�ŒRk�Y�cV�O-\\��	kd���KoX��K��/�9�]��V�O-U�<��@��嬥Vγ[����6U�����=e�ϵo�4TݭY�0�eH�դ�\r��9����6�(󮝕+��7�yb�rI �|�\0�:Fz���\n��|��s<�R�%J���]��F�3����j�Σ�Y��Z��^<5�X�IJ��M`�nO\\�B&�r���s��Q�uz��x���	�T���Vw�J5�g	�?v�qF4�9�ӝ����6�zj����OV��\r�u�=�@ʒfT͚����y��	�֫pKaXU9�m����\n�ekMo��5\nhT��ꦦ�V���v���:��s���\\p>��L�:��)�O=nk}j�S��&�֮��~���y��e��ܚ�Zֵ�)j���t�VR�V��s�r�:+a�o��,!T�l�Uϕ�*n��5��\\�U�dv+�M\\�)]B�|�J���l;4��5�pL��ӵئ7Li�[~bmt��Se�\"���B��v��d��@ͧS�4)ؒ�Z���\$)��5ic!������Ό���\\R�*�SD���w\$�9�tS�\n�Gf�Pԛ��ʸ����*�	K���D�Vy��5�uȦJב�\\��C��\$��W,�M\\������5�����k^�V�s��5�k�ֻ�M^��{�u��ϤwFQ��J�H�gWN�k8�����ʉ+�����1br���˕���V�X�]�dL�j�YT��v��6�twy˕�k����vx=�5�h������8�]����˷x\"c|�ufU����\0�ҧ5�jȩ}�Pkn̚Rl��f٪�+���ۣ��>c4��W+T�Do����q���SX���b}}�hn�&<�?�/3��-áh���qn���	�p�%)S�yP\r��͵�m-�f�5���[�\\�=�T�}�y )��Yd�ؤ46#Y>�3��נ�m��\n09h;�4���0��+�a�e\nȃİȞ!�����)�@�x�x}�\$����AF��Ñ�0N� R�	���ӄ�iܥ��U�?���b5�!+׭\0G���w{��Ӥ��lI �)�w-4;p8��ؤ;@\r\n\r���N5�ƅF\\ӹhgPE il0��X�%�)\n��Lk��^���2��<5F��d�I�<�F�j�bM�d'�	�ƲD��Bma������OY�Xgg�8��ZV�%mf��%�F�-�,�\n���a��F�wf��s���0G乑�Z�\n	1�;J��1�\"iP�B�y�C�����t�zӉ���;l�4��ҡ��J��mLX�+lᘪ�{�8�\"�\n�V�����(�\$Y\0�d\\݆6�D9B�H�d%���1����6f �\"�T�J��`/��>�C=�c�쨱��?e!�k*�3l~���i��,�A��z/d���Mo���ڲn�\"ɽ������zTr}eٌ{M�aC�7�fiT����/6W���P����8�Fa`��5��M�f2V]�['}cn4]h���e���Z�ŧ\r��2���XllGa`(����(����\0�����_�lO��f&f�1c8�D{�Q��	S6�p\0�Y�����\0\r�q�3m&*f�;�p�6r^c�ϳ��`ɵ&z�n^ڱ�;D��S�oj^�=�L'g�5���&���Ef&���|\nK 6?bX*�.fψE���~&9�!��d�k@�v\"F�G�x\\�=�E�7�XP2[:��\0�׎��X~��7���X6�4���(�\";B�\n��X��hy��&�Dֈ�Z�l\nKC�������p���`mS�	2�U�;G���8��{��-��WBm��\$F��\r�l&B�Y2\r��mA�ő�w�Z�6�RВ��%d�����_��T�5�``Ba��G��c�XK�\r��\0��gN��\\���;N����s^\n��u����ѲVwz�U�F\"\0T-�,^��\0�����2 /� ����EW�/\0¼��ľ�4;\"�K-NZ���McλRVNe�Z�wj�6��a��ÿ���KV�lN?���jt2���T/[�N���j|0t% #�����\0��`��5F<����X@\nӢ���ZF\\-m���cd2�p5G�v'B�'�7{k�*'�L�A�Z|I�k�\n-.C�6����k�-����S����k�]��_\$��+G�נ[^���z]k��8�\\��F|��?B���^��B��̎|��@����B��zP�W/R?[!bB��k��Ѡ'	(�e:xf�r�7\r_��q�Ma�\0#��7|�Q&\0Ɂ@)���1�뮆LA[Pt�\0���`�6�\\e���zx��S݀vՈπU:�ڱ�T����ϗ>f�\nq�l��+K(|�\\��ѠG��U؋��@(�*�iS�%F�\rR\$��C��L����;�d��ļg�-\$m?�lhʝ��3?P�Y�\0");
	} else {
		header("Content-Type: image/gif");
		switch ($_GET["file"]) {
			case"plus.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0!�����M��*)�o��) q��e���#��L�\0;";
				break;
			case"cross.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0#�����#\na�Fo~y�.�_wa��1�J�G�L�6]\0\0;";
				break;
			case"up.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����MQN\n�}��a8�y�aŶ�\0��\0;";
				break;
			case"down.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����M��*)�[W�\\��L&ٜƶ�\0��\0;";
				break;
			case"arrow.gif":
				echo "GIF89a\0\n\0�\0\0������!�\0\0\0,\0\0\0\0\0\n\0\0�i������Ӳ޻\0\0;";
				break;
		}
	}
	exit;
}
if ($_GET["script"]=="version") {
	$nd=file_open_lock(get_temp_dir()."/adminer.version");
	if ($nd)
		file_write_unlock($nd, serialize(["signature"=>$_POST["signature"], "version"=>$_POST["version"]]));
	exit;
}
global $b, $g, $m, $kc, $sc, $Bc, $n, $pd, $vd, $ba, $Vd, $y, $ca, $pe, $sf, $eg, $Jh, $_d, $qi, $wi, $U, $Ki, $ia;
if (!$_SERVER["REQUEST_URI"])
	$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];
if (!strpos($_SERVER["REQUEST_URI"], '?')&&$_SERVER["QUERY_STRING"]!="")
	$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";
if ($_SERVER["HTTP_X_FORWARDED_PREFIX"])
	$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];
$ba=($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"], "off"))||ini_bool("session.cookie_secure");
@ini_set("session.use_trans_sid", false);
if (!defined("SID")) {
	session_cache_limiter("");
	session_name("adminer_sid");
	$Rf=[0, preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]), "", $ba];
	if (version_compare(PHP_VERSION, '5.2.0')>=0)
		$Rf[]=true;
	call_user_func_array('session_set_cookie_params', $Rf);
	session_start();
}
remove_slashes([&$_GET, &$_POST, &$_COOKIE], $ad);
if (function_exists("get_magic_quotes_runtime")&&get_magic_quotes_runtime())
	set_magic_quotes_runtime(false);
@set_time_limit(0);
@ini_set("zend.ze1_compatibility_mode", false);
@ini_set("precision", 15);
$pe=['en'=>'English', 'ar'=>'العربية', 'bg'=>'Български', 'bn'=>'বাংলা', 'bs'=>'Bosanski', 'ca'=>'Català', 'cs'=>'Čeština', 'da'=>'Dansk', 'de'=>'Deutsch', 'el'=>'Ελληνικά', 'es'=>'Español', 'et'=>'Eesti', 'fa'=>'فارسی', 'fi'=>'Suomi', 'fr'=>'Français', 'gl'=>'Galego', 'he'=>'עברית', 'hu'=>'Magyar', 'id'=>'Bahasa Indonesia', 'it'=>'Italiano', 'ja'=>'日本語', 'ka'=>'ქართული', 'ko'=>'한국어', 'lt'=>'Lietuvių', 'ms'=>'Bahasa Melayu', 'nl'=>'Nederlands', 'no'=>'Norsk', 'pl'=>'Polski', 'pt'=>'Português', 'pt-br'=>'Português (Brazil)', 'ro'=>'Limba Română', 'ru'=>'Русский', 'sk'=>'Slovenčina', 'sl'=>'Slovenski', 'sr'=>'Српски', 'sv'=>'Svenska', 'ta'=>'த‌மிழ்', 'th'=>'ภาษาไทย', 'tr'=>'Türkçe', 'uk'=>'Українська', 'vi'=>'Tiếng Việt', 'zh'=>'简体中文', 'zh-tw'=>'繁體中文',];
function get_lang() {
	global $ca;

	return $ca;
}

function lang($v, $hf=null) {
	if (is_string($v)) {
		$hg=array_search($v, get_translations("en"));
		if ($hg!==false)
			$v=$hg;
	}
	global $ca, $wi;
	$vi=($wi[$v]?$wi[$v]:$v);
	if (is_array($vi)) {
		$hg=($hf==1?0:($ca=='cs'||$ca=='sk'?($hf&&$hf<5?1:2):($ca=='fr'?(!$hf?0:1):($ca=='pl'?($hf%10>1&&$hf%10<5&&$hf/10%10!=1?1:2):($ca=='sl'?($hf%100==1?0:($hf%100==2?1:($hf%100==3||$hf%100==4?2:3))):($ca=='lt'?($hf%10==1&&$hf%100!=11?0:($hf%10>1&&$hf/10%10!=1?1:2)):($ca=='bs'||$ca=='ru'||$ca=='sr'||$ca=='uk'?($hf%10==1&&$hf%100!=11?0:($hf%10>1&&$hf%10<5&&$hf/10%10!=1?1:2)):1)))))));
		$vi=$vi[$hg];
	}
	$Ea=func_get_args();
	array_shift($Ea);
	$kd=str_replace("%d", "%s", $vi);
	if ($kd!=$vi)
		$Ea[0]=format_number($hf);

	return vsprintf($kd, $Ea);
}

function switch_lang() {
	global $ca, $pe;
	echo "<form action='' method='post'>\n<div id='lang'>", lang(19).": ".html_select("lang", $pe, $ca, "this.form.submit();"), " <input type='submit' value='".lang(20)."' class='hidden'>\n", "<input type='hidden' name='token' value='".get_token()."'>\n";
	echo "</div>\n</form>\n";
}

if (isset($_POST["lang"])&&verify_token()) {
	cookie("adminer_lang", $_POST["lang"]);
	$_SESSION["lang"]=$_POST["lang"];
	$_SESSION["translations"]=[];
	redirect(remove_from_uri());
}
$ca="en";
if (isset($pe[$_COOKIE["adminer_lang"]])) {
	cookie("adminer_lang", $_COOKIE["adminer_lang"]);
	$ca=$_COOKIE["adminer_lang"];
} elseif (isset($pe[$_SESSION["lang"]]))
	$ca=$_SESSION["lang"];
else {
	$wa=[];
	preg_match_all('~([-a-z]+)(;q=([0-9.]+))?~', str_replace("_", "-", strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"])), $Fe, PREG_SET_ORDER);
	foreach ($Fe as $C)
		$wa[$C[1]]=(isset($C[3])?$C[3]:1);
	arsort($wa);
	foreach ($wa as $z=>$xg) {
		if (isset($pe[$z])) {
			$ca=$z;
			break;
		}
		$z=preg_replace('~-.*~', '', $z);
		if (!isset($wa[$z])&&isset($pe[$z])) {
			$ca=$z;
			break;
		}
	}
}
$wi=$_SESSION["translations"];
if ($_SESSION["translations_version"]!=578941549) {
	$wi=[];
	$_SESSION["translations_version"]=578941549;
}
function get_translations($oe) {
	switch ($oe) {
		case"en":
			$f="A9D�y�@s:�G�(�ff�����	��:�S���a2\"1�..L'�I��m�#�s,�K��OP#I�@%9��i4�o2ύ���,9�%�P�b2��a��r\n2�NC�(�r4��1C`(�:Eb�9A�i:�&㙔�y��F��Y��\r�\n� 8Z�S=\$A����`�=�܌���0�\n��dF�	��n:Zΰ)��Q���mw����O��mfpQ�΂��q��a�į�#q��w7S�X3������o�\n>Z�M�zi��s;�̒��_�:���#|@�46��:�\r-z|�(j*���0�:-h��/̸�8)+r^1/Л�η,�ZӈKX�9,�p�:>#���(�6�qB�7��4���2�Lu*���/�h�\nH�h\n|Z28�\0���Cz�7J���H\nj=-�B�6�p�ʥ)�:8����1�#���<�����Ђ���9��X�Ѐ���D4���9�Ax^;�rA47���3��\0_AУ���J\0|6����3,��bׇ�x�4�8�1�����O���ӈ#c:9��;̶T*���<Ua/8���&�#�΁7\0Aq3���7��|�*B�=�6ƍ��:��ƀޫˌ3#��f�M���׀P�:���\n�L�8΀���\$D��9���\0 -C�G���8nRZ�\n�\0@����ˇ5�V646�1��@�&\ru��J�|1.m5�e�6��d�4/�B� Z��0ʺsǭj��\$�C\"Jő������.����\$�鈂Q�k`R��_��D���:��gJ\0A�p��;@�@����U�%�4N��4v����\0\n�z5/���6�X��3`Y|�����L�+XA�\\���с=G���D�:�����2�BE����C�]<��@M�W�%��Յ����58�P�t	ٔKFQԅ%JR���ML�M<9T��VcuP��9��[����Xp\r(�C���x�9�v������\"S�0,�����b�R*MJ�u2�߰.S��8�k�B�l�9~�@|�4?ἐ������Kɀ�@gvOKk�'䦡�h���@����L�9C��#BBC3Qu/������Si(Ѡ���a�00�#������jM簚�\0��@q���\nI\$H�\"P��q�a�4ˑ�\"L�2�����@yɉ3��H��|HÙ�N����\\��JG�>�5KJ��1��3��hѺ9Gd�!�0���@\r�x)�{���v�,���ZK��B��P�H�IN8d'�=:X;���-k��\$^�� �ܐ��k#I� !�ٕH`Ƀ�~���2<x���=�U�%�Zc�O\naQ��<���3��8u�:ڽ�n�S����<�q��P�Z	�3\$x�'��D�?\$�(ɒ0�\$Dv�ޗ����:I/�\rV���(\n5�ʔ����\0U\n �@��� �&Z��Wb�7��'�Q��N4�1 vd�Bi�z�R�Q�樣��Fa��(�QT�_N�s2�,�!Āy�s5�X��[z�<'M��TO��QAY�%���y�?\$0�\$�����0a�3��� \$��,�(�x��ģ���66���t�����ɳ��Υ@�fl\\�gmN�MU�.�!Y�@+��t֪7G���*Z�\nd9'K��[K/ɔ2j��xe,�P�\\`�^�+�-&U%��o̓/-h�'�������4�0�cM�<\rU��	~���T!\$	�_�t��S�=��FNۂv��.`^a!0a�r���yE=]��B�Z�\\�,�9��@NQ/Λ*<�2�Z��.�l�LBt��J���gIB�ș��fk��X.ɸ�)c�ퟣ�X.��ݲ��SrZ(���SD����#���Ҷ��b�F�\$9\$��(b7�*�����5i�A�Ý��w�֡�2d�C�,�!�d�]�2:-�\$ΓO���G�\"Q���b�Y��q_m]��~�;&�]ײ��ѳ\rE��6��GK.Q��u�C-���cw7��Xji8���0\"�0��ų.\0���b�W9e���%O��F������̨O���KI��|��C�v�Ӳ\n�v\r\0��V�N���\\2Y��dZ�:�;���yŏ�[C�F�ݹ��9�2��9KB�xK�Oqn�5�:Ҋ�{�پ��9�!eb5�{GMg�Ț���nW��)bC�w����5�|����.���.}(�7�C�A.I�<���;~K�*�q�6���|K��!���QT,��  ^�&�*e~\n~��~���J�˥<�Q�����E��Or0���'����n����}�\\��?ʓ�J>���|�vJ?��w�P�����N�~������7չ/�������Y����X��i\0Kb/��5���j����92\"��N�6.��o�.���.�o,��*�6�)F��Ha���H00d���\0f�u��O֋PX�&B�\0�!)Z^\$����02W0��pF���6p���.��H�0�^N�\n���T�n��N~t���		Ƙ,�W�^4��d+�����\r�\r���8�LB�Ip�tGe�\n�yP�%n����%\0�<D\0J�7�N�P^�0�ވ�2nP��\rbL\"���c\"���cp�QH��#��l#\"�-���hٰ�����e�\r�A���,ޑ� \r�V���`��BF�0thx\r �t��A\"p��vv��\n���ppc\\.��,���Zml��l��*�֏��\"f�3���v4J�.�㎪�&6���.���l�U��t���\n�\0��hrU�*K\"@e\$�: �<B�\n��'����.q��N�X��K���ޫ�\$�5�&K_&��Rq\$�T�F�6\$�'m�'*��k`m���Ϗ��\$���:��bcnN�2��N�\rD�,F0�VN@�%���2�Y�\"vj��\0=�t\0���q�p�\\Y�D�R�\0�,�\0/��-�������\"�-LPDD�\\����\$Ϻ���s'2��'N�3\0\\";
			break;
		case"ar":
			$f="�C�P���l*�\r�,&\n�A���(J.��0Se\\�\r��b�@�0�,\nQ,l)���µ���A��j_1�C�M��e��S�\ng@�Og���X�DM�)��0��cA��n8�e*y#au4�� �Ir*;rS�U�dJ	}���*z�U�@��X;ai1l(n������[�y�d�u'c(��oF����e3�Nb���p2N�S��ӳ:LZ�z�P�\\b�u�.�[�Q`u	!��Jy��&2��(gT��SњM�x�5g5�K�K�¦����0ʀ(�7\rm8�7(�9\r�f\"7N�9�� ��4�x荶��x�;�#\"�������2ɰW\"J\nB��'hk�ūb�Di�\\@���p���yf���9����V�?�TXW���F��{�3)\"�W9�|��eRhU��Ҫ�1��P�>���\"o|�7���LQi\\� H\"����#��1�|�t���\n���|��!Ҝ��'����e�:���\n&T��=�)����������VK����v�Ҩ�2\r�TBP�O�p�6#��oP�hYh�����޳�����\0�2�\0yq���3��:����x�}��\r�cAPH���p_�p�B�J`|6�-+�3A#kt4��px�!��Ŵ�UOL����3���j�Q8���d�d�mWK���Vɣ����n�\"@P�0�Cu���%R�#�;��Ȓ�'g�|�Y�e�/�J]R\n�\"]��6B��6�+�/s�IGʊ��'	j>\\��zlU�H��E*���D۬�WM&�d�F2Ѱ[����G��L��B6�66�^��c�J\$Br)�\"bԇ����k.�l�5<�d��N�wU�;��Ƽ�a�yO�[�k\\��֞\rY�֌dl����u�q����h9��l;�k¿��F�w�D��x@P�!j�o��	��KAߺcJS	@���g�@e�7-V2ɛ�:\r�*� �\0Q�4\r ҆���b�0��33��	:BAP7��,�� �T:�%��X \r��3���C�r�!�3��\"{M7Sv\n�)0��\\B���BU&4���ZL¡�@͊(t�OX XqQ.���HdX�Ю�༗��_�~/���C�`��	@���0\"�5���6�J{�/b��������*��b\\ �\"WT��+���&a5����R�@��8�܈�R���y�U�W�w_��7 `\\��#�PRIChp5ᵂ�I.�tS���X�C[C���>]H��%'환��d�r=��4�J�d{x6:@�iC���q��-��>a1�D8���6������������⍑�aPeX�¶��K,�Ԇ���i?f��jVIijB��ԙ�633C��3���\\l+NCPxٛ�*��(o�F~�f\$l�sC�N( Czăpp�ˑ��f���c\rL4�u�(=4��:��I�h�)� �����h*�3ӂڐт&�r�\$B��CKb&U�ߥx`Q��>(b�VFR���Q�w֐�b�QJ9�9b\nY�V���\$��Q��fH�y3�\"<�r6�g)�6k�8���2\r��f,z΁CBn�!�cHkZPbN�\0�¡\"*��?�]�R̼��F�Q!6o�:�S�hC�}�i�M�*{�bi��%�ڟ\n� \n (ۆ�P!��k�l*��L���Z��0T\n@74��6Vz��wJ�#H��i�GETVʒ�BRlV����P�xNT(@�.H�A\"�����\\V�N��,�)E?Q�a�`����;��\0��l���W��,�)TK�JL�D�%t� LRQ>��\"��P^����ŔԱ6�\r7������DjA�����d�,�š.��i���9�N��/��P�3�a���F*�!�{�b��\0�p��AI'k�w[қX\\p���AK*��񋔱W�=\$���\0#�!�5�C\0P�S\r!�Ti���S����lH�A�R%���<�7H�B��Ӎhe����v��N�`�6�r̬�Q�|W]q'���\$�J��������m*�.���N2Q�MV#��ͳc��D�>f_�Q��Ro�H>aX��)>���C��mk�2�_����c����)�'� Aa \\��!%�Y�©��D��L��d���h�]ixJ�H�,Ӌ���;Pʤ�R{ł}���U����(9�˴.\$��\n�2e�\$&�A��%·���>,4���~��Ym�ޑ�M�\"2�P\\[���WģO\\֑u4�Q��Fw!��\n	�� ��G������}UN�����ǕA	x6<`-�K�LKe\"��yh�<���O���+(h�=]�:��\0��V ��c�\0002\nV�<���b��p�\$��C�v�bgD�e�n����d��Ch!b�0*�̷�IG:\"n�O�~P�B��c�v~#	��\r���e�y��:�xW�6R�f�n�Ble�������\\���>>��A\nn)�~�\"�����f�Є�	�:?�K����\rOr��1�:��+\$��P���˄�\0P:�� @RQ��p5-(w�T�-X�M����\$/�w�^�����R���I���qM�PngM\\~�1�MM\$'�\$zpQ�R���Lg0��Qd|�[C#�:��n�]/\"O�(�+�1��N�\r����\n��~~�0��*[kNa�fԧ�R���1��)��'�S�>�P�#�>/�P�/�/fZ0o��c�g4I��*���H12��\0�)P?��L�Ƨq��d��V��\$D����=!D�<o� !��D����į��Q��0�q��d��r~��G0mN2el�1������)mI)�~f�R�P�Zq�x���!�+Q=��Ҳ�q�:r��P{��9F^\"p�\$���@S�nv�f��B�P\nt�:�B�\r+ppO��13,2�1S1��+��0/3'���P�2R�1�s2�;�`�mL�P���M����]2�4C�IP�6�,��6�h�PF��u4�30a5��c�HI��3�9�U*3E��03�:�v�s�i;\"�1�e;�\"H�� ���i��j��U��\"m�5m�5�4�%;qv�CSA91��S�!��.ETr�:��/B���#�4���'<��@�0#k��/������.�F�k�CNR�'/��U�A�\"�;�����\r�V� �`ց\$\\f�nx������r[�f�J\r��B���\n���p�h�H��O�%#C�	�>#��0�G\0�8��(2nI`�H��&|u��xr��~�s2~\0E7�L�P��S�?�4	��Æ ]Ų8/�Q��=��%%Q�=K��3L�g�fa*����3�����(��0Pk�~Ep��_>PeT�-U ���f4CH�\0�`�	X�QNV�����&lp\$b���W��c	G.s1l\$'0n�T)��(��H�b�	0��hX�`�Z ����� �\0��8�l��:O�~g�pV:c'�c'~�,!\"N�6I�X�\r�n�V7e6��_2ZT��KO���:?��K@�	\0t	��@�\n`";
			break;
		case"bg":
			$f="�P�\r�E�@4�!Awh�Z(&��~\n��fa��N�`���D��4���\"�]4\r;Ae2��a�������.a���rp��@ד�|.W.X4��FP�����\$�hR�s���}@�Зp�Д�B�4�sE�΢7f�&E�,��i�X\nFC1��l7c��MEo)_G����_<�Gӭ}���,k놊qPX�}F�+9���7i��Z贚i�Q��_a���Z��*�n^���S��9���Y�V��~�]�X\\R�6���}�j�}	�l�4�v��=��3	�\0�@D|�¤���[�����^]#�s.�3d\0*��X�7��p@2�C��9(� �:#�9��\0�7���A����8\\z8Fc�������m X���4�;��r�'HS���2�6A>�¦�6��5	�ܸ�kJ��&�j�\"K������9�{.��-�^�:�*U?�+*>S�3z>J&SK�&���hR����&�:��ɒ>I�J���L�H�,���/�\r/��SYF.�Rc[?ILθ�/t��#�\n���K�<h�=[D�V9v��)�)��#1,գ�Q��B�ŤC*5\\ �ʰ��2\r�H�F��uG��#��w�πF�|cƣ��:\rx��!�9��D�b�@�2���D4���9�Ax^;�p�z^�@]��x�7��29�xD��lW��4V6��H�7�x�.6����Г��\\Vd��V�쭰�UN�� l�<��;�ݴ�;N�<��XPb��Ur�\n��7`��J2!�R҉�<?��(�4�!�JyA wQ���S4҄#䃶�s��@�'���զ����\$��/sn�].�76𝠯j��q���%�ƸE0�D��)ݪ>QYi\"��4\$ҩcf�9\0ں~��s�\$z��#��P&�D�)&�J��n���xSIQ>W�\r�@D�YJ�<��J��L!�p���d��u��(c�ۊ�l˅�8�,W	\\����&�	\r9]\r��b\0o���e���\"�s��38��ǵS�CaJ��DL��AB���E���̇.�DH�O46��Z\$@EDA���)֫�PD\r!���j[�+D�:�tu��eћ�V��\$/e�������:.��C�p�.(i��5�LL���<J��#AD�����CNI�\"��2��'�y^�NAF;��\0���8�2FV�p	Z��Q)J��/2�CJ�k,\$T�:���Ks�.e����|�K�I3���q-yg&в���\0�J��A�Y'3*�Q�F@�:�P��ך�^��'�9C\"�c���2D�3(eL�;��aB٘rf���x�S8@��4��Z|�O	��ؔ\n��&2`�Җ�q\r&�:��SPv��qU`��,�\"o�D�9�rr�ՙ�'��Q:*�Y%d쥕��_B��4f��AH@�!�S@A�X�S�)�>�h��*��.e��'�>S���ø3i������IǊ/1R�J�U3 gٮ�STwc)\\/Fܩ�r�Vg�hF��4�@Ã`l��Q@�heq�3HJë`��:� ��:���<4 �c������Jl��{HU�+�%�8(��·V��e�Jw���\n (&-x-�?���R^j���j��@��׸c���,�Hv\r1�3ۦ,���sGa��[In�J(h�銇4���ʹ��7&Œ*Gg��4��sW�gd����h(eO�aY(4�O�!?�'t��2�#��\\w���ʙz�M�2�bē��n��M}FVXTw�J)�AE*Q&sH�j���M�(�P��D5%�Q����d�^��BoeA��V%?e�j@�)�\\�J��t�H�%��ɶt�T˧����\0�[����_;��0\n�w+6Qʂ�)�\$IXd4���E_�򊆅q�k�Ėt�uE�A�\n��ttL�����ui~b��O���]����R�����O��� �K/&��M6��R��KWeFl�ϓ-�t���q��'��Zj����2���wl��n̲��J�%�¹����\n����Uq��T\r��U�\rS�H�Xᰞ�����½	�/���`�<�\"x-}����Ws���B\$����ㄇ\"J'3�����อ���mZV���>�bq�r�ӿU��;���(���eE���!*��K��H��L �3f��B;x���yrRƫWaTQ�f�l�V4����d�tKs�~��Dخ4V��~WЍdv_�܀�J��כ\r��Q�1�\rP��0�?rNdw�<o��>���x*5d���s�R��\0�S��:;kk6^��:��\\�[ߊ���7N� ����΢��	y���ٟ���;\\�nv����(j��B#<��jR�\0���Ŧ%�t��Fk\rd�.���A��s�.d:8������\$l.�7�R����z�,�N�E�W-�4 �\n�� �	\0@ �E\0�G&\0c�6G��_�䐄�T�D%��M>�K,���@#8�hq��Q(.�F�̲%�����q���ìzº�i��*����ʣ�+0�cd���\\0�0���8c���J�-(+��	F������Ъ;�+\r���p�\n�8\"��Xn�\r��k�#�6%�Ľ(�K ��Z�N-fZ�P���j���1~.�*P�����w�j.!</	%ś����z��J��,�F�-�Y)�3� �L�즍Ǆ�\r`4ѐ+�؟EA���;Q�3���gZX��>X&������@Bj�!�B��o�z�����%\\B�\"H�z�,�M��z�:�2(6� ��]�7g�&.�5�ȁMR��.*���g�&n~CR����R0�J�-U(ʁc�\$)�N�pk/Ա�;c�n��[�����v\"�(G�O.\"��ڽ�HQD���E@�b.bj���Br���\\��K*�����ŔvR�P�ĉ���M��Z\\.%(�`�\$�.N��~�ls�B<\0P�Q@T��(�Ue��J��g)�w���n�4�lk(T@�r���͒@�c�'�J�n�\$N/£*r�7p�5�M(�t��)~YO�(sg%9�{5��'�_:s��s��s�}�\\3�9�<�B*�3���5\r�-�<���\$c+d�?0�R�3��S�(��3-���%>�V*���?�6��\0���q8/5T&��\0�y;t51�)=?B�:S��s�V��.31l���2t�Z��tR���8k>�Nx:��}�@{�l�g2�z��|�\$��?E����)FS@�iG.�h��xus�X}B��D)Rl�0�i��P�o���;nuJ�\\�O�mN{<�F�4K\$��Ob�<�7:�V+I�OI�O�QE��JI4yt[�QQOƋ2�6�����C��S^��4�	D�F�A|X�\\�UHt�M'U*Q���F؈cU���%Q�)85Fv�&e2�'\$:ar70�\"�]��.��6M>�H�%8�6S�ES�P�?'��[3��3[��U+P�����)ҋVe�8�[�nC������U��^�aMu�Af�S}�_'� 6��\r`�[n+�\"��Z'���]�BE\\ӫ[��Qv4Z�h��?]��Q��j3	OvN�VSc�T�μT_V�(��d�fr�,0O�!pp�vdږih��7N�PtF\$BP�:.6�K��^q*u�_�q%�3W�i	�Pp����d��֯m0)j�o9uQgB.'�mP*̓��v[V�_��^��V�l�mlүq\n�YVB|iX'�%�l�V3id6� W.ܬ�sS��5�R0/oQs�o����L����-\"]PU]��#��b	?�A��\"�9�p����S�{�I@7�l��\$��]57�ARg273,���R�!��\rf/��	�Q�>o�IB\"�@�7�%=;w��~5A2\0��\0�b�:bb�d�sf�Z+kH����D��M��nh�I�AK��\0�\n���q\r��h�1	?�]I�n�)f|����1P)m����{����X���g�2�~��r7	V�ֱ|��(yT?oy_�����	�'@E�����D��p���3c�dT�a/��N'ZK.��eT��rN4��Og�lqA��0d�b���4Ӊ�%�̧.��koR�)�����Cn�X�YMw_y�XD>xo�R��.��sMryP�)&�+��0�U4,D-v�'�N���G����Y���NO98�Yܱ� ��寀��\r��gXO~τ��)1* %<��i�����c#���,\$����]XNd%����J��tLѹ��I��jM��|cK��ڔhOQ�Ȑ�\$�=������W��N�=�1�Of\r��E\0��		s�0V��}���z/e���U�v5U|W\"#�";
			break;
		case"bn":
			$f="�S)\nt]\0_� 	XD)L��@�4l5���BQp�� 9��\n��\0��,��h�SE�0�b�a%�. �H�\0��.b��2n��D�e*�D��M���,OJÐ��v����х\$:IK��g5U4�L�	Nd!u>�&������a\\�@'Jx��S���4�P�D�����z�.S��E<�OS���kb�O�af�hb�\0�B���r��)����Q��W��E�{K��PP~�9\\��l*�_W	��7��ɼ� 4N�Q�� 8�'cI��g2��O9��d0�<�CA��:#ܺ�%3��5�!n�nJ�mk����,q���@ᭋ�(n+L�9�x���k�I��2�L\0I��#Vܦ�#`�������B��4��:�� �,X���2����,(_)��7*�\n�p���p@2�C��9.�#�\0�#��2\r��7���8M���:�c��2@�L�� �S6�\\4�Gʂ\0�/n:&�.Ht��ļ/��0��2�TgPEt̥L�,L5H����L��G��j�%���R�t����-I�04=XK�\$Gf�Jz��R\$�a`(�����+b0��z��5qL�/\n��S�5\"�P��1[R�]�Ԭ�RW�|���Kk��Z�^H�Wҥ\n�|8��CY�|NKՅDJ���� !��B# �&M��=<���?�!\0�1�o��&��e9�S��;�/�>�/E	CC X�h�9�0z\r��8a�^��(\\0�ل�7��x�7��B��xD��l��I�4�6�#H�7�x�Q(M��,M������є|zE��LPF�5d�E��0D�t���DJQ}��}0�7-�[��/R��Q����(��7��B��9\r�B����O�>g��ې+A�.�^��ԅ�UI��+�S�3D�ҭ�]�TUst}~s]�(���\"k#�6O����ew.<���(Ȓ��@�l�=b�\n�/}��й��\r  ��:�K�~Qp5K2Bض��8�u�)G޹��5kő�vr�BH\n?AN\"����,�[���64{�\0c!�\$��T�1d�f�q\0h͚'YjY��E*� ЦcL���\\ s�D'e��Rj��A�G		��}،G�u�AT�\n5�5�1kR���3S�Z�JѼۋ�\n)�9�,.AK���\$��2��0��?b�)�/#�i�c�!GUM��vR�h�3��O<�u7���2���t2������n\$ҕ�p��\n;�}�@��0lf\n%�\nbX)�8�AP7�w\0�� �`:��|�� \r��3����àr�!�3��({�e�:��P�L� jw�h������א��\npT<)��7��>��M���Ʀ��i�Ū��֚�^l\r��6f�M[Xrm�����p@��8��\\:�0e�M�����q�-�%��&���A[�ʓ�v�@'{��m��\0��1�5:��^ܛ�QMa�8����V��m������(wl�7&�\\�sp��^l�&�Chp=����J��� ��)�KTC[|PI�{�Z�m�b�C��R�*��u��w!�MY���1���3����H\r�41@��)u�y��3W��?��\0�������h<��0�@@�=�\r/�m��F�%AQ��^���TI��@\$u1-�,�6C�nR��(.@�=��Qk&��0Ŕ����c�����{Oxey�\09C���L�a�;��s�>M49�6{DQ�o��8QV��ڃ���1��TC;]V��H�S��+lnD\np@R�\rE���pZ��r���eI�H�E���岺�MaDX(�J��YQ���S?gdVP*ǈ�-��Q\rX��p���zۚ\r!J,�*&)Є5��?7<at6��9�\$���w��:�\$�����|��q�!�>�d�i��f9	5:��N0Pg�D���P.3�'�0�C�Z�-7lK�%�ph�B��K�&\"�ͽrZ�G���������LC#W����X�ȹm8�|S�V�7����� \nqXlPcM�SE7�m9i<7_�@�xӁvb��E�L��f\"G@�glڻɑ��\\�α�d�ݗ3Q񈯬#�A�F�[4�1��\\��)�FDLH��s� ���'� X2J�B~��ћ���JB^�\n���qBè�Ƚ���wk�l��\$B��>]G�XWe&Jy�YF*���V�1]�o�����S��m�X̹c�:X�ԁ�t�%-���M��Mc�D�Ԩ�uE/��\\��OI�aJ��.@�h�5;�06�ђ���ϥ�q��E�Fϖ�]G�x9�ŤǄW*�[=�Zl�U���WI���iP�+\0��e=؋A�[s���-�̺�~��诊��i��g�=���D]u�nw�Zdd\"�ƌ�\\ǲ}*H�J>���v�p�!N�c��w�F\$>�F����*e\r�����B����ìHN��J�T�`����(�e%t!b����3��P\$+�����G��#�����X3��R�#\rAI�0JW�T���pv �\n��`�\rtM��OI��fnX?E�&\$�F�ǆ)��n^��z@^6y�\n�~z1,��t� �1�P�'k'*��/o}o��>}�����Ϝ�JP1@�p�0Bt��B-��c�*�1bZ1gQ�÷�R�p����:M者*��\$qôΎ��rWA�\n,�O(���0�G�f�4�H���Q���P����{q��p�v��s�P����v�:�Į	�0�d��B�1�vj�P&NDz�x���#h�q�e�¸1��m���Ac�v�ΐq�����#��&Ub�vb�#�.]�(/x�`wO��u�ܓb&�z�'B�gv2l�-�b5OJӧvR#�s��t,���Hi'EUd\n&�� p`��U-��w�x(�]�\0wo�J��%�O%�\$w�\"7'��!��_d�o-0�%Ū]���010A�����W��2�[R>J��2�����4��#7!qM.rW4q���6/�5h�F��%7�M7�f�n���d\"�H�d�l�\$�V�\0�`��	�#R�&.�vKs����*��(�V�0�s�����jB��{��Z��\rC�/?3;6�K.K�23u5�z_P��,�~�V�2�\\�@|��*��(a�o\r���b���3S[8�K0t:�t>��q�.�8�-7*�8�P�TԨ�+�,��	F�N�R�TV�1I;�I7T4�S�\0/�%�U3X{t\n�sHT�Q�%���q�J�SI�|xI)J3u.E@RYIq�LrKNws5>0k6��@ц.17,��)6��^��]�OS1)��B��O3&(��R���P3�5���5H�U\"���	�C5r�D��F��T���!�L5EN4��D0�(���G�}4UoK5r0iF(�H�CrE�=m+1� \\���N۴=Y�.R�-�j�1M�ܭ�����[��{�>�5�Z�S���Yb�}TՐ&M�1�!�8���%�,0�T�t�2�\"T�G40���9\\�eAe=RP���IOBS<9��K'dt��Mq7�U��W6@`��DN�D��Xs`U6V�y8u%U�Ttvn�2�-���/e�T�YfT%�����t�C�VԙW}iee �!g��jSwe6���:�Gjj�įv�ֵ�kl��wp��ܭ��+hA&��/���Lq���A�,�)��,s��/��f6�X�}qS�s���v�d��8�)N�IW��g6�aH>HIr�id���<}�vS�)�uo@f)u�|�g;tQm�XܣWu�0vV�awyX0��.�(�HcB�|h�RN ։J�Wr�	�\$U,�3K�z��Lh�W�fr_{�w{��lw1uU�|�j��|D�}M%}�Svג���zw�xo=)��u��%��q�`��%N�%�\n]�׀W5~LV8aղ�V.�h�.�[W�kx��3�3XJ�]Rt�2Q��mVwkM��k�G�� #ax�G��QX[�%чxeM��v�H�m+p7�.���V�l)Y'�H���2�DxUD�n���_w�x�AQ��Y�����q���W(cd\n�qv5bsJBV��]<�1+Y��x��t']5;\n��mWǆ����Rяu�;��TY�lX'��.�2D�\r�L��AO�2���6��g���2���	�5\rT���j\r�V���`��i�����������i\"��\r��O����\n���p��q�D�.�[]��:�)Su:-�B6s��X;y�Ra��4+�!������Y7��|�v)\$��Ј���7�\0��9�2v��\\XÈ�MZ�r�t#0�O������d���\0%�~P�L8�w��	�\ny���#�@\0���h@�RLsi���*�����BR�gWR�x��n!8��;Y�gZ�8�(��6��yف�ͪs�B�,5%�����)]8�R����i�>C�<l��ߘ@�_��^Z�6C�կ���8 =g�\0ӟԇ\\n���C���1�kY\r\0!�%<(|qGK���_S�@�g`����N���Q`����b�<�d��\"�ZB)��/���k_Z�rctY[�����c&up��\n�/8c�eM� \r�@��?N����{��p�D�n�,DV�����	\0�@�	�t\n`�";
			break;
		case"bs":
			$f="D0�\r����e��L�S���?	E�34S6MƨA��t7��p�tp@u9���x�N0���V\"d7����dp���؈�L�A�H�a)̅.�RL��	�p7���L�X\nFC1��l7AG���n7���(U�l�����b��eēѴ�>4����)�y��FY��\n,�΢A�f �-�����e3�Nw�|��H�\r�]�ŧ��43�X�ݣw��A!�D��6e�o7�Y>9���q�\$���iM�pV�tb�q\$�٤�\n%���LIT�k���)�乪��0�h���4	\n\n:�\n��:4P �;�c\"\\&��H�\ro�4����x��@��,�\nl�E��j�+)��\n���C�r�5����ү/�~����;.�����j�&�f)|0�B8�7����,	�+-+;�2t����2������Q��9��l:���br����܀�\n@�>��,\n�hԣ4cS=,##�J<ծ��AЀ�1�mP梌�oP\";�C5OB#�'\n��\0x�����C@�:�t�㽌4����˘���x�	B����Jh|6�hB�3.cj>4��px�!�=/��	@��B�����è�:!�� ��`�EF�;N2_�\n�6�]�'\r�O��ԃ�#�#P����b�d=����\$��@�8G�`ܿN\"������8�3#��2�[��]��#���ü��H������,M3Z3����n���b�Zh��:��������\"����9�ק�ȸ�ɡۂM���N�67kN��\"d9.#H��:'zRS18}�`�H������s�#�6_�5Á��P�؀�ʼMO�\"r��b�v=<�?��-�J��_����[���vW4݌�\"6��O�k�����B�ҿ�< �uGr�ip�5�Z�̳x�7��2�%�~£�@o5��7�N����TA�� ��(sV�z5��QN)�d �I�p�\n�)%�T��S�Q�2�2��ΟsV��(T��H�3��C�\r'uJ�s8���X	b,`�R�Y��g�pOϢ�[\0�'�3ƹ�I.\n��P�\n��r\"[����j�NJ!@�и�.�O�VkX����̠.V�� +�~�V�X�&(�Cr�}9�ȕ���Ip���H���1���8���%A���˙~�p��T@��0r=!�ͮ�\nj��,!\n~(��X` 0�����f �UM��e&	,��V���B�a1��1�3���Iw}�@���BsP#��#@�,��\r\0�(����9Uj\nX� �4K��*���3Xh���4���VmJ���;Ǖ5̙�5qyi�35��n�9-TN͘c�f3��lf��h(�&�/��aL)g����;S�;h�kf0u%�ٺs�I�kTk�6鄑��g'�84�2��#� hlޝ�F��I\"���xwDe�n���ՙ0�MR��\r�U��M�Λ��5�* ��MK2e�@�T���ʔBG�,+��Ш3�@Ѣ2&�x�\$��\\3�\r����>�\n�n2k��V�\\�)��ja�d�i�B�\0Sq���=�J@B0T��E<)Ӌ^��I�ܵ����l��ą2��٤j�@'��@B�D!P\"�\0Ph��D�̙�eF�)W�@�-����%ʮ�o��&��8�VJ�/-�5i�Ժ[@Stn�`'��t�j �l�S���tE\0�3��Nu�v+'��.S�M�ż�?�7�K�nv���P�y�Bix-&��\"��][����A�����k��P͖��	�=��\\�/�4�ӆQ��L���ML����x��Ɨ�*;\$ ���So��������e�z�qG��\n�`�K<�h��c�z���4��c.eQt.�5�P��;2�(���\\�әaN�5A��T�:gN!��H��>!8��K����Hx�����C~�8*!Ebf�))6�-p������0<ݶb����O%�Gc#�ܿ T!\$\nC��\nm�2���U(�͠�@���/l�FK�H��̠�Gt I�Dw#��ֽD�S���щSk6��HU�ܓ��9����\r܅���y10\\�����	���˙sN �9\"�s{\0�d\"������r8K����#���N�!��g�srO��7A㽂�s�Ȉ3�\\���s\"Z�y��}���ƈ��(�|��<8U�cl;愓�R<)T71y7����	|sd0�t��Xlm��x4`���3EK��_��^�Q��Y��1g�: ��%/��C�Um��,\$�S\$M�u~Oq���p��<ѫ�O�����bs�_{V�\n��Ն�9���`vx���lB��k��\"Ҥ�(���#��\"������h�P0@����\0	-`�C�3g����G^��te\"xe�\0�n�'���C��)l���[̎�O��ȂL-��CC�enǰd�O��C/�o���%�b�O��2�\\O����	Ǣ��<�F�M���t�NHM�'��bL�GJ\r�Q��P̠��\r#(���邺0��Mb��*�P�u��̍�C��(nH�,������'�}4���������\"i�z�&Te�[�3f�>�4\$JHPꛍz(�N�Gh��EF� \"��B &#(8�\"\nY)�\\l|\$l��J�O�16My�������lE��_���DO\n0��h`�(����1��q=\r�\$�Ia��kf�8����wQ���)��ܱ����~օ-��x�� �q!�~lb�1�>\"�V1/R���F�@]�b1�h������2���	��%RO��!&���0��&��-��2��ۉI!Rlorq(`�xT0S�NI�>�Ą%�pl���\$ɍҬ/��%��'Mj!ǆ1��g�\n�Cr�D�&Nd%��/�^�&~(��=\$�.`��Q-Q�Hv82[�?/s/�\r2��-�/�(��31�&j��7��Nb�R���]3ci3�9�.D;3��-�>	���%b��j�1,:'0r�A0H�7o8SbO7�n��?��E�(�n���p�hE�A�Bd#k�~�c�Е�~�dO;��8��#D���� ���1d�\r�V�V��)\0C�\"\\�M\"PB	��\0�\n���p{�s��j2�X��q\r�%슬����+t\"�p~��R��6��i\$��f��|��*+#��13DC�:/ik?O�3f\"��NF��1M�EI�D@�=0�K\0�H%M���B�+,I��d��>hm�\n�ȭ:�O��T����������{s�e��Bt�Ks�ϝM��8���0�����%e�`�1*�k2>t1�3q��L�K���PW'pnpT.�z��0\r��D�Cm�2�(�`����t�CG��|'���2P�\"#��lq��'�-�2laF�G���?H�M���c.�.D��� �Oc\nDA�V\n���:�Ğ*�";
			break;
		case"ca":
			$f="E9�j���e3�NC�P�\\33A�D�i��s9�LF�(��d5M�C	�@e6Ɠ���r����d�`g�I�hp��L�9��Q*�K��5L� ��S,�W-��\r��<�e4�&\"�P�b2��a��r\n1e��y��g4��&�Q:�h4�\rC�� �M���Xa����+�����\\>R��LK&��v������3��é�pt��0Y\$l�1\"P� ���d��\$�Ě`o9>U��^y�==��\n)�n�+Oo���M|���*��u���Nr9]x��{d���3j�P(��c��2&\"�:���:��\0��\r�rh�(��8����p�\r#{\$�j����#Ri�*��h����B��8B�D�J4��h��n{��K� !/28,\$�� #��@�:.̀��(�p�4�h*�; p���p�i{]\0�RL\"r2�qT��;ÇBHPu&�#p�3��Z��&f�R�M,ը#���P�2&��M\0�c|>�D\n0�c27�����X44�{WAÐ��������D4���9�Ax^;ځr?R��r�3���_���J�|6����3.����x�B)@�X�+�7�Bj/A�`N����:!L���%l.�5�7�}\"�1,[.����+��ui&�� @1-����Ly@���ڽG��)@K��F�l�=W�oB�nx�3,T\n;/c��L#��T���I���V�.!��(�20؃��zR6\r�~�'�N�!7�Ct�P��Z�oR�d�2R�D��KZ���-��V�22�\0�(��������+�RC���4ᴎ9�+�1������J��8~Oѣ�,x(5�z�h��B(�WLL�e�i8�D���[�!y=f���Ӕ��Ƽj9�N�qʏʓ}Q,N��	����h�0MJ���x���81��>�̀T\r�p��򆌓VD`3����C)���K��>̱R���\n�)�a���>O�?ܒ�`ϑ]��\n�@���rC0���bL�L�YK1g-����T��l����z/K�, }���^+��BF�+��4�#�˪�:*9H Z����_���e��P|k������<KY�=h�5���0��y���W1BιPF0|�\n�b�̚�ֺ�R�S��@�I9.��N\"t*+H%>3�t�\r�갋��]8��GJ\0r�e	��P2�R�U���B��ш\rt�#6�hl���TP��3&�(�FW_z� �@\$�x D�jE�q�GC��W\r`i5��B����*#Ͱ7�y	\r�ەF��9��e���\r�,	�\0c\rԻ��Nnfٛi\$�N}�S\nA&�@��p �A�9>s\\II9V]�)#�Nf�cj%�\$��6MI�=Q&���@��&�D���\"M\$�d�H��i�F̣� \$�����5!>�����uF�Y�~F��O0�¡R�؟�h���^\nR�a[�L�N3�tc���N����:���洹4��yV����g0RG*Oe<ɉ�8�3WU&�A0P^%%>�`)��Vԩp��\0U\n �@���D�0\"���SԔf,�������r r��#��8rA���%D�A�%'\\�����S�_���:#�xhI�2�	�/�d��i7�AN����x��zvި���o�\r¶@<���ަ%_��9;�X۔�AY#�\$x@	\"�\nĽJ�4X� aIL��i-J|���E�B�h��#�#*Y;��7Q�#ע��zi���f�\r\$!�<D�ʽ�qOp��q�q��ɍpe���j���nȕ&�wSRH+z�fX#(&�@[�s�@ �ce��B��i�D��Y��(��NG˫�\rͰ0�1O-`j�<\r8��\0PRCqQ&�SPH�hDz���!�����T!\$J��]�������z.�O��;N�\"4� �x !���\0���c���5@���pcCd|�[BI�e��0�Ѧà.;�>�r����8�0�p��L� ��1`�ɇ�6����K�	%�e���r}�I8�2��)Pp�V�Q���D�s,��ʦ��dȞ�3��� \$D����I���ˉ�R�&�	�j�pU�N��X�d`�\nq��i�bP��zL(�\\��&�9�?.�;=W�&�l�� }��rI�!91d�s�Ρ�Z�\r��ÆiVb\n���5<wW0�����#�љ��K�L�\$����͜��c8o�q�S�ޅL��j�e�%�ɍ���.��q�m�R�!`��!�0�D�cd��J��JL3��	+D�W�����G�.�7�X�)�L*%DV�8lf�\$(�,fd_@Pm�h%���RI/Z��\"�,T�,,Nl�@ܨ��%/�l`w�d(o�0���BQ+�j��~wLGLM,>�Rv������d{G]Bs�=�pG�P2g�5\0��b��N0{�\rO��P��&��=����@����H�l܍F�e=����F�Ԉ�/PX��P���\rP�&NzmP��N.���ct�̅6�2d�%%�9C�H\0�C+���Q/K��@�unR�P߃��✳%�%�R	�Q:F�d�@�\r-J��%�eL�G06I\r:�-@_K�\0�!/}e\$b(k���1�ư���bP�v,_��p�&�����/e��v0oM5\r���M����pc�l��LM�ʣ&i�L�k��nSQ�	��\r��\r�J�� �&-b�t���̲a��d1�~ű��2��0ٲ(���9�}P�K���3p(R`�;�\r��wB%�y%�c�k%�`R\n�/%�}&2:��{&�~M�p:1 �\$)(-6���ތ\\ղ�)�<ɉ��d�'R��2����f`R���\"'U-��(�[.Mb\n�\\Y��S�H����/�.ҥ0��\r\"b	��`��2@�eB�fҔ�h?�@�Y\"��E���<��3��`�\"�qM�d�Ơ�H/��4����n4,:��le�pN� �Zd�\r�V��V�Zi�������&.�|`�B�\n��@\n���Z΀�V�X�'�4���Э7c�x�<�J�\$����� �P_*���s�P-�%���@y�=��=�d�c���\$6�0�u\"L*�ib�- ��&L]*6�!C\">;��1fh'f��zUDHl�Jᬂ����\0�/>6F3�\">��B�j�F������G�yH0J>�UF��1Tp\n�| �5\n^�`��&���ԃq�e��(�2�i�F�UMcC0p	ܳfo�p\$�\0dJ{\$0&�\r\"i�G�x�Fd�t�C�D/D;@� �b�u3��  9-/G��9H�~��'�_��R�|PH�� �.�pSm'�G��JD�.�]~@�T �	\0t	��@�\n`";
			break;
		case"cs":
			$f="O8�'c!�~\n��fa�N2�\r�C2i6�Q��h90�'Hi��b7����i��i6ȍ���A;͆Y��@v2�\r&�y�Hs�JGQ�8%9��e:L�:e2���Zt�@\nFC1��l7AP��4T�ت�;j\nb�dWeH��a1M��̬���N���e���^/J��-{�J�p�lP���D��le2b��c��u:F���\r��bʻ�P��77��LDn�[?j1F��7�����I61T7r���{�F�E3i����Ǔ^0�b�b���p@c4{�2�&�\0���r\"��JZ�\r(挥b�䢦�k�:�CP�)�z�=\n �1�c(�*\n��99*�^����:4���2��Y����a����8 Q�F&�X�?�|\$߸�\n!\r)���<i��R�B8�7��x�4ƈ��65��n�\r#D��8�je�)\ncʍ\r��9(�j�F�\$AH̐�P\0(MRD9���h*O����k�P�I�� ��l�=������2Lȭx���f��!\0�2ÐL��~��0z\r��8a�^��h\\0�5T���x�9�ㄜ9��H��J�|;&��A(��K�7��^0��X��n=e#�C{��R#��5��]7�CkH77����`x.޶l�:��[7\\�+0}�Pʁ(�[0��d����cJ:.o2:�(�\n��%ˉ��\"�P��#BL>9���Ŋ�z�`�xЉ��z��Y@�:��\\g��@��F��YCX�'CH�=\"փ#I+c�FM�Bb`Ȉ������`W�Z�i.z�G����H\"��&L�a[�@V�2�̹ZŰN�s���+{{�%��\n'K�(���#�s4v#Gf��EK�X�R\0��;��\$��+\"�S��:(�]Ƕ�WQE�y�w�8�z=�Í1�H9��C����2���ҟ�@_TK=>�� ���Q��f�	��p�R-F9b�v�\0mj�L�10�\\�Ui�M��L��|��CV���(TA��`I�'�H7�B%��SM\0P��[!���;B�H��Y�0���C(#����\\W��+!�>�N����RQ6A�zi��F�5��p��bg�HJoȒ7)�PB��U�A\r!a,E��R�Y�B?-0�V�/���@�%\0>�a�8D,Wr�#�Q���T�R�����%:��MDTΑ�҄j��52F�Q�Bd4D�\n+��+�?j�_�E��V:�Yk4;�����t�[��J�ܸ�Lm%��/(A�E�L�x`�7��`�'qBN�@s���צ`��z�����<*IXAp��:��nPY8	�]I�Hv���Q�Kh\0���TQ^Y��X�)qNa�������xz;IXL��Gd�uL�>���Jp9)8Ԅ�\0�� �4�V�j:\n\n�)A����,�z# -��P�P�u%.\r	 (�AI�UA��K� �\\�y�A�^W�G#y�\"�e(R8�_�3DF^��pI� aL)`\\ 8e;D��.��?�虄�V\"���Cc�DH<�X��K��3M��OӁih!:&�<-=����BO�ֱ6F�~g�Dǭ��)EL�\n��դU�Df�g��aC���^����\n�<!@'�0����iJu�/w����je���<�Hg�FƃR�n�/�+wk���)��V�_\\_1\$�؆�޻\0F\n�\$ߓ�q�\$~Dm>G�V�Hz�'|V�R�P��\$IR�ViM\"�M4�⪰+Hz\0��,���7c��/1F�*[��'A� �Ud����55�LI0�)b�p9��`�I�1�+�� �A�oA�uA��<歭�\"wX0�c\n{�fqu.��w�����#�i�8���|�G�����\n�PO��>��Rj^Z�Ѡ�c�/`�M|a�M�P���u�x������1���Sȑ�&��ń��iK�\n����Sy8�&}n��Z�����5��0�vgg�#�f�2��a�(�t��\"\r@(!��~�M\\��lw���g	|3�t��w��_��'�(k��\"n`�4���2�C���<M�fk��Be��rIv�A��ۍiAF���@B�T!\${�I��#z�\\r\$�LQ���#�k\rl��*�����V����.y��ki��.����T�8��DxJ[tX.��Y�v�hcf�]�<���>dx�����׻���]���1 ;�^u��2�	�˝-2fUU�^l����v̄2?;�������G(\\�=;��>����Y���s�b{H���ٶ㋐T�f���'QDD<��t߂�S	�4�ko����f�f��V��G�%`ES\"��(�\0w���!/X����E�t��XN;?����ukb�F�l�P`�(�RL�8���t1\0�7�:��c#~�C~lz�c��DcN�7��5�J�#�'P>/�~7y�p6�A��_��`�dܐB��&4�w�.��v�\r#4�&\"0d�N#x��ri��jm�A�jĂ�ppv� �`��4��� �'m��L���,��'\r �b��\n�|M�+)��N�����rU��+/�ffj`@L�40�~F��M�P�� �ِtΥf��\\U`�\rF��;���k,G��ڐ�#1)�J\r�^{�~�H�Ǣ'�l{gf�0|`�(%g�}��A�: ���}�8�+Alp4��������ћ��F@��q�4���T�h�Dg�b7�=Qe����O2ތR�n��p�`�O��Ń� 1� ���[ ԕ�L'��� ުψ7��&,�Q\\��d�&'>!�.���,��|x#�#�ȤEjȆh9�\"�l�ebX\$���\r��#N&<��'��\$\$�+0��ޒd<0�cM��(�\"��l�-�!P�&� 0n G�,R�\"1K��^Qx孼a�������{,�s�J������/�I���Pu��7�0M\nz3.�2����C:=�x@Fn	b*�M\nf�<��<�F�9���,�/4�5Q���5�I-�O���`@�F�9��7Ks5^qL7�xkÝ8�a1������%b�g@�DB�C�\$���x�;:��:��0�m	�;�y<1'60S1\"�:��93lR3�C����4�W>��<r�t�u%��R�T�#;r�tE#!t�s\n�q�+���@m�t\$OĆI��.��\n�l�+CA3�5��O�`G���D�EB�O�>���5��`g�2'�GgT=>� �4!�/a`��T��4�����AH�H�5JcGIq�I�:��V	b2���e\rf�9��24��#�m�����\r���4�(CD�1�'�K+@#CWOG��N��e�\r�V;n�\"�e!�W�\0Oj|?f|��D��'2E'f�\$��Gaf�o�&��\n���p�Ѧ���(�O��4�\\�Vmm���qP5t��mP����LB&\"�/E\$��zphb�rtƘB�? f�#	b9��G�H�f�G�&B��(�9[��4��F�\n��/��ld'�\$ҋ�*�\$AcPJ��(2S&v\0�M���O�4�����6��8̨XG�#aQƄ��jc�g�%ct�2rH	,<���{d�#R���l;^��9�\$C�A�P�\$p�ׂ������h��(B�f �'I�U��E�1i����2\"epJ��#��e��C��SLxu�k�a����,���)���Ǯ�mL˦�6\r�c�?k�8k->f�\n6\r�xI�@@";
			break;
		case"da":
			$f="E9�Q��k5�NC�P�\\33AAD����eA�\"���o0�#cI�\\\n&�Mpci�� :IM���Js:0�#���s�B�S�\nNF��M�,��8�P�FY8�0��cA��n8����h(�r4��&�	�I7�S	�|l�I�FS%�o7l51�r������(�6�n7���13�/�)��@a:0��\n��]���t��e�����8��g:`�	���h���B\r�g�Л����)�0�3��h\n!��pQT�k7���WX�'\"h.��e9�<:�t�=�3��ȓ�.�@;)CbҜ)�X��bD��MB���*ZH��	8�:'����;M��<����9��\r�#j������EBp�:Ѡ�欑�������#�j���\"<<�cr���Rbj��hT	@-��;\rȘޑ?�X���\0P���\0P��2(�����X ��j֡�{������0�c��.,��Ob0;�#��7�����o��2�\0yI�	���CC.8a�^��H\\��Ȼγ��z��C�����\r�:0���\"���px�!�N+0�cj2=@P������5���Ta��\"0;\r#(�[��R�Bp�ж�+�#����hl�1]c(����%���-��\$?�\r�|�x��\r#�{�0� �3#��Ў�h�م5�ȭ�4خ�Ä�X��y���t�\r��9���~��1!�I�4��0���cF3�BC\$2@�K!��`Z9�l�)�\"`Z5��l�r����\\�����2ܻZ09�,�'�����3��o�R�p/R�\$�����B*W�0��-��[n��Z�iޢ\"���\n��=0�8�P2�	�܃X��N&)�`��B� ϴ�x�3j*��⎱��7ųh�u�A���A8���9�#8µ���V�#(P9�=���k����8�42I[m��#k:U�U21�#JD@T� S�]P* �2�UJ�;���U�y-�7+�}�OX�%\r(6P��ax�(\\cBAg6�7 `΂A1���\0����e?��B2u	�r�S�z�5J��HwUi���`����u��\n����A6)�A�|�A�,d�����L����7�J���\\��������m@hѹ��Jތ��3F|1�@@�\rX .��,��P[�O�=[=<�\$:�\r�A�6VF��Mᤶ�#\\�*�A锸�>Dc!���s6�H\n\0�T��|P((���K�2��!�R/!Ry0E�T4�@�\\�K�>+Q>�>��*\$d��0�~��Q��7\"���I!�4��&ӱ�42.u���^UH)���F�����Pkי�h1��CBTK	q�afx3�^R�\r�]���\n�酨С2�DC˻/�%>'�=,}c�K��\r���1��ڣ���.TY�\0�¢�Ls���Jf�h /d\rA���\n�����Je�;'�@���Eq\$�j��Zc� k2Dp��\\�äk�y�9H0T\n����{P�r��X��p���y���\0�p��ROn�8P�T��@�-�K�	7R���q�m.����iܷ�S�rɫ\$\r��%\\��EшF\rHE \$#}T���Q\n(� �^!�:D���%����erM��6�>��h�e�).��Lkez��I�Ӣ��N\\˹{*�:��E���pp	D9�E�\ne���Y����*R�tHzC�5ľ�pX��V�s��(����\$������\\xt�DJ��q4�!��IZMb��d&IC1:\rF���5g!h��'�̢.2�-&�5&��ޙkGe����@�:�AIH��\rEG�1�v�>�\r\n�P �0�)ݡ>��ST�h�\$���V�|�l.HA{\0^U��1d%}��)�b*	e�\$�\\���\0O	jgP�s~rZ�D%���V�5tl4:�ZjmFP�ȥ�Z�����KO=���ɫ���[k�-b�v�oV��w����\r���ĽJA�#�|��K����žy-�S��Q����+�P�0�-��H%��lIZ�c�\$��)�O�k\"��I����K9AP�Z�_��fA6�u�@o�7����r�iQ���2��a�RC��՘�sƍ��5�<�n���*J2�ϑ�;x��cEHm��ȎE2�\\9�:\"nZ�����c�n�Nǫ�����@^E��ޑ��B#D��g�Q�K�\"+��7���o�� �����R߁�1����mv�u���O��o�&Kr��M�C΍̻��6��u/a��o/4^��=Y�� �����]E����} �Bc���[��N���\\�efg��1�ח�2�ɕ�}��o*��\\�o��S#�Pqj�Ll�����ec���2��3��4��ȇ��(��f��o\n\r�28�, ��7�.*\"z>#;\"�)�lxOʳ/�%\$m����F���f�r\\Oj���Zpl�O��t�t�OH���c�t��������\$���0��0q	В��F��N��\n����\n\r8�k`�R,�J�/�lcO�	I�:EO�N���o��e����	om	��\\Ǣ;�)&�?+�/��C��41d���&:��PB'��F�\0��)�X����:\rq?Ѕ;��\n��k�B�G_F�1>�f\r�EJ�1O��&�l�ˑu\0��*��}јC���4���b�q�̀�Б�K1���Q�\nd@;q��i��3C\r�������p�Uc���Q\"�ӱ(�I^�e\"�m� %(D �\0	fӢ�Lb� �!�:]�0��׭C#��/�ـd8\r�V\rd\rmp��b����Q�\$(�f�PW�J\n��\n��K�\$�j��h� �M�(��(B���@��V��n��#X�\r�&n�ECz��6��7�Zj2T�\r���L\"�Q%��٣£�E\"0r�}.�@hB0K�	|#>cK�F��D\"���Af���ǌ>�i��-��q2J�2��1�Bl\0��s\"�c(����54�2�B�Bb2+�)S2k���^�d�k�#&����\r�o�i�>.~�i�8�H-��bB�Bt��;��5��)�-���F�l���'�U\"�0��3��J»ܹ�`B/�@�-�J�e;��0B<k@��`@-JB��b\"��";
			break;
		case"de":
			$f="S4����@s4��S��%��pQ �\n6L�Sp��o��'C)�@f2�\r�s)�0a����i��i6�M�dd�b�\$RCI���[0��cI�� ��S:�y7�a��t\$�t��C��f4����(�e���*,t\n%�M�b���e6[�@���r��d��Qfa�&7���n9�ԇCіg/���* )aRA`��m+G;�=DY��:�֎Q���K\n�c\n|j�']�C�������\\�<,�:�\r٨U;Iz�d���g#��7%�_,�a�a#�\\��\n�p�7\r�:�Cx�\$k���6#zZ@�x�:����x�;�C\"f!1J*��n���.2:����8�QZ����,�\$	��0��0�s�ΎH�̀�K�Z��C\nT��m{����S��C�'��9\r`P�2��lº�\0�3�#dr��5\r��Z\$��4��)hˌC�/0����\"�눡D���h�B`�3� U&9������`�2\r�\n�p�CT�v1�ij7�m�B��4\r��{Ԕ��և�B���D������x�m���Z��pP���}�R�!xD���l�O��F4��^0��cݎ��5)�C��:����C3�+�++C�@� NH���Rb.��2��{.9�c�+����9���<��HK�e�k؝\$��Z�M3S�ÁB�7�p��R����jzP�-�.a)�<�B3��ދ�T3��(�y�NP��1���1(����K�ͅ Oֺ773~&2��و%�j�@:/�z1��آ&�^sC�~ۺ\$�u24�]65��˔�'<�Sϡ}\n�6�e	C\riwJ0�MKW6�#mk��X�<v�׊07N�u<�5�F���v����4a'�w��a�oZ�i��=s���Wh�3�e\\��#�����t;+^�?%c`ބ�\r��+:v�B�p D����4L��H	� ���NC`./؏?�C�g�O�	�D	�e� p��Z'�f\n��A2����'rt������?��J�^A�2��ȇ�\$:Uk}W��S�&@�f��ʴV�CZ�am-ż��r\\dl���Cr��q��Մ`C[9#��{/��b��gq]��p�K	�(\rƵ�@Ț^�2�~j\r��uj�]=�`u�P&2������k[+l;��Yr⎡�r7�8����`�ڇ���n���,�cd���ML+��Ԭ��0r'-h���@N[!3@'P�Ỉ�\n (Ц����\r����r�\n�/D�*�o�ipE�M��6�)!��+5p��0u'o�P�(�d	�3���:|&QW\0����!����N�q[���ت��9��3��u��OJN�,#�����A)H�r4Q���2@^�9W��7�R��m	��n=�eL-	��x�OUN~����8,eβ��h+�|���cZ�3�_�x0��0 \n���ޜS�2D�:d�D�<W_g%���B�M�*�蜓xAI�@ ��=%\"v�A\0F�J�R���qt�����\nA0a\\�C�Km}>�Ю���Gb�p��1�h䛇%Eh�CA3Є�D�(�kO}-@�`�R�I��a�d��c�9G)%-�Z����;���x����9>�V��\re 75��A�5� �.�z\n��0T\n����4i	��5��	���Gv�nd�0���#�ob�Rp`pAr	��*�\0�B�E�E\"����Y�r���5%��3���>�r)���Bxp��\$rzL�N?��i��>'Ͷ:��AE_O*G�\rH��9%���6��`�?�7�z	3���`�8iz,�������Ob��zi�ӥj���dݖ\r�]�7�|pD��f<13l`�5�)`(5���D�K=_��;���|3��t��g\\Z��r�D�L�C�gH��=��f*[�R|6Z/p� Ӣ�\n�����\nb�5\\a���5'uɺJ�\"d¡tk�ǀ�RXuw�_��RT��EC{�S���\"��,t�\$n�b�'uC?6���D�]F��P �0���6f��@ޅ���MT��󫀕xS�3\\4��yb��}������3g����\r*C�,=2�:ޖ�L�!F,	�ʰ�:6��FG�u��ɛ3����h^`��\r�mȵ:���Bq���D�S`�X�b�B(}��o�jP�'����?lR��~��q�{�3F-�κ�,N���r���!~��)�&fѱ2�.6�1LPǰ��F�c��\nI�RS`��d��5�窽�9\\��Py������Ŏ���^��v ����)�>t�8tE��A����Q��I0�����χ�b��#g��#⟍n ��/�+p���-0,��J8���9����U�h��i\"if�kĨA �{�Dr��5�D0�^k,���j�Ph1F�db�v���Y�6ܢ6\0Z\n�n9oj\0P	��,�ɬ�s�\0����4u���c\\\r��P�А�\n�&NL�����0�c�3����5p�u���`x��yp�\n��c,�gΊ�C�\nN:����<�,��������&@�O��5ξ{΢�q&�V��P�\"0�#h'p��l����,ip��P�ό_��c��π�c���\r�0\0�C����T4�>6THB�\0�E�� #ZBi�T������WK&���on+/Z�+� Š�+��_`��+p�aQd�M�'	\r�އF�\"��-�=Ca��a�\0�p�͹!q}þϒ!�%g�\"�����*��'\0����B)�k0�1�A\$P�#P(&rL(�E�&nC%�>�R��#`	JM�Έ�Pg���Ș��j1�0��G\"g)�5�U\"���^�1pj�&�D�J�2�j��v1�\r��2�f�V#g�\nC\"`�k�G�P��b#��%/����Q��n�}&�/�;0��>�/��1R�#�1��ros+��ҷ���33,x�\n�c��H����1�'5�_4275�U�K6��-�Z��%�Ne FsWR)s���!��9sk#2�&`�A��9\"�%�B=`�\r\$R`i\$e A-m{+���2,�<��Ư�)��=bf	g�m�|�˘Ʌ.NϚ;H|%,\r#L'qF�hf\r�W>�2��`0���jj{+�\$�F�tFѶ{ �\n���p4�ޙ�l6�&q>����/P��7=���P\"�b;E�\"��M+\"N�f��&�m�5-i��0�ĥC:|��:�G874#I��4��Bt.%�f�I]H#'�S�>Q�T-��FjL�6:�*��2��#� .l<'\$�S���\\̣#Ib:� �GU\0u4��	��T�NuQ��BqQ�,0Q�\"�����PÔU�M�.(���oX�d�#��Gr+�A\$��\0��d�\$rU�dN�tQ-��##<cRC�����OIOF\r����O�vc#,��PK0����a[��q��@޾��D�2�(~����~ϖ%�*#�I7�/b";
			break;
		case"el":
			$f="�J����=�Z� �&r͜�g�Y�{=;	E�30��\ng\$Y�H�9z�X���ň�U�J�fz2'g�akx��c7C�!�(�@��˥j�k9s����Vz�8�UYz�MI��!���U>�P��T-N'��DS�\n�ΤT�H}�k�-(K�TJ���ח4j0�b2��a��s ]`株��t���0���s�Oj��C;3TA]Һ���a�O�r������4�v�O���x�B�-wJ`�����#�k��4L�[_��\"�h�������-2_ɡUk]ô���u*���\"M�n�?O3���)�\\̮(R\nB�\\�\n�hg6ʣp�7kZ~A@�ٝ��L���&��.WB�����\"@I����1H�@&tg:0�Z�'�1����vg�ʃ���C�B��5��x�7(�9\r㒌\"#��1#���x�9������2���9��(Ȼ��[�y�J��x�[ʇ�+�����\\��FOz���\n��]&,Cv�,������[WBk�4�F�9~���lD�/��/!D(�(��H@K��C╖��=A��PX��J��P�HF[(eH�Bܚ�;�\\t�C��%%%���%�*d�7���2P��u�h�vĈ�,͞����,�FuӼ�4Ȥ���dӇn��@gAu�0XZ�^�e�A�����Kq8�\$􌄗e�ra,# �4���9N���=O�\0�1�s���T�4�sl�;�#��7����OÝA�\0y����3��:����xﻅ�}�LAt�3��(��;�2��\r�,ߠ�(�4�#x��|�5p���S��d����q�YR��`�aoF>RY��D����:�>=c��a��j�v�վ�:P��r���s�\$&rX+�#�ݨ#�(�C��2�>���NyZb�^Ri_x��*�w�)s?Ob���j�31u.�P����]3� ��&5�T�/\$m���Y�\$	(\\:�~w�!���%��vg\n�)eXF�sZm��(FkB��L��aDE�@�?r@'Y�\"\n�ם���H��B&����Q��9F�Y�tN�J��c�u�LTb� \naD&�Đ�`����h��R�xg�J;��rDUq�I���)A?��1���:�T�H#�T^�,�2��V�)J=Ĩ>�N����PE�\"�)�M�B\$H�\"tn��2��Ѯ��\"�� ii�6&��*a\r�c'I�1�(x|a���`�]�a�����\0�]�-ͳ����6�j��3�~]�e����XkI9��\"#ϒ{>%�/����xT<8�F�N����e��e�*gE)\"�!�:A�:Ap	 �!9�K���'�|�u�@Աʠ�T�В�B�T��Q'KE	}S��qYF�Lǣ�t�����|��:%Ԍir	((x�@�R�tr!�V���=g�96P�5�HdhM�6���[{qnmջ�v���c~N���2ã�p`�Xw)ZÚ.�^��	�H�byԝ�ZH�Ϻ�JV�SsSP��\0�ĀDY�6XҾ_��1�����l͡�6����tn��V������3Njط�����a�[\"��)Vl��KG	qSk�0C�Ʌ���#�sP�*.��½tJm�B�!��wthȄۊ(P�]��e�\$=�b�������K�\r`6���Âb�a�2����daզ4��p�l\rᝠa6�ZM�\0�ګ�n�!�6Mr@��I+Zt���Ƈ ���!��j��y���7�`�T�4��@@P\0���\\�ϒ�>ȩ���s��/�!�f��Mh�!�� ��L��u�'��0��ok�U/a���ztk��?4�A�q��\r������\\>\r�1�~�~�8;�K�JT�I�\"�t���raL)id�T�I�\r��3BE\$�����g\nSVs���7W�#�%�Q*�O4d��<��2�%�@F�6Hjb�P��\$Zz�K�j��f_Cv	k^D����{��+ dD���� �M��Cj�53�����R �k0G�'��!Q囥�\$�(�%�@R��¨��T~���:���n��>R6���2�	�-�~�oFd�ͧ���f���D�J*��_Ӿ��lZ^E�vSa�e���`���\r�%�B���S��D\"^�W��D߂@���i3�*��W(��!/����'Iq\"��P�#�/�IH>4gYF&|R8>*�q��T��Xo�)1j����)g�3(�Y�t�e4�1�M*�ڻJ�+�r���	5��S;i˪�g'?#�|�����9H��@\"Q\n�5;q��JJYv�)�rXJq>S��y�����1�bR�Q3��k6t+P���HN�-��%��᜛%�;�8CB��b��L��\\ui�&/z�-�������`'`t��*����x,���B�}L��4�R#�>�N9�XX����\"\rȞ��c�,B��������m�7k֝O����i05��I'��d�B��_�@� �P�O���|�PuP�uЊ��d��p��4xJx�̏����'(��\nZ)D�B((�W������,����#2�cj/f0�-��	�c�ȉB�`P�&I:�\$!d��I8���l���g*��\$x\$\n\"�)�!ix��z���ht �\$p��G�:'H�+6t���c�b�J�d �\n��`�\0���\r\$�iF��\$��f�G�Pg�IN��	�/D4Z���g��@#'�ˢ�\"�X|��T�\"�6�q�&�+4�b�s�?�\\��4�JZ'���P5�� �W JC!D,/�\r2c��\"Q�\"��\"�05r4(R8�I�#�1 '�9�¾�b0��(�Ҿ��\"E@� +�{�Ub��N�p(P����=%�\$b�z)B��*��*�&�r++h���x��P� �%��='2�l���&΢����^0����T���\r���^%φ]PX&��'~�s\0���D%:;���4�e�����3ǔan�Il&��-p4�dO��3�5n�&�v�cF��^XH ���zTz�J-hIp.EP�F�<<3������2��[�8�\r�a��\n��(D3\0����:-ǤZ���>���#��s�!��IG^��*q&�k?�=�?��9�v=�,��\$>�Ќ��@ͳ� G�@O�BP�C���?�?�6���B���8�D�\\bOT>�:\$h(*JI�\$��<)�R��bf�6��\n8�\\ZZ�p�3�H�';�;�%p���t�x/�͐�_O���~�4F�h�~����%L���}��%�,�SE�`�=\0S�CQ>�4YieON�R�a�Z5�	PCEITE�[Q�/���l<�)DS�@h])I����C4]\$��bc�Q�iE��>�6��v�t�R��S.�-�d�+(v��5��*�Y#_&\"�&b�&�(|�O�GW*Wk�-�}'u!�x�7,�oY\r'O{)���n�%�/Q8;#1�PH�<�5FU��\\dP�D)\\�?��S�\$����:C�^��]\0�WQ�WSR�8G֩i��	&C�Z���%/\"�wrvTO�^�wBKb#��.4�.M~��f9�8�C�7�c��-l�'�wL�m�c�E\"\\�d(i(K���\\V<�E0Ȕu�iu^oYQMW���&�@c�D^M���Lv�w-�M�[=�4w�[5�)�)R�U����0��_B��TU!np�mv�%	SU6V�O`��n��x5܉����Ou�O��,P��m�<��aW@�1r5Op�Vu�v�@x�����r	!�Q\0�r�7�\\��B\"�\n�h�wl�:�o�J�K������/φͧ�5r�#WBcY��B��p��Y�����_e_UGY�\r֓p�UW�u��� �u�JC�V7�ut,��~Wat�d�F����[p�5|��w������b�2�h��Ae����Pz��]���� }�σX|�t�¥v�\0�2\"J�VS{�:�xc~���E��X�7ɸC��\r��?BNv�+T1���B\"q���v\$7|�}H�uG��gl�{p7�<8�x��_��D�MoC��<,�H/U{���V[çxܳV��O����+��f��~W�GK���%1C���x!Z�otkK�(XQ���щw�B!�\r�U�6߅���<ʘ����l~cxD7:\$�_ux���'�J%��D��}�]U��r���!j��B��S����['�5�c`V�D��7Iq���\$~�Ϙ#Q,(?\ng����Pي�9���RG�QX���R����'cb��)*�6i�\r�V`�Ԁ֩�StNK�a~�p	dW�>5.=%1��hW�ek��BCAy\\�4�}�@\n���Z��@9�-�?A��5�\$9��B��E�M\0r|�V|��_ȱ\n�FTO;8�m���d��J�Vc�[��=3�s5��6,�QkҞB��Cz�;�ĳV�������ob�NB�͏2�]d���Ԗ��=�o.����er\"!�63�VH.]�O��S�t�r��C��G�`_�sXkB��;Dǔ����n(T��+��R��U�x;>�_Bcj�K�3�;q��tXd�[Tu��G�[���Bl�QUG0�M�5�H=6v�Ne6ʇJe����yF¾I��Nqe�	3ٽh����f�1'����\\ 1z�Co;!G��o��P�c��\";�)���w:Ğ�+YGc�kp�3͎Dw����b��O�����!gH�NU2b+ M���8������|hC�e��X�\r�	jQ;��f�wQ�.qG^E�/��3��)�";
			break;
		case"es":
			$f="�_�NgF�@s2�Χ#x�%��pQ8� 2��y��b6D�lp�t0�����h4����QY(6�Xk��\nx�E̒)t�e�	Nd)�\n�r��b�蹖�2�\0���d3\rF�q��n4��U@Q��i3�L&ȭV�t2�����4&�̆�1��)L�(N\"-��DˌM�Q��v�U#v�Bg����S���x��#W�Ўu��@���R <�f�q�Ӹ�pr�q�߼�n�3t\"O��B�7��(������%�vI��� ���U7�{є�9M��	���9�J�: �bM��;��\"h(-�\0�ϭ�`@:���0�\n@6/̂��.#R�)�ʊ�8�4�	��0�p�*\r(�4���C��\$�\\.9�**a�Ck쎁B0ʗÎз P��H���P�:F[*��*.<���4�1�h�.��o���0x����35����>�+��̩L�!����ʢ�7C�|�&\r���7�S��Tyc*�# ڴ����OP(����2�-Im*�Rc��:Jc�	A#����841�0z\r��8a�^���]T�s�-�8^���\r�9�xD��jܑ-#2ܜ�� x�!�h+F\r��=7��F4��S�7���:�c�*�,\nåM*�0L#߶���:��<���x���U��\0�<�\0NQ�e��F\r���G�g/8�S�\$��%8���_=H+��B �	��d;.x����،:�1-�A�L�i&Q�k4�e�6�9�*��\"��(c��;�(&<��0�Wc�S/����F�&�)�����=7F'.j.)�\"`u`��V�j�v.�RS�KPxn-�)�8��lf\n9��+ցv��)?@=\n\r#L�Ĵ����Cv\"�;،��*���&�k�u�)d4�q��B;�A�#P�ΫDh��o���7l�8��Cx��\"�FG�P��G�J�\n�����pZ�DVK35>�KHsBɀ���KI��l���\n�)&�L����K�i_���@@0�I@Od�*��F�q*C�*U�}���#��,c:�VZ�Y�Ei�P��2�?+uo��^�\r{^\\��0bԼ׬+��\$�#F�\nH(L���[��8I�ނ\0��	��/���.\$��p\r&1��|QY�Ai-E��\"��Kyp?�����`��B��hЌ�\$�x`�P '!�v ���N�����0c~g��Wq��\"5�����7����a,��l�D��Cf��	�8*Kd���4�RQ�p4�30c����Ȓ�S����ȊO̒4@P ���4\n\nP))�0�GC�FGm0����1xf���r4�#�.s�;���}�aC�<0�D�`n\n�����C�0%�ȸ,�TH��mCe��h^�S\nA�b\nyp.Y�ƨ��ɥ7����K&�Qg�4<'\rm8�K\"~I'�\"0�! Kuv��RB�:!�*a4	\$L<��:�)\n!4���H!��\"ŕ�J��zMdv�*jjxO\naQ[�rG� 1p8��\$��z0���x�	k6o(~|ȴ�Lk�5\r��*��a4U��`�[P������\$��Z*P+XH�A'\$h3Y2�V�3��jO\$6�� ��� �`� U��\$-�bn�B	�H)_��C�R�ś#b-�0R\n������!qaA���q�3`1f4ݘ@J�Lxb������sɱ�Cf���ׯG�	n1�\$0�Lj���%x�bF�B�N!�ƯY�37d���ɯԔ)@�C�sI�YE>U@�C\n��6�X�b�Q�8T�y?'�f�TVa��Nt5]Y��\0��yH�gw�#w��2oNR���Y����+d�;��Sj}x�Rv���vN�wK��b���l�eк�~��[2n%����ZN{�a�!��H�g��Fώ�*̵�%T9�b�MCu�\$A�[���w���I��K<�r�)�I��4Wœ^��Q%���/�HԷ���bUr������XCROW����PC�H/+l��`�˙O\r|,���d]�C5�[�<NKC2wl`��T�Ը��1��<�y\"G��ǚ�tG܇\0�F���t�f�U�&|\"�R�q���#c�!����y䌾Ţ>�F�W:�<��t�E`\noLyM�0��5�yD��fکm�C�!7Y�y'+�A�e\r��mE��BׂUICu������0�#h�-��q+X�YiX1��H|\r���̹�Y�c	&1��C2��9�����?���9����zTz~Ĺ�y�����ښ��,�p>	�����������1�u�Od�`p��;ȴ��o�3�%?��#��[g��eu�=S�iS	_Pɱ��r��n�b�lC�P�'\0�`R����l�PO�Co�̂#C*� �|�0*�O�gLV��-v�m,v�\\<��E'r��d�pNwPHb�f	�:y�\n���n6�&����,x�.K,��N��\\d.�0��+��	���Rȏ�h�\nd6�F+����P]��D,f�BC��ւ�8&��,���:ʾ���]B�G �B�d^ iCP�/&LD�pν��\r�p8��O`Y*Pk�S��.�'	��\0�FP�O��1-�ttc�m���k\r\rF|9or�\rI�T��e�=L�z&#�jܪ0�kN�܍��p�q�QJu��h�v7�p�l���fh�1\"X8bJ\$.�r�F>ͼ\r���&�1�x1��O�a��0q�q��q�7�*k��i��p�ү���g �G��;q���(j��\"*���X�.�����r.c�)28�R3��� �#)h\r\$Q(Pr!P��0�\$�%�gR'#�؍�lc�q�'�S(��N�(��S�_&�Ju��(NlBU*%F�y�M�D�6�r�'��R�&�h	�\r�W�����J�ve��M��������\n��2�e#\$ObP.���%gm��Bf���1�3\0�L\0�d�0��D�\"D#'�0�@�\"iG \r�0@��� �\n���p�c�0b�&�;	����	o^�BH���椀#�pmфI�@�l���t/hЏ�e8�ȥф�m�R��!�4�d_e��	�\n0����#��h<'� �4J��jB���N���ľ9��D&2#H| ��.�ȥ	�JIb��t������\rB	�&Od/�,5#<M�x2J8�NM��T#�\$%D0u�n�b���Ocvo�4pL�w�lp'\$M`0d�{d�>#���xPoN�là�DO�7`�Cs�7m!��7d�'�v����,XC��T&U�Du ���v�CM#�����\\��N/-���%��2��H�@�	\0t	��@�\n`";
			break;
		case"et":
			$f="K0���a�� 5�M�C)�~\n��fa�F0�M��\ry9�&!��\n2�IIن��cf�p(�a5��3#t����ΧS��%9�����p���N�S\$�X\nFC1��l7AGH��\n7��&xT��\n*LP�|� ���j��\n)�NfS����9��f\\U}:���Rɼ� 4Nғq�Uj;F��| ��:�/�II�����R��7���a�ýa�����t��p���Aߚ�'#<�{�Л��]���a��	��U7�sp��r9Zf�C�)2��ӤWR��O����c�ҽ�	����jx����2�n�v)\nZ�ގ�~2�,X��#j*D(�2<�p��,��<1E`P�:��Ԡ���88#(��!jD0�`P���#�+%��	��JAH#��x���R�\"��Z�9D����\$���(\\� )0�7��p���rr7�rL��/�N3p�:\"�`޶\"�	Nx� ���QrP9��<?Ä\0��#��'N�@ߵk�����U	T	,�`@7�D�3��:����x�g��#����H�|�ׁxD��jҔ�C2�6��H�7�x�&��F�M��j'8*�~¨�Z��,�j�߲I �����\"����7��_�����@P��7�H�5�P�&�N�,����T:,���.�<8;���70�m�Kכ6?��\nH@P�2�㮈2C`됻��/����AE�ڥi�B|Գ�CF�%���,[2��#�(\r#H��	�`�\r#X֣.\r���p��.H**1��\0�&����;cu��GC\n�\"Û��'�hܿ���_9Qit�b�Ë_���b���Duf(5�Cha��_7	#l\0��B*s���@����z�(�!0m'o����6IJ����� KL0���X7k_�k��Qf�'2������0�F���Q�p�\n@�o��w8�0<���UR��8�@7௓\r!������\n<��0R�N&P\ri��j��(ȝ���@��Fr�� ��z��c]d�*�f�X�%e�՞֌>Z�Yl�^~]0]kxE��v�j�6��u.�{R@gAeQ_ 6�{Cd4�=���\"bC)5&�������px\r؃�~�bJ�Y)f,場���:�Ul>�(�V��x������A��8�[�sO�� *D����z�#���rV�H@���C�ԪCBR�b��V��×9X��oU��*�LLp�ĶL%li�1)|4�C�i&A̚�R�S��x#(P2�\0��hqH@ё�N���\n (N�&�c�J�\"������cf\n�S�@h�!T����� o��6d�*��@j�U�\\�pp<���\0�Y�h8��c��[6\\c�6��.��S\nA6���J�Q��a�s�Cvc�8'K���Ro�b�I�S^�2	������6�	�I\"���*WE�!�!sb����O�f>!�E%AI�hc�&�\$i	�P	�L*רk ]5��nq4���\rFj�Vh��+y.&�4Y��J�@p�\r�C)[\\a�51�[��h�\0Sq���5	�0T���U<f�*��{\"���\$�^�m�1���΃(yKE�R6f\$[�xNT(@�+a�A\"���z{32���4�6�j��\r����E��3*�i�Hu^�H<��BV�pQ�7�ȑ`�\n�C*�\r��� ��/:�Q��D�7U[�1b�����>W�2#�QF1��1&z�@����Vo� n��Z��W��|i��.�t���\r�S�}����hl�� �)\"S5\n�T�dX*�:��n��.e���j`C�!����B��݆G�)��cOÛ��!����*s�4!�;�����o	&5��Jo�Q'%�DLF���\n�L��a���.x��d��73�pQS �0��H�֧%Z�Rk��I�\"�\$���:���@`Շ���^m���1���rxiP\"�`���SKX���q��4��P �0�+�\$4��4S������P��ZiM\"�V�E�W�`n�2�\r��g����Xu�e�%��J��&�� o�]��[O\rk����z�(��;T��b���`;���1�NO�w\"�0���HI%l���a?J%�\"��a;�ۜn]���Բ�_;�����B[e�,�o�PrOU`�gEv�X}t�A7�J570�fMƥc����|���o�%Nr\0��;&�S�\\CƄ���)�����u�HoT漨�e9z\\4�3u�b��q��������9rǳ�7���LO��Ύ�&k���Q����V`��ڄI�?&���J��#�ј�o�t��'VA�z�����C����ͅ�8fG���ߊ2Z	��r(�D�nl��LNruO����#�r������P��l�/s�t'G\\_�b#�Ԭ����g�F1\0�o\0P6��v0Md�\npW�\$����N8��Ǆ��4��!���\$��	��hP��Ղ9�Z��R�И�K�����l�.tJ��mU	��\0��`~k85�ZH�tX�6=⨖�\$.�B��H]�h0�s�����e}\r��\r��'�Ip�c���.�\$R�e\\4\$V��	m�u��]�	�jխB�Z����<jf;e�`ĨaF0�?�aOO��a&a�f!���p%1u1{�N���`�s0\r�M��\n|�n�\nq�8�	1��V����}0�q�\"��slrӑ� @�\"\r����f�� �%�<\0��Q����&���y��\nB��ڐz�PPP��x���`�����i�u\"p|T�����nk�]l�u�!�*vLnߨ&=2N\r�l5\r�%r(cP��d0��R�H7�!�t	R��^��tѥ�p�F9�L�J�o��\"��\$2�\$r�F&ny�,���H�`Jr��:׍bOW����ʰ��-2���%R�o��QDk�w,�{/\0P�\r�؍�Q���E�\$7ұ(�NO�\"�r�)�92���2�on:t	\r`,��\0� &���ڲI��tl0�N5��1�5�\\,�*%6ȉ7qeSr����h	`��6nn�t��7F<dW	M�:o�8���ld�\r�V\rbfb��!�����?����\n���p=�܈BJ;JҸ��\$��e�ЏP�Ơ�HZu/''�l�\0�����M`��J����0Q\"�C��j���n\\J>V4E>�	��l�V#��\\d �_&&�CLi�2���0����d\n��d�+��Af��n����#1�L�D��#t��4#Q���>|�3#6��( `�fN�\$OKD�`�XE��̍Q�wC��i:B����pQK ���O�D&��\0VN(k�\nN2���QF�L�Lfb�N\"d�W9�x>-<���*ԅH�MI�\$40\nN�xC�9��c �O��-CY(��c�3�0��7�j��\$^+�w��	\0�@�	�t\n`�";
			break;
		case"fa":
			$f="�B����6P텛aT�F6��(J.��0Se�SěaQ\n��\$6�Ma+X�!(A������t�^.�2�[\"S��-�\\�J���)Cfh��!(i�2o	D6��\n�sRXĨ\0Sm`ۘ��k6�Ѷ�m��kv�ᶹ6�	�C!Z�Q�dJɊ�X��+<NCiW�Q�Mb\"����*�5o#�d�v\\��%�ZA���#��g+���>m�c���[��P�vr��s��\r�ZU��s��/��H�r���%�)�NƓq�GXU�+)6\r��*��<�7\rcp�;��\0�9Cx���0�C�2� �2�a:#c��8AP��	c�2+d\"�����%e�_!�y�!m��*�Tڤ%Br� ��9�j�����S&�%hiT�-%���,:ɤ%�@�5�Qb�<̳^�&	�\\�z���\" �7�2��J�&Y��[��M�k��Ln� 3��X�K�#) \r*F��j�/�l��N�#�鼩5(�ƌ�Z�T3�C�T��V�2zK3 �( ��k�°�3\rð�B#�o`�bB�t!	B���:\rx�!F9ı8X���9�0z\r��8a�^���\\0�5��x�7�ݺ9�xD���l\n=�46��H�7�x�9Q����5�WU��3d�\$�jB��������3M�<�\$�k�ᐌ	D��D.b�,Ȯ0�Cu���#ɗ�@N����������J�\n􈕲�h�e��'�3����!�_d\n��[KQ�0:zV�j���D���\$D#�Yc01�::��40⿺�n�f�2��y���y��+��+�B'i	T�s���y�f��/�(\nb����*��� �O�w�Աs�T�}�]�'�ѓ7|)ⶹ*GIx�v�ޑ�:��?���F)_?.q?i̤2�jάL:w�J����ﬔ*X��!zja����HsB�\n0@��0n�p��C�t_k%���:�zWK!�:�`l�(�*�ܮ�t���vg�(���i�qG*�\$ӆ H�)x��\r� 6aBh�8Aa�{�)�]z��X�9 &�RWK:�1��AC�>m!�?�-.\"Dh��\$L%q=�E��1׆�y�V0qEa%J�&���\n\0C,(9\0�Oh W\n�7 u��i��p�ƹW:�]k�w��׬�^��|��^��Pta���P�Okbem�d����=E�ܮ�a!*��ɻbt���I�>�2LA�>������͢RĖ3���\\��t.�ػ���^�1�����A�\nJ����8d(ĈK |���Ǆ0�p%j�S���+�\"�H:0���[�hq3T%@�*s0Z��hB�U0�@��`l�\n!heh��3A)\nê�Y�:Ҡ��9��L4�� 1�rI0�*�l���>5(��\"�LM�:2�u��1)�q�%���P	B\\P�wD	�A��0R�q���5����|�r�D�@޶��i=p3�%��P�CA�lR�L�@�!����8 �U�����\$D������*�릕�KH����)� �Q�xk_qf����6Ht�8SMI�.I������&�Q�ecd̬���AT�N��/My�0Qd�3��������xI#A�k!Y%d��\r�jY%�C�C����(U�G@����:�k��#��(��v4�U�dSI1;\$��nIF2U�U�HB�y\nHŒ]���	�G���#4\\R�I'F���;�1=�6)��&�qZJ+��ք�D�J�a�u����B0T��1��6���\rU�!W�U�i]��t�Es=�vPn9TTD`�p \n�@\"�s�y�&\\��a��i�֬3�⦞�?Lh���I>_C�0��n��șS�?D~���D\"k��fky��\r�'>\"R?'��֍T*\0������<X~���2��ةQG��!h�����y7og=]o��a�?4h��r���hGH�*�lyZiQ!���@`u���<%U�W\"�B'�c#�U���K���aG����y>s��M��������Y���p�|A��SoHU�V]Mf�Y&1�Uk^��>W�Bng��(����V�\"I�>%�����%�|�782���֜����TӍ^�IB;�4餣G=�}\"+���y�דS\\+SMRtغ�2�Υ����X��������[��P �0�0\r(ab.K�Q�*��D�����Y���\r1#iQȦ4�X|Q1��)ǹ��2����P~�T�a�G���Cz��h-���r�	_�ƞx�{���\\|�����}˗9/9%:��K�Mr����mJ�nI���6n�8~�\\şw��4g�v����Mə�\r?���	�c�k�z��MZԤ��Lчt�`O\$�㭼J���h���\0�r��&����j�oN�� 4<k���,�͌����r;O�G��ܻp:ѧL���%*��63�l��������0d��Fx\$��4Ђ�d�&�lP�4>����HrM&�v)nj�0��Ljc|�A^憜F�@\r(&�R�������Mg�h�-��\r�~�~�bHvjA���8#����\r��J�a���%Q�1 �pW�&%q*lжk�j)J>�0)�԰rdd��*�h��o�r眺��.�+�Rk��-���h����g��SL�PB��yn�,CS���R�q��\n\$}pYQ �T2èlq@yoR��hP\"vJDap�!!	�R�����\$�1p!c���Nc����F�X���N��Oy!Q<�q1Q.�N���g�Y!��1�!�E#��n����2DUNc��%&6f�FF1>��S��N�\nIh|?a�\n,+��8�@�QN8�4��n'?r7i�\"d�!�O�����\"���ѧ�Q1*��%rK+#�+p�\0��˧>�jR�\r�(�1/�\"�R�pkҤxns.���-��.P�<�Ip�*h�2f�*�jl��J=R�N�2���0��+.ң1r�*�<4S+�J�\$�&�.~��S2ʉsl3c�F7�<rOO6�sҽ.�����ԃQL2jv���.�lY�Jipr���C�q>K�3��TE@r��S�^�FS:c~5��-\"W���hBg4 ���0Dì�3f���.�`7@�l����\n��9>��'Q���\n���ph�x��A,�4\">�]C��\no;��m͙�-��'�[ƻ��Em����B�)R����3o(MFʹ�*���I���N��V%i�9g<����GR�.,8���Ϣ02�'p|��\n,�J}p<&�����T�PF}��-�V����\"8��ް-N�Nr�P�MDr�̂���M�N&iMlY!���C|�VF�D�����nM&r���(}�\n�u,06�.6�C�z��xy��vUQK���G�Z1O�ð&��5'1���J��q�T#�YQ	wG�\0U�U(�Y-Hu��5������T�<�%5f-��>ceHe�'n\$`";
			break;
		case"fi":
			$f="O6N��x��a9L#�P�\\33`����d7�Ά���i��&H��\$:GNa��l4�e�p(�u:��&蔲`t:DH�b4o�A����B��b��v?K������d3\rF�q��t<�\rL5 *Xk:��+d��nd����j0�I�ZA��a\r';e�� �K�jI�Nw}�G��\r,�k2�h����@Ʃ(vå��a��p1I��݈*mM�qza��M�C^�m��v���;��c�㞄凃�����P�F����K�u�ҡ��t2£s�1��e�ţxo}Z�:���L9�-�f��S\\5\r�Jv)�jL0�M��5�nKf�(�ږ�3���9����0`���KPR2i��<�\r8'���\n\r+�9��\0�ϱvԧN��+D� #��zd:'L@�4��*fŠA\0�,0\rr䨰jj%-��*�%�k(%�r`�AS��#Jl�Dp+p�)+��MM��:�BBX�'���9�-t�B�N���##��L҇\0�SH5�\"@��(@4��Ry@s��K�\0悥#�@6�a\0x����3�Р�t�㽜\$���\r����zW����Jp|���c��|���cN�	C'E�s��7c(��H�ܶ\rc��魷�R׶,@�:�*�\0Wlv���膌�x�:�!.2��l@��-(<Ƒ��)&O|l �R��.���H�!�߈�ã��p֢E��3�C>f4ŔU��3��X�	��N� +s�2����)B���(�n)�\"�[��Ж�W\r���-�����ɰ�&�&Z���;�����#�3�A0��q���3�V���q��*�	��C���j��b(��&s�����*	o;�� ����CR��[#Mr�׳|�\\֖�m�0�w�B覽b�E�0圠#��3Ә���J��2���,H�4�CoN�TE\"�79/sE��.����a('!H���E\"�)\$|Γ��x�S\nmɠ��_���\$�?x���~������G_驀�1@@��y�E\" `�Mc0P�AccM�U\r�f\0��P��#A�4�S�i��\n=������V�:-�џu|��X�!e,Ŝւ�.��j-e��y6�\"8%�C@d5Ę�\"�����!t.�jbY� �7s����\n��Cw]�#>���o\n�!#���b��,_a�U�J�Y�=hƸڵ��e�7!���TA����P|�AjS\rᐐTd�ϊ\\C�G)\0��̇�\$n?��@��Ѥ`�0��N��s0��oW)�.�IOX�P`�^������{�����M4�a��䚗�@��t&<K��¥>���P	A	<�-4� 8��E@�S�-��NPV8e.��Dh\r׉����P�<'�~k�G�(��?(&\0ʲ�I)���T���@s�����#����eʫ��(��,t���\0C\naH#��JQ�(��&��z�{24T:�.�qwf�����]ɋ1l\r4���O޸y�a-�v�H��A(�=�]�P�\rǪA05CS�@ ��\r+I���:����W�;��:@'�0�W�����[�Tm�r�q�\rZ�;,Gl�xd7`���\"eʙ�����\$�[����L��`��<0Н �bHD�#[�\$����bK�Md.l�!{���%	8\rA���@B�D!P\"��@(L�Q=�y�O��I��4�1\nz\n\r���,�H�ƨꓗnM�xr���Ǽ�&e��;O�M�Q78�9W4����b�c�T'�����W���!�+��q;:��V!�\$����\"���<2\\��:z6���)Gέ	z/q.�U*h��v-�Ķg\"��U��1��4�i�ZNk!͓.�~h�T<n�Od��&d��:1�Hb�\0��r�����^Csq�l]�*�b�Ω�15ٸ���f25\$�t�8I��\r\$���}�)I��Hhd���Qhy;EQ�3�Y�R����V���j��ːZP���\n�!������q��4���xy��\\���Ȗw�yQ�I�\0^8i�\r,u��-�`y�/�4�8�������\0���8�\$�|u/�?�� �D`�y��KTӽy�mɬ`<+��7йA��E&�i�i�CdA�C�r;�qr*�2�ā��q9���w��L:�Q���'���U�LE&����I��jq��Y|�*���s��EtA;�s��=�~x��^�\n\n?^q��Y��wF���`c�YR�	'���DLv�8�Z�W��8.S9/_��v�_�#ʾ�� ���1D暸�u�}���Qn��):s�~&R/�4:��c��\n~H4����PP rg�_\"�S�R�q	���Y��e��mC�b ���f��Z�A�&�I��fe\0�eFF^�j(&\$n�,�vJ�Q��\0`�l��+\"��� )1�\$�C��@r&�o��(�/dM�h�pV����K\0v��8PS��4I��-�����ɘ�\$�nJ�Ph�Є�0����o�J���<�O��I\n����<`��̪��S\n�\nHi��G���~�� �~�&J4F�\"�ʢ�����Fp\n\r(���d��#�2A��KP����\$|�EhA���L�R@�h=c�e��M��nm~u��#�b{6�mP���0��MX��[bk�_O�z�0��j=�����*�\r�-�!POP-1��g�j%1��,�u��I�@��f(\"`���v\"k80�l.�2�P�>�X��	NH��o��ܑ�Bk��둦0G&#��hƐ9Mj�\"�r���\r!�#2',TO��#P	\0��%�za���H\nr:.���G\$�?CU/��j:��#��1�&RhC&Ne)�\$Ox\n�6\$f���(2lu���M�ڑ��H�����%��}�\r1����2�k1+��� �H�i�E��c]/w�-IK�_����oxQ@�>��0~O@��#v�c�\r��R&�f�b�(T2̂K�p�Ȇ)�\"�D�E�13(<n]���'@pKNK�D��Ha��0jd�\r�V6咈ˠ�p��J�n��KؐQ:�,�\n����p�#(��\n#LS�����T��t���� Ƃqr��b�#�0�#.�]<oV\$\$�D�<�')\0a��5ˢz��\$��k�1+�\"�2�6Q.&Fj�%pۯ���R��yL�-�6�\"��0�Q�T&����N�j)B��)���r�����ID�*7с�D\$�F/��	����xM�%&��&�Mp��Xh�a�M.F1̀g�@R\$Q�6�2V��7-�f�P�MB3-���EJ<3Pa�OD\n���t;JH��R�cR�rI&rLC&/,c\r�`-\n�3d�B>";
			break;
		case"fr":
			$f="�E�1i��u9�fS���i7\n��\0�%���(�m8�g3I��e��I�cI��i��D��i6L��İ�22@�sY�2:JeS�\ntL�M&Ӄ��� �Ps��Le�C��f4����(�i���Ɠ<B�\n �LgSt�g�M�CL�7�j��?�7Y3���:N��xI�Na;OB��'��,f��&Bu��L�K������^�\rf�Έ����9�g!uz�c7�����'���z\\ή�����k��n��M<����3�0����3��P�퍏�*��X�7������P�0��rP2\r�T����B���p�;��#D2��NՎ�\$���;	�C(��2#K�������+���\0P�4&\\£���8)Qj��C�'\r�h�ʣ���D�2�B�4ˀP����윲ɬI��N���2ɦ�;'\"�c˞a�\r�)��KqEÜ�G�J���s �*IK�7Ph�:O.ڵ>�UK=�uC3(J\n�L��6�	�1\r\r���%ʋ���h��'�\$(�S��h4 �1ġ`@'�C*3����t���5�r�%�8^2��{Z�ă���J�|6��B 3%��7�x�'B�&\r�<7TJ�ai������L�S�+��@;@8�����D�,�H�2cc&��P�0�Cu���� @7�h����ݣ��6S�\$�(H��h�����͕9���3��\0��<���#&�E�^b2�3�\n�����>�3��V�1X���%���7�e�*J��:����O���6� ����:�Ѳ�no��V�4��h�bzä�־a�R��(���[ĺ7���1�6@���9K��Bw�\nd9�٘�#k	��y�ϟэu;��a��&(q+�dT��?�1�(��:\"����J�{���K��J\n{䝠�\$�Y�Tg�ux2�BItHH��#��|�3�Dѐ�`�JR�F��RtM(�����N�F�����U\n� jH<��`p[-M�qeϕ�nE����^�ቅ�q(&\$��#0�՛�AMJn%!�#4X�Û	s�T�&�@\\:HA\$%�2H���\$1kCHRJ��L+mn���W�\\�=��仗�/x��|�&v��`��F,S����hPkYG\"�|A��nT��S\$�\">�K����D��B���%�V��\\�r.`�So˵w�<� ��_!�>�d�ea��@��K4��\n\$�Һ�PeCq���1YJ�N�J>?glԣ����'AQ1,�BqA{��9GStϧ+�t1�@���1\r��̬�HO��7D(ʔ��Q�I�F�A?r�YRw3Ч�4r��\0P	@�+��A\0(*�P���:1\rF6����c�(����BO�_W� ���ʉ��_�%��y���\r���5�σ�7&�̾-�XHRn{��7���S\nA�T\nRN�VE��΂\rg�3`&y�\$R�T�ѕ�e�P�A )�<��9nd�\r��&�7vhL�\r,��E�NjMZ�+��Ù2n���u���VJ��Z�(�:N�|qR��}0�OӀ�T'Ŏ�h�RcH Mb�͠cV�	��*F���v~�--�����9��lc!�'�Ě \0䫔�2\n�֛{`�L]/H̷bDR��0va1�\0�)���/��U�e�Ƽ�=�䣐�C���T�(��Bi,\$01��2�\0PO	��*�\0�B�E�8�\"P�q�\nK]K�ð�!�BK\$����TA�'n`���8Q.Ğƀ���Ψ�22�LIBHm0�T��L'�{������^��(�W��c�S�69��^c�TS��C���*8��o���N������%Q�g�:��%�-���\\K*#�L�6��,�! �	rz�f}/37��yV������8��J5�TMS�����tBJ�o����\0���9�)��0�څL`o�0R�鳉7GE�=��Q���;�L:�Ӽ���k�}�RC��1l�{U	ҍ�Y�!i��גe��\n���d�}��)L���,n2Ө~�uƒp����8U�2J�CS�qV(Q5-rGF�� ³D������xH�3���Aa#c�R��(Ct�`��C��7�#=�u!g��v\"c	C+Dg�3��]��6P�A��R�\rQb#�~�J�\r*\0��m�q�z�.��;�c�kY��ę,���񪕐��u����N7.���݇����y\n�Di�Fk�wk��'��������\n'nS�0�,��i/�eh���W�M�G�&Y���o;bP�\r\"�凱\0\n��9�W���x���i��>��WuZlA:J���8ENkF�N@@������?c\n���1���bfiX�-.�ь���w��͊'�3��?��;I�fJ�� �&�*�V��I�4����w���4��|&P��Ʈ2px�g��Ȗ��l\"N�q��f����\"��g��b`���`�,�ȇR'�������\0��FЄ�aR�&�B�V��iL�~�P�27\r��pz�ipӭ\n�]-�Px'�M=p��\r�;�\"ұ'�5	!S��'§=qè\0'�O��y��z�)1Acqj���<\$<�N?�z,d�~�N�o��n���P�`��Q/��\$������1 �4��N�Qw#�?qD!I�n0�P�QO��Q���1�p�kR!,�i�#�%�x��/�S��5-��+\$si�J6����(* ����b �\nj�=��!�!JJ<����k\0�=.<�1!�.�d��1��3M����&:�'.\"��Q ,�IpK��)��+���O����o*�0bM4ddc&�aP�/����,�G�5*a-�x��+Q���r�.�a+�	��.�\\͂��0���HđF�2%&���<\$	��es*q]0h3Nm��53SE��H����\0���P\"F�=��c��+�&+&�5�+�*�6j��S�*t�\r���O)�+3��9#�9r�53?:=9��.SU(�Q:ӗ6�#�'9N��,J9-0:�S�;�	R�;�=���s���NLN�-=�`B�\0��Q*r�S�@��+��>>N�A.=Ac(�GBS��`�.��Ns0lad�@�\$ S�4puR�Dd�D�4t�YD��A�C��?qf汎L���j�����7�J��Fg6v+@�t�\$h<����t��t�C�1抌GN!;4��F�R�F�I��0V6�!�+.JBb�\r�VŠ���V3�����6�ebr'b������m!�b�N<1c8�lh\n���p�Ѷ������f��!MЃSt�'�\$BH�.�-�m�Pu���E[5�uP��R(|&D\n��EV��CX8u\08�p%��\$����\r�=�1�p},a��qbgN4MC83�@�c�B�U�:�*���u�7PB\\�\\P�q��EBG���U�4c��-]�aԥ�S*��_U�J@��SF�öW���<Қ#�o���9^b�#�*�g.?�l�N�76<&0����<�f2o���:\n���,�r�<0\0�(�1���A[��#�������ͬ�+��7&�7�7Y��͕��攔#\r���s�<s�X�%�C�\0�e�kC�P�D\r�";
			break;
		case"gl":
			$f="E9�j��g:����P�\\33AAD�y�@�T���l2�\r&����a9\r�1��h2�aB�Q<A'6�XkY�x��̒l�c\n�NF�I��d��1\0��B�M��	���h,�@\nFC1��l7AF#��\n7��4u�&e7B\rƃ�b7�f�S%6P\n\$��ף���]E�FS���'�M\"�c�r5z;d�jQ�0�·[���(��p�% �\n#���	ˇ)�A`�Y��'7T8N6�Bi�R��hGcK��z&�Q\n�rǓ;��T�*��u�Z�\n9M�=Ӓ�4��肎��K��9���Ț\n�X0�А�䎬\n�k�ҲCI�Y�J�欥�r��*�4����0�m��4�pꆖ��{Z���\\.�\r/ ��\r�R8?i:�\r�~!;	D�\nC*�(�\$����V��6����0�\0Q!��X���@1��*��JD7��D�P�4���5*�*�H� �<���6<�RB�8c��I��+dǊ\nRsP�jT��M� ��eB���@����0[�Co���\$#��(���]��0X�(�͌��D4���9�Ax^;�tiY)Ar�3��\0_ِp^*��ڼ��p̼�*r*�|�\nc*@1�H�qN�b\r���,��:�HK~&��j��5-��b�sp�7Ș�\n�)�7S�P��G�憌�\0�<��HK��m._I���ھ�Q�6Ry:N�<������X�d���<B��	\$���3U�&���*#C3TF�B�͌�!B����4Z�����uK��;�dƦp(����*�n�e\"	D�R�T)\\�\$�ݰ�N�/�\\P�(��U��[k���N.��Tֵ���[׷#��2�S0��}�xߠs�؝���\rCU�bJI]1�(����?η�M8�Ib ��x=��l.�\"FA%Xj���*���q�:�ЯSt����4n-��BP8\nT���b;B#�#����\0kJ�E�g�fPJ!+��Vb���	�\$�	�*� �:\$0j\"H; �!�9fn!C��J�d�����\\T�p�M��>Hu{��.�Ղ�K>҆JV�PZ�Yl-����\0w\\J��.UκCp/*�}GD���=k�#\$h���9P>�\$��W�i\r�&Gɪ�e\nU>s���HlN��<P\\��cZ�em�վ�Wm?1�u?�BEWz�zA��8\0��*�aO8��OBU)�����+iC�!�� �	Xq�0����9	��6O�,`�&�|Ŋ�%���iNCW��3�@�C9؛��͓ޒS�gp	�6(X�� ������2a���\r�\0�(o{q7d�ÈSFl���@����)������\0gKR�AUl���\r��-M��u��GA*�?�U�J�A�:n��UHg[Ȑ�I�ܐ��\n��5�P�FO�P�3B*�LjnG2H0��+�fy8���Li×A�\$\$�v(J�vm���੊j���D�Ȅ*JZ{F~��ò�`\rI�&(���Z��QfUP��L7)88���+@ \n<)�F�[�mn��I����%H���2\$�3sl;H�\$KHA�lȑ!*i��Ԋ�X��rV��c��f�0��vJ�\nd*Q���3W�%P��5^Z!�ƙ��\"����o��\\��i�Bx��节��M�C\n�T ����RM�<'\0� A\n�l�xR\nXG	�P�B`E�x�O�候�@ŵ����P\n	���T�Å�m���#�wa�dj���钯��.���U��{�?�n��sΫLS���`������ĆNЎ0��	��X��C7���߈O�'JN��Kj����ȫ��J�h�?vz����Y��C��Vs�*��t�Y�m���~��6T��߀�\n�q��K�N��D.���V��\"Nb\$iTʼW�aN�\\��@+(�\n�Nѵ6�\r����\0P��D�hgnǉJcۊ���ʓq�<�~��#�l�zR����nԛ�\"���\rz��U\$0��A_�2��!L��@��XY)U��[��\rh�������B�T�! <\"�i�D�@fu\\�jj䃑H/�HΙ��x f�ǜ�R;��+M�T�X��F��g��[�\\Tg\0�Aёpx�&��Bn�Nz�\\�ŀ��<�Q��C�l�HL�G�Z�*�c�,WU��󸠮��:\\�ꏼ�.�Ҍ��p���If�,**��B�|���]�A�i�5�ќ��(9^���볽����@ĉ�7l�%��^���s�G�W����1Ia!D\"2���v=��+�s_�2�7��i���NR׌C\"d���n��c�~�!s����v*�w�L�������K�p�~�~^e<2��H-H4����\\I�L!�l\$�\\}Cp\$�%.��D��02r0^�^#>)L��B5��1�z�p�PVۧ>g�~0�PH �p�|�X�m�VO�fL�ˇj&��+�'����Ϧ0��ͅ\n��L�\nO��o��Gr��d3o�NG�xo�c�;�8x��c'#+\r���J2N&/��f/�,�ꎭ\r�� ��n���T���d0�\n��\$+�/�����D�QC����*B�.�4/��vb��Ğo�F�)�\"�&*bz3��)�6Y↜\$^ #�C\".�ƄN��j�qT�D���j���Ҷ(0�ZC�&De��h�ъ+�t\r��z���фh��2\$��P�����о�A�P���=\"�\"�	 w����M����cp��.2z`�ð�M�',K�ipT����#N������R>����7#��%D݃pl���2`%2[\$2{K\nl��'RO'0h0^vk�\0��B�>��\"׃\0MQ�KЫ)�w��&��2�Mr���)ҭ!�+R���\$�i����+�?��&���җ\$���%+��.�/1�/j7/���P�7\r�/�'�0F�w��!R�.���i�\$�*H��3:��	&\0��Q�rP2��B�ڇ�n�BX\r��(b�4.�5�Z���6Kh�p1�~��<\$�0�277�sQ�h��O��0�9E\nd�\r�Vej\"C6�R�k�KCH\$P�\"����cpO\nqH�\n���Z}\"F�c�O��*�/�#��9G�`���C&��`Cb;_<�C��m����<.�(���pa�C;�F`�uJ�M��DF�<h�ƀ3>(4��\"�2<��(�N���0�db0�ȌC�x�\0�DACH�HMi*�H�P�1%\n��)�\rI�uIO�e�xI��e��ԥh�3�֌�\0,tǇΎ4�prǥ8\$0bU'�A �(J6\r�:~���.��1���cn2��R�l{2,!B�5\0��#+F�d1�(�)N��\n�؈U*)G?H��� �/C�`2�*F�C�7�(qg�O�Xm�+��";
			break;
		case"he":
			$f="�J5�\rt��U@ ��a��k���(�ff�P��������<=�R��\rt�]S�F�Rd�~�k�T-t�^q ��`�z�\0�2nI&�A�-yZV\r%��S��`(`1ƃQ��p9��'����K�&cu4���Q��� ��K*�u\r��u�I�Ќ4� MH㖩|���Bjs���=5��.��-���uF�}��D 3�~G=��`1:�F�9�k�)\\���N5�������%�(�n5���sp��r9�B�Q�t0��'3(��o2����d�p8x��Y����\"O��{J�!\ryR���i&���J ��\nҔ�'*����*���-� ӯH�v�&j�\n�A\n7t��.|��Ģ6�'�\\h�-,J�k�(;���.���!�R���c�1)�!+mz��Hiz�.����D�Zv�GMzw�p�I���Hs���(f�Lק�r� ��h�7;�s��>�����1�#�3ѯs���oh�4��@�:��@�o�\0�D4C(��C@�:�t��t4\r�����p_RTÐ��JX|6�Om3<Ck�4��px�!��9��ړ8��&������)}؃!3�P���T��IRI0�A�p+�#��I�A(�!1�<զ�LO\"02�Ҁ�i���\$�t�&���p�Ni�_��w�n� ����{�PD蹮�S���� ����<O̩�Ā�N��2�#�rt��n�<�\$�w��;G���=ڃ���\nb����\$�\\�5�|�k͉\\�r�7����B7yO#�o��MiT��r̖���a\"�	�H#i���f+����~��n|Kʦ�-|�p5�(�9�#cڥ��7u�{��v(�:X���9�L��կc��601gi2!�B7O�Λ�}�'�2w��l�[r�p:V����P�������2�瓯�;�l���'�l�7a�4��Q��A(@��I�g��UX����V��\\+���X!�a�P^{ݠt[�`BLX�[ku�\n5�981�1���	N�\rq� \$S)�5�PِrvC��&f���^�U��V��]�E~x��X����}	s�\$�!7�G\n��(�A�'�j_��T4��'ǰ\\�?^�\r��t�Y-@�0����A�QЉl�6` G�8���he`�3;�ê�R!�:���:���p4:{]d��V�E��lx4k�BM�q��k�0@@P\0����M�\0C[*1� ܥ�oTa�4�`��<�Tg��:����;rh�H������g�H	U+�n\n�Q�����ph\r!�V� ά����0�S؀�!6@d�㚕��q/��6D���A���H	�0hf\$��؊Z��.A�1�f(�#�\$E,�B��HW���2��@Z� *�#�(��Z��;J���Y{3�K�&���1RM���0u\n\0�¥2#����uL�k�fH�	%W�p#�y��\0[Oz	1��:t˓	'�*L�L@Ĳy8�# �^�J_DA(XX`�R�C6!��h�\\��d�y*��OZɮ��,�/K\rKQ8�\r)��fl��c (Nb�'&`M=�&�rl�Hbm��җ>��BJ��<]d�@h�ٶ䚎��P���Hɾ^���\\=�K/����G��FG\0��D�C�ф\"��\"��R]��-F�bLi�2��CK�M��0Z��+z�������C�lX���I��+�����9/�d����k^�=�\"7�IS%o��#&��VD{l���g�mΗ����=g�׏��\\6�1�B5&g,�bu��Ыh\$a�4�t�H�*�t���<��\r�of��E�bJ�u\$A*@��FH0o�@���H��������������](Bt�Oh�H\"a �F���\"^L�Ǆ��䬜9rA��<�������>�ˉ�j-`��D7L�\nZJ^)Kf�Xh����ͭ�d�X�.��'D	y��¢�� ;7k =�G�j���He\"դw�k2��|�&�[�F����e90.,�������6ӑA/xZ�B��Ϙ��I��09�Q����A�Z��/���S_�� \"�(��%�����p�O)�\\�������I�9m8bv�&���s��@r�i�kU�Tʊ4VCC'�qb\n,�6��]��|�)07D�o�R9�[���}��w�ṇ|��Ǽ�u�`[æ�\$�����<1�>!�����0&/9/n����:����_u����@�zVD/)�'XK����r��m>�qg4�r�[a�z\"���l�Y���o�\$���E�]��>s����݇�M�k�6�'�+WDR{\\�Q���kXd�f��X���*~6�n�����]5�\$Ȗ�J2��m\0\"8�\rN�O�^n^�C��\$o��0,�\$ʹ\0���9�!�p�p8#�\"����L��g4l���4��\$Ĳ�D�l�\nFm���'�@�M-�c\"�m�p�\r�wcpk��h�\n��J��Omv�ll�*4ìi�qm�\nc�a�\$��B 9�\\򆄦�\\�Mp�	� 0��0Ұ�pϐ�p��f����\0���i���p�7Ni/n�K���G��P�Bz�q\nsl�u+>�Iy�TLciod�J��s]B8Oc�����#����\"���`������j�\\%��d�4����\">ı�Mp_O��P�DB%)�I\"d��Mx#�ZJD���Kcl�nr����{����P��@T\0��q�63��rcP@lLdq���':c��2܆�\r�Z@���\$Kq|�B^G�\$\$�3��ڌ�b<1��L�Fd�2>�&0��\$Ǡg#\$M�l�e�ڬ��\"�0y�x�NIXC2o 0��x�/����*�*:8z�O	�f/-x/P\n��k!���+(Ξ'n�����X�r��,��/�k&e@�-X#g �xK/�d��mD�n\"!(�#l:9m����ԼH^ e�b(y1R�@�þ�w��-��.0&*��e0�2(\0";
			break;
		case"hu":
			$f="B4�����e7���P�\\33\r�5	��d8NF0Q8�m�C|��e6kiL � 0��CT�\\\n Č'�LMBl4�fj�MRr2�X)\no9��D����:OF�\\�@\nFC1��l7AL5� �\n�L��Lt�n1�eJ��7)��F�)�\n!aOL5���x��L�sT��V�\r�*DAq2Q�Ǚ�d�u'c-L� 8�'cI�'���Χ!��!4Pd&�nM�J�6�A����p�<W>do6N����\n���\"a�}�c1�=]��\n*J�Un\\t�(;�1�(6B��5��x�73��7�I���8��Z�7*�9�c����;��\"n����̘�R���XҬ�L�玊zd�\r�謫j���mc�#%\rTJ��e�^��������D�<cH�α�(�-�C�\$�M�#��*��;�9ʻF���@�ޠq��<H��(0S�4H�d�=?�Af	IC\r\$�����	B�8: P�6�� �=�))�cj����\rJP�1�l(��&L 1BA\0��\r=d�C�\0�2�\0x��\n@��C@�:�t�㽴N�2��8^�����!xD��l֮�46��H�7�x�&��X�#bK����5�Lk�'*����i ��/n���/��!�b���M\rI\n�:B��7=�xJ2 ɠ��yj���3�f	�68b�l��2l��<�o��:�w��I꜃K>� �3#�R��2�B[f����B��4�#Zp3��@�1���㉾���`��^T(B碭��V5��t�5���B:G�Ym!5(#���X�q�6��n-Z1�#t~(�� �Я S\$3�qT��-�m?�RN8�����zv��	�����ݦ1ڑ`�~b:vn�zB�d�>�8�:��P�6�����ǻ����u�����N��&���tT�t��8������Xa�\r��֔�\0��ed7+u�ɸL('l�i�B�5��3b�B��^>�`��@�kס�\r�+El��:\r�Z��\nI�g%�C�b��@e�����.�gE~��P�\nOW\n�S�%��zZU,Ԭ場���[h;��CAr�\\a��H�׺�ѹz��ξW��l��0�\"R��jK��d�T�� �]�Nq��0X��c2�IHDK)fE墴֪�[+mnƈԹ T�ѱu�0|�Á����<��d��7\$��C�8F�I�F��=��RC*� �!�e��Y�ȫ���k\"I(G)Քc�fɀc�!_��s\"��r^A��2����-V��M�g��8�� @���\r,��QyFM�����A̜8j\r�������Z`�(��B�:���\"��GɸC^�*q+�l��6��S����&�zAI�sC����4�rNC����A\r!�i��?ڭ'&�8'#�e�@C\naH#\0�����`���#,<���*|�\nF����|P�Z3�=)�D�̛a�<�2�Vg����p�DCɧU)h�!v�pH-l9��̂�T[��6I�C�M~��rE�Z�\n<)�B`� lL[8C��r�}�<����!�ahm:�Ws�9�*���׸1VA�`I�\\�*���&TɬP0@��t,����|L�B�#F����\\�\rmY��kx\n�������q*����p΃xp*a��=�Y�!Xt\$9 ���gm-u�ȋg�C�N��B�s��cSj\nh��@�vo���T�:�o�p�O�=Id��cCxbk93\$�G����OyT�<`��1��1������1b͵19�͆!����e\nbɣ�C<��f}P�������9IS'��F��\\�Or ��-8��<\$����������x�AP2�`�a��܎�Ғ���\r��<L�e��b�|# �D�B�<ڴ���m� �����l�b���Ni��bE6k	��8���I��s+�+=��Aw��j�� ���\"El*\$�7\"���d�AP*��#A��A�:�P����4�ǋ2nk:\$�3^WىL��q�8�Pf8���[�qk]������YY<f\0���VV�.8�5��xչ��(#\"r�U�9�K�ԣ�r��y�#X���t�ʺ+����0�>�9����އV*z7[5D�{!�Nd	����s�I�(.h�k�c�z��\n-�\"Q����k�C��ou��Y��׮��@\\2�#���AQG%1Bx֐v��o�EdR\$d��\n�a�ܬ����H;�8�������BYs�#��L&Y�U�璆E��Xl5��棜�ا��l��|���nS��a�e�_��c[o��_9X)[s�`���?+�����/�HV\"P2F)K�9Cm@�Al4)�\n�if�^�� ��0Db\r,T͌=p:�l:�����T�։�ug�%'�;d|	-f�FDg�,g*��@P(˜-Z�Bp�L�v���PN�\0�y��<��i��Ў�g� �����\n0�8F6 ���g�~�c���y\n�V����g|���9\n�3���AV\r��.h���Nr0䇐���NACe����2p����<���\n����-�3���/��T�`ؠ�\r�,�?0����*\r|;�\$ �ƚ�\0�4@�h���/C0I�&Qn6Ɔ���Y\"c!RG\"Fv�ʋBZ�1�>�q^W#�k)}KVD��q��%�����J�1\"��1�dO�L�'���/L2��d�G�'/�a�s��M\\��6��mb��&\n���f1����%@���M7�&ݪ��I#?#L��R���\$Ul�;�J#�7\"�w �+@��Xk��\"R�N2�\0�^�:B���\rr-��'2���m)d\$j�sF�m\r�%�b��\r�-~2/�%&�*��!�}rU+���]-����9ED9b_\0���}C�C/2�,��/��r�G����,F�Ar�-�r>0m(�g�y0��S%2��36i�!\"B�\r�2��4m�U\r�P�(*E�4�U,2�;�5�1%甿�%mܯ�lP��,��8\r�H��#�*�Q���`?C�3rgE4�ɩ��p���W��f%�;,Ȑ\0ʻO�/�?<l�iDu<�~�o+=l�<��=2�@�ޤ\n��V��XAV�S�q�b��%�(�T�\$r�4��B��F\n\r�V��8�%�W&�\r���e����pE�n�ؘ�J��x\n���Z�H���+�Cc��n>�Hz{�x'�}4�{�z�<\$D\$�@���FgB%�b/b�J��\"�J��R�C���0�I9�~��Z��<q�۳uE���R�+s3��\"��� ޼��)t���Otn=�7�\0i�beo�:p,�M�a�t�\"sJ����B-�?/�є�+rJ�6����U<qUBqb|\"ϥS����_Ê�cV5��L2ieL�jѵA�+&�&\"�<t�U�@W�D	�� ���_MpX/�yt�SSAl�V`����t`\"?�x#�+d�(3�	CVb��\r�2W�6q�ܨ�H<uV+��	L�ex-a^Ӷ�b*��\r���d+5�/D�-a8+ �;��N񶇤�.(-af��^@�\r�";
			break;
		case"id":
			$f="A7\"Ʉ�i7�BQp�� 9�����A8N�i��g:���@��e9�'1p(�e9�NRiD��0���I�*70#d�@%9����L�@t�A�P)l�`1ƃQ��p9��3||+6bU�t0�͒Ҝ��f)�Nf������S+Դ�o:�\r��@n7�#I��l2������:c����>㘺M��p*���4Sq�����7hA�]��l�7���c'������'�D�\$��H�4�U7�z��o9KH����d7����x���Ng3��Ȗ�C��\$s��**J���H�5�mܽ��b\\��Ϫ��ˠ��,�R<Ҏ����\0Ε\"I�O�A\0�A�r�BS���8�7���� �ڠ�&#CS��k\\�1��(D�C���N���.\0P����\\�0\"�(�6�(� ��j�\"�n����c`��H@��lp�4�lB6��O���4C(��C@�:�t��<(spܔ�@���}��C ^)���1�@��M\n���|���Ғ��P�a�H�?0��������V˻�z��.@P�7HI2d:�B�d77��J2\$�#�%��d��h��ܮ����7�V4�x� #K�\"RC�6#c�:����4B2B3�sX�H�x�����2I�96��r:�k��\"\"�r5E�|��h�f��2��0��C���!�J)�\"`1L.j��5��J��v.��\rrk�!�H��\rv��_i��(2��1@�BKV�[P��)k�Nv6i{,�戂ŭC���M;�@@�pЯ��(�	/k,,��ר�JL;�1�x�3-5Z%��*\r�><�	�<�c6�\r�:9ѐ�`0�h@A�\\��ی�@��iC�����%�����f귡�Q%`��\"���R4�+K�4�;O�3{�SUp^�@�����G��.��~��^�c�@�q23�Ij_I��U���?�5G��(��S�x;����(rT��9@�YUz�m���/H�rA\r�t/��^�%|�CԘX��P(PŲBn�Hfz��������g�����GW@as)�٧�����wEA�2��a#��1���PJ�B��`[�LBk�(��N	�� ���\nc�C�(!�UG��>2fT˙�ʺ�t&�: ����S%\n�'�2��A4j�7C�J�R�FF�1�T�Ա4��81�e�C{Y(!)� �\$J�I��%��(]����/�y�]�g���@�PP d�94!U9�D�c�������P\r!��!RZHxy1�t'E@cy�R!ņ!�y���~S��5ܠhI7#&h��@xS\n��9W�M	�隌�/��P���LĞ���\"ʁ,A�3�@���H0�b�<��B]3m�ɹ'A� a*Hrr�wP�= ��\0PK\$!�~/��X�+	�8P�T�*��\0�B`E�@('G\"Z}}MH�G �hR�.6���)��#�Ыƅ��g�e��C�ީzG�\$���\n<ry�'f.�,����%�t��CJ�\\��2G�%���a�<5����J*\n�q��gd,�)	���e`�:B�<���Z�+M��-༇�%M[�I�b+��tΫ�v��ZD�b#�i�ˠ�P�V��E�e{�� w���D��Ӑs�J�Z���Y\n|7�Q75�d0qFD�up6�r�TDCM&��=\"��\n\nF܄-�kOZ]n�����Aa Pƈf��\r��;#B�PG�l�G @r�!]\0�e��e�2��9Z�أ)�����H���R-�u4gb�	;fР׋�3�_l���s�Q�(<'yc��6yù<����Z���#z����s>�0�x9%.SI��ԩ�T+�������F;��ը&S��0�`�H�(t�A��]�S�h/��n=�Z��[��h;W|#��_�	��f����HN#nZ�:q戲�2fm�kp<ݻY��U��o�۬u��[`�}�Du>Wũ��\n3;��*B�vN/Lx�Y���J���q��H3�[�4�k숗!7��ΰ�ں\n\rc0#�˶�m�>��k#xsv��s.������s--�.�B���u��{���l��L��T�u��N�]k]kz�t�����eݙN�:k9���5[�n�u���\\5�9�\\�5��=޿�o�|��m�~�m?.��'�0�A��T�\$ՄA�\0j^-�x��4�]&>d�-�nGSqi�+�yH�\r���q<�x>��N���^�µ'�0����x��0��<��x,A�>��ߞBٷh��\rhE���ǯ��~6#�����q��#�5�f/Y0�N�ǆ�J��;\rA�g��-N�xO��[\"��������v�>b��2bƓ�P�I�bf*�kwiJNPD�LI�-.�kj�NI��&��fR0^�nH�&���q�|����/��[�n���j�D�0=	�À��Њ��\n� �Ю10��\$�p�\\E<\\\$h\\�MD�ǦAp�hp�0P`��\rdN;�j�Z���LdƆT�+ 9,�H���j�,�f��x��.�	5���������Zd>\r�V�Ц\"�ʩFzcDu�x�'`P�Zʠ�\n���\n���Z��R#���		0�'j�=,�x`�^n� �,dg��C�٢�;d��&��N��\$j��,7\"@�E�(��!\r�	�ީ%fR&�X��c��:B�c��4�������Z�τ�q�c��,���2�f͕!\r���bt��ݐ������M\0��M��Ť�	(�j^ʩ�@���Z�Nd�lr9�Xf.V��^^j���|����#d/�\0A.&��&&�C�b�]�Ec�X2�����~�q�%l\$��(�Z\r��(40oDt�fo\\ De�HBDj2\0";
			break;
		case"it":
			$f="S4�Χ#x�%���(�a9@L&�)��o����l2�\r��p�\"u9��1qp(�a��b�㙦I!6�NsY�f7��Xj�\0��B��c���H 2�NgC,�Z0��cA��n8���S|\\o���&��N�&(܂ZM7�\r1��I�b2�M��s:�\$Ɠ9�ZY7�D�	�C#\"'j	�� ���!���4Nz��S����fʠ 1�����c0���x-T�E%�� �����\n\"�&V��3��Nw⩸�#;�pPC�����Τ&C~~Ft�h����ts;������#Cb�����l7\r*(椩j\n��4�Q�P%����\r(*\r#��#�Cv���`N:����:����M�пN�\\)�P�2��.�c�ʍ\r��Ҷ�)J�:H�*�*��^�2Ó��B����C\$^�OP ��v��ɺ\\7�I(��'#t�\r# ڵ�H��ս����O��1�l;�@��X���L̶��\0x�\r��C@�:�t��L3���8^���w.�xD��j����2М�i x�!�Z+5M�=TKvYD�f&\n�����/K�`�*v��Mb�/�r�;#�ʙ�º!\r�:��\0�<��M�}_��7��\"�\rã~6�T�Эc����(�0��c�&�ЧwM88���,(�3����B����8�P�.��R����\$T�N��3�6�Aa�`��W2���#v���\0�(��P��0��kq[�+J�L.n\\��1�2��2ڻe���I����6ΰ�H�@.N���Ha�.����B ����d+���5�\n�-\n��1)��;͌��\n���Z](CXԬ�2H�B7��3�9%�+	\r����<��EÌ��A�ͣ�*�x����;��(P9�/�	��4sl�\r�P��ޖ������ÓD�ޡ��0T�s#_u.��T�<��T��T*����V��,ܭ��4�9a�T*���kU(�0�V0�H�r&�đ.�����T}������B?jYL?�:��\n�T��T�H�{�t�[+��	����|��ɡ'!���T��S1-	��ᓴ��*�3��G�Y�*�m����a#��̘'�E�aШ��2v�H��z\n4:�Q�!bI\r3\$p���C\$�\$����#L1;?��E]B@\$��\r8((����9&�	-l2��hZ�\$y|�Vj�r�@A�;�7ІR�5kT�(W�Id*��I+8\\�Kl��1O/�C4M�8o�7�BJR�9%����8-fp��`D[`i@�9\r�D��K�\r3�('�.�&K^��L�1_)���jMZBf���@���2y�\$�1�S�j&�3Ϣ!�H�礣	�L*O\0�C!�(~	���5���!)��m���W�1m2����\$�������cVk\0���%3Tz-��=���J�\nY����RrF�t4���\0U\n �@�ͫ�D�0\"��ޏdS`˦�) �\"NS\n	�\r���l*ce�����r�\\'���\$bZ��\n��u�T`��OI����T�Ԃp]� u�ls���\0�SrfH#�&�@�\$y`t�yr,�X�J4��m1���ILQ�e\"�HЛ�(�U�-E���:���T.eՏ���Q��EL|�F�t-�9\nnFvg;�D*���!�i�ւC0��s�b�E�	�mzg��Tk�jsf����v�ȕ곋����c	,T3�����F,4�:��C�q�T��1�L2����H'��e�_Y��%���Aa Q�\"g�e>G�c\0�y��ˬt9c5��_%�0���7Ӻ��֧ ���\0O	jU�I�\n�9��'=,���\0 �Z;�Ey�	�j%�8Ø����4�<2�p�R-_�FK�h�R���|��5�'��u9�~����|���[A\$�����\"H�)����Ͳ�s;��J�[R�Z��UA\\2�#��h2��S��<EÁ�*�]�p޵�����n��aP�h ,((1����[*}�\\Vn�\r��'g��7{2;��%͜#�K��]\"�'�ƪ�l%��q�*L\$^���Qs8����B���܉�I]���(�%�}��@\\��ْ���?iAX��/��O\"m6�<:@o�\r�(�(V�)�n5ERr7�x�R�,��ݬ�_)��E�%��0m8�!\\	ܡ?ynq;��HN�rR\nY59�z4��1�2�<|Z(O9#�:Qt�m-z`�B[�R0ˎNK�1xg�!��wk�?=�����~E��ּhs�\$ZO�u4|b]��2��\nC\n��4\$ۜb4e�~�\$:x����jt�p��|r�S��<Djj2�T�n�:��^ӓf�߉*2�!�NX�p�b���Z�&l��Z'��UNPp�Jʌ\\,�����r���H��4#\0.�88>�&�*J0���	�dd�8�G��;6Nd�#v�.���7\0�U���D�b`x��MLPmC4\r�\n�+Ўd��\nK�	Ć[KTG\"�\0�M䦣d��n��?E�7 ���c���䰖Z��\r�Ϊ�F,-bV�DJD����b|�B4�D�\rF����n�D�1�DUCwp�DQq0�,xO0w��g�1�jO0B�Ќ�qZJ�5l��\"�J\n����HO���	tC\$�D�NKe�f�	�d����k�PѬ ���N�/��z..B�0T��</�80f2ڪ�Bx�p]��b�1l��I���^\ni`�A �9@�jJ  ��M<I�v���`�`�NA�X*i��\n���ps�r/E��M*&d�ւ��J�P-4Jkz�,�p�@&�1bP%G�lfG	�0�;#�z)-r%	��b�# �\$֪�t�&�*rH9�M���d/��0B1̌,�|q	:!�H#�4��,b�&2���-o��B�����-C1-�C�����R��I=�L��@5c(��\"�8vN��?o�-\"B\\2e�p��-���\$4�e겥��N�hϺ��A#�.�n�� DXfʍ2�,\"�Yk,�b, �еD:\"g.�-�F'���\0��9P(�sf#�\"�p�\r�,\n�A�Lf���hl��,��	\0t	��@�\n`";
			break;
		case"ja":
			$f="�W'�\nc���/�ɘ2-޼O���ᙘ@�S��N4UƂP�ԑ�\\}%QGq�B\r[^G0e<	�&��0S�8�r�&����#A�PKY}t ��Q�\$��I�+ܪ�Õ8��B0��<���h5\r��S�R�9P�:�aKI �T\n\n>��Ygn4\n�T:Shi�1zR��xL&���g`�ɼ� 4N�Q�� 8�'cI��g2��My��d0�5�CA�tt0����S�~���9�����s��=��O�\\�������t\\��m��t�T��BЪOsW��:QP\n�p���p@2�C��99�#��#�X2\r��Z7��\0��\\28B#����bB ��>�h1\\se	�^�1R�e�Lr?h1F��zP ��B*���*�;@��1.��%[��,;L������)K��2�Aɂ\0M��Rr��ZzJ�zK��12�#����eR���iYD#�|έN(�\\#�R8����U8NOYs��I%��`��j�3���A�\$rs�q�P�(��VO�,�[���(�sD��SUT�\\��yQ��Jsøs�QD��Yւ�R�# �4��@A\nB�t3\r����#�܎w �7<1B-`�4��5��D1�2�\0y|���3��:����x�\r�rAt3��(���E��\r�[Yr�X���#x��}u=pA��NE�\$Ўh�K3�J	se��b�*�WjZ��t�)�M��txN�A \n��7^�PJ2�D/3�F��&�o�8��g9+AJE�r���@�1��\$�DsN�'^�1HN�D�e��B�tu\\pO��2�mА��V�3\\t������d��è�67nKm\rc�\nb������\0�pd�Z@�.s��#�[T�u][a�Z�I��wЗ������H����d����6�҉�'ŵ�B8'V+x#�A(�]�KWu�d�	&��`+AY/�e���Tߨ�~�U��\$~B!�yF�����t+I0�9C0�n^��9�%�l���e�ápf���4�J�fpAP7��n�� ��:�%��0 \r��3�@��bY!�0�@A�� \\a�:� P�LNTQ@AE&�\rJ7L��U����h!��8xC\"�af��1\$��cLq�H���+'B��:3�N���g!�3�L ��*#��A�6���🀉�S�\"\ncV�{Z2�K�1hu�\nf{�\r\"�pP�����t�iaȕ�0�*�X�b�e��v:��,�L��2Xpɡܧe�\$6�lY0t���φ�\r�r!�����̯�*Y��lI���<�\rm����f`�H��@��)Y� _�#XD��\r�0�i�����eQ��F8ʁ���7a��0�>\0c�s�4��I�1Fh���r`�1�4�����.F�#dp/Hx�*\$<��?B�2�D��2ē\$�[UPф6t�C���כfmM�en��:���׃�\r����;,:�h�y��q�nՂ2����a�9����)ç�0�`�sҜS¨!�0���hj)i�9D3��-2��H�jg&�ڛ�x����O��P��LG�g0���ƁD,'��-\$ÔQ���ȣ4n�\0��E��F�B�!�M@)�a��:��2�Pm)�C[0��cBT�âps�O\naQ𬔠PI��&�����hj����.�X��)����\\3�V���v1��\n�Ѹ�i�j7э�/�Ki��	��3X\0@m�F\n�A|��g������j�ay�Xt�8�˨��q�Qt�BxNT(@�(\n� �\"P�tyL*����w��w���Lz+���<Ǡ����.���Ki�6�H2�|D}�>e��1Z�ho����E2!b\"8���'�I�{�rs\"�\\������������ڊ�-Y��W��)�>jjnds��l9�*r*-١�	j�x��wUp�V�]^�i%f�-�TJ��#�VK!-���Y�Pl��H�~�d�Gĝ�˨�L\"�lN9��x���Q��U��b���4�,LV+���ʣMm=VA'���FbW���x����kΪ���,���~m[Rf���4���*X���k��U/�o����_�P��*�K�<�l���}�\nì.G�W�6N�WM\r4��/�3�T!\$�9q�mƱuSڇ�j��Ń������&I��A���^cʪ֢@L޸����R�z_�����BG�h�p/Zެj�aL9������>���9��[\\�b\"@>P�A300\0@���J�%��X�[v��X�m&��K��_I�.�������4!|5b��-�h���A\\2�%l���(��ސ����E�4�Ƹhs���mZtoIbb�������bO!Zj����b6Ă���2\$2��lFȽn8\$o��gl&�l��k���Gof*0k� 2�0�Mt��\0000Oa0��Рh(��ؘ��	�k��͜��-vn��H�0z����qg����X��D~���8������mgY���\r�>gB�Ę}P��#nvE�0�J��d����W�0�h�|�-j>�Nե��|�bn�� ������R���f��x�͖��m�\n�B�g�YB�Mwјz��뱅�KF���!x�(,\"�����kz�� �oK�n�_ѻ1v��~Ơ�q�������c����F���� N�ف���U�����k�t\"��h�0�E> ł��\r�~�cD,�S�\"*�LB�q4��~�C\rW>+���\$2B0�H�+̪��ԑ������BL�bi���F�A�Nh�����i���*�� �mr�� 1���,�j�{!��.�jA�C(�^��c� �mA��g�:1�X�������3�91q�~����1�0{'.N�9-���1\0ܢ8S΃s4���,s>T3BU��4��m-�Q#��5�>0�֒��pSu-i�83���y-����[9%�)q9�w-�8�EZM%X���#��r�s�#�-0��#��r�<d�MS�;��:S�Ws�M��s�R��N�+�<.��R9�����<�@t��A��53'��r�x*��.�;1��T;<�&jӯ>�B��B*x��N�����a0=a�(2k��N��Ȏ\"Y4\r�7�x�d��.��8�y�Js�v�E�gIvt�/�&�p�-/fh�\r�V� �`օ�_ ��\n���\r��Ȳ`�̬\r�C*L���\n���p�	�i9�\\��Ҁ��YHP��}.,�a�,F���:d^S�49��d2m�\r�UL2#&��n.�lEL��T%�6}&�>�q�v �~=c�#�Tܢz'��1���j��9)0h�y��*��\n5/U�Na�x5�Rϼ�B8��LP�R�[�{���ct5#V�&L�������dKZ������4�0�s�'V�����K#a~�nz�v .�`�C4 ��@�^ ���Y!\0q���JLA\rZ(2�a(bV��k��.�� �����LF����M\\�� #�x\"�[��燨u����\0�6C�Qӝ73v}֥\r�fX�V2�������Ol!\0";
			break;
		case"ka":
			$f="�A� 	n\0��%`	�j���ᙘ@s@��1��#�		�(�0��\0���T0��V�����4��]A�����C%�P�jX�P����\n9��=A�`�h�Js!O���­A�G�	�,�I#�� 	itA�g�\0P�b2��a��s@U\\)�]�'V@�h]�'�I��.%��ڳ��:Bă�� �UM@T��z�ƕ�duS�*w����y��yO��d�(��OƐNo�<�h�t�2>\\r��֥����;�7HP<�6�%�I��m�s�wi\\�:���\r�P���3ZH>���{�A��:���P\"9 jt�>���M�s��<�.ΚJ��l��*-;.���J��AJK�� ��Z��m�O1K��ӿ��2m�p����vK��^��(��.��䯴�O!F��L��ڪ��R���k��j�A���/9+�e��|�#�w/\n❓�K�+��!L��n=�,�J\0�ͭu4A����ݥN:<��Y�.�\n�J�M�xݯ�Γ��,�H�0�0ө΢R��v��M�XO,T�[S��g#R��8�­ڜ֤EUau*s=@k;�Y�OdA3 ��j+)+t����K����W[�����/:�X@ ��h�7���r�e_R7��p�U��V�\\3Z�J(�J��\r��3��:����x���p�9�x�7��9�c�~2��@*N\n���x�8*����uJ����J��.Sk4�U��\r���{PB�tV�����8�-=�\\����[�z9�l�8�JV���Jpd�/=!(�{�6:�Y4�@7t�4W���R�tB����-F��j�NI�L«s�w���}m=���R����=��O�I-]�\r7��p\r>���:�З[��yP�kA͚=)_ϋ�!BTW�T0?�}.��\n�'�Y\\�sNr���z(�%[�;�\$�� �[�>m���nR��XQ!L(��^	��W	�u���S�r�C�) %���,o�uڝ�����[���AzS�r�2�^C���ιB>�q%:��Q@#�%]��l����q4��U>�\r\n��1D��ԍ!4<@P�ݬUx��;p)Jd�\"�.W���W.Y�d�W�pv/\":�W �\n!�\r��9<Bu�Q�e<���8�T�z3/1S���\"���F���ZN�z@nf���R�G\$s�t*�U:T�\n�H��'�ZG 1/+�M�e ���Jmǹ���1-��{���u�|I�o����Sa�T��)K��y��f\$��Ü���T����J;&e��2�`̙�6g�l��~�Z/��7�h#Mj�UctG�I�s�U�����Àg�\\��� �Yu_����@�<jLk:] �m\re:�&�,����I1�z�BnK�d��@��Dv>�%��e,����b�٫7��ͦ|�D��:40�B#Nt-��C\$�K-�L��Y�)I�R\"N�A2�je�ӥО#`���&�s�R<�K�S����F��2򌜔�\"H,�\n�915Fq�#�{��T�Î�&S�jt��EM	=8�谤��%0I:b*�r(�xid1;L�㩖&�ݚ>J�!,�uqc��_ž��G��Np |�uvު:\0�)�\0�Z�*�=�ț�YZ�l���@���fk��ӆ�J��2e�J,#�^!�3��샽o�}J�~R��%6v<�ε�R�>�:F��+m����:%n#���2D�&�Nt�RR��ڵO|-��.b�#�FN��cF���z��h�(}T����wGcz`(V�)�]�\n,�}��5�u��-(��G��V��=Ua�?�R��fEJ��Co�q?\n<)�K�q�#��94�����T��N-�+�<΂���~T�y�?��N�%�o�M{.�S�����Ne-[��\r,�^� �XM��O.Ѽ#@���5D��>�TR����ӱ2H��l�q�z��'�7��t��MU`|5��S!�k&���s���k�uJձ��CK��V��p��|qj-��+����I��\\��*:9h6��1�����*�C']�����B�g|���D��K�Y�W^t&z���?c�7[�e�@ĳ⢉-������P	�K[�\0�\\�p�K[�qI*���8Ut�PZ��:�5�'\\y�2�=�?�%oX��Vr���I�6��+�CG\\�������V�\"�j[�1ȩ��ae?֚�A��v��jn����Х�\$����+mu��;X�G��O�r��:U��*�\$�i�o�9jL�@k����/fS�+zA�O�=��]��I�F�wc���^e�ŝ�<���d	@��+\n#�O�~e�*�w�bV�ٲ�J.��+-\r�O�?��18�T\n�!��0�6W&�m*�\n��f�d~X�dW�FE�p���A����0Q'\$\0^/�\"����쾘�Ս\nE�,��8�#�#���\$� �,h��Z���e��D�(�/�'�P��d�bC��<l,|]f��L?��#�+�L�����c�X͠s\"v�o�r��K�H�MO^��k�������v���l�6�)DȏNx/:Y��b�>qk����)F���|�Gܯ\rҀ/��BѯH��P���UK\\�1BK(K�ǯb�2�o.��4@D#�W��1�O�2T�1bA�1\r��S\0�:�r����I��z��Zn�N�^�J������B�\nW�<��]f���'gtǂ���{i��Qv��R�+�\"����|�)Q�;\$�c�\"����M�Vh�'/n�L�N@Я�Շ��4���� HP.vǦ,އ�1��r�N9q�!dd�D\$�\r���(O�F��H31!��p\n,\$�����\n�hUR}&����;&oH�ry,&�Qh�m+T�m\n�B���ȡ!Cw'R�[�v\$�`=�4-�8����T�n](�(R����8��='2�cM*��/0��h��-Q�܄ʰ���1�f>\$�C.��B�\$���k6)�:�Q�t-V*R�H��G��4ݐ�\$�i3�R�\"h�[�c3�8�ETK������_]7b���	.2���1E�9s-5Jk3.Ʀ�J]s6nQ����lF�\r,�r�,���Vi���AQ��҇=\r=H�=��T�3�=.�=fҶs{&��tXϾ�����4�{%�A�q1��%�B��)�0�R�#ҍB�+C�y�<�o2�E�C��D4Wo�@%��m�7D�\\?G���1B�o@�q��g�ܭ��OH���B1�/h�]�J]?�K?2�J��K4G1�bL3�;�EF��E�K��K�8�[<\$(So@�NF��J��ՏE��AE��h18o��#�9kjx�����BS���&S�@�Ȗ�?2��'2�C�)S�/<�IMO~(�*DuOA ���*�=JS�J�s�ECTK\$2�}��N)1W�5q!(�e�gR4�[�N��K����Z�T�SSY1��0�XF�[��LH�ՅC�OF&I#/�Y�r��>��Y�0�\nX{�a[4�J�ޘ��{�W�d�!`?�l�\"��T�R��O�]��DE��y0��Y5%-v�s��t���k�v�v\$Xԁ+5_���p���.��ԥ�+%+73�7h�i\r�V�m,�\$~g�ߖE\n2�N�)3�t\$���vO�@\n���`p4�H6h�8�J��Q\\b;/\0�����o��n狨�k�l\$�\0�T�K�ELTxn��f������y(0|7q�X҈�KgJ@�\n�26~�g���G�<	0Av��b��t-@�j1q`T���H�t��oh�?��Յ>/���E�lZ����qb��6�A��;��Ǉ�g^�ąx�IR��v�J����mw�dQm^��m.�K�qdΝ�<.�v�	T�I:1�;�98,i>M�?��淍wļ�4RK��z�c�+9�D�t�ʯH�v��r�;qK���#1;i6�ʱ�?I����'g@:��B�|��2��T�=�\"Z�hR�uq?.�*�s�x+'Qpr��n)l��&.��╇���*Wmr������\r*��5;�dI�b�5����a�,QԜF��\$�";
			break;
		case"ko":
			$f="�E��dH�ڕL@����؊Z��h�R�?	E�30�شD���c�:��!#�t+�B�u�Ӑd��<�LJ����N\$�H��iBvr�Z��2X�\\,S�\n�%�ɖ��\n�؞VA�*zc�*��D���0��cA��n8��k�#�-^O\"\$��S�6�u��\$-ah�\\%+S�L�Av���:G\n�^�в(&Mؗ��-V�*v���ֲ\$�O-F�+N�R�6u-��t�Q����}K�槔�'Rπ�����l�q#Ԩ�9�N���Ӥ#�d��`��'cI�ϟV�	�*[6���a�M P�7\rcp�;��\0�9Cx䠈��0�C�2� �2�a:��8�H8CC��	��2J�ʜBv��hLdxR���@�\0��n)0�*�#L�eyp�0.CXu���<H4�\r\r�A\0�<�\nDj� ��/q֫�<�u��z�8jrL�R�X,S���ǅQvu�	�\\����:ŝm�vBZ�!�%��)�Sy�!�eL��Ӛ�u���v��\$���,�5�����TjLŝ��u�@@���yE���}G�B�6��|;�1K�#�H0�c@9��8@0ð�1\rC���:\rx�o�`�F`@]@�2���D4���9�Ax^;�p�iڰ�\$3��(�����2��\r��8��А�\r#x��|��-�i[H�P�;#rD�&X�B����\$�E�i?�ř\0��c��ye���i�U/1NF&%\$�?j�@���1`���K�ՅQ�%;5dy2������\0�:�� �0����Og�DJ�bp³�<\n�9��EBc��D��P�).����Ⓔ��-K��L��t�� H #p�d�\r���1�#s�(��1T���M۹�r�Y�/\\�T��j4��A�YNDj�����������z����{!�vhs��O�{���b<(�Ǵ��̓S2-y�	8,z�~J��\n@\n��|����n�T�D\r!��!¨�\rЕB��	�(x�Yr2����.S�9T�;D��eeG�#Vr�Z�3bT�����o\r�������nD˅q�g`o��9�����a����	[J�\r��2���\nY�Ήq4A��l,N�� f�d�C�\rh����� ZQ����\r�HdZ��/�\0��#a)�v�r�M��t=\nã)b��J�P}�c.(0x͈Rk�`�m�H�/!	��(������q�!	�@�P�3L,e���i_��}����X`�%���%�rb�Y��(i,��	!�8<�Ń��ж�\0��C�Sd᭑��h�����C�\r�BH�����*���41C��8�%�*�6;�ć(r������6��\\^�y�FƃWr�LT������CrD��q�53\$�\0�5P:@���L�G`�G����`�!,�X�6U*C��4%z� �}g��n�DC�o[��ײ�\"{#D+�9����j�da�8/5�f�i�4�0�Hg`����48�\$xe�byB�uS\nA�fp MО%�L�I3�I�Ey�MD�5�Z4(��V���%I�H)E0��\"dM��A#�PY��[&YT���j:�R<�	��T�PBI+�ɛ��n�q���⃚\$�D6�)P���\rq�\r�;\0�+�A\n<)�C����o�B�Xk�������\"�Sʍ�B�����,T�>��zߊ�J�E꽌���p�� �\"���*��\ni�qXJ�i/&\"q#d�l��K�~��@f��OrE*�Y�.�ȸb��>���)�Ī�ޛ��%L!�9�i)������PU%�S�!`Lvc�&?A�R����}<�tF>��aD�B%�&�X��Y�O�e���ւ�f#[�\"����{9)Q���w�2W�:��>k�k;�w����^�U��{M}�������yT�P�׷��(�,��Bu,_E**��U��V�*6/S7J,��K�|JW��u��`�-{��VQ��#R6FD���uK�P!��@�7��uz���r<� ��չ�M��S`iU�@��KK<0'��M�\"U�/W�QI^�\"mXO���asNkͺ���.ۇ]���C�t�a��r�Xk�,����)�[@sے�/B�T��Ҁ�N���a��ST��͌ �@�BH���@����[h�Xc\n/�]�vdy%�a0�\0/\r��2��g��\"�ZZ�<@|���eL2yx��ڇ����Г[��X��)���x\np�{�^�:Q��\\s�;�.G��fk�f7>fPQyO\r�F�k�4��j{,�=t~�~�O���V	TI��~}����j��#�#��������tc��C�s�<#�3#63�kF���ʁ,�M\\k&�T�2=�b q/p#\$C��%F4+t�cX儠20Mm\$�P1�T�/Pj�L9Pb6oNو4�_�c/fUD�\$ND��#=�\$��\"l#F8a7I|*��\n�\np��|�n�M��H����08�#��.`Q��\0P��\\/��l���S-i�W��2�-z�p����A|3i��\0�M��Gā�p��wH+���ޤ��G�-m�o��8֎�=���n114{�Z\$Eb���n����V6�Z�4��QL�n�͂�m~?n�a��0������q��1!p:��b��!%b���o!2(�����R�6a:S�,�@ҧ,�p*h��vL�#�4W���\$���+���=�E�}.N||ĺ��\$�Q9f`iR3p[r0�+����n*Q\"٣��Ea{\$RM�:6N�m�hu�Lᫎ�<�D%��Ї�H:\"��b �66Ǣ:Qp���)�DP�㉅2�'2D쒥�Vf �\"'rM��9��,�%n�%��-R܁��0��r�1�-���q�*��I2�/���,R�5�0����a�R���1���R��8g�rC1p�3n�R�,d�2�H@��t//A;(7���\ri�a,sd��+2�#�E7\"�6�4�@I��82U&~�Su6�!a`TN��n�5D�6�8�w5%M:�yrk;��\$�%&�*( �CJL�����8k�<��4�J83�!nr.n� ͞%�0�=�h?b=()�>��:�&#�^�M�\n�4(t(��+�,%P1Qfh�\r�V�k:\rhPA��p�C��d �\r ̊��( �\r'���D�J���\n���p�I/Fc�� ��oM����<#%<�#Ch6�>gL��CN�qN'BO�hi�\$C�C'	�Ll,GF|!R5��GN���9j6g�x��\"�g��M-w>A\0��T��P��dsL�~N2.�B�|G6kg<c�L4���K��T�eK\"<��6!ԩ0f6�0�%S4���gB��;Ud�3�|\n�JD)�8��,�\0��%��dcW#ڻ��%5�<ˇ �`~�ds/1�8NuGo 0�[͎�\"V@S64�0C�?k������)%:��Rjh5�	TjB�O�lb~������m�6F���15lt�\"���&�S�1�rX#u�(��*��Nd�";
			break;
		case"lt":
			$f="T4��FH�%���(�e8NǓY�@�W�̦á�@f�\r��Q4�k9�M�a���Ō��!�^-	Nd)!Ba����S9�lt:��F �0��cA��n8��Ui0���#I��n�P!�D�@l2����Kg\$)L�=&:\nb+�u����l�F0j���o:�\r#(��8Yƛ���/:E����@t4M���HI��'S9���P춛h��b&Nq���|�J��PV�u��o���^<k4�9`��\$�g,�#H(�,1XI�3&�U7��sp��r9X�C	�X�2�k>�6�cF8,c�@��c��#�:���Lͮ.X@��0Xض#�r�Y�#�z���\"��*ZH*�C�����д#R�Ӎ(��)�h\"��<���\r��b	 �� �2�C+����\n�5�Hh�2��l��)ht�2��:���H�:��Rd���p�;�8ʕ����4�Q�n�)KP�%�_\r鬛8.1��=-�P�4�3[���\nB;%�D�K,�Z ��j�����p<#�ϥ.�aT����ʎ����AC�AЀX�����D4���9�Ax^;�p�R��\\���z���(�2�t\r�*���V���px�!�H �����dD��TK��_���b�c����k�x� �2a�>\$�.�6�͚���C\n��\n�D(�=.ʀ�ق3���_������\r��膠c�.�/�Q��@\$���8��P�2��9�%���_���#M�X�J����g<�\$���*��uh�\nHҿ<�]�\rSsO��R&%Y���h�8�F�\"QQ[ �65mk*9�c��\n\"`@:���f�O�ҽ�N�9�v �o�NG4�2�L�=�\r��)�C�oߌ~�툝�IG���;�4��ͳ�B\$��Dn��G���/���8�w��wqH����y>�S~�����5��J�-i��t}��s\rĥz�@�r�YDq���\"���o��6*u�Rl��*�,Jӓ��a�Y+@�֋xo,�-c����gCg՚�`��C((`���K��!;����,JNwm(�ɟ^�M�.L��RC���\ruj�d�%�����Z�Ylu��\n�\\�7��Z�u@�;������\$\nf}�qJQ*(ز���։HYw��,ȉ%SJ�r�����/RB2�����Z�]l������\\a���[���H\0|���Q&��uނ�r�\r&AHDt��J !�Qw�G�8�aP#8�eU�y\n�˳+2b�^e�33~�aj\r��`�C9Ư�;�!��/�^�I`\r��ÖՅ.�\0i\r!���c,��i..:KM��Q�P	AP�D��A:��(!�R��g��y�B��I/5F]`�p���x'\rĂDU�;�+�7���l�e��Լ�@��:М�:pʄ�e;�,!�0��!-�'<L�Dn���&��i�u�� �s�I��0�Χ�rRJ�i/.�C�\0ڎ�cA.���#m\$�5&���Rtk�Xy2\n�ՠD��s/5���p���\r�r8*�P\\g�\"Ws��<I��/f�H��+�q1���7�|���Q��Ɔ�0���XvJ�b*�1ea�\r�V�~p���CV\\Aj�}M\"[j�1[��H��2�ma`�G+:{���ڔ�C��5�p��BqI:SSD<�9�áh��#�W�j�M�4)�ح���IOľf��J�P\$�� C�q��R	=f0%�\";v���+�r+���8G�:w.}L�x:ys�L��>��Ka�y����\"Z�r�x���\"<�.WwO�5�f�ރ�Ra�Y�;0�-A,���L�(B��İ�t���:�&HѺ;�=�'JIJ*�K�\n=�ZM~a,(_�f\r�e�&>�>%�hS\r!��W��p��d��yH~RV�Q钲HI�u�t�t�ŋ�\rԬ{S��B� ��cf�g��\\�(�%��*�P�[�o�Q�Д7�� k��\"�}�&�����+�\"�#�8���.(R{�!�y+��՘d�m��\n�&v ��}��6f�<��J;a*@��@ �����<����!.i�F�RQ.)M}����Y+'l�����9Ɍg��:u�F8y3?�Bcy��y(\0��^�K�8��&�I�[:�>�X_:n�t��K��k��_�Lm\r-�&�\"GI�y�v.���~�l�A�E3�w��[�G�0�O��~��C_��+P8�~C�&'N�	x:�	�Gdc����&�S��\09'��QC�C���P���� 3��ΔG���AN���\r�\rt奨\$���XM�\r��i;V���X`�����l�	��� ��nm{��e�=N���r)�S���z_�ٟ�������>�@�DDr� (:`�\$+��\0Kl%�,i����yc���J Ā7Æ`�\r��;\0�dD�P;�D�B�p 0z-���#�\"�B:5�b,#��Æ���T&�RĐ[\0���L�ˏ���ϯ�	��	�.Ad�\0��\n��\n��P���\n�n��\ng�%o��L��0�yE�����PҠ��/n�'\\���n��.��/���D��a�����d�m����0��\$��z�\$w���aB�����b\"�/�7D�����QT<qY��|ϼ�-�J�7�:��zL:hi�,�`�B<i�\"#��ކ�l���7/�J1�����(P)�,�h%N7��.\$���(��o�Ab^�g��Ѡ�0�c��R%3���̱,<#\r��m��Ư\r��P�Dk 	O�x����;c��j�P��R&\"�+0��5���O�bo���!�,��Re����?��,#�!�2�+\nS�`6c�1+�\rbY\nKq'�-�<��Β|-��`��rl��;r�&��҈�oy!�?*E++rR32�(����\0�zG�F0� �H0�����8\$����v�Ù%�/m�-��\"ФN��!ql�R��\0�,���,��\"�0�\$�#�,���.o�/�4�.��+1�G��Ib�&��O�2dm�,2��\\P\0�M�'32� ��G3\$\$�� ���/���|��*��g��aB�f��Ω���6qhꓥ'�d�f� s�s�)O:����\$H�:&^�fꡀ�;�S^%��N��C>��s��4��e�\r�Vh:`ր�XO�k�V]�j�n;\"z�J��*��@�\n���p�H²`�\$���oH��	D�=E,�;Q1oF#4T�0��XC�����mP\$�8`�o\0�+��8�\"�`�,b�/eL8��bc�Bya3D*�4�96]�\",e��\r��]�x4��	��D�\$%�J�ͭ�F����F��MH��@��)6GI6L��p\0��>�4/�O��a@ޔ\$�PG��\n��@c&I�̶\"#�LՄ\"�Q�\0C�b�<��nF'p��Wm�,��J��Gp�{pvq�\\J�R&�\0%�1Q���C�F����\r�	�\nq4�=��-;Zs���%r&�\\\n�D%1QL}E���A\nʧ�d�\\��RM�\r����.d�\"�� k�I�`ߣ\n2)�͠/c�@";
			break;
		case"ms":
			$f="A7\"���t4��BQp�� 9���S	�@n0�Mb4d� 3�d&�p(�=G#�i��s4�N����n3����0r5����h	Nd))W�F��SQ��%���h5\r��Q��s7�Pca�T4� f�\$RH\n*���(1��A7[�0!��i9�`J��Xe6��鱤@k2�!�)��Bɝ/���Bk4���C%�A�4�Js.g��@��	�œ��oF�6�sB�������e9NyCJ|y�`J#h(�G�uH�>�T�k7������r��\"����:7�Nqs|[�8z,��c�����*��<�⌤h���7���)�Z���\"��íBR|� ���3��P�7��z�0��Z��%����p����\n����,X�0�P���A��#Cd�2\0P�2�ɳ'7��E�%�a�6\"��x����::�`ޜ�+�7N�6�M0�6�.��\$�/��\0@J�1�I�p��O�����H�4\r�-- �4�4C(��C@�:�t��\\(�����x���|	MC ^)A�����H����B��|��J��r��\n�m�8h%�&��5��\$��:�B�`�'�J20�˞��˞��c|���MV!�\"Z|���\r,7�V�31*2�h�`P��&�L�T)���q��ƨ�Z���ɁL��:�,��K�+j�'\n����z�\$��w-�jEӊ�Ó�݊b��7��7\n�s���*�͗4 �%��(�l�����I�Ѱ:�S4�����@#���~�Q�к���5Z� ��m P�i\r. ��>�c�xA��\\����\\��<9�r`��)6�-�d?# (��'#x�3-��K:2�)o����	�N�7-�C��LS|j�#r�r��t�a�K�(3��+{�w�eIw~s12F+��&�/�å��\r���r0�D�&=ϣ�8���k��К5<�T��T*�X�������+ep�xd-\$T4��~���d:�f,�d~���A�A�g�Ğjuz����J�c}6?T��!���N��B��*�U*�V��b�U�rV��ѺWO\0sǌ�J�\0c\\�|@R6̃8eM\0� �Y��>���\$�N���\r!��>��}�cYF�H��>Oc��Fq|�sÑ�D�`�G�bo5 �H�4�С�j���]Ip.HP��Loei!7a@\$j�\0()@�<��NR\\�*�Yhy`��٘ �����J\r%F<�K|T39F2\n�\ry�Rk7D%āP:����4�2��:�}R~Q�_to'��!�0����I�٠��M�d�G�\\d�e�3�\$�4�#�!��J��19-A���'sYG�AAHm�T�!���&�2i���A�3�y\n�6#`�L8 0M?=zR��k�p�|��P	�L*\$���^!�F�5�s]2M�N�5��szl6�|�;\n��,�l�]���Iڔ�Qd��8�̰F\n��E_���KiT��s'W���(1�\"� �PO	��*�\0�B�E�6l\"P�m\n*K&]�;T�R�-r���F����cEr�I`��i��H�\0��ӓ��M�F��^���I�ɝ�V�Tl9�4\rk]v�U�Y;.��4M�vR3_q\r���{�v+�'�L8��ǭ�q���6\"\"��t)@�2X��e��� �ZKb���rqI�p��	�e\$2E����������@�+�HzKᥧ����JBG��jc���[\nf�#Xe��ԧ��lbSZ�I�DE���S�.7�BW��˓Q{E)�Ǚg�Z�a>Q�,���Β�R�	<4Ħi_C�cK ������K0�<�T��{R�\\E!D:�\0�������l�����*;���\n��_\0�i�\n�H����!�2vQ�y7%��N/��\$�j��T�:dө�e�ƏS��#ln��ڌ��-K5F��a����T�����D��Z�F��'ڤX�m�;]������[`Juw�a�+�\"g�^�I��m�N���Ʈ���eֲ�9��~���K��;�ȶ#�L���2�s��\n@�l��DҌǆ��A�\$�m�q�w���2�����o.��F���RK�دFd�T��Z^''�+�* ��v2���9���ޔ�7q��)e��,�?Sc�1,��Q/�Q�R�F~�NP`�\\��qAM�:�Y�_��y1ٗw���Zh��#�h&{�viJT����[�z�M�\r=��������Gś��1�58~9�,�'��\ru��[��V��̃X��d2���W�̀��l���^K���o���c�|�/��hr���F�G+4w����2fUtv^��	*s'�W��g e��}E��<���D��)��~7��Y�~��\n��F�\\�ص�t���oF�0�z�0~��ȆF��\\l�Jl�!	�Η�np:�p(�`�o%�Jɨ�C��^σ�\"b���1T����&��?J�/�2�6B\$~��U/2�,�	oEД�n[����-�\\����Z[�e\0�c�e�ff��`�@\nC�f�\0�dp�����0V���P�I�[��lͭG\r-a���f����\\�F\rB-t���?p<���P�H�-�&����Ⱥm���:/�\"�P`c�pK�8�蘱bA����~���nh���0-�H��M�N_��I.Z�B��20%�>��i�z>��:*p�|!\r<RN&�b��Rd>\r�Vb�g��cTb�t�VBC*�3��Q�~D�J,�h\n���ZJ��b�oI�O��(N��mHݎ���	��\0�h�S��\"�.�ʚ|��LJ8~��M�Pq��%��[�n�\r�}E��Y�Xk�@�LM'v.����\"w2��p��)0H\r��3bf�2�F�jv��d���1�{�o��苺g�r��&����l�� �T �;�@@�� ���Dҩ*û/��b��B�2�#B�/�~(\"\"Z2�9���뾙�,;R(Ɠ0 �à�9��f�'��0��� �>�~od�";
			break;
		case"nl":
			$f="W2�N�������)�~\n��fa�O7M�s)��j5�FS���n2�X!��o0���p(�a<M�Sl��e�2�t�I&���#y��+Nb)̅5!Q��q�;�9��`1ƃQ��p9 &pQ��i3�M�`(��ɤf˔�Y;�M`����@�߰���\n,�ঃ	�Xn7�s�����4'S���,:*R�	��5'�t)<_u�������FĜ������'5����>2��v�t+CN��6D�Ͼ��G#��U7�~	ʘr��({S	�X2'�@��m`� c��9��Ț�Oc�.N��c��(�j��*����%\n2J�c�2D�b��O[چJPʙ���a�hl8:#�H�\$�#\"���:���:�0�1p@�,	�,' NK���j����ܠ���X��3; �\rь��?Ø�#h)��\$k	G��0�B��1��S���̢H�6;l:<5\"�|�1\r�  ��j�PA��4P�I��c�����@�,���C�:!�4E�0zQ#�t�x�g�ɍD�����a}{_��xD���Ԭ�˰ڡ�+x�!�!7�H�:2�i\\\\�1�*:=���:�@P���bOm�ͪ;�\r�C�'+î\n4�t+����J��C�V�i=#�p���H�(�0�CrL�S�US���3ѣ(�0��b;#`�2�q#�u�1�K\"-'�Z��h3���\"̗��C2��Td5��\n3�t��#�#h�%���ފb��\r�h�C�+����.s{GRf=�-�[�����q<]ϩp�����P�zQ,��6���Ȉ��C��Pنrl�\\�Rh��s5��\"�ڮQ.��}�X����=]3�j&#�Ù�`,�\n�%�xͭ�i���R�2�\\�p�N#��WW�6{�#k���ߟ�3ʊ*��%6�d2��R���;i\\��΃�a�>%Mr�p�8 TL���X�	��,U��VZ�Y��h�F�C��[(\0�G����,]	�u�״�S�!%���5���;/T8��Ċ��Mp˓t�q��\r8�u�%Tv��Ã� �����'�Yl將��/[��9�\\≘t����&%rJY��*(\$���MS�DO���H��q�HD\$��`�A�cK_G��3DC`jb%!�3/eR��{�ui�����J��sP��1��d˃KAF%��<xҙe�EM	�\0�\"��\$��D�u\0(*��2��^�Z�ff�4�SN��*f�T4^b`D�kD��p悕tI�톳#D��)��D>�J4�\r��Sp���x)� �O�\\X<(�&�B\$�=+���f\$MI�9'd�M\$��GeyFEǸ:P�\nl*��\$�@�f��i+�9y\"�bl�pqhm�3�{�<�(����jnT�Q&�@'�0�]y�6e@���J(Xk.O�R��GH=2Vd��C�J��xeN��z���b�72�k	y1VA��7V�]�:�*L�T]�b������!5��2���C�\n��t\".�\0U\n �@�g�\0D�0\"�`�IxmPi�(IV���\n	�ʹ&Ss�8VC(l��~�Bxm����fx�#X9�b]��Ɲ+���g񆣍.��.K��(�h�Tӭ9�@�>�.��Ԗ&ȅ2L�X�S8��<H�/e����O�r8�\$�lDx��cs]��1�T�HT�L!�����y�������\\��4`חv��&���݁0L(`�R�&A��<xQTӇE0�B~_BAfF��0�e�+�\$���p�G�U/a��#�'�)���(c�M\\�^�ޫ�#\0H�8B,���7T��/5��C	\0����=4@����v���V�A��^4��#쀔�S\"Z� �P��tY	����/RP�k�=��R�-Q�H��\$cW���K�t�o\r�HmfJ�fh2�I>4(�auz�Z�P]�4&�N�}�m-G�ΦS�l:l���-\r�܋�V�k�0��w��LN�ڄ�Dg�:Mo�E?�@���\0��EE ��]��6��Of�Fk�?c�\n�Ņ�W�`K�/�\\�J�����9�%2F�������*Y̔�W���W�j�}ǜ.���]���_9l��s�09�\r}5�[�x���[9�L;���\n`pD�>ں&(�n��ͧR\\-�{i^T�=�M玐�P�d��c�T�m{&'�����R�wt���[]ˣ��	:��6�έ�Vk�g�˒Ε���:�:�sgi��ך�O�@K��<\0ܺ����᜶����M�WԵw�G~�}�v�=����YK,,�E��#&�\\��2�z�?'e���a�Ȁ�'�R:�-���4�Aá\n\r��U���`j\r�ER�2b��nSM��H���Җ���b�:5�?��\0bN����\r(��:0B:���,e��+b;�2&�o��\0`�����&\n�/�L�jL�%n�oH��k&]h���X�L�I6��Tհ��c�v�l�;�LtN&o,rGf�k�^��C|)��;�ڽ��J�z�τ�\$�����\r/=	OT�F�mg\r\n�����E|ɐ�n���\r��#0�sB`��Ц��+�&�oR�10&i�#�^/.�\$�1BR�;L�\n�S�|e���Y���V�̹\rn�\n�t�p��q|�q��gl���φ�\n�tL���#�T�x�\"�C�y1�p��\"�3�� �<\nEt�!bf/c�RE�!f�d#Gb�5(���\r�B�o.x��\r%\\�B~�Te�����k�2b�n�� \r��0�e�\r�V�D&o\$:8N��Ǫ'�d!M�92��4�X'fʒ��\n���Z����L�j�..9\"R#�x�'D -��\r<(���@�(m��0#B�#�C�V*��gP&��M �҇6(N�N^1Q�\$\$8#��\0E,��F�\$��%F8�a�q��IJʢ�M��jM�l7��m+�ILN2�Hg�^��IR�q�.B��c0����%�Y �6*8G�Po\"��Μ#�5)b��j���*c838R\0'La3Y��`�,j�~�%kD���l�xm.��L��9`�2�4�#�� �_����t���ࠂ:��N��F\\)�,` �!��/�vR�3@N�U�E�L��0�,d��g\0���m1R}N�\"�7��K\$���	\0�@�	�t\n`�";
			break;
		case"no":
			$f="E9�Q��k5�NC�P�\\33AAD����eA�\"a��t����l��\\�u6��x��A%���k����l9�!B)̅)#I̦��Zi�¨q�,�@\nFC1��l7AGCy�o9L�q��\n\$�������?6B�%#)��\n̳h�Z�r��&K�(�6�nW��mj4`�q���e>�䶁\rKM7'�*\\^�w6^MҒa��>mv�>��t��4�	����j���	�L��w;i��y�`N-1�B9{�Sq��o;�!G+D��a:]�у!�ˢ��gY��8#Ø��H�֍�R>O���6Lb�ͨ����)�2,��\"���8�������	ɀ��=� @�CH�צּL�	��;!N��2���\n�8�6/˓�69k[B��C\"9�C{f��*���R��<HR�;\r�P�\0��s��(-̢��HS\"���t�)B�6������\n��\"1�o�\r'(�1>a\0�4��@�:�Ԍ\09�P X��П��D4&À��x�Y��]\0��Ar�3��X_O�#���J(|6�ĩ+>L��}'���7�B����o�:��+RՎ�H�;�WT�>-��'.#\n��7-�8憌�`�!-�1_�&7��*EC.6�����4�޶\ri�\":1�(�0��c�;-î��8#\"�l�\\0# ����0)ۼ4�C:6�*8)��n��8p���HN�#���BC\$2A9�Xص��Z9�l�(��hֲ������wM��Ro��u��X�2� P�������3u���|�(-7*�H�D��\0���������bZ�<�\"L��W؈�.��3�*_Z�(��^2�	\0܃KP�r�\$����c(Z���hН�-��M�.P�3k*��3���7���x���G�\n8孃�G�-�¶0�%�[Ӄ(P9�)H�:Or�z�\r�y!��@��MU !�G�����R�n�5J;UJ�:*�`����P*�]���ϩm&iib�R�K��ZD�a���s��l'a���@��r	_I�9�n_�Д����j�@�6��w\"�UmV��b�ú�O��]%x��úw��b�0|䃁ycd*� ��!�7ξSN�Q;5f0��c\$R�ck��ѯ�BvM��m8a��xC\"�����\n�b�k��\"���z�!��wƨ(l|�IK)��L��sfD`1�YKs�.%̦)�ʅ���.�H�-�H#�P	@�h*��p((��*#VR��DBz�\r-p�L�r2;a��R�&��[j�%\0�_d�R#&�H�4\0�_��YA�8)�J�P�R��4�9n�:�A��O6��JKI��!�0��8oi\0�#Rs��{2&���Ia.&E�5��Oٲ)��1Ir�K!�ÈH-�!�RH�yz�~�b�I���'�œ ��{	��r�4�Pe��@̤B>i�.Q�4җ	| e�֖\"��Z�/���xO�ĥ8e�%�L�e�̿�o; !� ����<M�\$.����6�I4�\\�D#@����irmA�J�?HRb�쭍J��Ό�\n3�T�����\0U\n �@�߮\0D�0\"�f�S�:MK�]�ΚBaK�d�2���&�>䁕�Ȗ����]�f���BJI��I:'��S���1 ��� �,�k7T���\r��)��@�N�V^���D| a\r4T����_h�Қ�Zd;E��,������<�5\\����_,,�W���Hzaӑɿ[��X�D=����4��iPa�` ���W*�[+�tLz�ˉ�%�Y��\\�]�'j�4���r'q�y�ڗ�n. o\n���#s�o\n#-H�(Hd�[N/��V��RS��t�7��)�A���\\P�BHP�.i�j�r�h���RNFM�����yW��(20��\0K���Nis�xKTz���!J�C�	��'a5͋����z�Ŕ97�6�G11���}�	�����k{)����'� %��|7|��f�l�R' I�P8�u�RR�##���3��A�#0oY�Q�&\0+�P�]����%�hL	d\$p���]\\[�Jb|���W�Z3'd�(8@\\����8)m��@p>�=�t{�\n(/f-ڔ9ta\"4���n��!\"r��.��������.���u�R�&ţ��u�~F-���oʃ/(i��f�р������1��-AvF�\"|�-E(���R0\n�� (']+�*|^�2��d}9�)�쒚c	��:=�����\"}���ݙ�zYս|�iyG���ҾW�9����[������:m��&�6���'���>�� ;|4�#��gD'�|��R3�d�����_�������'�z�o��\$�x�P{�ڰ��(i\$4� �dE��`�Ti	�N��v(B�-����8y&���Tdh�\$0g�c¤'�D�p\")�����f�P-���]�����pN�̶\\�5�	)��n��\$ː�\0ξ6�lP���%��,����BS���/p��ЭP�(M,oϞ0��\r�.7�S�8	�[.Z� ��K\0��d�����밤��'��q\0��\r��#�<�M1&��/��a�s�*eĵ80��\r��:��\\00\"�xo�\n00@�Q!���p����12(1n)e���1�afa�V�����9�^�m*N�.�σ�Л�4�1�o\rqH�1�D��Fƪ�~N\$�!1i\n�LL�c���}\0�c2�1�\nڝi�r��b�A�R����N�����\"Q6T�BR/\0�a%�&@�>&DO�,\r��\$mx����m�1���/�� dF\r�V\rd!H.���8q~5�����<5j�\n���px��<�ޣbS&)��G2�M��R��kx�-�.(��s�e\r�X�o:Ê�1��!@Z�1(4��̮�C�X�f6�E��[\0��j�:@�\0�^у��Lx�C\\���S:��r��2B12�Z\$���6+��s*0��#3j�5��4N�5��3�D&c\"��2,}p�_`��������U\$���0��9.0\\�����,�To�l- b�ڼ��\nf�!X@�%2c��41���%���=(�`�T0\nx!@�-+���0���#��\n�-i F�n\"��";
			break;
		case"pl":
			$f="C=D�)��eb��)��e7�BQp�� 9���s�����\r&����yb������ob�\$Gs(�M0��g�i��n0�!�Sa�`�b!�29)�V%9���	�Y 4���I��0��cA��n8��X1�b2���i�<\n!Gj�C\r��6\"�'C��D7�8k��@r2юFF��6�Վ���Z�B��.�j4� �U��i�'\n���v7v;=��SF7&�A�<�؉����r���Z��p��k'��z\n*�κ\0Q+�5Ə&(y���7�����r7���C\r��0�c+D7��`�:#�����\09���ȩ�{�<e��m(�2��Z��Nx��! t*\n����-򴇫�P�ȠϢ�*#��j3<�� P�:��;�=C�;���#�\0/J�9I����B8�7�#��H�{80��\"S4H�6\r����)����mc���B�N�Oc ����\$@Z�0�skr1�\r��3�Ø�幣\n�寍d9�T��2.�������X�#Ƃ��p�(,S�\0@Hp�4\r�`����&�420z\r��8a�^���\\0Ԓ�N?#8^��p�BAxD��{ �\r�3򞎃H�7�x�*cx�0�4r�)�C���#�j��K�R���������R�L	Ϣ�2C\"40��Ό��\"LŎ�NS��N]�?��*;�BX�6I\nm�3 �=�P�a%bH�1�K����<8э�A6�Ȱ]���B0����p��D�	>�X3� ��b��pc\n'�`�P���	�J�:0�H��0��0�R\0�|I�'�:��%0�B������k�4�9��\nb��@�+��nsߋ���.�Q�i��t�\\���\\Y���}zO�;���4s�H��<��tR\0�!Pٸ\$��w\"�^������6�Ou�=���4w�#8=Da�B�l:���Q`���(���\0��0e7)e�ɳ� ��6&#�AH9	��3\$>M��yRm��\$2J�huw=�P���;��d1 ��2t���^�V��&�HNU�,*}jB�8_]4#r�t�I���Ya��b<)�q2*D�`�a�t���1C��ZeQ\0:BE�+AmT���~�y�\$����0��\nTj��\$HwX	�\r�XL����Vz�ZkUk��.�\$a�\\+�6�FP��]@�їÔ&W��&��P�\\�s%1I)�5<��Wzp���\"R\nPhQ\"��rR���+���]��,`eY�Ai-E��Ԡ[҈9. �)`T���u.�d�9.hfX��.Øn~��0��.TA}G�4)r�A	aA�����@�� �\0001K���Q5\0�	�T~\n�1ʐ�P�Hl\r���伃�IeA�3uNx����֕�4�J���a�M &�iZv\r�@���+�E	�Qv��s��E�F3G��%�ę�f��b%/�)=D���rv��D�PTLH�H ѝa2v(h�5��C�FB�c7C��R��aa�d�ч�\"� �6~������P?W��7��P�B\r�&PδieB����PМ���iMD�[���D\0C\naH#M`�4����S\0�O!r�D�\n?@�~�o��䝷��]	RH\\8R���G=�`�BᯯR�˺�YLK܁)8��>A���\0|�}� ����2O_\"<c\rY������%9�WP��/��\0�f�� 8�4�B;ZV)E�k\"b��K�	��_����)TE\0�%b�q*V�ʒ��A�����l��m��̵��w9s(��w8@Ppd\$�̺��5�����e���\0ƙr�;�����u�l��d���Z.�&@\n	����sb�M~;�(9��hC� �LiW�x�Ι2�̔�\r@!,q(\r�ݤ�@���Q��%���\$��)�y�NP�B{*��v�����6�pd�9�ެÛ�v�����FB�\rP�j��\r�|�*\nJ<>�NCz+�a=5	����^2��D�\"r����oXТg%��%�vj0�]��xt��zV�5��z��6a��Ԡӭӛ�b,L��8ّ�|��҃\"87N�ХnRN���0�Y۶��!�aǐ�<U�K���@��b�4w>����l���Ǥ)y����xWg�P����m���T�!�I��H=g��)|N�� �@�BH#얇v\${Sq��o�;LGée@��2�@����2(H<�d�����[��b#�W%� ۽K�㼙���k�y����{j��2�LN��o�d1z\"z���W���Q����G�;*���˦Pƀ���)�~�i�}�O���\0������(���Z�~�`���~?�3\0���g���!̹Nr�#B8��*��D����B0O���^�̉�P<\0�� L\n���H7d� \"z5�2����`ĭa2��>_Ɋ5HD(�M@�ك�L�BJF�K�0k�9�� �p-Ϊd\"���C�#�8���R��Hz`��'�.��� ȀФ�0�:G֤-�(0���ΐ��d�F����n�0�����xC���&�\0�g�t�<�cF�&�Á6������C��\"@�d��4���&U�l���Q44�`\n�((��{`���w��\$x�gFx\r�\"vp��RH��H��Q��w1T�p�א��~y�i���J��Qe�Q���1C���hY�/q\r��yD�\rc��q��X��	����Ŀ�l\n�/ ������e�.���ώ���h��f��	���1 ��!L�����!�%)�.��3!p�f�����C)�fs%��%Ч\$ѥ	̱�J�Ņ�f2�l�c����f�g����,��NqD@%j�I'68B�Y!�Z��%�8��p�\$Vr�ol��x?%���I�,\$�)Gp@E�D� �3'd�%���G-d�F0��5���:�&(�r'�*s�q�\r0�&0��\0�bND�/����xM�b:�*9�̺*��W\rRb�����\n�2BDQ[6�m5��61���m16�\\%��x���{9\r�JG�qf�%��=C�8���,T@�8��r��E\nlT���2 A2`S�7#ο=0�2Q�<�?42�4q��@�`֕�a�%!�SJ|��?��@�O@2\0b�����+D�2\"him�>�3=�gB��B�#>�d�\rt*4��\r�?3��IC�	4ӕE�J�F�6P��\${(�(s�BTF4\$�.�/�oC,7	Ԉ;��H�_�r�Ԋ6�_D��TV�t�鴂x��'Κ �5���z�*ԏd\n�����I�[	��CT�Ht�&��J#v�@�X�-��=��zR�L�c#63�H�<O�<���:###���\$XeU&��\r5-R5Sg�%C3b�n�^�#�a��R���ͧ!U3#oT��[%v��sV��T��L@�`�*�?QFrJ\$��c�)ú),�1,���O.,\$�6)B�/�^�� �\n���p&�u\n4�,�j\$����2rm�]���u�u���`���Z\rk�@�D�#	��\0�#��֪�&8��(��-՞n��#X�bJ9@W�GB�j'\$�tS�P9ef5�\$#VO\0C�`��gА�>=M�KЁC�䢈�2B @ތ�cJg���\"Z�v��V�\nbsֆ^��06�wV�/֣^���o0�j���3Q3�7HB9��E{gu:��V%\"\n`���	�ͥOe��6�I\$p̦v�&\rGz-��B�W�,=Pt��lp�m#-V����:MAuǤ�V���	U�܃KJ�>OMf��P3�6h#hn����LbBt[B��Q\nb����y���@�%�";
			break;
		case"pt":
			$f="T2�D��r:OF�(J.��0Q9��7�j���s9�էc)�@e7�&��2f4��SI��.&�	��6��'�I�2d��fsX�l@%9��jT�l 7E�&Z!�8���h5\r��Q��z4��F��i7M�ZԞ�	�&))��8&�̆���X\n\$��py��1~4נ\"���^��&��a�V#'��ٞ2��H���d0�vf�����β�����K\$�Sy��x��`�\\[\rOZ��x���N�-�&�����gM�[�<��7�ES�<�n5���st��I��ܰl0�)\r�T:\"m�<�#�0�;��\"p(.�\0��C#�&���/�K\$a��R����`@5(L�4�cȚ)�ҏ6Q�`7\r*Cd8\$�����jC��Cj��P��r!/\n�\nN��㌯���%l�n�1����/���=m�7��lnP4�ȃ�RR+1Oq�4�+���J���>�+�+8�j�QR�рP�2\r��\r�S�pr�#�<�F�S��4����VB)(9�`@ �C:3��:����x�i�ѵB7 �r�3��@^8BU���J�|�3��3.��懁x�'�J2�k��;F҃�R�>]���ılk��+��4��kb�`�0�7��̺϶�P��HpΊ� @1(H���Kbcx�:�1�=�LNt���p����r2 �o���k��2�c��-ٜ�{5�˚ǈ٤����ӭ��l1�x�፛=F6\r�r�'�	C���K�c؄�)�z�ԍ����3b0k#bMZ5�7g	�ܤ��&L�����Il����)�MX��>B��6=dr\"�c��N��>t�V�4����(	#l�8�(���rV�֌(Z����\"OOkہL�9(���	v\$��\\o���P9�L8�a����4m*Y��0�z��8 �k��&H�T\r�\0���@d���6!�34�@���XG��K��?��k���\n�)^�L���{M�� J�W�2nNM9�6K����b�ֹXM\$�\$F�\r2�Y+-f����ú�T\n�\0����yH/+�, }���^+���.W�5F��a �4�9�wf����\r��B��4��,��,JYK1g-���T[A�n-��Nj�\\�8��#>)��'+�l�`k@����w\$J�8&�����!bjh���	�qp+w��:DP�'2`���4��T�A�B��ɻ@��'X�*	Xl~DXˆ�c#��	Ġ4\"p�'(AA@\$�\r��@� �LXs#�\0��hsVĻV&Ў���k�9�#����N@�Q�:����z���SJwW�7��*β�1�x2� �(��C\naH#B\$�(S�7\nH��^�Zq,.1НʊZ���M	���Q]�1���X^s�~ �-!�@�	jc'I�i6NI&�\$D�C&W&�ζ������Ri����sHpz��\0�¢��\$��/�@ҳ����.�@�B�q�rN�����3�֨���L�Vp�e���AI� ���+ �]���CT��@���'����uԃ5y�Ȓ `i�!�bvF�Ƣ(�\\Ta9�('��@B�D!P\"�˼(L��A�Xcv�9b���X0��<d�͗ �]1mY}�Q�,�!F`�aQ��Aj���G�P�◹�)�(U!CIJpH!��;�z���%=��x¶���Vt���c���Ji��6�&��B(D�̽�R\0PV ��,�6@��#h�釄M�*��o̻X�Q��[!Ѵ)���Cbk:��L�E��K���Bd��HHdIt�a�h쩶\")�M��ɉPe�\$��Rq�k�b5ɓ^���UW���BCjQbe�i�װ!�wA�2{uX0T��\$9`\"c��� ��S�Q��k<��V���M�rlX���u\"I@x�����N5�\n�!��@ڋ�\nu�T��D�I�#%��*���Y`/,,���h��6�4Q��\r�_�»�kz��Ff:c_����ܔ�����\r��f�Ut��DC��%�W��p�\"'Saēq���a��R^���ӌ���Ƴd�y�����rS�ޕ\n&�܊IEVr�iK<}8�f�����V�����D�H��P*(�.��{AC!�2�,��y�aE���06c��� ��[\r���&�P\$ol]ZN\\x�<\"��X������?\"��)�b/��w�wL���9����+��n �������#?%}~���}�QFN�\$�Dk�.򯀛	6C{�H�,�|��u�����:����gH��ӈ+�y�8�G@�퀫�GBo��,9��Y���c,pg	Bb�x��a���o���L�/Z1ξ��lDO�ňon��9�D����Vo��9�T�7��0�,�&�Np�{�*��6PDt�hՉ캂�:oL��b5\rZ`\r_���Q�R��E�d�e<��d�,N|��%�냞?I`3c�p\$�b�6o�XDob�&�@ O��mZRdD��D��p��X����C:g�f�P���k0����OR0�=\r�J�GBS��p�ц�*�\0���l�p�1�ƢZa�=-��po����]�I,��Z�Hf\r��q^2\"Dj�(�`�g|�\0SB2��*�O�lc�(�P+qB󍖫q�hQ�/���T��X���q\0��Ǳ���`y�5#�J\nّ�c��#�\nj\$2N��lO��aU�&��W��_/bu�^���\0�Q�' q�(�%b)q��D�1�Nq�z�!�4f�d.�h��(��>�C��'-t%�1�L�2J֑��%�b�b\0��=�����bQ����'ĕ �9(jS2�%�p	\r�N��g|KÌ���8�Y�~pH_Nm+�@����Bf�_bdШ6�/n�1�XW7����2N2�r�c�\r�V���m@@\$��AHlE�#1�%�:o Zm�Xq��C�0�6\n���ZNg�4dNO丮�.��Ԫ��㉸)�8G�������jVO`�f/\"�,�2^>ͯ\0\rr�1�F�\\��涆L]y��:�w�\$rati��.Tr��5��0c\n6F(g�|�Fh1��P�`�b�'o2P#�G�6Pd0��i(��	V���CS�R�?oR5��I�\n\0��T3B����ׇj|E��\"�6S��ov�����{E|�M4��h\\�#&��:�����D+�B��S��I�B��1�!=��R�<v����%�I�p}I��.�� @���k�rs�M>��,�XG(=>�";
			break;
		case"pt-br":
			$f="V7��j���m̧(1��?	E�30��\n'0�f�\rR 8�g6��e6�㱤�rG%����o��i��h�Xj���2L�SI�p�6�N��Lv>%9��\$\\�n 7F��Z)�\r9���h5\r��Q��z4��F��i7M�����&)A��9\"�*R�Q\$�s��NXH��f��F[���\"��M�Q��'�S���f��s���!�\r4g฽�䧂�f���L�o7T��Y|�%�7RA\\�i�A��_f�������DIA��\$���QT�*��f�y�ܕM8䜈���;�Kn؎��v���9���Ȝ��@35�����z7��ȃ2�k�\nں��R��43����Ґ� �30\n�D�%\r��:�k��Cj�=p3��C!0J�\nC,|�+��/��╪r\0�0�e;\n��ت.3>�J��,��\$2S���ҼA#\n������͉�z����z7M( ���0�AM����!�#��!\0�@�;�J\0�TBI*9�`@U�CF3��:����x�g��U<������ax�	�c ^+�ѻ�p̾'����|�=����T�&�O+�ۼ���*�cb\rG�	jH��8��_�\0�7���J�B�^7G��C����:�\"��6�\nu�Y4���K�,1�yx�5�桪c��C����C�5=6�����L�6��%��h��ӌw�(�0I�\r�	��V�G٣n�:4И�*T�wK�B�B��i�bR�M�-5��V\\�*���&L[�>�c( �H[�!P��aE�<�`��Ϡ�2��C+�6��\\�A�7�殛\r#b<��H�S��(��3}\\�phYu�O8\"@WS���������+	�>��>�q\r{1:V9�lx���p�3mSX���x�3NtÌ0�I�ޠ���Beê�6��34&����<��0�w��\0 d �O+p�\n�)~�ޱ�B��Q�7��*��n90C�N-0䯙�*�}`Ն�V:�Yk5g�u��L�Z�`7�`WTE@�#��һr�\$f�4��\n�Bm���0-D�3`�NBi�`j�\n!c\r�W��e���b�ZJ �8��_2:}p���mY�P�H�\$��P�7���5�⤑�>Mݗ�~���Sj� �/êk��2e&���bH V��I�R���aѮ*�	��V�<��LqM�6>b2gCB3�/�����z%0Ƞ�J0��w�h�RJ�232A̐�LJ	�&\r�`1� �nI�v�ٍ�spp��o��/J�%�Ġ[d)r�U��l[����0��ǖ�<�&�?�ԕ?�)� ��S��Nr�(�ZuH�hL���U2NB��:)��\"�Q��'��L���4!HF<!�`�	�0(�L��rH�y5h�� �,J��7��d6i�D-����6\$y�[a��\0�¢�1J ��E�:U;1�%��Hq18mЎU���u\\4eL�7hb�#Uodq���BI�!��EYS=�PLCs킒�@���q}��㾃C5h��ȓ�7k�:&4���Er.ϛ�N��<'\0� A\n��\\ЈB`E�da1���S�h(�(�q�nd��6@����\r-)zO��,�)GK�ު��}/�_@�<��Q��y.m!��\n� u�%�\$5�Uus����2a�۳�J	˹�*E�E{sA��@4��xi(d�ϼ��v��`�qe��\\����G��i�Y�8J��RU��pu#�t:�̇<N�s'f06>��CҚe�@�(6�A�\"��Fm纵_��RB��̘,���he�(+��N�[�b4��;��X!%Ъ����OZ�0\r�D93%}�C�yԯ��qX\0T���9{�&�!`((	4t[�\rg�%`��\r�\r�I�RNtR�s���'�b� T!\$�_P[�ʕS�W��H�9��%h&�L4lLӲ7�ӹ�d�A#��ZɊ7��qM�L�1�^���-�^�&�V�czn}�i���J,���B2k�)Ŀ\"�{���;�PS�w�X9�}Fm�ƷcB1F��q�-�f�>�+�<�_�ԃpA��;�y\r'��3+��ڐ̰a\\�B)��ۘ�U����BD0�\"B8xS�|Sh���^����w��(;�.uJ�L&���9� c�A�!g�|\\2`�m@���}��>|�m�ï@�O���!�߆��D�x��L|~?�y�}�V%���\\��9u,�c|v%\r���?M�r褐L+�A����f���^%p��V(�����h����ɨp�iÄ�\"��j�A־�S�_�]ﳌ��z����q��Z�\$�ٹn��v�Z_12vR��G4����Ĝ�L@v�2,4��\$��\0�04c�I�7\0�P�/x�/m\0,N�C����L� �^�P��;UOd�pN��RDpHҩX��x2���c\0���h\$��.U���m��x��`�0z4O�P��cP�M45�8�m>���\0A\n#`ӫ�G\0�eP�\nm=VL�j7���{B�%c�G���iH��*��b� �XW�|���D#�Z�\"TQ�_\r��00���,k�	M�����Q�����\n�B�\n�Q�?��\r�\$MM,�l<����L��p9l#c����3�u\rN�؋Z�o*����1�p@��%/h�����(_V�c�̀p�H���fI�i@pȰSb8ɩ�B`ŃnOg&��p�Qu��s�<�1��3�jҪ�i��s�łK�xd7 ��R�Fl/Dt�'4.ώn�&/�P���&�\r�3�RFIU\$�]�.�2W\$�O���I%�3!�3w%Ĭ�R33���Y�:r�x�Y'Ҡ\$/*�B�ԒQr�*��+lJ����T��\rO))�=,R��mO�Ԃt ��n�P\"�+0[.���)2�/2`�1��K��J��-V��3h'\"�\\�FS�����!bF�����+/�f�^�46#r1C��(�f�!I3/5���Ī!�g�c�\r�V���k�,\r��\\�9#�4�:����'1�p@�C�1�\n���Zg�\$��3���O�	Y3(b�	6�h�p���8#Ϯ�i�%#0æ��Oo�S`�K��`!��>��ʇ��#�:�'�(��T\r1Xt�8�|R+J\\e1L�QC�Ղ�Rd�at`B���n�L�>q�.��7p1�i3��e\$�D��E�I=/���|̶6�p�h=F|�ͭE���4t!W2�l��H0�Hc�\r��*b��\$/JD�A�J�>Me�I4�/���0M���Q��l[d�#���G?�.\"�`k\$Dl���6#Nc���3B3�96�Th2�A��&%oR�5qR�&L�b��̲g'�\\���;\$��Ĩ�3>�����:";
			break;
		case"ro":
			$f="S:���VBl� 9�L�S������BQp����	�@p:�\$\"��c���f���L�L�#��>e�L��1p(�/���i��i�L��I�@-	Nd���e9�%�	��@n��h��|�X\nFC1��l7AFsy�o9B�&�\rن�7F԰�82`u���Z:LFSa�zE2`xHx(�n9�̹�g��I�f;���=,��f��o��NƜ��� :n�N,�h��2YY�N�;���΁� �A�f����2�r'-K��� �!�{��:<�ٸ�\nd& g-�(��0`P�ތ�P�7\rcp�;�)��'�#�-@2\r���1À�+C�*9���Ȟ�˨ބ��:�/a6����2�ā�J�E\nℛ,Jh���P�#Jh����V9#���JA(0���\r,+���ѡ9P�\"����ڐ.����/q�) ���#��x�2��l��1	�C0LK�0�q6%��3��̎A��0�6�I�gA�2��/\0P���!0�3�@��Ή�x���f���*Έ*Z����ʝ\r�>9+R��9�/�9�l,;�CCef��J9�`@%�C�3��:����x�y��R�^�Ar�3���_Z���J�|6�	��3/\n,���x�9�\$��X�.�P#���UBh�̥�C�� �_<o.E�\r93�ɍ����\rb��AN�J+��r�3�h��\r�����>��ŠP�#s;9jS�Y�#��J�2�YF88s:\"�56+C��:���,ͽ)�1ţLJ��ɩ!�\rp��(�Dռ�\\kR��`�� #:�Ѹ�j:�J�Z���2j�2#r�ꮐ�&��9 W1�8��9xӒ��P��/u�=MX�M�B���GiM������L ʃ˾\rL�7��|�M�:\"�_'�cWJ�]�]j sH��zuSB����i¤�\n��D�(�����	;�̞��[bh6\$m\\4���b�'�\$��y�W��<�Øf(a�[Ġa aV���҈w�(`����@L�� f�1��z\r�)�0�U���^��q�za�������ֻWz�^a�z�%���\r����>��)��'�Bp'\$\$���X��N1K��\0�uñ׊��+|fL='�5\n����Q2:|�8���ƹW<^]��x/%轖�_q�~��y��	)�ʧ���(�m4s�{C[\nD*��+9P!�19�q!�Q.W%�8����V\r'p-��M��7Q1?�p������b¸Z`V��8aۧ��y9�/�0�#�&�y2��H�P�d���hA�����fy�q,l(x�Cr��KJ_���n�#G|�8䬂���Q �C�K�\"*����v�![�d;��i\r�:�0D�D%hI�C\naH#\0�0LyEa�1�<�	H3D�r\$��JJE(�y.�VrkR�MC�\n���X�\n�K�OI@�^\$q��l���d��KGY�7K�S�#�RD���*g���P�s�����+�50�T��-����y\$�T/.I<�r�A�-W��g�JI}zQ�u@Bm�qJ�����6��?��N��\nRӪ�@)����M�,�P(��\ṅ�h3X�r���jO����@Q�R��+�T	cf	M߃�~��m��(���z#I�۴��Y�z	�Uˠ&�A�1!�3�z�ɀPM(ʳ,�����\$\$����S�*�y�dј��۠�d,���#�����h�����#�w�����I��{ځɭ����e(Vk� ʝ&Z��3�`��u�	���(��T�>/j�Tl͜�oA�����|�5���IB\$Z�o\$-Ź��~���zO3SC�qԼ�V��v�n�(wN�Ҳ��¦�.�|Ç�ޞ1:��V��C&��\r���7w��*�Q�t�<�e�o{g��@7H��:�d3�@\n8cٛ91��4�N��J�6|��bt,4�=��5zl%�ݼ����\0PE'm\n[?բY�P�eD����Aa!ֺ�eK�!�C�)R���\rl�خ�;�x ����3����#:5�((��HX�c���r��N/ֿ%zd��g���12<̨�Uʒ�9d������@�<�O`�s����6���]��o?�ǫs\$�\"6��=]ZU���ûEg	M�㲐ٹ�dĪ���U����\"�fz�M���7���=��]��b���H���)\0@�	�c&)\$�o���3�d�Pv�Cy|g����&�#�+)S���W�	�^���r���%���ѐ�f�߸{���?�}\\O�3�b�ꢇ��F��x�~���Xt�l�����k1����p�>�k�@R_יj�`b��*�CJ�Nb�\"L�Q��nL.k��M%|�\nsC��oENJ�&�<�������O��Fb�VKB�֐>����=@Pf��r���Ĳ�jǌ�U���8F��L��̟\"rLwG���+O��b{	L��L����\n�҆�	���O��g�Zp�	�30���H�lv%t2��0�쮆�\$*��J�h���gp�o��y�O�7-�d���J5̃������\"��g���0~�lw'�c�������8�k���6�#���i� �#��:�P���>M�PK�x�^<�b���,cx%B\\�\\\r��c�;(J��Z&��( [��k��(�#J�&h��׍b�\r�ؑ���^�mJkl\r0����O0�/��U0��U�d�I��@��W�. O�\n�8�c.�r\$O��D���\"M�!��)GV��׫�<4e���j�J���ԂB:@�aDE,'�d���:�j�\0���v��\n�<&A���w(QJ��\n�u�ҋ ����� �CP�)g� m�\$�\$�ʲ�F�l<�E�oA�p*�b\n!�r�Lw.2�W��.� \"{/�.�7.��R�*Ҹ=Ss!��,��1�\"s&K2�//�\"(�d�6��s8�3��4\$oKT!L#�13��5��Hr��C6�_\"��*�I\$g�/3jM�y���0NM�3!M�@ľ��H��8�S�Td�6�7z\n��L3�;	�� 	*x�����3��Wj��f\$O�?e�Ψm,°���J�>��1s�(l�hXW��gl/���s���3p:�j��o?S�;2/@�b�%\r�V�Fb\rq�7p��\"WB&���R.H\$r\r��+I�F �\n���pGBN����&�J�h�Y����\$��=���c,��:#�B\$g�	 fg�xgbO\r\0{��3G��P�l�%Ҝ�)��;:��\$#�sb.0�2Av��Lh��#����heL�D�BjM�T����P�f��P�C/`r�Ϻf�Z�ЫFT��u ��.���2�#!�eI��2��6�l2k\$�d���<G1�eR��f��jj�D�4�x;��l�j��X�CF�&#�DG>22d�g8oL��p\$@�fep�/��ƧEb����F�:u&�CJ	�CW�8m�z���5�4��Tf�\r����~2���')E(#��4���\$��	\0t	��@�\n`";
			break;
		case"ru":
			$f="�I4Qb�\r��h-Z(KA{���ᙘ@s4��\$h�X4m�E�FyAg�����\nQBKW2)R�A@�apz\0]NKWRi�Ay-]�!�&��	���p�CE#���yl��\n@N'R)��\0�	Nd*;AEJ�K����F���\$�V�&�'AA�0�@\nFC1��l7c+�&\"I�Iз��>Ĺ���K,q��ϴ�.��u�9�꠆��L���,&��NsD�M�����e!_��Z��G*�r�;i��9X��p�d����'ˌ6ky�}�V��\n�P����ػN�3\0\$�,�:)�f�(nB>�\$e�\n��mz������!0<=�����S<��lP�*�E�i�䦖�;�(P1�W�j�t�E���k�!S<�9DzT��\nkX]\$������ٶ�j�4��y>����N:D�.�����1ܧ\r=�T��>�+h�<F����.�\"�]���-1�d\nþ����\\�,���3��:M�bd�����5�N�(+�2JU����C%�G��#���\n�T����,��`#HkΖŵJ��Ljm})T�ʣU%�c�Ļ����7�\$�qN\r�9�\0G�j��\nu2N�\$=\\��R2Oճuƌ�n�T�6HhԿ�2O9��r�0wd�V�r�# �4��(��`�?3��S�\r\$�Bb_�E�V�۸��A�\rc�[�Ќ�G��\0x0�@�2���D4���9�Ax^;�pÓeP\\7�C8^2��x�0�c���xD��\r�FP�s臧)*��D2�\\�x�.��#��E�����l@&.#	�w�d���< �;��3U�rh_�2:M�ZݻD2\$����p�6a?�H5��B�;`�&v���O�㪯ו�n�C��wB�)��2M/*8��*��W��`�#�6��L5���b�r���󭊁	��H��,�HLI�KpG�P��K�zp+M���Bp��M�9�-T�L�A�A&��R)�!�M\0���[�`�����KEkX1����*P?����3ܱ�{�?�,0�k�Z�Ԍ�SRܠ�I1\"T.t�C�uq}�'vKX�Z�i�(�<`��W�ej�T�k�GQ���XVHgK�J5�[\";�+�-E�SӤ\$#\"��4�3&F�7ē�4]�t��9/�� B�q�A�����H��9��)q`���-�ĺ����K��@s\r!�73��\nb�A�:\$��QbU���hГ�*���#x�R�}Y���Ld��L�R�u�'^ĪC�8��L�焂?�2�[�of�0\\W��J���A��^�T�#ʶyHI�ğ;:��XO�^g�F���P2\"�)\r�@�е,M�]�3����-;'r6�q��Q=i����~�jT�ie`f�#Ɔ\\i�M!��q3:�J��e����e�S��_�9&��IQ,=p^m�j��%d�94��I��&�0��rQ�BǸ�v��Z{QjmU�����Z�{lM��6�^&`a�-��&��\$XG%�W�T�g��y��`�O�4����YT�b�K��1S�xi�\$���\n�\\Q��)V��p�ݏi�A�5F���\\�y�W���[;i�d�F����;��'z�Bmp>��0�K��!�����<Cgm�&x���LU��Iռ\\Ft)pM1��8�%��#��a.��٩�q����SSi�#j�\nJa�#�Z��\n2��ɰ����\"\nP!�\n%JO�ci���4(��S�_�ʈD\n��K�y�Of����`�:Dˮ\"B�]LSE��Ѩ��\nK��#�xh��^�f3w�M0�`���\$Ņ�=_K�e�q�n*ML�_C]7��p���)��*��gs��'Z`w�L��4����m+*Y �0��0-�)`�Rf0bV����Q��8�J��^P���<}'m[��X�èg}5��/��3]E'=��	�u�<�1�;��Q�E�v�x��0��\\b�q\\��eP���=��r�{9?DJ��k��ڭrS� �Ҁ��Nc�OgQ�`-��c�4DTy@'�0�Y�`��*�zX��P叏�����MϘT��-��9~�Ш􅳒��\ni/R���z/ǈ�R8��@�B _E�G���h�`�QGU<p���4I�}'��`���d\\HIt8ѡ>�;�x�2�Li+�IWo-�靉�\nC9�AE�`��&��l�'���������]J\\�؟t��BL��K�����\rjԭ���T�ND����£*OU��E�D��?~�T�D#or3�N.Y�U}Ġ�r�X>5%���9�l�w�՟�,�a��C%TY4K\$��8���z\"v`���d��a2�z���'�X�D��\0��\nb��Ǆ�.@t�N�\n\0�\n\rt�\"N�(\"�*.I�XF��8p�����\\#+�#�������(Ƒ�Du�>vH��#ZZ!\0H�P��R���̐t�*�I#��#�[O���D���Gg�	���d�Nt'GG��c���g�.����4'Ǆ�B��`s�4�0�0prNL�\r�^�L�H�u0�u��WrcO�(��n���TP����psǢ:	;\"�-*f���[\n�-��s\0���gNQ����j#!JF��*��B)An��x�������N�Z�p�b�\0�K�Ln�V9%\\`��\n�� �	��H�\r�L�^(l҄�@&�.�0�d�K�5l �\"G��ykS�@y�:h�A��/%�~��n��`+���0F�\0�1�d!�Q��n�v�P˯ �\n���,*� /� %��<r(��!��DnF�U!�DV�\\Ճ���\0,h���W�X��\\�b�%��8�&�m�#az�b�;,j\$�(\$(#�(��(��RҒ(O	)��Q�C�u(�{*�ho#ܫ��	(b�c��>#2\"�b%���=N�(o�-�[.%�/��-��.�n!��#¶R�?��0�\0#%�E ��n��(.�������[.�\$b�\rL�XJ��t��`'أ\$�5	�u3�i�~��Qc��d:v��0a#3i�\$�R�gJJ�\"2?8�,���1s�9bQ2�X-����K0k\r'bK��/�\"�(�\np�n��;��K��s�{�7��,S������#oD�����'�T�]=�)'�����f\0R-D*'�s�a���b�\"0tK�\n��SCZ���?Q?E(=���ؤ�X�+?��\r�M=rV�Pk@c��^ͤ4�Ġ/NI�@a�!*��S��6���a����u;�)D3��\\���ĸ܍\0����_>�	\nt�K��h�#1<e:��<0�=��L*�Ģ�4�;E�6��MJ�H�a��:��P+/�5�.\$�)Ӥ�5O��J� 5\"�4?L,���p@uz��*�SRTc,�+R�PġSqoΗQ�:�R`V�T��#R����QQΩV�!8£ �{m���XuHRsr/Y2����X�A& �%�\\&-�TPA�\$�-�C�hu�H����~c�&%���1XG䢲�g��RbH��Z\$�~�#\\#�r�i\"��_�8R��ʦD��)u�Z���4hu���0�@0��(��KP1,�T1*gNwh\"tO!9��J\n3F�W���g;/UX����3gIw���6Y��K��h��M4w�g��z�ԣ�5�mMԔ[i��*@���Z4�,�5k�=(U>��N2U�\$��k1��o�[q�n�B��{n�!A\ro�Bx1i��[�\"���РDqe6`@5w�h\n�I3�\nw#S�ar���v�,75�'Qh�R6�gЧef��x ��VS4��t�r����\$1u���C��wn�%w`��\$/\$�H�D�cO]��AV��jt���1\0[k��X�0�0�xu59�B����bj�\\w�f׸b�|�p�i���1<:W�TҩuwB����w�7ײ���{��jo�-��w퀰\rAwFeOk��k��6#=��zm�t���{J#[��q ���n�Y�-X�.�EXX-8]l��YR�17���o��AM2�xy��w���͡��z����Ko\$qG�\\�]\$���Zx���	�U!>W�!�uz�����U�u(�.�KZ�z���W9QUcD�H/3�1/�Y�-��KrO(�Q0*u�uqq2MJ�g�O�K5SH�3�\r�Yt�C��{�F9L����0�\n�8W-<qT�Q����Tvq'��T_TYm�˓�x;j;�*gX#�����a�-dX��Wȴ+���&T\ru�������Ycn2�~�*uv\0�n�\\��â8\n��� qm��o��Yfx��q�J�	���%��rq�&ju�6�4�O���&X_\0�P��,��`źO�I�U�����qg��VbCo=7řu��3�A�-�@�H�KifZ�Dc��\"\$�T�zyr���92�x�e-�4���/�e���'\rg�z��4��W��s��+�^�3�<�b��TbK�U�4�e��T��L��Z:����d�pd\n)#�MFZ���*�*s;z̉d�3������ѯ˯Qt�Vy6h�xЂ�E^S�;����G��?\rF.��l+�q]�մ䍵5�`Hr��dDK����@��u���b�R��O��ձ�L���}Gu�y0��m��1�Spq�b�O�C4tG0%�@���L�\na뻰�{���ۜ���z)�~X�7Ղ�&xB�,��\0z�T���SX�Z��@";
			break;
		case"sk":
			$f="N0��FP�%���(��]��(a�@n2�\r�C	��l7��&�����������P�\r�h���l2������5��rxdB\$r:�\rFQ\0��B���18���-9���H�0��cA��n8��)���D�&sL�b\nb�M&}0�a1g�̤�k0��2pQZ@�_bԷ���0 �_0��ɾ�h��\r�Y�83�Nb���p�/ƃN��b�a��aWw�M\r�+o;I���Cv��\0��!����F\"<�lb�Xj�v&�g��0��<���zn5������9\"iH�0����{T���ףC�8@Ø�H�\0oڞ>��d��z�=\n�1�H�5�����*��j�+�P�2��`�2�����I��5�eKX<��b��6 P��+P�,�@�P�����)��`�2��h�:3 P��ʃ�u%4�D�9��9��9�cʳ\$��s���@P��HEl���P��\$��-��64�ɀ��r��99#d�N�1C��p ��j��L��Ü ����~9��p��ִ��)���:\r�{TB���2�\0x�\$�(��C@�:�t���/��09Ș��!|)`ZC ^)��ډ���̉�c��7���^0��H�&JNUk,J�������6�X�)L�V���П���r�6��:��Bv�7c\\�+��m���� Ac�C���h �<���x֎�p�bC��1�B��	sJs��4H�:��\0��C.>:J���:��uJ:�Xᆱ�0��Or\0�3�#;���y|�9@P�2E�z~��X����=(J��	�u�+i��d�*�E^5�r��6\r�c\0�!��7!�T-�nd=.#�l�1�C��2oVN�64�B=e�X����	�a}��~QR6傟��ح��'	�Z`;#l�\$����\"�3��w^R���L���ʣ�V�,`�m]9L)���3q\0�YT�nt�����I&a�'6�gJI)��3`ج�HO�uU���	�\r��75t��s͠����M��0�p�]A@h��PP�L!�j�V'��CeNĄ;*��f��)\n�����E��Xj�u媋!�ip�Qk������\\+�r�uҬ�b�^���f��\"���v�\n��������w�\n�U*y�`P�� F�䒒VVU��B�`�����P��[\r�8-����\"��Xǅ��z񁑦@���� p'\r�C��bR�#f��ֿP���M^D�\"{Ns\"0��vY	�&��\$Š�SԀ?�)�3��Ӄ�_ML�0�cdE!|1�n�D��\rd�\"��9P���l�佗�0@�	�l*� ��Rի�5�l�b���dB)�ӥC���L�\0PS�H\nl�6��c�4�|2��t��Y�A�����g1�_���4(��	�Y��7�����ғ� η�u)��0�RR����2g���F��bb��d�m	�MI��JB7v�\0i�/fA,%���B�5a���'����\\����^w��2S�HI!a��+X�P�a9gK2�6��\n� ��H�X��c\$��\$(jPӱ�焞)����O\naQ���f��4��pD�՚���������1s�F�P�i�Hp��\0�1A]\\-�A��,@�\\�\0Su�ʞɼ��P8!����Pu�M6�R�RèdI�5��!a�Q9���@�p \n�@\"�p�!�&\\N��i�!�-���`��\$�\$���t撨Vp��1\$�6G��<��!�T�ȁDY��E6tN���A!��WV(c}N-���g�P�{l웆�\n�c|3��������/��f|�d�aC�7��[9Nm(Ę���ȱNS���0\0�92���T�L����=>��q�-\0>���pT0�����3|�C�l�p�pM���:Мf��\r\n����N�D��2�u��%�F��<U�s�صE���\0j�H���m��2A�xD=R�*/���j\$x3��呈��5��(���ڽk#n�ƌ1zVMx\n�,e]\n�X�N\n�rݰ\$��[�8h:���xT\n�!��Ak���V�]�R)�h>�f���pA��/+�\0̠���L�\r0�#�������qEf�X��zPuZ�IҼrs���xpL\0���gS�V��p��O\\��{9���[`����ǩD���\"�M�\$ܐ�KI��c�G ���<�:��\$H�k\n̗�a�`����t�y�e�0�y���=��^ғ;(<�==�0��S�U�@܆�I�)Sc��1�CE>	G�_\"�YƜ�K��!�B)\0ɘ ��H�!��H���eP}cҁ���d\$z��X�(��c��蓂B7b�6���F�0�J�C���Mb���b\"θ; �=b.�4���̈́�Ԃ�;�n��ap�p'�v��B�Mf۰,\"�6�-��Lۂ��n\"�\rF��c\$@}�\\�� ���(�\"~H���LY�\$O��W��\0b �� G�c��.P�G��I���(�#p�˅\$��IƾܨX�I4c��(�I��*�Ɔ�j\\�3 S�@g�xpe�V�o��xBI�'09�-q'��mg��u�:~�zqM��Pzz�B{,�go������10f1�6���c�F�QhJ���k��-\r���!t\$�;�81���<��t�Q��Q���b&�|&��b�����`������,C��������Q�����Q�ޡZE1��q[���\0�\rF\\IB,n�!lfi���S�,=b�բfI6&\\���&fu!�}#�e\",j�L�\"�Zȧ�[B��.��X��l:T�r��.��,F²O\"O���-�R\\'Md��V�&+2\$i��&�1��'+��M �+��\n����-�d��:�8(B�2��l�c�.C�1=R�r�r_��9��2�(c&y�D(�0z/�@��j�EƠ	bL�s	-��\$T��u�:D�l��Q�.�K.�?51u��apu,�	��8fn��e�v�qpc��ς�7�A7�C-i/3����\$���BP��'�V��YL�@�E�t3o-JY;��'3�/��G�y�<�8�9d:��9F�\nR.��-���<�n�e�1��:�F.m�+�[?���tV��\$ߴ(�11?ӂ}�?Ӗ}�BeJ�/����J���Aӹ@tT��\\��+p'Ed�F��7\nYB1f4��R�ӝm��d���h[4+ʭf�Ѕ~�oHc&	4EOJ�\0W���c]JO�����&�\$\n�1��G��(%\n\n��qԫ�\"E�,\$#��@�|����\r�V\rh>?+��+,��(%�cB&T�G�G@���K�\"��*��@��Z�Eb6<�\0�LW�h�1���l�U\$%H�Y�)KCVF��V�_t��mh)),#\nB#�>����&	�JĜ\n�R�FI�_\0r���>)#dg�C	�Ҋ�V�rrE�<�.	��gE�l'�J��/��iL�)�4JdJ��e�:�U���o)�v���-ӻ�ih�����0p�͢(&ޥt�aQ;q`�'V4z��r���`Ed�3`-h���+�2�a�z����:S�\r0r���v({&��1���ed5CZGN�Z\$���P��d�5�\"g��1��ED����1��[m�\n-�����Ur#�������j�*է9��<6S�<&�\nTu���gWo\n�K�	\0�@�	�t\n`�";
			break;
		case"sl":
			$f="S:D��ib#L&�H�%���(�6�����l7�WƓ��@d0�\r�Y�]0���XI�� ��\r&�y��'��̲��%9���J�nn��S鉆^ #!��j6� �!��n7��F�9�<l�I����/*�L��QZ�v���c���c��M�Q��3���g#N\0�e3�Nb	P��p�@s��Nn�b���f��.������Pl5MB�z67Q�����fn�_�T9�n3��'�Q�������(�p�]/�Sq��w�NG(�.St0��FC~k#?9��)���9���ȗ�`�4��c<��Mʨ��2\$�R����%Jp@�*��^�;��1!��ֹ\r#��b�,0�J`�:�����B�0�H`&���#��x�2���!\"��l;*��1��#J��h�_!�\\L��LT�A(\$iz�F�(�)�ϐ*5�R<��l|h ��j��B����?H�~0�r����8@��/��� ���h�\0�C\$&�`�3��:����x�Y�͵�Ar43��x^�Q�\0��J@|���8̍\r�H�7���^0��{����,:%�P�7�c\nD�C;Z2KH���[d����]�r�.�H�7�7e&B���R(�K\nI����5.xf�\r8��_�\"T6(�;ŠP�%A���## �=2Of�����F�8�:���d6�U��/�0��s��-���q�N�����Z�9'�ӉE\r3���&#~`	�BI�x�q�ib�����6\r���ˢ��\\-�(��Pȃ�C�X�^���a#��z���v�\"�}�[< �q�n.=MP,�5\n����}K������N�!slh4��j��j;6�s\rwc��ޏ���T�����S *#0̝*	x�2N���*\r앚7,��:Ѭ��3g@���T��0�h�a��ꀅ�R��S&��9�ӮG�xT1ǔͬ��� P\n�� ���CK*xJ�ƐUP��b�V\n�Z+e�Uڽ\r����J� >��9H�%��ɱE G��¬)=A�Е�� �Ѐ��|H�X<D\\^�p �����Z�Պ���@�ut�����V�C�>	!�8u�!�>xi,:)�(gViD\$��>6J��7���`@�	�b\$��%��ׄk��.=I�8^r�pa���@䖅f�\$Q�(���_y�S1�ѐ �RX e钆�>��7��С�,T�zB��������_�. ��AH\$t�'�Dny/k@�I�\"gH9�3�e��6iO��n�;��6z���,yjqÃ�S�*�sH悂�Q����Q.�Nd��ZrS\nA,��n	!��� �@�xk5���^#dq�FA��!Y�Q�d�<��H��|��1D8��	�\nb�k0�b�<�'�7G�Hf�(qg��c�G �'�� 1�/g��30��bv�V���@'�0�B��\rG]�*d�i�p�Hd��f]Rk�9H�j!����Ad��)6[{	�v�#\0M�0T��-\n ��O�^IOx95r���&\rf�:�\0u�3MDm4,jhu,�d껃�p���ca��)r�l|5�ճ6�hI\n`'����vG�l�8&a8���y�4�d��^�*\\�>6&�����q/E�[ �cÕA{'I��B��\\�@q�<�pd�z�c+���@��\0�E���rR��]��h�ro��)z\0��xr	�t�8�'�2f��PZ�FŚE�e�ӜS�:?��˳ +�-`KD��T��d�\$2R�,+�B�\0002�u�%��|�α��s\r{�6E�����H�C���®�wI��7@�ٛ��n�4L%���(A4lL+zB��J��rb:q�L&p\\˘\n�0���|Â+��\\2j%�1O	[&}2&��Aa U��}��SQ'�{��I��v��t���\r0��1m�IZ.�0�@2�-����a/z�\n`:h[ ��i�7��+i�r�V��]2��(	�	h6{�vn]�iTCYov�P]�~��j^Go}��w�;�5S�����%�#�oM�÷��\\S�S���6b#\$�ռ�<Q§	�����\"x��>����w%�7�R�ߜ�f�7����ψH�͖�9(�q����ϝD�S^��zHbZ�\$�6O�`w\"�8����J�#���v����r]��^oo��L�҆S�ٗ�@�x�N�f+ɸ����C�������\n�\"����~��\"�G�}�`Kڋn�vGͺ�.,�\rձ��|OP�R���>�����N��u+�a0U�e�����x�G�̜Un��.M>I,�Yhb�*�\r��WoB���r�5��������\$���C�����u\$�\n��-\\���@���ܧ\0ҁ\$Z8�F�\0l�:i�>��\$b<\"`;��M�P�Ϟ�!|�PD���M(�!l��pF�ϻ`�����v'5�\0�#Tʏ�u\$�#VvM��@'Kl	btnÐ��\$n#���:\rnI|��ٍ�	��\n\"\nkz%P�0�6N�\r���۰�\n��(p�A�-p�0�7%\nJi�A��\r����,�ϫj����w��*A�(ϋi�ov��DH\"L6��7Թf<���6h��[K6BJ�	�.bl\$�X5�\$9�j�L���>c���hDtY�zk�*T���]���a�:��n�(|Υ��w�.��❑0��)���B[��'(���K�	��d&(q#��)o�ͱ�QBl�I�?@���q��!\$�uTB�\$� �fXdN�c�:��� �1��pd�[�!O� 4����		\r+�reR!�(��L}��Ng±�%r'\n�h`�h���+�p/�Z�1hd,�\rbf\r�����+)�!q�+,�&��ʲz\r��,���e!��-Ҵ- �1��_.��,P�%��q�k.2+0l(�\$�� ȾKu3\\m'r\r#T�!12�!�\0�D�2�G22�.M�1��G��&D�0*3I�/Y2Rg1B;�[6�u,qBɢx/cb-2U&+����{`�s��dq�����Gd�\ri�=��8�|�s�3P�:b��:4_�_	Š�,�\nrJ?�v`�99��6и2ojI�02�\"��;��.s�6��2N�/Q_@nJ���_��=�I��kg2I�\0�����7�t%������~.��\n���Z{.��R\"�tO�>���}F�N��n'B�\$��t��m�d�F#4]��:���Ի���@����mECi�<dN�#|en��%�-�� O�C%��\r�*Y�=��M�Z:\$�n���F��.�*,�.ӯ�n��K�Pn.^����QP'������/Q�Q�r3c2�-@\$��U*u��9��&�&aD.���̀���\n��'����.c5l'Up�����)��\r>+��Q��G\0	��TGi=�\"�Zf���\\��@5�\08�T&*�΋ؐ#dC&��.ss����#�&Ί�@�2����-�A��]\r#�v?+n��";
			break;
		case"sr":
			$f="�J4��4P-Ak	@��6�\r��h/`��P�\\33`���h���E����C��\\f�LJⰦ��e_���D�eh��RƂ���hQ�	��jQ����*�1a1�CV�9��%9��P	u6cc�U�P��/�A�B�P�b2��a��s\$_��T���I0�.\"u�Z�H��-�0ՃAcYXZ�5�V\$Q�4�Y�iq���c9m:��M�Q��v2�\r����i;M�S9�� :q�!���:\r<��˵ɫ�x�b���x�>D�q�M��|];ٴRT�R�Ҕ=�q0�!/kV֠�N�)\nS�)��H�3��<��Ӛ�ƨ2E�H�2	��׊�p���p@2�C��9(B#��#��2\r�s�7���8Fr��c�f2-d⚓�E��D��N��+1�������\"��&,�n� kBր����4 �;XM���`�&	�p��I�u2Q�ȧ�sֲ>�k%;+\ry�H�S�I6!�,��,R�ն�ƌ#Lq�NSF�l�\$��d�@�0��\ne3��jڱ�����{T������R�\n�,����!�\"��D	���3���FK�,В�zs3U�D�<��o>�@�2\r��`G�� �r�#��p��|^���#���r���P�2�\0y����3��:����x�\r�wF�����p_#�rL��K@|6�n\$3Fck�4��px�!�D�3���L[jh��#4T�M\0����\\��QR���Y�r޾��z�8�'��6�]t�b��9\r��F��#>�N��(�/��^�X�:��\$��Ƶh��b�v����^�+�G.����:���0����\n�f�G�*5?�ɺ������Ī���nST�@��;c\$� �\"[������z���\$�wU7\"m�>I(�����7�戮H&f�s�����P�R�ʖb|�5�j�f�[JA`()����y�K�����@Q<<g�ۓ��L��Z�U:����֘�m��!�Tۅ#�4�e� 44L��n����]jm1p���\0�?���+����Y�x��\0004�� �#iu0�z� B\$��M�\$���A��@ Fh�7Fc�c\\i������C�k)IX���R�ɻ_Z5`�qk�mX8���#>\r��X�W��Π��Аsb!�9I@��\n�����N�(`���2\"O�b+�zF�r�\r�*9��9IU��������8�C\"�bf��1�4�� dL��L�R�[-��6�F�ˁ>���	�6�Zޤ/ d���܋1�ne���\"�\"�Q�bzEC�MG'^b�d��xx��\$�\$�&�clu��F�+'\r��2�Xˣ�u����Xme��w��s�o�D3��Β20�T�&�(t]bO\"�Z=��/�w�k ���t�\r|�f�0l\r��1��&%2o��3Pą&Y���rzP\"�vN�[\$A��\0�Ei�iu���!�a�Wy%{�ĺ����)S��2!A\0P	B��Ŵ\\SX.�ի��/JCh4�1�i�O1�9G02�Ԉ���;h��V ��ZF�簰��ܫE�e���,c1a��;���)�i�n����::-e��D�BS\nA&\0\\A�vl��,W�ʑ	������]	UK\$�>���5��H������*��CY�턈�Ti<�%\nV��%i��Bdd��2�vv�{.�9�̌�lɤK��\"��'��y�i���J�����!<)�K%��IȖ�����,Y>ē5��h2�)X��(H�T�r���T�������dݴ5�\$���B7F��\"�'�3\0Wh@X�� 9,,#K8Cs}\r4�{�pA��~9GA��#C��:�O�d���ʇ��Ĩ�AW��p \n�@\"�A\0(�K��T���H����\0R�� �&]o�u��\"O�K�^D^��!j�|�P	�P(�wV�Ҿ��j�Ro��<�b_yyj��B�\"�J�Q�F��;BU�\"%z\$��g_�,���+\"ƕ����qJ��k=0��)�.Y��B��*YBm����JU�nxQ\n��E�\\o��h?�	&'�Aޮr0L�Wy7�~�b��Vi%r�l�ӟ�R V\0��cJL��Vs�tἩ�Ġ+����\\JoT-�Old�t�K�;��؜X�HS\r!��ڪ_,xS�.��X���\"Q�sZ>���>T\$�#\";�}9!�;�ʕ|܌4��<��!h�&S�>������\$�����.�v�{\$CPYd���/lQx���=̒y42KdC��Ne�HZ�T�s��4!J%�\nh\n6e7��+��=y��_z�3�+�^ݮ��޶\n�!��A���>n�y� ��I��?*�nNB<��kb:o�^6H0p� ��\$���y#S,!�v�␗B��E2�\"�b'l6��?B���04�:.�r1�D��HLo�H�nAB�?l(�0_\"nPSip��C氄�{p&�o.-p�1��3��\n��0⨿i��j(PN�̵	\"-�k	�\$����+\rD���\r�i	�C0�\n�\r\"��I�'P�	p@YP�_b����@	�&��d�F\$��~��H����%�)��A�mM�%�+��W��W�|஠t&-�>N҂D�&Ab��Xqhj/�T���nw�H�Q\r������-�y��\0��C���,����\0��j�O�M��P��QOr��Ơ�&�(�@���L�#p�Q�����?�&���.1sep�����d���q���\\OD�\n.���M �#!Q�~bd���cZ!d���%�5�\n�0�3�x��x�\$���\$������F@��9H�\"J�TM��(�'#V��\$j%&��'�����ʃ��?�LOoBQe���G႖�K&=��>M�(Β3\r�Z�˱�r���Ԋnl��&����dT2f*з\$(j�.<�q�#�{�\0�N+0�10�k\"��q��!c!�%!�-\"o~m=.b�3�!\"�\"�(�Ğ*��sW/�<k,QC�\nPQF�0�'�sВk�fݳl�N?*�r&�0_(h�7�e8\n�6�����8�Y�������H\"�3G.B3;e\\x��mK<RO�� �O�<�1bK�P����\"�?��,���*�?��ꔀ F\"�3?�k@\$��tt	A�*\"y4%�S��%�[H ¯B�>1k&#*��:!���B?�O>�>S�=of��FmtJ\\\$-D8�O�B�m҆l��L1�2CY�Q%Ԕ��3NMJ3yJci>�KC԰�n\"Q��Iqk�P���<��ކ��neI�O2s�-��M��K�A1Ij���W���۔���L����vDo�?���Q�EK(i���\")`mϽ8'�4�EJ�>\$�7:\"�NINS���S��#���R���l}��{H��T�����)k�^�S�=�V��v� '�1HV��x�0?�����\"A~VH�M.J��A[pd�u�Kվ)��5�V/p��US]]�!+UL��?#6�U�=�8�[j5��{�_`'&	�O�[��Nv\"O�\\�P�O�br7K�����>\"�a��\$��Y��@o�bD\0\$��m9N��P,H���V!Աfu�f�)I�Z\$��f��_��NaK��T5EUc�L�z)�:�E5��wZ	�\r���S�V���?@��9Gk�Pd=��KEBMpJk6�	���V���\\�?9/�N}B�F\$�泱9*��	�w4�iH\r�V�v�C�qVL�.ꨆ��r�(@�� ��j����@��Zؙ�TBp�:��ml@j�)pWl��Ă��p0+:��kM�rRA@j�*��N�T��D��@�\r�T���B[s�9t.fG�AC��R�T��n,�)��+t���l&E�q�~2YF��1�Ig�y/h,e�T�bn��.�Av���3�m=��O�2�/s�tQM�x\$�u�,��kF�!�x-p�?MJ����~I�6�V1�/����	\n��C��;��~�\r\0�|4\$��%Tv2Z \r�U��-��|r�ݴ6���M�3�~�EVP.@�_ ���jS��q�f'�*̒�HZ�K�S\\)�j�����*����D�\n%�d\"t\r\$3�-��H����5#K/��\r�:�:�����Q�l�Es��ke��.`";
			break;
		case"sv":
			$f="�B�C����R̧!�(J.����!�� 3�԰#I��eL�A�Dd0�����i6M��Q!��3�Β����:�3�y�bkB BS�\nhF�L���q�A������d3\rF�q��t7�ATSI�:a6�&�<��b2�&')�H�d���7#q��u�]D).hD��1ˤ��r4��6�\\�o0�\"򳄢?��ԍ���z�M\ng�g��f�u�Rh�<#���m���w\r�7B'[m�0�\n*JL[�N^4kM�hA��\n'���s5����Nu)��ɝ�H'�o���2&���60#rB�\"�0�ʚ~R<���9(A���02^7*�Z�0�nH�9<����<��P9�����Bp�6������m�v֍/8���C�b�����*ҋ3B�6'�R:60�8܎�-\"�34���P����<2j�7Bbn����1>Sl����P����d��r���<# ڎ�����?�@Т�\0�6��x�\n�L��Ԭ@�����R48�0z\r\r�9�Ax^;ցrEC��r�3��^�c������	�k9O���7�x�@(@�5 o�+\r#����X�:������s�C\\��\"p�/\0�4���C�>����2�`P�7ғ[�`�Lj�_B\r*��6��D�M3�QV2E#��m��P#�u�4��3��*�gM��n�&�*�(9-���x��C.b�x۰2���<�\n:#%&E����޽c\\A�\nb���xv<7�d�X�;���B\\�M7:`��\0003�H3w0���i�\r���>C��>�L�4�����6��\"��Y��{��׮;��\"NO��6�E�0j�L����,�^�WEA��c6۫C�(:삓�7#0�W)��;�R�τj4��6�&<�z�Ҧa��ac<[H9l�@���\$��*>���lU`��Ǵ�P>���f3��7�Z_c�\r�T�B\nk [�{��\0���_�8D��\"@�\n�Q\0A�	�s)>桜�� �U:�Uj�:*�b�ժ��`�]����\"�b�=b�肮Lb�Zfɂ6X���?a���HL�`�x@������d?i��`AU\0���5R*h��b�V\n�Zum�D<J�_4�>Ubz�X�<8i�J��L�djZ	\$~p���F��z�O�x�C}H4M��u�\nT�	Mñ�ѩ5B�}�3eF���)Y\$c�\\3�I���D� �z%�z#H�0R�sKA�]���f\$���R�H\n\0�h�Gt���>��2`PSK� N᪗HNI��RjVa;�@e�ɛ\r&	�bv@�a�R��R���c�2C�X��R���0g*�2p���Y\n\"�ycp�	�dcn�'���S\nAn�L��\r2�ެƲR�4\\&�ܜ�h,�Y>(V\nD�:�H�L�t�QeH���'5D�l!s����k�����2� �.�j��hB�����2\n}P�����D�P�B�O\naP��k%�wʐ&�ֻ%Ȼ� F�[UP�_CI�ei���ا�1#%&��B�[/\r��4����:9�A�X�]YerL��#1���A\0nD��Z�ZX[a\$\"\0��D!P\"� ��=ø�'� �G	�>�v�\n	ׅJ��mdz0M���:�^�׽lۓ�N�;�4Vl�_��O���m\$�aI��s=�	�X�N�q��f�j�e��NK�[81ě�����2v�1�:��+��q<H�\r&�ۛ�ЦH�I'mŸ�&rRZXm��faB�8���(�������l<�\$*K��ip�>�@�hn	p[�������2�0��<6���3cZĘ�4\\WaȾtJ{Vm�ݺ{%���dF���C�w�hy|qS�|�d]��\0�kJ�f����a\n��86�[� �ȭ�ܻ��@-����[}���)���:�X�� ��`����	&�F�9kJ�ItA�o����*�+��O����1�X&��&4�m��cpTԭ���,�F����:��l�{����ޚ�)n\r���Lh�AomhV�q�*%�9��Ifi��2�5\r�m^��L�%2�D���Lv�Owd{K�����r�j�Ka�/�W�f�j���3�3Zp�Vʤ�\"�I�7��z���aͧK��s�ξ�p�C�kê�6�ԳJ���k�u���`m\r���1�C, �E�̆�&On�-]�u�����~Y쑫w�:�I4;����w��\r<3�@R�o&�n���a�а��>^p�@*����yv��=!웥��c⺐St}w�u�C����Cś/w�WJ���O�ءϚ���p'craK�wC���w��Lo�3��9o�Om���D*�����aO�\r\nܿ�y�:����@����bh�\n�\r�Z�G��kbb��4��\$����cBE�ġ��i�� ط�\"!d*9�<=����7)N�C��L��-���������ښL�'�ֻ�^o������x��@�0z믈�,4]-DԂ.�΂�K*�d��/����\n�����\"R���0�S0��.�9%(HE�@�hd~/WKF3Lq\r\",��@��\n.��p����/��	�Ze&V�p������:�3`�iF�2�\\��jd�A&6'FL`�Ԃ�\n?\nH�be��r�TC�1(qa�-�b`��EΪ|DZ��Pab[n��11r5�-M8���C\rkѩp����A�nZu1��1�ѱ�F�\n�{-JE�.�ĩ�\"��wD�n}�4\"����<��\0�e�*	�2C�ð�J\nF1`0m�\0Ɖ��������<X#:�<SΤ	d�m��'j�`-\n�k�ڥ����@��2�,p^�K&��'P�>�hdF\r�V\rd����\$��5+**�>�P\r+�\n�X\rdӏ\\ة~�\0ҵ�/2B�K��Ο�%(T���JsgjM�����h�C�n� ���˂.�&<�^��\n���,yNF�Sn�Q G���t&��CɔJ�HN�*�'A��E�&�K'�J�Bv쬏-�l���qi5�l���x�ފ��.-�80D],�f�(�pF\0��b���(��\"�M�m�<܇N�P=�2�<B�K�~���/ë0`�5k��d'�tAk�@�1>K�0f7'0\rg�.7QH�Fw#[�@�z<�r\r@";
			break;
		case"ta":
			$f="�W* �i��F�\\Hd_�����+�BQp�� 9���t\\U�����@�W��(<�\\��@1	|�@(:�\r��	�S.WA��ht�]�R&����\\�����I`�D�J�\$��:��TϠX��`�*���rj1k�,�Յz@%9���5|�Ud�ߠj䦸��C��f4����~�L��g�����p:E5�e&���@.�����qu����W[��\"�+@�m��\0��,-��һ[�׋&��a;D�x��r4��&�)��s<�!���:\r?����8\nRl�������[zR.�<���\n��8N\"��0���AN�*�Åq`��	�&�B��%0dB���Bʳ�(B�ֶnK��*���9Q�āB��4��:�����Nr\$��Ţ��)2��0�\n*��[�;��\0�9Cx����0�o�7���:\$\n�5O��9��P��EȊ����R����Zĩ�\0�Bnz��A����J<>�p�4��r��K)T��B�|%(D��FF��\r,t�]T�jr�����D���:=KW-D4:\0��ȩ]_�4�b��-�,�W�B�G \r�z��6�O&�r̤ʲp���Պ�I��G��=��:2��F6Jr�Z�{<���CM,�s|�8�7��-��@���Z6|�Y���L��\"#s*M���yp��& )�#��6�j��Zdy*cL���t2�hZV�'�\n������)IJ�6l\"�D,n�r�V��YҲY%�괙�w�)m�;u�ucy%\0P�2\r�D�Ns��<s��#���rO�M����K��O�?�#���44C(��C@�:�t��d(���3��P���p_?t�L\0|6�O�3MCk�R\r��^0��&�����)�X ��\n~�\"-�S���er��n�Î��ط��%-�_0S\rw�)݀s�ZO�\$B�+�3R[�q7W�i ^����Pj�R��\\*�B](W!�7\$��HA(dFK��\0�S\n�Tfa��7�@_\n9�kT雖�P��j`� �:r\n���M��\n��#�!�6'��C`u�|�/g���R�o!:����!9h={�x�_�@A�҆w��Y�]`%�o�H��{�Sh��U'�\\l_�X�3�\0�!�2��B�'����ipB\$���AKn�v1�@�|O��\$�0��BaH��&-�}�;���ȹ\\ĤAkq�	8� N^&�)��!\"��p�̈\$�`��&:g�	���H�HaM-'����z�!��6��Et�_suEu4��6lF�a#��>Y@/)˧֮QҘ,�n�CrmA�BPD�u���i��9�F�	��(/�d�}�N���:��<�����S_d�\")�C̘o}���6JTM|*8C�*O�MI�7T��|*Z���ު5J�E�o����aC�\n;gv� ��0lo�\0ϗ���&XT\r����#+�qN03E@@P�:Hn���������:�pP�Ml|0Ъ��Df�Z�]��7\\+{�c��e���a>09W��{vɝ�:�C#tGyҺwR��k�v.�ڷ����Nu64*W��[�I/I� �-�9jU�H�?D`�M~�_��A��#AB.����3��m&79��ՙ^	����׀��f�4�U�]�u��:�`샻�o�܇'v�]�TTOA�<j.P�3��PT�@�\\�*>X�0����S=u�WZQ\n밈Q��4�6�uT���.%��������;V�*�'sS+�\r��1 ���c�A�3_��^�\r}���9S�|CA�a���\0ǁ�i�`�)v�EST!�݊�\$Z	B���<ޘ�@��H\n#�|}y����+��\ru�+,j���xL|O)�='���XT�Ðt>�;9��þ�����ؓ�惚~qv%2������so� �~�h��3��A�sA╁�8���F)��Nxk�j5k�=�,d���Hs��咑��C�LܗѪ���`�ق\r5��~��B�����V��4l@�7��W3��'��/Ȝ��؅�w�1�u��<K�u��x������g�����G���p�V�{����t��-3`@�Hm�8M���|�CCN9�V'��p'2�R�|٘�TB�O\naS�D�DLm�S�����3asB͚��,��,g\\t���b\r (���3l����e�2��à�͔�\n&��.u+)A(eǆ-�������4������4эSbS�Hb��94+\$Lf�K�<�P������.�ἄ��P�*P�\0D�0\"��эa�{���p�6J�j��f��pǺ��@P�y��&}�%<�i2�Y��g=���o���8%�BCX�d�:���\$�N�b�&\r0�\n_Q��BK�l� _���Ĥ���J*\\�ɂ�x��0�O��f�%�]N�\"��I�PT��J��sJ\"�-�|�f_��\"���І�p>C���&\n��.Z\\�u	/�	���\0�ĥ%,�n,JB����<F���K��j@�+*,��M(F�P\n�F�C�=�r K,D&�%1���>ͨ5nN���-��LL?\$���\r�l�������/�~<Z�\$0�l���df'�t�J`�g/�7�m���n��N���\n`�H��cN���\n`�=���Ϯ�\r��#F��4�	��l{�>�e���#\n,\rȮ�HT\nm�4�² �k��J�T=@��U�\$Vp�v�H����'��0�b\0�\r�r����D*��� o �1�\"`B���1��\$��.U�@�����(���al�!_\"���0�q��c�%���B��b�1�\$������DFST��چ�>�r+�,g�\ne��b\0�y��g%(B�Dݭ\0F��\"#q�j�a���\r�i	?!\nN�fȥp������ �	\0@��\r\$ꬮzp����Pl�Sp��T�n�j�7��H�^�B6�Ja��:�]3�'�6�0}���	�F�*c�]	�F�/�@��3!%݇��L�CSb|�<�di1pX�q���m�	qr�D�3k3З7{�QQ�3��F��B�)���b��R���&֦Ie7��P���Ϯ�e�l���5\"�93Y9s\\��\r��X3:�s�?P{,����7�Q<�a*S�4�i<R�;��Ì	�Zr�@M �I�V�\0���+�#�~�B�(�0�M&�&+7�h�ȊX��(T^#FGS��M�A�C�-�#B�§�n-��Ϩ6lp@άjF\n�J�KIdU���e(�:���jp)��n���k)\r�A\r�~-�(d��A���ɫ=�\0^k����MS6�7h)+�%�Pc؄fr5�99�\n��HD-�4��81d]f���Bt!8�/��.U;7�����t��UG;��;�N���UiN~��n�P\nt�T��81�T�N��#+��Wt�:�X��SU�;I�7��Y����mY2�WR�Y�[��8p�1ֆ0����<.���5r�BS\ruu�V�Ȧ�� �uv�h�+��M ��5MSAR�~9��,�p򓰆�ga��NO�9��r���	�4r\rs�4T���Q�[�\\��\\���r:���\"!��\0�,��r��m\$`QT4�Vf�c��,�'	mƗ��OЁ\\T�@�u6uq\0��5v��s]�e��e�5U�K@�M��AIܦi/59i�Vu�]�	k<p�l�e��fkoʦ��dT��C�j��j�eZ(�	�q/�nT�?�Pu�k�mw#p�h�q�7V�p6�[4)k7\n���+�H�0�/|�O2ꆩ�s��WӾ!���֊��9l�W5Ά3�5�g8o�+K�eb��v��q��vuI%�w�w�����4v�GEf�5J�a^֓���W\$!U���z�9[o�}�WRExR!Z�OCSeuw�|��'�UV�wW�fr/\"����4�:��Q\rzp;��Q�y ���2A|����esU�u*G1��1�Q��L�zqhT�r��Hk&��LI�A�K<��1��&�x�ԙ�X�	�I�-h���FD2#R�v羓��.w�'5�R�8�s	x{3��'�p6| �������~������5�kJ���S�!U�uX�Z���&��7�@���\$8�!i9X.-\$�	��|��Z�'�vӐW HG������z�Q��;UU�7���Qnw1�ԋ~�s�A��G�,��_PC,��^Cz���\0�< ��q�Un���}��72Ȣ���Rp���Vず(k�f�|����r痴ρ���ҿك�u�)5����7J�yޟ9Şp^�9��7r���U+I#PC�'�|g,�/��J�S�fɌ��a��6/+�6ܨi�XUb��u�uv+���)syk�I7y��+���WM�yOH�!��az��Ycv�م������ƣ��JP�?����G�Q�J88ڈ��K�\nU�Z�H%)�qN[�5��JZ�`D�x̧��d�c��+i��7��m��C�����}���q�	�:ۃ:�qZ�7��Ց���!W(��9�i+c��!'Y_���.�*�56�~uE��i�9?g1�@��Z���q���̟�N[Ch[��;��m�[_�%Rh���f�I�ZY�m&x�&ۏ\$zh����󸛞G;�[�a��W������c��?�;y�{ǻ8�����ǸZ����9���Y�@��c�j��T��W��u)�P�%W+����v��h����ZE��E��2����Zڱ�z���x 	�� �u�)��y��G���)��Ǵj����.����2+��A���\nCx���I@��\r	>4��y�ZSN��	����=�p�'�T�[��-�7ʅ�?��5�2n�����T#GE�6(�5�@��{,#F���P��BW��Ő\r�V� �`��sQ��M@����`̮�,+����ڄ��\n���Z\n���I\"��y(j!��d���3A��a�)mUQ�[����j#\$�c�qgo]�Ss��U�I���0�][#\n2x���\n���.��I�M2l������5��v	��Y�����\$��wd1�L{Ӧ�@AU3ϗC(}�w;qnR�U�ɹP��E�,֣�?��b���?�{���f_y������r��Q9:��䣬C�{f����Y0���8��9PmI��9Q��u�z��YeZ+�u�f0���Zcn��U[e�6�\n��>�<-��{�QC��f��[���.,�,���`�yV�ɷ�f^m��6�h�&��^;�cB~��o��i�7�\"�C�o\$�?�@�J�w��>���@���\r��=�s24'������l�ğ�~ @S�a�Ibغ0�/��E�݄�\n�����=gΉ�6����̜�\$Ë0'\n|ȝ>qm��a�)�6\0�h@�=��܁�0���7η�s2������\$^�T��	\0�@�	�t\n`�";
			break;
		case"th":
			$f="�\\! �M��@�0tD\0�� \nX:&\0��*�\n8�\0�	E�30�/\0ZB�(^\0�A�K�2\0���&��b�8�KG�n����	I�?J\\�)��b�.��)�\\�S��\"��s\0C�WJ��_6\\+eV�6r�Jé5k���]�8��@%9��9��4��fv2� #!��j6�5��:�i\\�(�zʳy�W e�j�\0MLrS��{q\0�ק�|\\Iq	�n�[�R�|��馛��7;Z��4	=j����.����Y7�D�	�� 7����i6L�S�������0��x�4\r/��0�O�ڶ�p��\0@�-�p�BP�,�JQpXD1���jCb�2�α;�󤅗\$3��\$\r�6��мJ���+��.�6��Q󄟨1���`P���#pά����P.�JV�!��\0�0@P�7\ro��7(�9\r㒰\"@�`�9�� ��>x�p�8���9����i�؃+��¿�)ä�6MJԟ�1lY\$�O*U�@���,�����8n�x\\5�T(�6/\n5��8����BN�H\\I1rl�H��Ô�Y;r�|��ՌIM�&��3I �h��_�Q�B1��,�nm1,��;�,�d��E�;��&i�d��(UZ�b����!N���T�E��^������m�0�A��IKW�\0E�V�#�*��\\`���e94��t!J�]Õ��P���+����\"xv��V;��GM� �ֈg%'�幧����eӃ,Ŋ�g(�# �*���>>3�8Pc��0�c�9ʣ8@0�S��;����;��C�#���4C(��C@�:�t��L;v�9N#8^2��}9�P^1��8���8�� �7���^0��-����ޟ��Kt��\nd�\\�x+Uf\r�ˇ[2͌��7�WL��1N��E�Ե���n�����#�VK��� �WgXp��s�d�2c���s1`�},��v�c2.jtJ��TF孇\$B�N��[-�8�=���V�7��H��Â-\0PDsA�:����l���%�\n��NB'��,Ff�z��a\r�0(J�6%u]���]1�#GI�B~vVCۅ-a5dZONCkTl���qq\n����M�rv�I�+l\$���F���kkp��Һ��@F���� ��a\r�e���Z\0S\n!1�+\"��W�nB�^R�\"��Q+�}-t����S҄2��F�2�����2����ޔ�1�I��l�p��B�5�w�;L�OrS!A0J<.r,�	�\\\n,���Dx�[��0|��h\\�z�cp�!%)�1�Fy���c�bqM���+��pe78���� D	,C���A��7�`���I)�(�\0��ѹ\n���<�A\0u��վ���A\0l\r�*�7(�8!�0�PAS�,\rm�:�PP�M*y��8?�`o��g~,�d��@���X\n��4�D��J����4��\r!���c��\\۝s�ѺWN�k��N����C��v �X����3�+M��%S�Z,'�\$�,���it`R���6¸��/j��z0��F��;W%`&�tD��rI�<\0��T[�r��9�@�#������k�v4J�Qk&�BHm�6�\0�fA���2��@�C[�P���^e�-�\n�[0p��g��e\r�x��b80�d^  qA�J#�uo��40�k~�)�~v��JT*��R?�9��ȼ�� уoQҫ;��M���Β�{�]Q����I܃�@\$\0@\n@)U(R^�zN߹܀7���S�UJ�\no�`w���>���X�C��A)������6M����@桛�OM�ކ��U\\{�r04;���{�Hgs��b3�&k�eyQ�#��h- aL)h�N�IL���\"�%��z%�0�S�ݹW���TWUijE�Xǭ��IaS�d��DXWKav8F�V�3�v�u��<���M�uzW�`\$�P�z[�{͊�^�\\�q�a�?�d�k��n9�6:��1kP��ϔZ��VM���+8�V��=*�4\\cL�6�\nU\$�jM̞x�\0���pc�Z���s�EO,pC��*V}��b��T'�pI�؀j�pz3D�F6d��`����\r\r7�BT�ۻ� r=��N�Ш!3jDE�N��ˉ�����\n.��p \n�@\"�{?i�&^���� 0Q�y����9�G��%�pQc^�]�>�(�]���Z4�MX����FV6��?ě��Q~3���)�2�J�Q��K�xK���7Ot%���7����x�i>���z��mh6\"�I���������vVS�x~��iz����:��*Jl�k\\Ѷ���q��\\�\$�j��	��e�L��P.+����9|�,�����0h&��( ��\0b�)'��p����(��ʄo-�\r 􈌶�J���8?,�İ7\0����c�:ό�/|X��Kz��X�:�����^	L�'������.b`�gޕ��|�_��X���b{op�����0T�Z2'�	ap�H���Bf����D�ITS�|���HJ�\"t?������DS�E��úf,��V�ä��Ҡg�E�8�b��^Ɇ�[�v8\"��\n���ir���Ƕf�\"̦�&�N�\n��`�\r�M��O�F�f����T6�Q�<���H\0^3\n��öI�\"[&�'e�\\D&qr�C5�H%R;��^d���6.n�8�@�P�����C�1�OKdE���&+��)<�c���)0�ϖA��Deg&o���g��;�9��Wm�XD`�O��c��P\"�+d��f�JW#֢�9�e�].�\n��D��F��)/���FxBJ[\$5#�I\$�1'k�ǥf�\"�[�V�\"?\n)�z�P9�h�ke)�Z9�~G28[C���Pa�f��}DJ9HE	T�m.Vh��j���h2[����QǶ<���Y²;\nR�IeR�DL���s}DK&�(�E �8��-�+p�.�1��Z<3;/Ǯ�]2���:DY4�N���m��	�\n�:���)�E�H+�rN\0�W�~+2����B�:��F+g�S���ә8Y)I�����IU9e�7C[n�	I	��z�8fb�X�@A����B���=Y=n�E�*Vh0���S\n�./\0G	�6�	�D�s%.�/<Iz��;p�3p�U2D�li6����ov낡A��}��h��TB�B�W11}At\\�i��r�II�?DsF	�FQ��P�:I`8�SF��I|9u%OnLBS%���MI��0Q�%��^S�i��<d�F�+aDI�t�!IH�����8��}4�H�M<��+��N�N��g�Oc�Li���+=��\"��O4���^�Wf�>H�?fC�0�H�<,u=�L�4YBÚz԰S��H���T�\r	�Q=�Dd�*DR�CC���{bgT����H�4��KM+n��Yn����'�9������3C�]�B���To}c�O��H��<��5 ;��N/�A�V��G*7GsG^�1\\0�lq(�q/(�\rN�F��`��`���O�]H�P�5�4(C�q3aQ}_��N�-��P4�9�<��bt2�VY`�AbQ57��Vs~�e`:#h�v>��U`�ja�w;�_��1�zLt�b%`<%Z@�q��HR�a�}C����^�O<v1]ԓR��+�kt{��a��O��eI�	\$\r̼x�P	cnc�\r����s6b���V�lk4c�N�p(�/r�9��B�j�FR�g�Ѝ���_n��^��IK9s��tVIm��E�<�wAmS<��]��}�uk7u�at��J��vvSowmcw;t�Cw�d�A/�[�'tV�\"�I+��y�UQ�R��73�-)� u0[�[pi�|Cy��gIKR�8\0���v��7pW��7�l�b�ixUR��u�{VMvP��j��A�0�|�A���a��_6�kf�}1F��L���vTOl�5���}��ś_�G��XO����	�\r!JoW.M�h-�'�+/�K%:�\$Z���Q���4�5B�T_G^2�\\6�g5�}%o�A-��DKG0t��<ҋQ���d;ezj�\r�V���`��b\09��3%�-N��`΄\r��O����\n���p�j����8ёtB�ϴR6b������10Ǯ&l��ߒ@�.�?�fVT9�D 	��U���Ld6��X��LM{µ�C�y�]rYD��x9�C\"� �\r�twg2p�Y�u'���K�X.�z)o���E�,u�f�6\r4��@�ʫ/�Z��@�\n�~�T�X��ܝ�)@�-��{v�\0���=��р�v\n\"m�r��#Q�w^B/7V�XL\"��#�6S�t6��z�bg�[@r�6�	�?���i�Uqӣ5�B��z4�e*�@�o����sz'w̛�:��l[�z�|:#��\"zd���n�;��;��%�l2�e�n�F�9Fv+9����d9�i��}���y:�edǋ ��`�>�x�\\�N��v+��e�o��C��1㶅[���	\0t	��@�\n`";
			break;
		case"tr":
			$f="E6�M�	�i=�BQp�� 9������ 3����!��i6`'�y�\\\nb,P!�= 2�̑H���o<�N�X�bn���)̅'��b��)��:GX���@\nFC1��l7ASv*|%4��F`(�a1\r�	!���^�2Q�|%�O3���v��K��s��fSd��kXjya��t5��XlF�:�ډi��x���\\�F�a6�3���]7��F	�Ӻ��AE=�� 4�\\�K�K:�L&�QT�k7��8��KH0�F��fe9�<8S���p��NÙ�J2\$�(@:�N��\r�\n�����l4��0@5�0J���	�/�����㢐��S��B��:/�B��l-�P�45�\n6�iA`Ѝ�H �`P�2��`�H��<�4m� �@3��N)�#�:6�c��:��T*��Qb\nP��B�^-�\\/#BJ�Ħ.8���3��2 #r<����7����90��Ɏh0�3���=�p�4��@ި\"��*�C X���9�0z\r��8a�^���\\0Ј<\$�8^���3�����\r�+\\��,x:\r.x�!�O9#��́B��8'�b���=##C�\"�\r��l��D���άrx�*�-�rϰ�dX�\"�-�.�J2&������ԉs{��E�*J:�x�\$�#K0 �\n�x<-{��x�q��P���h2H�F�]��\$NVP0�CC4S�Ò�#�Z��h�B`Ҁ�\"k>�I�t:��+-�/R�1�x9��X�&BrN�7 ��7�NB�a|�f)<�`��\\M�:�{�иMZ��m�ZR��WzS'\\\"״�I��(}I~�|o�������ǀS�r���@I+t7w�#��@C(���3�9�����Cd�d:����'LO���.=\$��|5���Ä0�=A<bv9d�76�2��.�<���.b�@\"FLEÙ�8m�2���҈�&���' �O[�Aa����xG	�'PjC�DQ\0\n�>|fd͒�:��XkE�莑�F�A�V*�Z�ur���wW�Ce���p/&ol��\\)%���x��x�,��^嶷`��~����L�`d��6������(�'��E�BHoP���� q�^+�#P.6&����YCh�C�d���WJ�_,I���Y)�<蒳��>@E0�Ƥ���L��0�srGWr�7F��I�O�&l\"|�*�ߒ�TA�,\"�HX�]/�FKg�)���1�tJ��<\r\$.��&�Ё'\n�7 u�]H���D�C0��Ȟ�bg�<\n\n()�-��r�S��\0*�<���/ŉ(qFb b�x]PAq�!h�E�A,v\$���r���>0 ��32@�\n�\r���,�P��Fh���ujM�u1<:��N�grJ\0�0��5\rF|��Y�Ðm1p6�c�I��\"%������Z��`�eL�Y�\0A\0L�����&\0G���AS���sfe�T�a OWd��'8�ӌh�<e���E��~�pn|��*���^�P	�L*�>A�]#�je�ٗ�TH�0Q�ϑ�<�T����3�'�N\$UFp�X��l'�\$\r����+=h{J\nm�D��g���3�Y ��~}SOA�\r����K�ЩPQ��1>�^\"�AL47p�[��	`E܄��P�*Z�r��G�@@*��������xE	��|�\\�`�.\$�R�.c%ȁ�ڛ[Q�V\nf\0����Xk9lИ�%�b����/.P�}�Xa�	\r��''��*FǗ���7&��\nq%��2\r��z�Nn�u��j�%�q���L3,\n� ��		��^�xD��,��E�/h:xʭOy�j]8��>[�pR������N[�рAW��/���e�k�����Z�3���!e���D}f(���K�a9��#���5���z#R����g/P���3�2�q��c�GC6t`;H�G�|\r�iY*5���74�D�A51���s�M�!I4�O����=��hޓ2(�˭�fç��Aa �J��\\��dƒ� ��C�\r�'��1P^Uثw]LM��>����DO�\r���w��F!Ɍ/\"��} �x�圹��{��5(���s�.��\r6o�i�i~T��\rm�T� OB�<�\r�QO�zN��0r~�oze\"����Ӗ^�heG2�W��I&�d��R\$��L5��@�P�ؖ�d�(�p����)���g3��TX�!��9��+�e�G6��;K���˫��.�R\n{ܤ����\"�Y6:k���ԉ>\r��f.�3+Ϻ���o\$��������?\\�Ь'�4��u�-ם��1�mZ,�Ȳ�\\���.fD�������ѷ@��#O�Jl\0��\$����<\r�E�_b@�m�#��N�Z/TcB�\0�*`����Gdr���/��L�HT����HopV�����̪�f��+	��gQp\"�\n�H�P�SL\"�p!N�C��R7`��a�\0����,F����A�r�0��NC�x&�����\r�����0܊���&��#�����/	/���7�P��ņS �o̬E�\n2\":��x��W�c#�a�49�8&òLQ*(c~�`�\$��D1(����t��&��	ͧM�^\r����M��po�1}�QyM\0����O��-�0Q~�q�1�	�\"��͹��J*�C���Gdz-�xJcp\"�~�\"Mp�\nш\$���pb�q�q��n;�*[\$���#I�\$H���f�pgC� P�!��\"̤\\ �!���� ��%q�\r�C\"�rDrI��gz%kW%�%��~��J�rniQ�%&9!y!Rw#M�[1\"��2e�(�M%�)�u)m.�1�Q�;���\$,�rr�\$O+�bݲ�o��r�'���ʲ;d,Ʊ�`�x�.CB�Nb���=-₭��Y/�D8>�3 �\"��dmQ\n�/P����.��p�v��4�� f�)c.��8 c`%�\0�I`�i�/��O@&��\n���pn �A1��1Z����<���\\+���0n���0���J3L�u���f�9�%5g:GĂ,�z:r:/,��SK\"\r�4�&&S�6mܢ��|0X]hM,Sl������\"쬻�!k����\$�b�����~7(<���(�,�Zz�B�1������s�-,��),��v��O�D�[)��c~�\0E�E��L���	�@�\$�p=\r��\0��e�>l���\0�@辀�8�@N\0����\\�R{\"4E�/� PԞ��q4t�1 �t���?����C�Mb��f3C��\r�b�>C�FK\$j\r`�j�j���h�@";
			break;
		case"uk":
			$f="�I4�ɠ�h-`��&�K�BQp�� 9��	�r�h-��-}[��Z����H`R������db��rb�h�d��Z��G��H�����\r�Ms6@Se+ȃE6�J�Td�Jsh\$g�\$�G��f�j>���C��f4����j��SdR�B�\rh��SE�6\rV�G!TI��V�����{Z�L����ʔi%Q�B���vUXh���Zk���7*�M)4�/�55�CB�h�ഹ�	 �� �HT6\\��h�t�vc��l�V����Y�j��׶��ԮpNUf@�;I�f��\r:b�ib�ﾦ����j� �i�%l��h%.�\n���{��;�y�\$�CC�I�,�#D�Ė\r�5���X?�j�в���H�)Lxݦ(kfB�K���{��)�)Ư�FHm\\�F �\$j�H!d*���B���郴՗.C�\$.)D\n����lb�9�kjķ��\\���̐ʾ��D����\rZ\r��qd�隅1#D�&�?l�&@�1���M1�\\���`�hr@�:������,����΢[\nC�*�(�kYCO9�	\"%iK�Q%��\n�Y����D�!5ҰM.ȣD�'-(�b5jC���l�h�GUN/�Ҙ��;s?K����p ��h�7�������*.�6�e�,�4�ky�2^8����(�|�!\0�9�0z\r��8a�^���\\0�8^\r����p^8#��;� ^,��f�]�Ԝ�%����/��|�\$��i\r�Iq-}�\r����\0ըҡ��pj��IP݄Y���i�k���ؽ\r��6�\n��&4��� �F�\$Q,��ė�=EU�/C'2�}M�P���\n5G�e��!�s=]��!���SN��լ(��<���m1��ܤ�#8�}3sI12�*{R��E�cW��*�.�xmTG�F�{u1F��mPL�����ғ�|�4Ób�B	[m!�Ap�[�\n�\0�\"��1\n(a��5x�2u.��C���u�ì=/���0���@dh棑�P\"�yNh�!%t��!Clu����Ј%2���� ��f�`0ъQnE�l��\"ֈ�\$���\rNy�#B�uvލd:�ܺ��� ��0��QŞtV�\\�a^P)z�@�b�D�������C�aA�2��@�AI�RRKI��&C�ti!�4��ܠ��L��6@���74*^���m�ف\$Г��R��Q�!h������r�������� .��D:e�2�V)��h���Iv�&%=�v��;�>a���rZ3)eLś3�M�k�'/<sK,�Bu���K8RT㜲��ι|�f�.3�����4fJ�Ys6|Q�����ؠ&Jm���AVT�\$��XQCC/VqOg�;TشJ�k8��w������*MrAĬr\n�`a)�\$��g�;���h�X˙�2f�ٜ3�xϚLhm���^%0a���&�O�Bn-��,��!��!�H��*�S�_L*������q:J%�]&�M���ԅ7��o\"�)o��GP�\\�&]�X��u-3��/f,͚�vr���wg���F��Pe�2��56�4\r1\n(�9<���-k�>����D�P*�% �E�VS\\\$���`���U+���@�A��ʂI	����N��������J��:<8�)�PT 9]Ohz��J6YlyB��U�SS�iH �Ng��*ՄUcC E��ɐ�SٳO����pӰP	@������G\0�����0NR9bE\r*��#\n�*a�Yr���Z�462�RH#~�Obq��]t�\"�=MN��7X����ŬQ�D�RU��� �,T�����u�R��Q#J��`:��~4�\$���RǙ��ٜme�t�evƖǃj[|hr��D:�d�\r᫆R �ty� ީ[�2)��h��\$R�2�p��9G���֔�E�����to�C��Y���C���r�	;T��S��S���Ŕ��\nM==���΂B�\n�UxM*!x�O\naQ�HH���1~�3]M-�U!��\$�_��������x�c4T�����bY4�����,�9�@��60Y��ӝ���`�C\n��(F\n�@�#ÜG���,|�I��[��q��֛q�n<ȑ\01�}�C7��;�4�:D�6���7Y b	�!�&Ƅs#�x̤RY��y�4j�1�/�IZ��jϲ�ZV��2�O�T�xM���m��oQ�*����㋷%����2W���N�#a����q���2�SK�11��-p6F���d�\\y\$���\$_p�W�����\n�/�qb{��UGc=�D����%�X�b!�xg�\n[BS4'O>���9��I����ʽV��(Vx�Ѧ����#f������8\$f�f��`���!Hj1(0��-b||��7ˆ7�R��B�k�p�R7o;�p����H�!�����h�n��h�B���R��F\$�L�LH��2qGʂ��Cp2!n��k�C�ɧnJgV�P`%tbp����b츻ʨ��{��d���d-�.(�^D/��Κ&��f�|�B�h�쥎x(��M.�ʈZ�\\�\$V`�\n�� �	��P�8G��-�Jb�*|����x��\nH�d�-���L�����pGT7PQ�VWqZSD%m���&)%�\$��#�+gD�O�G*�(d%�l�ǻL��)1���XQl6������r&ў�1z�~J����-me���Bu�7�iCr'�M�q��wQ��qW�XQ����Ɯq��SQ��1�,�	m�6�8�s���yJ��R��,+�qg\$NFz���2O!̼4��L�>��[�x�\$�\$�%�Fb�'+%#���`2L,c�c.������0�J:��q:��\n�|Ն?*D~\$��k\"�o�F%L�q�\$5%,��B�(�<�DQl���qi.oZ�q�&�?/Q�)?/�`�#��B�Q��q�.�_.��1O�CD^I�LQ�1q1pLq��3#n�(�O�T�N�4g���򍂻��2l�ꧬ�\"<{R�[#�{��:��\0,�\$��رh�ς?-S��G�����>������\"�fD��)0d�ss#�\r3q�\"�.w\"�;B;�;��>�FL\0P�b��p.�5.�^��̳;�_2r�]�|�W2�s/7���������EKA4�(-4?T 5t�;%��Ȣ�#2J+?�D;�H��L���tF^1�<Sz�d�-��CE� �W*�SO�����!g+/�cC� �GQ=F�N�i����F��ur��	/�gC����Gt�F��H�|t���IJ'C���&m�8�l�Kj`E3�F��NM\$X��	�#O�In�	�	k\nɕET'E�i=D�k�*E����\r��\$�.��� cP�[J�<E�M�ܴ&��iR�.�7Pc<��N�Y�\"\n��xY�\$k皿UZ��n����aqP�CQ+�	��5�VE�m5pNW����ﰊn�T!b:�'\n7u	FS9N{G\r]%�/i\0��]��!]��B�\$����+�?��Ou�`&]4�B�۵�ƿDշ�f\n�U�V6,q��T��n%�'�]�b1Oػ%�P�\n�.�n��y����އ�R?��!E���OTKF��fԃg&�aR�M�f�!h\$�hv�sL�p\\p2J|��lG!bv�+�Of��1�j��j־��ckv�oh�w��E�`.,~�6:��mO�]gb�^V�>���i�)D��\0,�o0�ԓh�+k�Wn�pKl�y*6�q7*��� ����+m�EDtWb4�qȱT70�\$&��tQР�6	g��h�eiq��wEvc�P�N��qrW\n��sW[r,v�ה:Lg1zvE(��Y�Co�qzʊ�Q	3t��D׽SscׅQ�Q]�J>��VӒ�v�L��R�ӎ|��&I�k%L\"��Zy�\r��`j�T6X'5�óԧf�m�w�e�~ĉ�8~��7�wU��[�,(�~q����Ϙ<r��L7�q��K�M�-�Uhч���M���8|���SBxi\\\r�WQl*X��)�zg��ˢ�6��z��\rNUX�.w�`�0��.CS\0@\n���Z젱O>uxT�h�Z�)��7�m��o��x���Q��Q?EX��'���pöL�(���-�k2��c����p ��7BS,\$ EDYO;2в��90��t���u�[��):�Y�S��Gy��n�\$9u%v`�R��/�b���)�CG�w������F#\0F���Y?��x�ts?���U�%�y���q��/��v��\"=�y�4C�\$����t�S+��XY\\�Q�Po)\r��\0�h��\$��eV��b���To���-<�UV� �]+lQ¢�!Z@\n��`������ǐ1}(txg�ֲ������u�\0���u��β5���L�K��&�kC<��Z1��w��#Y�`Zu��a��<6�n�\0��9E�F�5�L�v=��";
			break;
		case"vi":
			$f="Bp��&������ *�(J.��0Q,��Z���)v��@Tf�\n�pj�p�*�V���C`�]��rY<�#\$b\$L2��@%9���I�����Γ���4˅����d3\rF�q��t9N1�Q�E3ڡ�h�j[�J;���o��\n�(�Ub��da���I¾Ri��D�\0\0�A)�X�8@q:�g!�C�_#y�̸�6:����ڋ�.���K;�.���}F��ͼS0��6�������\\��v����N5��n5���x!��r7���C	��1#�����(�͍�&:����;�#\"\\!�%:8!K�H�+�ڜ0R�7���wC(\$F]���]�+��0��Ҏ9�jjP��e�Fd��c@��J*�#�ӊX�\n\npE�ɚ44�K\n�d����@3��&�!\0��3Z���0�9ʤ�H�(�\"�;�mh�#�\njh�-�aC3&I�O>%�*l����΢jV�JzT�\"�P�i�b�2�d�C�&! bk�:V�\0P�(2�\raYSiD_���+3�#�# ���\$\rAC�PP�1�p0��j����;�(��:��\"9�p�X��9�0z\r��8a�^��\\0�Vc����x�7�]�9�xD��k��c3�6��LP�|�+�T2��\$Uh ƀ����a�Hl��\nx�Yee���|�ú@P�0�Cu���\"�<��(P9�2DH:7TC'I9�h�&L5v�'��-�%�\r�P�0����d�P�!)�R������<Q֢�z�n	~�/E,��eL�a�M�]�@��ֳBE�ŵ��M���\$\r��2H;Wk�ߕ����-���a�13�P �ti�P3��ʥ*ol)�\"`<d�P��0�@�3l�H!�b'�����w�\"i�L©���>�]f(�g���wMQv�~MJ\n�O\n�%\nx]*�߃X}\"������F�ʲ�LnB B:<��J7A���@~A?��p�C(xj!�m��x��h�4=\"����\r!����Pn�,4�B�\rL�Z�5G�9���ӻ�H�]Q�\r��w�qF����eb+������AA�ʆ\\��j�;�(<\"��0k@�j�U�]�JˉA�w�8lC\"�^ �y�U�W��_�;�6\"�saL02\"�:��>������@ȉpPu\$���@t��M'i!!�~#*A{E����J�.SB�NE�>f��:�)��༗��_�~/�\0��\$�>҄90�¡���1��0|a�Q\$ȢKy^� sǝ��ĖBE���B�3P�cĵ�5�(X \\��6���\r�����az�[h3Z*zh ����D���^�D7Q�\rˡQHt]!�ܩ���N���w\\��зAA@\$h���Ȝ\$\"d��BE(1��XpA��B@�GR@��hD:(|��L>�a�0�V� ;t����RB�B�:���:����1��2�k�'閂���F���`�y��fC[/)@A��F.�Xu��)F�iY}=*�蘐\\DxHA�I��F��ۤ�F��?+z'j�j(��.*N!�DM^��;L@mH)%�\$���\$�m@A���ؽ\rBAA��9��s.�\n��t#Y��UFd���KxP	�L*EӾ�I��		b9�r�	������RI�t�����v|L�C�yW����gӛ./����G�o�D�#J�s���%=��HrU=�Uv�\$7�����ZI�8���Fb�%���eM��,I#��R���P�t�JbUȐ������Q��F�v�bN��V�\$��9k-��uԏ�@�Y�M'ܴ��e�B��D94���X �\"�����^��No�DC��L����.(�3<>g�!�-&��v���i�9��L\n*eN�0�u�f���=z,�|n�)�E�<<��g��6D���jM��\rpµ,��q�[�N�o��E�I4��(�d�e��UPz�h2�s�͞X��\$�p\"i��fƽ���\0��:c�TG��zNS�1\"&�ϒ����l�=S�O8�s7Z�H���?D��ۡ/���E�D�Wv�SV\0������	R�e�2*�o��\\u,�@T\n�!�����|D�KM��B7ZM~Q�����!��o��� s d���X\"ĝ�.}����-��k�b앎����iR,�I<b�|����A	.���<�g4Ti�]�2Tbce��+���~�}8���TK6�o�҂�NA&����6��/rI5��	�E�US=�yR>�F������U�b��K�Yk�{�v���i�J����Ö��q��'��'Ăm�D�*L���#��8����d.��������{\$�߂6�\"���F�-6)mfh@���8BR9��2�D��&�\n~�T���?P)\0~�'(e�Rh!t\"ؖPF���l'R\$9l.�P*T�.oR��p8��\0��\0�\$0�l�NNj�hAKrOG�u��j�^X��\$�����\0\r� �HӍ3���g\0{p����g�|���p�c��z��|bHh8��l���O\"���]�]��|G��c\"�������\"��\$�d�ʪ9^%� ��1*0\rԠP�L��9�~r�H\r���Sr���]�X���P��0xD���X��pe���h���\\\$��Ą�h���&�n��|�{N<�T�Ĕg�}�A�0�15��g�a�\n�ɍ�0�G�ԋM��}\r�\rC��[/�	���byP����0.	!&Zo�l�\n�����N7.X8DL�\"SAv�,�g.N:&j]����g��\n�I�s �\$�`Y���d�l��f���'�\$�X�J�.�'�o\"p��V�,Fm#b�9�V��/��O��B�' ���Ϻ[Ү-I'��L��)G�-(\$�O�q�K&��.���2����eY-�&��[\rQj�\",N	0��Nr��0� g�NE(�&����\$��X�w\"n�0q\"�̥11\"RM�Xlf��[+��4�V��M�z�L����f`%PCX0�&'s�5e��� :�KR���\\;z��j)�s�!Ɲ�|�1\0\\	�:��-\n*��\r�M�\n��\$�G��0�H��X���JIE> ��\n���q�ڵ�J̮�/�R�q���X\$�t��s\"0�tX���,r���P�(s��{=�m*6(�jn�Lk��T,UŌ6d�V�,��\0I��w�̎M#����+0��æFa/%ԋ\0���:�x\r�,�H��ԜhQ�B|\$�qK���u��p�\n8T)A���Jo�I\"�!#x���ډ��M�de@��i8�r��+M�f�. �.�N���h'\n�@@z�TB����k*D�����\n	���2Bߤ�Eb\$��́A�,U�̞l�U��IӨ!4�@�����j2�,��\r�f��#:X%�3��oΠ\04b�";
			break;
		case"zh":
			$f="�A*�s�\\�r����|%��:�\$\nr.���2�r/d�Ȼ[8� S�8�r�!T�\\�s���I4�b�r��ЀJs!J���:�2�r�ST⢔\n���h5\r��S�R�9Q��*�-Y(eȗB��+��΅�FZ�I9P�Yj^F�X9���P������2�s&֒E��~�����yc�~���#}K�r�s���k��|�i�-r�̀�)c(��C�ݦ#*�J!A�R�\n�k�P��/W�t��Z�U9��WJQ3�W�q�*�'Os%�dbʯC9��Mnr;N�P�)��Z�'1T���*�J;���)nY5������9XS#%����Ans�%��O-�30�*\\O�Ĺlt��0]��6r���^�-�8���\0J���|r��\nÑ)V����Y�m��*QBr�.���I�����lY��,��T^��C�@��<�# �4��(�t�d�lR>�����\\�.D���/�r��/i&����\r��3��:����x�Y��\rBP�p�9�x�7��9�c�v2��:e1�A��AN���IP��|GI\0D��YS1,ZZL�9H]6\$��O�]&J6�\r&�ד�z��i,X��ur=��ZS�����8tId�K��LW�eE���9Tr�PDOZ�}�D�g)\0^[���T���n�w� D%��8s���N]��\"�^��9zW%�s]d̲��:Da&�I�\\V�E�]2Ą�!dD#�ECGm�L)�\"f�n�I����57��NS�My�'1Q1��4��\\	�o���SGAM7�l�/6�1+->]4s��OtOd�I��������J0�D��W�*?hDݗi���6D��:Ijs���t_���\r-O�:�>��aHX��dV.LC<C�\$�@�(�l�C\$��Hֲ6W�K�K�D���1�!>:��@l��C����t:ȑ(��	�X��\"�U\n�V*�`�������]��~� o\r��:�T�A�3�_�:X�'��P�Q�'E �l��0/��5]�8-ߑ�i૑�T�O�Q4N	�*�U*�V��b�ú�V�!]+�|�(x��9�倲��́h) 舶��\$p\"�J�d+���r�\n9D`�Cb�B��T\$��\$1\$W���|ϩ���s	1��\n�*�ݐ	���8�\$F)���\\SI��\nr�#i�R����*�q�S`�(�����L�dTCb���0Dԛ��<9�p�H�s��e8��G�3@K�t0r�,�\\�@�<%�s\nx�����q�	��+���Ht�!1-�ZM4�\"�S\nAqI9f+?�E��\"���v�5����-���丘)�M��:C��WU��JQ����\nd�܂��8ީI�.�tWʓR��9�����Y���(��>�\\(���&,|R\nz�Ұ�(\"EƻX#� ����t�V���}��i�s� \"M2�P(�B(	�m�0��^I�r`d	�2�w2`�ؤǕ�O��+��\n	�8P�T���@�-�-\"�K��j��H�K���*/Ә�e�&Z��qe��0&��EE��w`��qvt�q��\r�t�s�̤�G�������ts�&���|9�@�bn�v	�JYDQ�	��NQ�Є��2���3B��A��:��Ή�Hs9����Ai�M����aB9�s�W����ta�ߐ�)ե��\$W��:B�{�㤿Y�n�忯����h��M�1@!�թ��W�chģHZL G��w����s/&R�&��C�I���a'����e�۱!~/�Pz'�:U͘p����6O��bC	��1Dcth��OX�x �����E/e����*\0J�u��@X�YM0f=�n#�Q6�/�\r!%���	�姢Ԭ:�\\��o��_�&!�\nH	�)�8�kAV���1�tR��Uf�_A\\2�-�gz%P�	����<��[QR�k>b���ץOAi��G�!�.\$��/%쾶Q� ��9�B��^6	�3�W&�Qet��K�q,^h��E^������v?��H&��)Ds'QB�R2�Yy΃��5{���őgD�tDt\\��KK+-(_���/)ieg�\r\"����ƹ�SD\"��H���|��'ǟ�oM]��f��z�wb\\��cG�y{���?������h;g��#��\0�gwSj�s��f���{I�\\��>�z..R�S�JZ���� 咒X�a�-,e��������.�\n���=���e%E@R������oHȔ�uM�?g�Pɤ-6��d̜!�U-5��<N��q�N�C��H��k�����Ӟ�o�_��~���R/�\0��n�`-��e�����F�~N��N�*�ȴ��z��/�E�*0B�/���i�����J�dُЦL�\0�?Ff�'��<�A6�|����/bo��&�	�K	g��C\nT�&���&�0�p����ΐ-�o�aH�jL��H^��\rd�	0�˰�\0P�\r���Py\r�����Gu\0����HD���0��n*��X`����Dj	���(-!t��׃B�����b����b�H\"����B}0D����I��a`zխ^~�\$\0��y\0�7�VI�h>B��|��Bm���,��\n���Zl��*�x�+̡��\"6#��e�ۥ��J��L.A�7���6��͌ܜ/���ed6�A��d3G�q�� RŪ䤦��\$�]���n���/��R����CI�m\$P���d�x*lq%j\\�OX���4i�gL�!nr\$S�Ĺ.��N�'����]#dpDM��D\\����� ���\r��\$2\\Έ]��G\"��^BbMr3#c��@���@��+�-2ֻ2�k���r�(�R��#�|��r��L";
			break;
		case"zh-tw":
			$f="�^��%ӕ\\�r�����|%��:�\$\ns�.e�UȸE9PK72�(�P�h)ʅ@�:i	%��c�Je �R)ܫ{��	Nd T�P���\\��Õ8�C��f4����aS@/%����N����Nd�%гC��ɗB�Q+����B�_MK,�\$���u��ow�f��T9�WK��ʏW����2mizX:P	�*��_/�g*eSLK�ۈ��ι^9�H�\r���7��Zz>�����0)ȿN�\n�r!U=R�\n����^���J��T�O�](��I��^ܫ�]E�J4\$yhr��2^?[���eC�r��^[#�k�֑g1'��)�T'9jB)#�,�%')n䪪�hV���d�=Oa�@�IBO���s�¦K���J��12A\$�&�8mQd���lY�r�%�\0J�ԀD&��H�i�/\r��U��w.�x].��2���ft(t	KS��?��2]��*�X!rB��]# �4��(�t��ek�\0���Tr��{4Ǒ42��zF�@4C(��C@�:�t�㽔4�9O�x�3��(���9�����JP|t)!B�1�/�B�|GI\\CD=z�%y�RQ s-�~W?��JQ]\$���:�A(\\��{�1�(M��ZS�����\0�<��(P9�*iXBJ��y<EAvt��C�dY+{G�e�P�mj�{_�����^��6C����↸��vs�|�s����GOޢ��D1T�\\yjz���P�2��@t����S%��\0N%�+	6�k��A��~�)�\"`A�����s\$���6�f\r�b�eU�xN*�`X'@]�0WVTu�-j�v��G��ϥ.r��9t�v?e�bݿQ�<^GXZ�x.�|�1ġ�D�a?�)^׹��P�=DD#i��`�92�A�M�L4rD3-Jў.Ϙ�(� 	sDF�\n��\0M+qЂ�K%Bt��/�`��5r\ndN\"	��{�Y�\"q'�@����2���r���@9\n+�2xD։r���X+\rb�u���l0ZIj-`^xn!�4���0�`b�_�:\\�s�u@ H`�^�z0ʶ�nr�\$�f��٨�4���P:*Q�N	���X	b,e�����Y��h�5����)\r��8������Jư|��\n#��W	2����\0Kh�\"����PE�D*��\rD�R!����*�fXEh�iHBl9�:O�\0�X09D��2��1J\$:��\n#1�q�����܈�L��(����3�\"/��U)/&\$��\n6�)P���%�9�(9�0�B�Tʬ�*���@!��Y�0���v�� ��������!��GSZ��&�,L0!2`@R��:�ֵ���J�ĩQxj��~�XW�l�D&Țd�9�p�H�\rY�'(�Ah�%ٜ()��s	�89���3e�o	�^�ÕN��s�H��4D�i��H�FX(�������&�ht�m�#��\"���V��V�bZh����t�5�0T\n�2�P��	�����ȝ���Ot�>S��%� ���4Z��@(JU	�8P�T�*�\0�B`E�L��Ҥ\$h�&d�G���o��p]8F�Ƙ��9�D�RvEE��/�O\0��#4�Z:dޛ���H@��,��S�����x�ȕ�A\n�ڐ��y\"��\$0\"�\0�����]aO���='��|ӡ��W�\0a�B��,�UcEn��	Uْ��Q�d�A4�*wp�LBV��Gf�_؉2ц�a,SES�8�<�2Ň8�M=01�t�зHs� F�.���s�Ӻ��\\T딟1!��tL��>�Ԋ����Ą���jc6��tض����;Q���J�\0O�C	���K'N�`���8����}EQ����IfE�%ӄ�m�xKV�op�>���\"|_�z��D��b��N	ŸM�t\rPDg=ɸ`��5ۘT��[����'�@[n\\l�\n� �����BH�ec�_�Q'&��:�	��V#,�㧎]���N���4�u��P����V�\"\\����`\\�#H L>����s�H��0����V'2cb�ArI�w�.Ef�Ә�54����f\$���9\\{���Xp�;<0lX|���O���d������_�t\"�\rQZƍ�%�u�ަ���t��1��Ǐ%ŧ�%6���~�E#�f�h�����ƒ]㯛��}�w���7��y\\����|�����w�#�ރ���9�;O�[�g��DE�A#��{��X�]���x8��l��va˼w����[~\nK��AҺ����&�\0N��'�����0��э�!�C��!P5�\"�vT%�n3����hP�l�%n�n��Lb��:��6��\\�\r7�>��\"lV�_⨙ci���W,���H4�,�D�/Z�&a�������\nCg-C\n,�\nk�p����l�\rp�t��k��M}\rmr��0��PAP�^�,��\nu�:!��梙b��'�7b��p��f�\r&*a!���m}Q(��t��i��j/�T~*�2װ���p�]eB7J��x\$pNf��<`B1-���1!\0�w\nt�1n̍Cq�1f���q�f1LAQ����m<Oqzb��ԭ?'emM�m9�@�����)��I��1���F�q1�0��p�A���,	�\r\0��h���l�~4g�N�݄0���\r�ƍ���+2��D���<AH_AF�0��b�\$0#k#��!�\\����\$�\r�D�\"\0��}��9rr:#�ق~5ƌJC(�\$N\$�8����P\n���Z�@�w�*�)ƧL&#�~^���,��%�A���8���mVz\r3)Jt���_r좬Ό⚭	���/�8�\"�u�����R�����z,1�ZvnB�G#��\$/Z�a`_O�����V����0-��O��yA�k&�%��Mv/ox��0��~p'6�2��~�.��p)�*�LX��� ���\r�|.�\0 }4�8��am�ti�.�1��2\$��Bi^y�����3S8]!&t~H��O�%®��B�1���L";
			break;
	}
	$wi=[];
	foreach (explode("\n", lzw_decompress($f)) as $X)
		$wi[]=(strpos($X, "\t")?explode("\t", $X):$X);

	return $wi;
}

if (!$wi) {
	$wi=get_translations($ca);
	$_SESSION["translations"]=$wi;
}
if (extension_loaded('pdo')) {
	class
	Min_PDO {

		var $_result, $server_info, $affected_rows, $errno, $error, $pdo;

		function __construct() {
			global $b;
			$hg=array_search("SQL", $b->operators);
			if ($hg!==false)
				unset($b->operators[$hg]);
		}

		function dsn($pc, $V, $F, $_f=[]) {
			$_f[PDO::ATTR_ERRMODE]=PDO::ERRMODE_SILENT;
			$_f[PDO::ATTR_STATEMENT_CLASS]=['Min_PDOStatement'];
			try {
				$this->pdo=new
				PDO($pc, $V, $F, $_f);
			} catch (Exception$Hc) {
				auth_error(h($Hc->getMessage()));
			}
			$this->server_info=@$this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
		}

		function quote($P) {
			return $this->pdo->quote($P);
		}

		function query($G, $Ei=false) {
			$H=$this->pdo->query($G);
			$this->error="";
			if (!$H) {
				[, $this->errno, $this->error]=$this->pdo->errorInfo();
				if (!$this->error)
					$this->error=lang(21);

				return false;
			}
			$this->store_result($H);

			return $H;
		}

		function multi_query($G) {
			return $this->_result=$this->query($G);
		}

		function store_result($H=null) {
			if (!$H) {
				$H=$this->_result;
				if (!$H)
					return false;
			}
			if ($H->columnCount()) {
				$H->num_rows=$H->rowCount();

				return $H;
			}
			$this->affected_rows=$H->rowCount();

			return true;
		}

		function next_result() {
			if (!$this->_result)
				return false;
			$this->_result->_offset=0;

			return @$this->_result->nextRowset();
		}

		function result($G, $o=0) {
			$H=$this->query($G);
			if (!$H)
				return false;
			$J=$H->fetch();

			return $J[$o];
		}

	}

	class
	Min_PDOStatement extends PDOStatement {

		var $_offset=0, $num_rows;

		function fetch_assoc() {
			return $this->fetch(PDO::FETCH_ASSOC);
		}

		function fetch_row() {
			return $this->fetch(PDO::FETCH_NUM);
		}

		function fetch_field() {
			$J=(object)$this->getColumnMeta($this->_offset++);
			$J->orgtable=$J->table;
			$J->orgname=$J->name;
			$J->charsetnr=(in_array("blob", (array)$J->flags)?63:0);

			return $J;
		}

	}
}
$kc=[];
function add_driver($u, $D) {
	global $kc;
	$kc[$u]=$D;
}

class
Min_SQL {

	var $_conn;

	function __construct($g) {
		$this->_conn=$g;
	}

	function select($Q, $L, $Z, $sd, $Bf=[], $_=1, $E=0, $pg=false) {
		global $b, $y;
		$ce=(count($sd)<count($L));
		$G=$b->selectQueryBuild($L, $Z, $sd, $Bf, $_, $E);
		if (!$G)
			$G="SELECT".limit(($_GET["page"]!="last"&&$_!=""&&$sd&&$ce&&$y=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ", $L)."\nFROM ".table($Q), ($Z?"\nWHERE ".implode(" AND ", $Z):"").($sd&&$ce?"\nGROUP BY ".implode(", ", $sd):"").($Bf?"\nORDER BY ".implode(", ", $Bf):""), ($_!=""?+$_:null), ($E?$_*$E:0), "\n");
		$Fh=microtime(true);
		$I=$this->_conn->query($G);
		if ($pg)
			echo $b->selectQuery($G, $Fh, !$I);

		return $I;
	}

	function delete($Q, $zg, $_=0) {
		$G="FROM ".table($Q);

		return queries("DELETE".($_?limit1($Q, $G, $zg):" $G$zg"));
	}

	function update($Q, $N, $zg, $_=0, $kh="\n") {
		$Wi=[];
		foreach ($N as $z=>$X)
			$Wi[]="$z = $X";
		$G=table($Q)." SET$kh".implode(",$kh", $Wi);

		return queries("UPDATE".($_?limit1($Q, $G, $zg, $kh):" $G$zg"));
	}

	function insert($Q, $N) {
		return queries("INSERT INTO ".table($Q).($N?" (".implode(", ", array_keys($N)).")\nVALUES (".implode(", ", $N).")":" DEFAULT VALUES"));
	}

	function insertUpdate($Q, $K, $ng) {
		return false;
	}

	function begin() {
		return queries("BEGIN");
	}

	function commit() {
		return queries("COMMIT");
	}

	function rollback() {
		return queries("ROLLBACK");
	}

	function slowQuery($G, $hi) {
	}

	function convertSearch($v, $X, $o) {
		return $v;
	}

	function value($X, $o) {
		return (method_exists($this->_conn, 'value')?$this->_conn->value($X, $o):(is_resource($X)?stream_get_contents($X):$X));
	}

	function quoteBinary($ah) {
		return q($ah);
	}

	function warnings() {
		return '';
	}

	function tableHelp($D) {
	}

}

$kc["sqlite"]="SQLite 3";
$kc["sqlite2"]="SQLite 2";
if (isset($_GET["sqlite"])||isset($_GET["sqlite2"])) {
	define("DRIVER", (isset($_GET["sqlite"])?"sqlite":"sqlite2"));
	if (class_exists(isset($_GET["sqlite"])?"SQLite3":"SQLiteDatabase")) {
		if (isset($_GET["sqlite"])) {
			class
			Min_SQLite {

				var $extension="SQLite3", $server_info, $affected_rows, $errno, $error, $_link;

				function __construct($q) {
					$this->_link=new
					SQLite3($q);
					$Zi=$this->_link->version();
					$this->server_info=$Zi["versionString"];
				}

				function query($G) {
					$H=@$this->_link->query($G);
					$this->error="";
					if (!$H) {
						$this->errno=$this->_link->lastErrorCode();
						$this->error=$this->_link->lastErrorMsg();

						return false;
					} elseif ($H->numColumns())
						return new
						Min_Result($H);
					$this->affected_rows=$this->_link->changes();

					return true;
				}

				function quote($P) {
					return (is_utf8($P)?"'".$this->_link->escapeString($P)."'":"x'".reset(unpack('H*', $P))."'");
				}

				function store_result() {
					return $this->_result;
				}

				function result($G, $o=0) {
					$H=$this->query($G);
					if (!is_object($H))
						return false;
					$J=$H->_result->fetchArray();

					return $J[$o];
				}

			}

			class
			Min_Result {

				var $_result, $_offset=0, $num_rows;

				function __construct($H) {
					$this->_result=$H;
				}

				function fetch_assoc() {
					return $this->_result->fetchArray(SQLITE3_ASSOC);
				}

				function fetch_row() {
					return $this->_result->fetchArray(SQLITE3_NUM);
				}

				function fetch_field() {
					$d=$this->_offset++;
					$T=$this->_result->columnType($d);

					return (object)["name"=>$this->_result->columnName($d), "type"=>$T, "charsetnr"=>($T==SQLITE3_BLOB?63:0),];
				}

				function __desctruct() {
					return $this->_result->finalize();
				}

			}
		} else {
			class
			Min_SQLite {

				var $extension="SQLite", $server_info, $affected_rows, $error, $_link;

				function __construct($q) {
					$this->server_info=sqlite_libversion();
					$this->_link=new
					SQLiteDatabase($q);
				}

				function query($G, $Ei=false) {
					$Se=($Ei?"unbufferedQuery":"query");
					$H=@$this->_link->$Se($G, SQLITE_BOTH, $n);
					$this->error="";
					if (!$H) {
						$this->error=$n;

						return false;
					} elseif ($H===true) {
						$this->affected_rows=$this->changes();

						return true;
					}

					return new
					Min_Result($H);
				}

				function quote($P) {
					return "'".sqlite_escape_string($P)."'";
				}

				function store_result() {
					return $this->_result;
				}

				function result($G, $o=0) {
					$H=$this->query($G);
					if (!is_object($H))
						return false;
					$J=$H->_result->fetch();

					return $J[$o];
				}

			}

			class
			Min_Result {

				var $_result, $_offset=0, $num_rows;

				function __construct($H) {
					$this->_result=$H;
					if (method_exists($H, 'numRows'))
						$this->num_rows=$H->numRows();
				}

				function fetch_assoc() {
					$J=$this->_result->fetch(SQLITE_ASSOC);
					if (!$J)
						return false;
					$I=[];
					foreach ($J as $z=>$X)
						$I[idf_unescape($z)]=$X;

					return $I;
				}

				function fetch_row() {
					return $this->_result->fetch(SQLITE_NUM);
				}

				function fetch_field() {
					$D=$this->_result->fieldName($this->_offset++);
					$cg='(\[.*]|"(?:[^"]|"")*"|(.+))';
					if (preg_match("~^($cg\\.)?$cg\$~", $D, $C)) {
						$Q=($C[3]!=""?$C[3]:idf_unescape($C[2]));
						$D=($C[5]!=""?$C[5]:idf_unescape($C[4]));
					}

					return (object)["name"=>$D, "orgname"=>$D, "orgtable"=>$Q,];
				}

			}
		}
	} elseif (extension_loaded("pdo_sqlite")) {
		class
		Min_SQLite extends Min_PDO {

			var $extension="PDO_SQLite";

			function __construct($q) {
				$this->dsn(DRIVER.":$q", "", "");
			}

		}
	}
	if (class_exists("Min_SQLite")) {
		class
		Min_DB extends Min_SQLite {

			function __construct() {
				parent::__construct(":memory:");
				$this->query("PRAGMA foreign_keys = 1");
			}

			function select_db($q) {
				if (is_readable($q)&&$this->query("ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~", $q)?$q:dirname($_SERVER["SCRIPT_FILENAME"])."/$q")." AS a")) {
					parent::__construct($q);
					$this->query("PRAGMA foreign_keys = 1");
					$this->query("PRAGMA busy_timeout = 500");

					return true;
				}

				return false;
			}

			function multi_query($G) {
				return $this->_result=$this->query($G);
			}

			function next_result() {
				return false;
			}

		}
	}

	class
	Min_Driver extends Min_SQL {

		function insertUpdate($Q, $K, $ng) {
			$Wi=[];
			foreach ($K as $N)
				$Wi[]="(".implode(", ", $N).")";

			return queries("REPLACE INTO ".table($Q)." (".implode(", ", array_keys(reset($K))).") VALUES\n".implode(",\n", $Wi));
		}

		function tableHelp($D) {
			if ($D=="sqlite_sequence")
				return "fileformat2.html#seqtab";
			if ($D=="sqlite_master")
				return "fileformat2.html#$D";
		}

	}

	function idf_escape($v) {
		return '"'.str_replace('"', '""', $v).'"';
	}

	function table($v) {
		return idf_escape($v);
	}

	function connect() {
		global $b;
		[, , $F]=$b->credentials();
		if ($F!="")
			return lang(22);

		return new
		Min_DB;
	}

	function get_databases() {
		return [];
	}

	function limit($G, $Z, $_, $kf=0, $kh=" ") {
		return " $G$Z".($_!==null?$kh."LIMIT $_".($kf?" OFFSET $kf":""):"");
	}

	function limit1($Q, $G, $Z, $kh="\n") {
		global $g;

		return (preg_match('~^INTO~', $G)||$g->result("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($G, $Z, 1, 0, $kh):" $G WHERE rowid = (SELECT rowid FROM ".table($Q).$Z.$kh."LIMIT 1)");
	}

	function db_collation($l, $nb) {
		global $g;

		return $g->result("PRAGMA encoding");
	}

	function engines() {
		return [];
	}

	function logged_user() {
		return get_current_user();
	}

	function tables_list() {
		return get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name");
	}

	function count_tables($k) {
		return [];
	}

	function table_status($D="") {
		global $g;
		$I=[];
		foreach (get_rows("SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') ".($D!=""?"AND name = ".q($D):"ORDER BY name")) as $J) {
			$J["Rows"]=$g->result("SELECT COUNT(*) FROM ".idf_escape($J["Name"]));
			$I[$J["Name"]]=$J;
		}
		foreach (get_rows("SELECT * FROM sqlite_sequence", null, "") as $J)
			$I[$J["name"]]["Auto_increment"]=$J["seq"];

		return ($D!=""?$I[$D]:$I);
	}

	function is_view($R) {
		return $R["Engine"]=="view";
	}

	function fk_support($R) {
		global $g;

		return !$g->result("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");
	}

	function fields($Q) {
		global $g;
		$I=[];
		$ng="";
		foreach (get_rows("PRAGMA table_info(".table($Q).")") as $J) {
			$D=$J["name"];
			$T=strtolower($J["type"]);
			$Yb=$J["dflt_value"];
			$I[$D]=["field"=>$D, "type"=>(preg_match('~int~i', $T)?"integer":(preg_match('~char|clob|text~i', $T)?"text":(preg_match('~blob~i', $T)?"blob":(preg_match('~real|floa|doub~i', $T)?"real":"numeric")))), "full_type"=>$T, "default"=>(preg_match("~'(.*)'~", $Yb, $C)?str_replace("''", "'", $C[1]):($Yb=="NULL"?null:$Yb)), "null"=>!$J["notnull"], "privileges"=>["select"=>1, "insert"=>1, "update"=>1], "primary"=>$J["pk"],];
			if ($J["pk"]) {
				if ($ng!="")
					$I[$ng]["auto_increment"]=false; elseif (preg_match('~^integer$~i', $T))
					$I[$D]["auto_increment"]=true;
				$ng=$D;
			}
		}
		$Ah=$g->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));
		preg_match_all('~(("[^"]*+")+|[a-z0-9_]+)\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i', $Ah, $Fe, PREG_SET_ORDER);
		foreach ($Fe as $C) {
			$D=str_replace('""', '"', preg_replace('~^"|"$~', '', $C[1]));
			if ($I[$D])
				$I[$D]["collation"]=trim($C[3], "'");
		}

		return $I;
	}

	function indexes($Q, $h=null) {
		global $g;
		if (!is_object($h))
			$h=$g;
		$I=[];
		$Ah=$h->result("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($Q));
		if (preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i', $Ah, $C)) {
			$I[""]=["type"=>"PRIMARY", "columns"=>[], "lengths"=>[], "descs"=>[]];
			preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i', $C[1], $Fe, PREG_SET_ORDER);
			foreach ($Fe as $C) {
				$I[""]["columns"][]=idf_unescape($C[2]).$C[4];
				$I[""]["descs"][]=(preg_match('~DESC~i', $C[5])?'1':null);
			}
		}
		if (!$I) {
			foreach (fields($Q) as $D=>$o) {
				if ($o["primary"])
					$I[""]=["type"=>"PRIMARY", "columns"=>[$D], "lengths"=>[], "descs"=>[null]];
			}
		}
		$Dh=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($Q), $h);
		foreach (get_rows("PRAGMA index_list(".table($Q).")", $h) as $J) {
			$D=$J["name"];
			$w=["type"=>($J["unique"]?"UNIQUE":"INDEX")];
			$w["lengths"]=[];
			$w["descs"]=[];
			foreach (get_rows("PRAGMA index_info(".idf_escape($D).")", $h) as $Zg) {
				$w["columns"][]=$Zg["name"];
				$w["descs"][]=null;
			}
			if (preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($D).' ON '.idf_escape($Q), '~').' \((.*)\)$~i', $Dh[$D], $Jg)) {
				preg_match_all('/("[^"]*+")+( DESC)?/', $Jg[2], $Fe);
				foreach ($Fe[2] as $z=>$X) {
					if ($X)
						$w["descs"][$z]='1';
				}
			}
			if (!$I[""]||$w["type"]!="UNIQUE"||$w["columns"]!=$I[""]["columns"]||$w["descs"]!=$I[""]["descs"]||!preg_match("~^sqlite_~", $D))
				$I[$D]=$w;
		}

		return $I;
	}

	function foreign_keys($Q) {
		$I=[];
		foreach (get_rows("PRAGMA foreign_key_list(".table($Q).")") as $J) {
			$r=&$I[$J["id"]];
			if (!$r)
				$r=$J;
			$r["source"][]=$J["from"];
			$r["target"][]=$J["to"];
		}

		return $I;
	}

	function view($D) {
		global $g;

		return ["select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU', '', $g->result("SELECT sql FROM sqlite_master WHERE name = ".q($D)))];
	}

	function collations() {
		return (isset($_GET["create"])?get_vals("PRAGMA collation_list", 1):[]);
	}

	function information_schema($l) {
		return false;
	}

	function error() {
		global $g;

		return h($g->error);
	}

	function check_sqlite_name($D) {
		global $g;
		$Qc="db|sdb|sqlite";
		if (!preg_match("~^[^\\0]*\\.($Qc)\$~", $D)) {
			$g->error=lang(23, str_replace("|", ", ", $Qc));

			return false;
		}

		return true;
	}

	function create_database($l, $mb) {
		global $g;
		if (file_exists($l)) {
			$g->error=lang(24);

			return false;
		}
		if (!check_sqlite_name($l))
			return false;
		try {
			$A=new
			Min_SQLite($l);
		} catch (Exception$Hc) {
			$g->error=$Hc->getMessage();

			return false;
		}
		$A->query('PRAGMA encoding = "UTF-8"');
		$A->query('CREATE TABLE adminer (i)');
		$A->query('DROP TABLE adminer');

		return true;
	}

	function drop_databases($k) {
		global $g;
		$g->__construct(":memory:");
		foreach ($k as $l) {
			if (!@unlink($l)) {
				$g->error=lang(24);

				return false;
			}
		}

		return true;
	}

	function rename_database($D, $mb) {
		global $g;
		if (!check_sqlite_name($D))
			return false;
		$g->__construct(":memory:");
		$g->error=lang(24);

		return @rename(DB, $D);
	}

	function auto_increment() {
		return " PRIMARY KEY".(DRIVER=="sqlite"?" AUTOINCREMENT":"");
	}

	function alter_table($Q, $D, $p, $hd, $tb, $_c, $mb, $La, $Wf) {
		global $g;
		$Pi=($Q==""||$hd);
		foreach ($p as $o) {
			if ($o[0]!=""||!$o[1]||$o[2]) {
				$Pi=true;
				break;
			}
		}
		$c=[];
		$Kf=[];
		foreach ($p as $o) {
			if ($o[1]) {
				$c[]=($Pi?$o[1]:"ADD ".implode($o[1]));
				if ($o[0]!="")
					$Kf[$o[0]]=$o[1][0];
			}
		}
		if (!$Pi) {
			foreach ($c as $X) {
				if (!queries("ALTER TABLE ".table($Q)." $X"))
					return false;
			}
			if ($Q!=$D&&!queries("ALTER TABLE ".table($Q)." RENAME TO ".table($D)))
				return false;
		} elseif (!recreate_table($Q, $D, $c, $Kf, $hd, $La))
			return false;
		if ($La) {
			queries("BEGIN");
			queries("UPDATE sqlite_sequence SET seq = $La WHERE name = ".q($D));
			if (!$g->affected_rows)
				queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($D).", $La)");
			queries("COMMIT");
		}

		return true;
	}

	function recreate_table($Q, $D, $p, $Kf, $hd, $La, $x=[]) {
		global $g;
		if ($Q!="") {
			if (!$p) {
				foreach (fields($Q) as $z=>$o) {
					if ($x)
						$o["auto_increment"]=0;
					$p[]=process_field($o, $o);
					$Kf[$z]=idf_escape($z);
				}
			}
			$og=false;
			foreach ($p as $o) {
				if ($o[6])
					$og=true;
			}
			$nc=[];
			foreach ($x as $z=>$X) {
				if ($X[2]=="DROP") {
					$nc[$X[1]]=true;
					unset($x[$z]);
				}
			}
			foreach (indexes($Q) as $ie=>$w) {
				$e=[];
				foreach ($w["columns"] as $z=>$d) {
					if (!$Kf[$d])
						continue
						2;
					$e[]=$Kf[$d].($w["descs"][$z]?" DESC":"");
				}
				if (!$nc[$ie]) {
					if ($w["type"]!="PRIMARY"||!$og)
						$x[]=[$w["type"], $ie, $e];
				}
			}
			foreach ($x as $z=>$X) {
				if ($X[0]=="PRIMARY") {
					unset($x[$z]);
					$hd[]="  PRIMARY KEY (".implode(", ", $X[2]).")";
				}
			}
			foreach (foreign_keys($Q) as $ie=>$r) {
				foreach ($r["source"] as $z=>$d) {
					if (!$Kf[$d])
						continue
						2;
					$r["source"][$z]=idf_unescape($Kf[$d]);
				}
				if (!isset($hd[" $ie"]))
					$hd[]=" ".format_foreign_key($r);
			}
			queries("BEGIN");
		}
		foreach ($p as $z=>$o)
			$p[$z]="  ".implode($o);
		$p=array_merge($p, array_filter($hd));
		$bi=($Q==$D?"adminer_$D":$D);
		if (!queries("CREATE TABLE ".table($bi)." (\n".implode(",\n", $p)."\n)"))
			return false;
		if ($Q!="") {
			if ($Kf&&!queries("INSERT INTO ".table($bi)." (".implode(", ", $Kf).") SELECT ".implode(", ", array_map('idf_escape', array_keys($Kf)))." FROM ".table($Q)))
				return false;
			$Bi=[];
			foreach (triggers($Q) as $_i=>$ii) {
				$zi=trigger($_i);
				$Bi[]="CREATE TRIGGER ".idf_escape($_i)." ".implode(" ", $ii)." ON ".table($D)."\n$zi[Statement]";
			}
			$La=$La?0:$g->result("SELECT seq FROM sqlite_sequence WHERE name = ".q($Q));
			if (!queries("DROP TABLE ".table($Q))||($Q==$D&&!queries("ALTER TABLE ".table($bi)." RENAME TO ".table($D)))||!alter_indexes($D, $x))
				return false;
			if ($La)
				queries("UPDATE sqlite_sequence SET seq = $La WHERE name = ".q($D));
			foreach ($Bi as $zi) {
				if (!queries($zi))
					return false;
			}
			queries("COMMIT");
		}

		return true;
	}

	function index_sql($Q, $T, $D, $e) {
		return "CREATE $T ".($T!="INDEX"?"INDEX ":"").idf_escape($D!=""?$D:uniqid($Q."_"))." ON ".table($Q)." $e";
	}

	function alter_indexes($Q, $c) {
		foreach ($c as $ng) {
			if ($ng[0]=="PRIMARY")
				return recreate_table($Q, $Q, [], [], [], 0, $c);
		}
		foreach (array_reverse($c) as $X) {
			if (!queries($X[2]=="DROP"?"DROP INDEX ".idf_escape($X[1]):index_sql($Q, $X[0], $X[1], "(".implode(", ", $X[2]).")")))
				return false;
		}

		return true;
	}

	function truncate_tables($S) {
		return apply_queries("DELETE FROM", $S);
	}

	function drop_views($bj) {
		return apply_queries("DROP VIEW", $bj);
	}

	function drop_tables($S) {
		return apply_queries("DROP TABLE", $S);
	}

	function move_tables($S, $bj, $Zh) {
		return false;
	}

	function trigger($D) {
		global $g;
		if ($D=="")
			return ["Statement"=>"BEGIN\n\t;\nEND"];
		$v='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';
		$Ai=trigger_options();
		preg_match("~^CREATE\\s+TRIGGER\\s*$v\\s*(".implode("|", $Ai["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($v))?\\s+ON\\s*$v\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is", $g->result("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($D)), $C);
		$jf=$C[3];

		return ["Timing"=>strtoupper($C[1]), "Event"=>strtoupper($C[2]).($jf?" OF":""), "Of"=>idf_unescape($jf), "Trigger"=>$D, "Statement"=>$C[4],];
	}

	function triggers($Q) {
		$I=[];
		$Ai=trigger_options();
		foreach (get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)) as $J) {
			preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|", $Ai["Timing"]).')\s*(.*?)\s+ON\b~i', $J["sql"], $C);
			$I[$J["name"]]=[$C[1], $C[2]];
		}

		return $I;
	}

	function trigger_options() {
		return ["Timing"=>["BEFORE", "AFTER", "INSTEAD OF"], "Event"=>["INSERT", "UPDATE", "UPDATE OF", "DELETE"], "Type"=>["FOR EACH ROW"],];
	}

	function begin() {
		return queries("BEGIN");
	}

	function last_id() {
		global $g;

		return $g->result("SELECT LAST_INSERT_ROWID()");
	}

	function explain($g, $G) {
		return $g->query("EXPLAIN QUERY PLAN $G");
	}

	function found_rows($R, $Z) {
	}

	function types() {
		return [];
	}

	function schemas() {
		return [];
	}

	function get_schema() {
		return "";
	}

	function set_schema($dh) {
		return true;
	}

	function create_sql($Q, $La, $Kh) {
		global $g;
		$I=$g->result("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($Q));
		foreach (indexes($Q) as $D=>$w) {
			if ($D=='')
				continue;
			$I.=";\n\n".index_sql($Q, $w['type'], $D, "(".implode(", ", array_map('idf_escape', $w['columns'])).")");
		}

		return $I;
	}

	function truncate_sql($Q) {
		return "DELETE FROM ".table($Q);
	}

	function use_sql($j) {
	}

	function trigger_sql($Q) {
		return implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($Q)));
	}

	function show_variables() {
		global $g;
		$I=[];
		foreach (["auto_vacuum", "cache_size", "count_changes", "default_cache_size", "empty_result_callbacks", "encoding", "foreign_keys", "full_column_names", "fullfsync", "journal_mode", "journal_size_limit", "legacy_file_format", "locking_mode", "page_size", "max_page_count", "read_uncommitted", "recursive_triggers", "reverse_unordered_selects", "secure_delete", "short_column_names", "synchronous", "temp_store", "temp_store_directory", "schema_version", "integrity_check", "quick_check"] as $z)
			$I[$z]=$g->result("PRAGMA $z");

		return $I;
	}

	function show_status() {
		$I=[];
		foreach (get_vals("PRAGMA compile_options") as $zf) {
			[$z, $X]=explode("=", $zf, 2);
			$I[$z]=$X;
		}

		return $I;
	}

	function convert_field($o) {
	}

	function unconvert_field($o, $I) {
		return $I;
	}

	function support($Vc) {
		return preg_match('~^(columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~', $Vc);
	}

	function driver_config() {
		$U=["integer"=>0, "real"=>0, "numeric"=>0, "text"=>0, "blob"=>0];

		return ['possible_drivers'=>[(isset($_GET["sqlite"])?"SQLite3":"SQLite"), "PDO_SQLite"], 'jush'=>"sqlite", 'types'=>$U, 'structured_types'=>array_keys($U), 'unsigned'=>[], 'operators'=>["=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT IN", "IS NOT NULL", "SQL"], 'functions'=>["hex", "length", "lower", "round", "unixepoch", "upper"], 'grouping'=>["avg", "count", "count distinct", "group_concat", "max", "min", "sum"], 'edit_functions'=>[[], ["integer|real|numeric"=>"+/-", "text"=>"||",]],];
	}
}
$kc["pgsql"]="PostgreSQL";
if (isset($_GET["pgsql"])) {
	define("DRIVER", "pgsql");
	if (extension_loaded("pgsql")) {
		class
		Min_DB {

			var $extension="PgSQL", $_link, $_result, $_string, $_database=true, $server_info, $affected_rows, $error, $timeout;

			function _error($Cc, $n) {
				if (ini_bool("html_errors"))
					$n=html_entity_decode(strip_tags($n));
				$n=preg_replace('~^[^:]*: ~', '', $n);
				$this->error=$n;
			}

			function connect($M, $V, $F) {
				global $b;
				$l=$b->database();
				set_error_handler([$this, '_error']);
				$this->_string="host='".str_replace(":", "' port='", addcslashes($M, "'\\"))."' user='".addcslashes($V, "'\\")."' password='".addcslashes($F, "'\\")."'";
				$this->_link=@pg_connect("$this->_string dbname='".($l!=""?addcslashes($l, "'\\"):"postgres")."'", PGSQL_CONNECT_FORCE_NEW);
				if (!$this->_link&&$l!="") {
					$this->_database=false;
					$this->_link=@pg_connect("$this->_string dbname='postgres'", PGSQL_CONNECT_FORCE_NEW);
				}
				restore_error_handler();
				if ($this->_link) {
					$Zi=pg_version($this->_link);
					$this->server_info=$Zi["server"];
					pg_set_client_encoding($this->_link, "UTF8");
				}

				return (bool)$this->_link;
			}

			function quote($P) {
				return "'".pg_escape_string($this->_link, $P)."'";
			}

			function value($X, $o) {
				return ($o["type"]=="bytea"&&$X!==null?pg_unescape_bytea($X):$X);
			}

			function quoteBinary($P) {
				return "'".pg_escape_bytea($this->_link, $P)."'";
			}

			function select_db($j) {
				global $b;
				if ($j==$b->database())
					return $this->_database;
				$I=@pg_connect("$this->_string dbname='".addcslashes($j, "'\\")."'", PGSQL_CONNECT_FORCE_NEW);
				if ($I)
					$this->_link=$I;

				return $I;
			}

			function close() {
				$this->_link=@pg_connect("$this->_string dbname='postgres'");
			}

			function query($G, $Ei=false) {
				$H=@pg_query($this->_link, $G);
				$this->error="";
				if (!$H) {
					$this->error=pg_last_error($this->_link);
					$I=false;
				} elseif (!pg_num_fields($H)) {
					$this->affected_rows=pg_affected_rows($H);
					$I=true;
				} else$I=new
				Min_Result($H);
				if ($this->timeout) {
					$this->timeout=0;
					$this->query("RESET statement_timeout");
				}

				return $I;
			}

			function multi_query($G) {
				return $this->_result=$this->query($G);
			}

			function store_result() {
				return $this->_result;
			}

			function next_result() {
				return false;
			}

			function result($G, $o=0) {
				$H=$this->query($G);
				if (!$H||!$H->num_rows)
					return false;

				return pg_fetch_result($H->_result, 0, $o);
			}

			function warnings() {
				return h(pg_last_notice($this->_link));
			}

		}

		class
		Min_Result {

			var $_result, $_offset=0, $num_rows;

			function __construct($H) {
				$this->_result=$H;
				$this->num_rows=pg_num_rows($H);
			}

			function fetch_assoc() {
				return pg_fetch_assoc($this->_result);
			}

			function fetch_row() {
				return pg_fetch_row($this->_result);
			}

			function fetch_field() {
				$d=$this->_offset++;
				$I=new
				stdClass;
				if (function_exists('pg_field_table'))
					$I->orgtable=pg_field_table($this->_result, $d);
				$I->name=pg_field_name($this->_result, $d);
				$I->orgname=$I->name;
				$I->type=pg_field_type($this->_result, $d);
				$I->charsetnr=($I->type=="bytea"?63:0);

				return $I;
			}

			function __destruct() {
				pg_free_result($this->_result);
			}

		}
	} elseif (extension_loaded("pdo_pgsql")) {
		class
		Min_DB extends Min_PDO {

			var $extension="PDO_PgSQL", $timeout;

			function connect($M, $V, $F) {
				global $b;
				$l=$b->database();
				$this->dsn("pgsql:host='".str_replace(":", "' port='", addcslashes($M, "'\\"))."' client_encoding=utf8 dbname='".($l!=""?addcslashes($l, "'\\"):"postgres")."'", $V, $F);

				return true;
			}

			function select_db($j) {
				global $b;

				return ($b->database()==$j);
			}

			function quoteBinary($ah) {
				return q($ah);
			}

			function query($G, $Ei=false) {
				$I=parent::query($G, $Ei);
				if ($this->timeout) {
					$this->timeout=0;
					parent::query("RESET statement_timeout");
				}

				return $I;
			}

			function warnings() {
				return '';
			}

			function close() {
			}

		}
	}

	class
	Min_Driver extends Min_SQL {

		function insertUpdate($Q, $K, $ng) {
			global $g;
			foreach ($K as $N) {
				$Li=[];
				$Z=[];
				foreach ($N as $z=>$X) {
					$Li[]="$z = $X";
					if (isset($ng[idf_unescape($z)]))
						$Z[]="$z = $X";
				}
				if (!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ", $Li)." WHERE ".implode(" AND ", $Z))&&$g->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ", array_keys($N)).") VALUES (".implode(", ", $N).")")))
					return false;
			}

			return true;
		}

		function slowQuery($G, $hi) {
			$this->_conn->query("SET statement_timeout = ".(1000*$hi));
			$this->_conn->timeout=1000*$hi;

			return $G;
		}

		function convertSearch($v, $X, $o) {
			return (preg_match('~char|text'.(!preg_match('~LIKE~', $X["op"])?'|date|time(stamp)?|boolean|uuid|'.number_type():'').'~', $o["type"])?$v:"CAST($v AS text)");
		}

		function quoteBinary($ah) {
			return $this->_conn->quoteBinary($ah);
		}

		function warnings() {
			return $this->_conn->warnings();
		}

		function tableHelp($D) {
			$ze=["information_schema"=>"infoschema", "pg_catalog"=>"catalog",];
			$A=$ze[$_GET["ns"]];
			if ($A)
				return "$A-".str_replace("_", "-", $D).".html";
		}

	}

	function idf_escape($v) {
		return '"'.str_replace('"', '""', $v).'"';
	}

	function table($v) {
		return idf_escape($v);
	}

	function connect() {
		global $b, $U, $Jh;
		$g=new
		Min_DB;
		$Mb=$b->credentials();
		if ($g->connect($Mb[0], $Mb[1], $Mb[2])) {
			if (min_version(9, 0, $g)) {
				$g->query("SET application_name = 'Adminer'");
				if (min_version(9.2, 0, $g)) {
					$Jh[lang(25)][]="json";
					$U["json"]=4294967295;
					if (min_version(9.4, 0, $g)) {
						$Jh[lang(25)][]="jsonb";
						$U["jsonb"]=4294967295;
					}
				}
			}

			return $g;
		}

		return $g->error;
	}

	function get_databases() {
		return get_vals("SELECT datname FROM pg_database WHERE has_database_privilege(datname, 'CONNECT') ORDER BY datname");
	}

	function limit($G, $Z, $_, $kf=0, $kh=" ") {
		return " $G$Z".($_!==null?$kh."LIMIT $_".($kf?" OFFSET $kf":""):"");
	}

	function limit1($Q, $G, $Z, $kh="\n") {
		return (preg_match('~^INTO~', $G)?limit($G, $Z, 1, 0, $kh):" $G".(is_view(table_status1($Q))?$Z:" WHERE ctid = (SELECT ctid FROM ".table($Q).$Z.$kh."LIMIT 1)"));
	}

	function db_collation($l, $nb) {
		global $g;

		return $g->result("SELECT datcollate FROM pg_database WHERE datname = ".q($l));
	}

	function engines() {
		return [];
	}

	function logged_user() {
		global $g;

		return $g->result("SELECT user");
	}

	function tables_list() {
		$G="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";
		if (support('materializedview'))
			$G.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";
		$G.="
ORDER BY 1";

		return get_key_vals($G);
	}

	function count_tables($k) {
		return [];
	}

	function table_status($D="") {
		$I=[];
		foreach (get_rows("SELECT c.relname AS \"Name\", CASE c.relkind WHEN 'r' THEN 'table' WHEN 'm' THEN 'materialized view' ELSE 'view' END AS \"Engine\", pg_relation_size(c.oid) AS \"Data_length\", pg_total_relation_size(c.oid) - pg_relation_size(c.oid) AS \"Index_length\", obj_description(c.oid, 'pg_class') AS \"Comment\", ".(min_version(12)?"''":"CASE WHEN c.relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\", c.reltuples as \"Rows\", n.nspname
FROM pg_class c
JOIN pg_namespace n ON(n.nspname = current_schema() AND n.oid = c.relnamespace)
WHERE relkind IN ('r', 'm', 'v', 'f', 'p')
".($D!=""?"AND relname = ".q($D):"ORDER BY relname")) as $J)
			$I[$J["Name"]]=$J;

		return ($D!=""?$I[$D]:$I);
	}

	function is_view($R) {
		return in_array($R["Engine"], ["view", "materialized view"]);
	}

	function fk_support($R) {
		return true;
	}

	function fields($Q) {
		$I=[];
		$Ca=['timestamp without time zone'=>'timestamp', 'timestamp with time zone'=>'timestamptz',];
		foreach (get_rows("SELECT a.attname AS field, format_type(a.atttypid, a.atttypmod) AS full_type, pg_get_expr(d.adbin, d.adrelid) AS default, a.attnotnull::int, col_description(c.oid, a.attnum) AS comment".(min_version(10)?", a.attidentity":"")."
FROM pg_class c
JOIN pg_namespace n ON c.relnamespace = n.oid
JOIN pg_attribute a ON c.oid = a.attrelid
LEFT JOIN pg_attrdef d ON c.oid = d.adrelid AND a.attnum = d.adnum
WHERE c.relname = ".q($Q)."
AND n.nspname = current_schema()
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum") as $J) {
			preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~', $J["full_type"], $C);
			[, $T, $we, $J["length"], $xa, $Fa]=$C;
			$J["length"].=$Fa;
			$cb=$T.$xa;
			if (isset($Ca[$cb])) {
				$J["type"]=$Ca[$cb];
				$J["full_type"]=$J["type"].$we.$Fa;
			} else {
				$J["type"]=$T;
				$J["full_type"]=$J["type"].$we.$xa.$Fa;
			}
			if (in_array($J['attidentity'], ['a', 'd']))
				$J['default']='GENERATED '.($J['attidentity']=='d'?'BY DEFAULT':'ALWAYS').' AS IDENTITY';
			$J["null"]=!$J["attnotnull"];
			$J["auto_increment"]=$J['attidentity']||preg_match('~^nextval\(~i', $J["default"]);
			$J["privileges"]=["insert"=>1, "select"=>1, "update"=>1];
			if (preg_match('~(.+)::[^,)]+(.*)~', $J["default"], $C))
				$J["default"]=($C[1]=="NULL"?null:idf_unescape($C[1]).$C[2]);
			$I[$J["field"]]=$J;
		}

		return $I;
	}

	function indexes($Q, $h=null) {
		global $g;
		if (!is_object($h))
			$h=$g;
		$I=[];
		$Sh=$h->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($Q));
		$e=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Sh AND attnum > 0", $h);
		foreach (get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption, (indpred IS NOT NULL)::int as indispartial FROM pg_index i, pg_class ci WHERE i.indrelid = $Sh AND ci.oid = i.indexrelid", $h) as $J) {
			$Kg=$J["relname"];
			$I[$Kg]["type"]=($J["indispartial"]?"INDEX":($J["indisprimary"]?"PRIMARY":($J["indisunique"]?"UNIQUE":"INDEX")));
			$I[$Kg]["columns"]=[];
			foreach (explode(" ", $J["indkey"]) as $Rd)
				$I[$Kg]["columns"][]=$e[$Rd];
			$I[$Kg]["descs"]=[];
			foreach (explode(" ", $J["indoption"]) as $Sd)
				$I[$Kg]["descs"][]=($Sd&1?'1':null);
			$I[$Kg]["lengths"]=[];
		}

		return $I;
	}

	function foreign_keys($Q) {
		global $sf;
		$I=[];
		foreach (get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = ".q($Q)." AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname") as $J) {
			if (preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA', $J['definition'], $C)) {
				$J['source']=array_map('idf_unescape', array_map('trim', explode(',', $C[1])));
				if (preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~', $C[2], $Ee)) {
					$J['ns']=idf_unescape($Ee[2]);
					$J['table']=idf_unescape($Ee[4]);
				}
				$J['target']=array_map('idf_unescape', array_map('trim', explode(',', $C[3])));
				$J['on_delete']=(preg_match("~ON DELETE ($sf)~", $C[4], $Ee)?$Ee[1]:'NO ACTION');
				$J['on_update']=(preg_match("~ON UPDATE ($sf)~", $C[4], $Ee)?$Ee[1]:'NO ACTION');
				$I[$J['conname']]=$J;
			}
		}

		return $I;
	}

	function constraints($Q) {
		global $sf;
		$I=[];
		foreach (get_rows("SELECT conname, consrc
FROM pg_catalog.pg_constraint
INNER JOIN pg_catalog.pg_namespace ON pg_constraint.connamespace = pg_namespace.oid
INNER JOIN pg_catalog.pg_class ON pg_constraint.conrelid = pg_class.oid AND pg_constraint.connamespace = pg_class.relnamespace
WHERE pg_constraint.contype = 'c'
AND conrelid != 0 -- handle only CONSTRAINTs here, not TYPES
AND nspname = current_schema()
AND relname = ".q($Q)."
ORDER BY connamespace, conname") as $J)
			$I[$J['conname']]=$J['consrc'];

		return $I;
	}

	function view($D) {
		global $g;

		return ["select"=>trim($g->result("SELECT pg_get_viewdef(".$g->result("SELECT oid FROM pg_class WHERE relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema()) AND relname = ".q($D)).")"))];
	}

	function collations() {
		return [];
	}

	function information_schema($l) {
		return ($l=="information_schema");
	}

	function error() {
		global $g;
		$I=h($g->error);
		if (preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s', $I, $C))
			$I=$C[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($C[3]).'})(.*)~', '\1<b>\2</b>', $C[2]).$C[4];

		return nl_br($I);
	}

	function create_database($l, $mb) {
		return queries("CREATE DATABASE ".idf_escape($l).($mb?" ENCODING ".idf_escape($mb):""));
	}

	function drop_databases($k) {
		global $g;
		$g->close();

		return apply_queries("DROP DATABASE", $k, 'idf_escape');
	}

	function rename_database($D, $mb) {
		return queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($D));
	}

	function auto_increment() {
		return "";
	}

	function alter_table($Q, $D, $p, $hd, $tb, $_c, $mb, $La, $Wf) {
		$c=[];
		$yg=[];
		if ($Q!=""&&$Q!=$D)
			$yg[]="ALTER TABLE ".table($Q)." RENAME TO ".table($D);
		foreach ($p as $o) {
			$d=idf_escape($o[0]);
			$X=$o[1];
			if (!$X)
				$c[]="DROP $d"; else {
				$Vi=$X[5];
				unset($X[5]);
				if ($o[0]=="") {
					if (isset($X[6]))
						$X[1]=($X[1]==" bigint"?" big":($X[1]==" smallint"?" small":" "))."serial";
					$c[]=($Q!=""?"ADD ":"  ").implode($X);
					if (isset($X[6]))
						$c[]=($Q!=""?"ADD":" ")." PRIMARY KEY ($X[0])";
				} else {
					if ($d!=$X[0])
						$yg[]="ALTER TABLE ".table($D)." RENAME $d TO $X[0]";
					$c[]="ALTER $d TYPE$X[1]";
					if (!$X[6]) {
						$c[]="ALTER $d ".($X[3]?"SET$X[3]":"DROP DEFAULT");
						$c[]="ALTER $d ".($X[2]==" NULL"?"DROP NOT":"SET").$X[2];
					}
				}
				if ($o[0]!=""||$Vi!="")
					$yg[]="COMMENT ON COLUMN ".table($D).".$X[0] IS ".($Vi!=""?substr($Vi, 9):"''");
			}
		}
		$c=array_merge($c, $hd);
		if ($Q=="")
			array_unshift($yg, "CREATE TABLE ".table($D)." (\n".implode(",\n", $c)."\n)"); elseif ($c)
			array_unshift($yg, "ALTER TABLE ".table($Q)."\n".implode(",\n", $c));
		if ($Q!=""||$tb!="")
			$yg[]="COMMENT ON TABLE ".table($D)." IS ".q($tb);
		if ($La!="") {
		}
		foreach ($yg as $G) {
			if (!queries($G))
				return false;
		}

		return true;
	}

	function alter_indexes($Q, $c) {
		$i=[];
		$lc=[];
		$yg=[];
		foreach ($c as $X) {
			if ($X[0]!="INDEX")
				$i[]=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ", $X[2]).")"); elseif ($X[2]=="DROP")
				$lc[]=idf_escape($X[1]);
			else$yg[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ", $X[2]).")";
		}
		if ($i)
			array_unshift($yg, "ALTER TABLE ".table($Q).implode(",", $i));
		if ($lc)
			array_unshift($yg, "DROP INDEX ".implode(", ", $lc));
		foreach ($yg as $G) {
			if (!queries($G))
				return false;
		}

		return true;
	}

	function truncate_tables($S) {
		return queries("TRUNCATE ".implode(", ", array_map('table', $S)));

		return true;
	}

	function drop_views($bj) {
		return drop_tables($bj);
	}

	function drop_tables($S) {
		foreach ($S as $Q) {
			$O=table_status($Q);
			if (!queries("DROP ".strtoupper($O["Engine"])." ".table($Q)))
				return false;
		}

		return true;
	}

	function move_tables($S, $bj, $Zh) {
		foreach (array_merge($S, $bj) as $Q) {
			$O=table_status($Q);
			if (!queries("ALTER ".strtoupper($O["Engine"])." ".table($Q)." SET SCHEMA ".idf_escape($Zh)))
				return false;
		}

		return true;
	}

	function trigger($D, $Q) {
		if ($D=="")
			return ["Statement"=>"EXECUTE PROCEDURE ()"];
		$e=[];
		$Z="WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q)." AND trigger_name = ".q($D);
		foreach (get_rows("SELECT * FROM information_schema.triggered_update_columns $Z") as $J)
			$e[]=$J["event_object_column"];
		$I=[];
		foreach (get_rows('SELECT trigger_name AS "Trigger", action_timing AS "Timing", event_manipulation AS "Event", \'FOR EACH \' || action_orientation AS "Type", action_statement AS "Statement" FROM information_schema.triggers '."$Z ORDER BY event_manipulation DESC") as $J) {
			if ($e&&$J["Event"]=="UPDATE")
				$J["Event"].=" OF";
			$J["Of"]=implode(", ", $e);
			if ($I)
				$J["Event"].=" OR $I[Event]";
			$I=$J;
		}

		return $I;
	}

	function triggers($Q) {
		$I=[];
		foreach (get_rows("SELECT * FROM information_schema.triggers WHERE trigger_schema = current_schema() AND event_object_table = ".q($Q)) as $J) {
			$zi=trigger($J["trigger_name"], $Q);
			$I[$zi["Trigger"]]=[$zi["Timing"], $zi["Event"]];
		}

		return $I;
	}

	function trigger_options() {
		return ["Timing"=>["BEFORE", "AFTER"], "Event"=>["INSERT", "UPDATE", "UPDATE OF", "DELETE", "INSERT OR UPDATE", "INSERT OR UPDATE OF", "DELETE OR INSERT", "DELETE OR UPDATE", "DELETE OR UPDATE OF", "DELETE OR INSERT OR UPDATE", "DELETE OR INSERT OR UPDATE OF"], "Type"=>["FOR EACH ROW", "FOR EACH STATEMENT"],];
	}

	function routine($D, $T) {
		$K=get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = '.q($D));
		$I=$K[0];
		$I["returns"]=["type"=>$I["type_udt_name"]];
		$I["fields"]=get_rows('SELECT parameter_name AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = '.q($D).'
ORDER BY ordinal_position');

		return $I;
	}

	function routines() {
		return get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');
	}

	function routine_languages() {
		return get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");
	}

	function routine_id($D, $J) {
		$I=[];
		foreach ($J["fields"] as $o)
			$I[]=$o["type"];

		return idf_escape($D)."(".implode(", ", $I).")";
	}

	function last_id() {
		return 0;
	}

	function explain($g, $G) {
		return $g->query("EXPLAIN $G");
	}

	function found_rows($R, $Z) {
		global $g;
		if (preg_match("~ rows=([0-9]+)~", $g->result("EXPLAIN SELECT * FROM ".idf_escape($R["Name"]).($Z?" WHERE ".implode(" AND ", $Z):"")), $Jg))
			return $Jg[1];

		return false;
	}

	function types() {
		return get_vals("SELECT typname
FROM pg_type
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = current_schema())
AND typtype IN ('b','d','e')
AND typelem = 0");
	}

	function schemas() {
		return get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");
	}

	function get_schema() {
		global $g;

		return $g->result("SELECT current_schema()");
	}

	function set_schema($ch, $h=null) {
		global $g, $U, $Jh;
		if (!$h)
			$h=$g;
		$I=$h->query("SET search_path TO ".idf_escape($ch));
		foreach (types() as $T) {
			if (!isset($U[$T])) {
				$U[$T]=0;
				$Jh[lang(26)][]=$T;
			}
		}

		return $I;
	}

	function foreign_keys_sql($Q) {
		$I="";
		$O=table_status($Q);
		$ed=foreign_keys($Q);
		ksort($ed);
		foreach ($ed as $dd=>$cd)
			$I.="ALTER TABLE ONLY ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." ADD CONSTRAINT ".idf_escape($dd)." $cd[definition] ".($cd['deferrable']?'DEFERRABLE':'NOT DEFERRABLE').";\n";

		return ($I?"$I\n":$I);
	}

	function create_sql($Q, $La, $Kh) {
		global $g;
		$I='';
		$Sg=[];
		$mh=[];
		$O=table_status($Q);
		if (is_view($O)) {
			$aj=view($Q);

			return rtrim("CREATE VIEW ".idf_escape($Q)." AS $aj[select]", ";");
		}
		$p=fields($Q);
		$x=indexes($Q);
		ksort($x);
		$Cb=constraints($Q);
		if (!$O||empty($p))
			return false;
		$I="CREATE TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." (\n    ";
		foreach ($p as $Xc=>$o) {
			$Tf=idf_escape($o['field']).' '.$o['full_type'].default_value($o).($o['attnotnull']?" NOT NULL":"");
			$Sg[]=$Tf;
			if (preg_match('~nextval\(\'([^\']+)\'\)~', $o['default'], $Fe)) {
				$lh=$Fe[1];
				$_h=reset(get_rows(min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q($lh):"SELECT * FROM $lh"));
				$mh[]=($Kh=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $lh;\n":"")."CREATE SEQUENCE $lh INCREMENT $_h[increment_by] MINVALUE $_h[min_value] MAXVALUE $_h[max_value]".($La&&$_h['last_value']?" START $_h[last_value]":"")." CACHE $_h[cache_value];";
			}
		}
		if (!empty($mh))
			$I=implode("\n\n", $mh)."\n\n$I";
		foreach ($x as $Md=>$w) {
			switch ($w['type']) {
				case'UNIQUE':
					$Sg[]="CONSTRAINT ".idf_escape($Md)." UNIQUE (".implode(', ', array_map('idf_escape', $w['columns'])).")";
					break;
				case'PRIMARY':
					$Sg[]="CONSTRAINT ".idf_escape($Md)." PRIMARY KEY (".implode(', ', array_map('idf_escape', $w['columns'])).")";
					break;
			}
		}
		foreach ($Cb as $zb=>$Ab)
			$Sg[]="CONSTRAINT ".idf_escape($zb)." CHECK $Ab";
		$I.=implode(",\n    ", $Sg)."\n) WITH (oids = ".($O['Oid']?'true':'false').");";
		foreach ($x as $Md=>$w) {
			if ($w['type']=='INDEX') {
				$e=[];
				foreach ($w['columns'] as $z=>$X)
					$e[]=idf_escape($X).($w['descs'][$z]?" DESC":"");
				$I.="\n\nCREATE INDEX ".idf_escape($Md)." ON ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." USING btree (".implode(', ', $e).");";
			}
		}
		if ($O['Comment'])
			$I.="\n\nCOMMENT ON TABLE ".idf_escape($O['nspname']).".".idf_escape($O['Name'])." IS ".q($O['Comment']).";";
		foreach ($p as $Xc=>$o) {
			if ($o['comment'])
				$I.="\n\nCOMMENT ON COLUMN ".idf_escape($O['nspname']).".".idf_escape($O['Name']).".".idf_escape($Xc)." IS ".q($o['comment']).";";
		}

		return rtrim($I, ';');
	}

	function truncate_sql($Q) {
		return "TRUNCATE ".table($Q);
	}

	function trigger_sql($Q) {
		$O=table_status($Q);
		$I="";
		foreach (triggers($Q) as $yi=>$xi) {
			$zi=trigger($yi, $O['Name']);
			$I.="\nCREATE TRIGGER ".idf_escape($zi['Trigger'])." $zi[Timing] $zi[Event] ON ".idf_escape($O["nspname"]).".".idf_escape($O['Name'])." $zi[Type] $zi[Statement];;\n";
		}

		return $I;
	}

	function use_sql($j) {
		return "\connect ".idf_escape($j);
	}

	function show_variables() {
		return get_key_vals("SHOW ALL");
	}

	function process_list() {
		return get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));
	}

	function show_status() {
	}

	function convert_field($o) {
	}

	function unconvert_field($o, $I) {
		return $I;
	}

	function support($Vc) {
		return preg_match('~^(database|table|columns|sql|indexes|descidx|comment|view|'.(min_version(9.3)?'materializedview|':'').'scheme|routine|processlist|sequence|trigger|type|variables|drop_col|kill|dump)$~', $Vc);
	}

	function kill_process($X) {
		return queries("SELECT pg_terminate_backend(".number($X).")");
	}

	function connection_id() {
		return "SELECT pg_backend_pid()";
	}

	function max_connections() {
		global $g;

		return $g->result("SHOW max_connections");
	}

	function driver_config() {
		$U=[];
		$Jh=[];
		foreach ([lang(27)=>["smallint"=>5, "integer"=>10, "bigint"=>19, "boolean"=>1, "numeric"=>0, "real"=>7, "double precision"=>16, "money"=>20], lang(28)=>["date"=>13, "time"=>17, "timestamp"=>20, "timestamptz"=>21, "interval"=>0], lang(25)=>["character"=>0, "character varying"=>0, "text"=>0, "tsquery"=>0, "tsvector"=>0, "uuid"=>0, "xml"=>0], lang(29)=>["bit"=>0, "bit varying"=>0, "bytea"=>0], lang(30)=>["cidr"=>43, "inet"=>43, "macaddr"=>17, "txid_snapshot"=>0], lang(31)=>["box"=>0, "circle"=>0, "line"=>0, "lseg"=>0, "path"=>0, "point"=>0, "polygon"=>0],] as $z=>$X) {
			$U+=$X;
			$Jh[$z]=array_keys($X);
		}

		return ['possible_drivers'=>["PgSQL", "PDO_PgSQL"], 'jush'=>"pgsql", 'types'=>$U, 'structured_types'=>$Jh, 'unsigned'=>[], 'operators'=>["=", "<", ">", "<=", ">=", "!=", "~", "!~", "LIKE", "LIKE %%", "ILIKE", "ILIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT IN", "IS NOT NULL"], 'functions'=>["char_length", "lower", "round", "to_hex", "to_timestamp", "upper"], 'grouping'=>["avg", "count", "count distinct", "max", "min", "sum"], 'edit_functions'=>[["char"=>"md5", "date|time"=>"now",], [number_type()=>"+/-", "date|time"=>"+ interval/- interval", "char|text"=>"||",]],];
	}
}
$kc["oracle"]="Oracle (beta)";
if (isset($_GET["oracle"])) {
	define("DRIVER", "oracle");
	if (extension_loaded("oci8")) {
		class
		Min_DB {

			var $extension="oci8", $_link, $_result, $server_info, $affected_rows, $errno, $error;

			var $_current_db;

			function _error($Cc, $n) {
				if (ini_bool("html_errors"))
					$n=html_entity_decode(strip_tags($n));
				$n=preg_replace('~^[^:]*: ~', '', $n);
				$this->error=$n;
			}

			function connect($M, $V, $F) {
				$this->_link=@oci_new_connect($V, $F, $M, "AL32UTF8");
				if ($this->_link) {
					$this->server_info=oci_server_version($this->_link);

					return true;
				}
				$n=oci_error();
				$this->error=$n["message"];

				return false;
			}

			function quote($P) {
				return "'".str_replace("'", "''", $P)."'";
			}

			function select_db($j) {
				$this->_current_db=$j;

				return true;
			}

			function query($G, $Ei=false) {
				$H=oci_parse($this->_link, $G);
				$this->error="";
				if (!$H) {
					$n=oci_error($this->_link);
					$this->errno=$n["code"];
					$this->error=$n["message"];

					return false;
				}
				set_error_handler([$this, '_error']);
				$I=@oci_execute($H);
				restore_error_handler();
				if ($I) {
					if (oci_num_fields($H))
						return new
						Min_Result($H);
					$this->affected_rows=oci_num_rows($H);
					oci_free_statement($H);
				}

				return $I;
			}

			function multi_query($G) {
				return $this->_result=$this->query($G);
			}

			function store_result() {
				return $this->_result;
			}

			function next_result() {
				return false;
			}

			function result($G, $o=1) {
				$H=$this->query($G);
				if (!is_object($H)||!oci_fetch($H->_result))
					return false;

				return oci_result($H->_result, $o);
			}

		}

		class
		Min_Result {

			var $_result, $_offset=1, $num_rows;

			function __construct($H) {
				$this->_result=$H;
			}

			function _convert($J) {
				foreach ((array)$J as $z=>$X) {
					if (is_a($X, 'OCI-Lob'))
						$J[$z]=$X->load();
				}

				return $J;
			}

			function fetch_assoc() {
				return $this->_convert(oci_fetch_assoc($this->_result));
			}

			function fetch_row() {
				return $this->_convert(oci_fetch_row($this->_result));
			}

			function fetch_field() {
				$d=$this->_offset++;
				$I=new
				stdClass;
				$I->name=oci_field_name($this->_result, $d);
				$I->orgname=$I->name;
				$I->type=oci_field_type($this->_result, $d);
				$I->charsetnr=(preg_match("~raw|blob|bfile~", $I->type)?63:0);

				return $I;
			}

			function __destruct() {
				oci_free_statement($this->_result);
			}

		}
	} elseif (extension_loaded("pdo_oci")) {
		class
		Min_DB extends Min_PDO {

			var $extension="PDO_OCI";

			var $_current_db;

			function connect($M, $V, $F) {
				$this->dsn("oci:dbname=//$M;charset=AL32UTF8", $V, $F);

				return true;
			}

			function select_db($j) {
				$this->_current_db=$j;

				return true;
			}

		}
	}

	class
	Min_Driver extends Min_SQL {

		function begin() {
			return true;
		}

		function insertUpdate($Q, $K, $ng) {
			global $g;
			foreach ($K as $N) {
				$Li=[];
				$Z=[];
				foreach ($N as $z=>$X) {
					$Li[]="$z = $X";
					if (isset($ng[idf_unescape($z)]))
						$Z[]="$z = $X";
				}
				if (!(($Z&&queries("UPDATE ".table($Q)." SET ".implode(", ", $Li)." WHERE ".implode(" AND ", $Z))&&$g->affected_rows)||queries("INSERT INTO ".table($Q)." (".implode(", ", array_keys($N)).") VALUES (".implode(", ", $N).")")))
					return false;
			}

			return true;
		}

	}

	function idf_escape($v) {
		return '"'.str_replace('"', '""', $v).'"';
	}

	function table($v) {
		return idf_escape($v);
	}

	function connect() {
		global $b;
		$g=new
		Min_DB;
		$Mb=$b->credentials();
		if ($g->connect($Mb[0], $Mb[1], $Mb[2]))
			return $g;

		return $g->error;
	}

	function get_databases() {
		return get_vals("SELECT tablespace_name FROM user_tablespaces ORDER BY 1");
	}

	function limit($G, $Z, $_, $kf=0, $kh=" ") {
		return ($kf?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $G$Z) t WHERE rownum <= ".($_+$kf).") WHERE rnum > $kf":($_!==null?" * FROM (SELECT $G$Z) WHERE rownum <= ".($_+$kf):" $G$Z"));
	}

	function limit1($Q, $G, $Z, $kh="\n") {
		return " $G$Z";
	}

	function db_collation($l, $nb) {
		global $g;

		return $g->result("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");
	}

	function engines() {
		return [];
	}

	function logged_user() {
		global $g;

		return $g->result("SELECT USER FROM DUAL");
	}

	function get_current_db() {
		global $g;
		$l=$g->_current_db?$g->_current_db:DB;
		unset($g->_current_db);

		return $l;
	}

	function where_owner($lg, $Nf="owner") {
		if (!$_GET["ns"])
			return '';

		return "$lg$Nf = sys_context('USERENV', 'CURRENT_SCHEMA')";
	}

	function views_table($e) {
		$Nf=where_owner('');

		return "(SELECT $e FROM all_views WHERE ".($Nf?$Nf:"rownum < 0").")";
	}

	function tables_list() {
		$aj=views_table("view_name");
		$Nf=where_owner(" AND ");

		return get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = ".q(DB)."$Nf
UNION SELECT view_name, 'view' FROM $aj
ORDER BY 1");
	}

	function count_tables($k) {
		global $g;
		$I=[];
		foreach ($k as $l)
			$I[$l]=$g->result("SELECT COUNT(*) FROM all_tables WHERE tablespace_name = ".q($l));

		return $I;
	}

	function table_status($D="") {
		$I=[];
		$eh=q($D);
		$l=get_current_db();
		$aj=views_table("view_name");
		$Nf=where_owner(" AND ");
		foreach (get_rows('SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = '.q($l).$Nf.($D!=""?" AND table_name = $eh":"")."
UNION SELECT view_name, 'view', 0, 0 FROM $aj".($D!=""?" WHERE view_name = $eh":"")."
ORDER BY 1") as $J) {
			if ($D!="")
				return $J;
			$I[$J["Name"]]=$J;
		}

		return $I;
	}

	function is_view($R) {
		return $R["Engine"]=="view";
	}

	function fk_support($R) {
		return true;
	}

	function fields($Q) {
		$I=[];
		$Nf=where_owner(" AND ");
		foreach (get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($Q)."$Nf ORDER BY column_id") as $J) {
			$T=$J["DATA_TYPE"];
			$we="$J[DATA_PRECISION],$J[DATA_SCALE]";
			if ($we==",")
				$we=$J["CHAR_COL_DECL_LENGTH"];
			$I[$J["COLUMN_NAME"]]=["field"=>$J["COLUMN_NAME"], "full_type"=>$T.($we?"($we)":""), "type"=>strtolower($T), "length"=>$we, "default"=>$J["DATA_DEFAULT"], "null"=>($J["NULLABLE"]=="Y"), "privileges"=>["insert"=>1, "select"=>1, "update"=>1],];
		}

		return $I;
	}

	function indexes($Q, $h=null) {
		$I=[];
		$Nf=where_owner(" AND ", "aic.table_owner");
		foreach (get_rows("SELECT aic.*, ac.constraint_type, atc.data_default
FROM all_ind_columns aic
LEFT JOIN all_constraints ac ON aic.index_name = ac.constraint_name AND aic.table_name = ac.table_name AND aic.index_owner = ac.owner
LEFT JOIN all_tab_cols atc ON aic.column_name = atc.column_name AND aic.table_name = atc.table_name AND aic.index_owner = atc.owner
WHERE aic.table_name = ".q($Q)."$Nf
ORDER BY ac.constraint_type, aic.column_position", $h) as $J) {
			$Md=$J["INDEX_NAME"];
			$qb=$J["DATA_DEFAULT"];
			$qb=($qb?trim($qb, '"'):$J["COLUMN_NAME"]);
			$I[$Md]["type"]=($J["CONSTRAINT_TYPE"]=="P"?"PRIMARY":($J["CONSTRAINT_TYPE"]=="U"?"UNIQUE":"INDEX"));
			$I[$Md]["columns"][]=$qb;
			$I[$Md]["lengths"][]=($J["CHAR_LENGTH"]&&$J["CHAR_LENGTH"]!=$J["COLUMN_LENGTH"]?$J["CHAR_LENGTH"]:null);
			$I[$Md]["descs"][]=($J["DESCEND"]&&$J["DESCEND"]=="DESC"?'1':null);
		}

		return $I;
	}

	function view($D) {
		$aj=views_table("view_name, text");
		$K=get_rows('SELECT text "select" FROM '.$aj.' WHERE view_name = '.q($D));

		return reset($K);
	}

	function collations() {
		return [];
	}

	function information_schema($l) {
		return false;
	}

	function error() {
		global $g;

		return h($g->error);
	}

	function explain($g, $G) {
		$g->query("EXPLAIN PLAN FOR $G");

		return $g->query("SELECT * FROM plan_table");
	}

	function found_rows($R, $Z) {
	}

	function auto_increment() {
		return "";
	}

	function alter_table($Q, $D, $p, $hd, $tb, $_c, $mb, $La, $Wf) {
		$c=$lc=[];
		$Hf=($Q?fields($Q):[]);
		foreach ($p as $o) {
			$X=$o[1];
			if ($X&&$o[0]!=""&&idf_escape($o[0])!=$X[0])
				queries("ALTER TABLE ".table($Q)." RENAME COLUMN ".idf_escape($o[0])." TO $X[0]");
			$Gf=$Hf[$o[0]];
			if ($X&&$Gf) {
				$mf=process_field($Gf, $Gf);
				if ($X[2]==$mf[2])
					$X[2]="";
			}
			if ($X)
				$c[]=($Q!=""?($o[0]!=""?"MODIFY (":"ADD ("):"  ").implode($X).($Q!=""?")":""); else$lc[]=idf_escape($o[0]);
		}
		if ($Q=="")
			return queries("CREATE TABLE ".table($D)." (\n".implode(",\n", $c)."\n)");

		return (!$c||queries("ALTER TABLE ".table($Q)."\n".implode("\n", $c)))&&(!$lc||queries("ALTER TABLE ".table($Q)." DROP (".implode(", ", $lc).")"))&&($Q==$D||queries("ALTER TABLE ".table($Q)." RENAME TO ".table($D)));
	}

	function alter_indexes($Q, $c) {
		$lc=[];
		$yg=[];
		foreach ($c as $X) {
			if ($X[0]!="INDEX") {
				$X[2]=preg_replace('~ DESC$~', '', $X[2]);
				$i=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ", $X[2]).")");
				array_unshift($yg, "ALTER TABLE ".table($Q).$i);
			} elseif ($X[2]=="DROP")
				$lc[]=idf_escape($X[1]);
			else$yg[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q)." (".implode(", ", $X[2]).")";
		}
		if ($lc)
			array_unshift($yg, "DROP INDEX ".implode(", ", $lc));
		foreach ($yg as $G) {
			if (!queries($G))
				return false;
		}

		return true;
	}

	function foreign_keys($Q) {
		$I=[];
		$G="SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = ".q($Q);
		foreach (get_rows($G) as $J)
			$I[$J['NAME']]=["db"=>$J['DEST_DB'], "table"=>$J['DEST_TABLE'], "source"=>[$J['SRC_COLUMN']], "target"=>[$J['DEST_COLUMN']], "on_delete"=>$J['ON_DELETE'], "on_update"=>null,];

		return $I;
	}

	function truncate_tables($S) {
		return apply_queries("TRUNCATE TABLE", $S);
	}

	function drop_views($bj) {
		return apply_queries("DROP VIEW", $bj);
	}

	function drop_tables($S) {
		return apply_queries("DROP TABLE", $S);
	}

	function last_id() {
		return 0;
	}

	function schemas() {
		$I=get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX')) ORDER BY 1");

		return ($I?$I:get_vals("SELECT DISTINCT owner FROM all_tables WHERE tablespace_name = ".q(DB)." ORDER BY 1"));
	}

	function get_schema() {
		global $g;

		return $g->result("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");
	}

	function set_schema($dh, $h=null) {
		global $g;
		if (!$h)
			$h=$g;

		return $h->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($dh));
	}

	function show_variables() {
		return get_key_vals('SELECT name, display_value FROM v$parameter');
	}

	function process_list() {
		return get_rows('SELECT sess.process AS "process", sess.username AS "user", sess.schemaname AS "schema", sess.status AS "status", sess.wait_class AS "wait_class", sess.seconds_in_wait AS "seconds_in_wait", sql.sql_text AS "sql_text", sess.machine AS "machine", sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');
	}

	function show_status() {
		$K=get_rows('SELECT * FROM v$instance');

		return reset($K);
	}

	function convert_field($o) {
	}

	function unconvert_field($o, $I) {
		return $I;
	}

	function support($Vc) {
		return preg_match('~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view)$~', $Vc);
	}

	function driver_config() {
		$U=[];
		$Jh=[];
		foreach ([lang(27)=>["number"=>38, "binary_float"=>12, "binary_double"=>21], lang(28)=>["date"=>10, "timestamp"=>29, "interval year"=>12, "interval day"=>28], lang(25)=>["char"=>2000, "varchar2"=>4000, "nchar"=>2000, "nvarchar2"=>4000, "clob"=>4294967295, "nclob"=>4294967295], lang(29)=>["raw"=>2000, "long raw"=>2147483648, "blob"=>4294967295, "bfile"=>4294967296],] as $z=>$X) {
			$U+=$X;
			$Jh[$z]=array_keys($X);
		}

		return ['possible_drivers'=>["OCI8", "PDO_OCI"], 'jush'=>"oracle", 'types'=>$U, 'structured_types'=>$Jh, 'unsigned'=>[], 'operators'=>["=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT REGEXP", "NOT IN", "IS NOT NULL", "SQL"], 'functions'=>["length", "lower", "round", "upper"], 'grouping'=>["avg", "count", "count distinct", "max", "min", "sum"], 'edit_functions'=>[["date"=>"current_date", "timestamp"=>"current_timestamp",], ["number|float|double"=>"+/-", "date|timestamp"=>"+ interval/- interval", "char|clob"=>"||",]],];
	}
}
$kc["mssql"]="MS SQL (beta)";
if (isset($_GET["mssql"])) {
	define("DRIVER", "mssql");
	if (extension_loaded("sqlsrv")) {
		class
		Min_DB {

			var $extension="sqlsrv", $_link, $_result, $server_info, $affected_rows, $errno, $error;

			function _get_error() {
				$this->error="";
				foreach (sqlsrv_errors() as $n) {
					$this->errno=$n["code"];
					$this->error.="$n[message]\n";
				}
				$this->error=rtrim($this->error);
			}

			function connect($M, $V, $F) {
				global $b;
				$l=$b->database();
				$_b=["UID"=>$V, "PWD"=>$F, "CharacterSet"=>"UTF-8"];
				if ($l!="")
					$_b["Database"]=$l;
				$this->_link=@sqlsrv_connect(preg_replace('~:~', ',', $M), $_b);
				if ($this->_link) {
					$Td=sqlsrv_server_info($this->_link);
					$this->server_info=$Td['SQLServerVersion'];
				} else$this->_get_error();

				return (bool)$this->_link;
			}

			function quote($P) {
				return "'".str_replace("'", "''", $P)."'";
			}

			function select_db($j) {
				return $this->query("USE ".idf_escape($j));
			}

			function query($G, $Ei=false) {
				$H=sqlsrv_query($this->_link, $G);
				$this->error="";
				if (!$H) {
					$this->_get_error();

					return false;
				}

				return $this->store_result($H);
			}

			function multi_query($G) {
				$this->_result=sqlsrv_query($this->_link, $G);
				$this->error="";
				if (!$this->_result) {
					$this->_get_error();

					return false;
				}

				return true;
			}

			function store_result($H=null) {
				if (!$H)
					$H=$this->_result;
				if (!$H)
					return false;
				if (sqlsrv_field_metadata($H))
					return new
					Min_Result($H);
				$this->affected_rows=sqlsrv_rows_affected($H);

				return true;
			}

			function next_result() {
				return $this->_result?sqlsrv_next_result($this->_result):null;
			}

			function result($G, $o=0) {
				$H=$this->query($G);
				if (!is_object($H))
					return false;
				$J=$H->fetch_row();

				return $J[$o];
			}

		}

		class
		Min_Result {

			var $_result, $_offset=0, $_fields, $num_rows;

			function __construct($H) {
				$this->_result=$H;
			}

			function _convert($J) {
				foreach ((array)$J as $z=>$X) {
					if (is_a($X, 'DateTime'))
						$J[$z]=$X->format("Y-m-d H:i:s");
				}

				return $J;
			}

			function fetch_assoc() {
				return $this->_convert(sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_ASSOC));
			}

			function fetch_row() {
				return $this->_convert(sqlsrv_fetch_array($this->_result, SQLSRV_FETCH_NUMERIC));
			}

			function fetch_field() {
				if (!$this->_fields)
					$this->_fields=sqlsrv_field_metadata($this->_result);
				$o=$this->_fields[$this->_offset++];
				$I=new
				stdClass;
				$I->name=$o["Name"];
				$I->orgname=$o["Name"];
				$I->type=($o["Type"]==1?254:0);

				return $I;
			}

			function seek($kf) {
				for ($t=0; $t<$kf; $t++)
					sqlsrv_fetch($this->_result);
			}

			function __destruct() {
				sqlsrv_free_stmt($this->_result);
			}

		}
	} elseif (extension_loaded("mssql")) {
		class
		Min_DB {

			var $extension="MSSQL", $_link, $_result, $server_info, $affected_rows, $error;

			function connect($M, $V, $F) {
				$this->_link=@mssql_connect($M, $V, $F);
				if ($this->_link) {
					$H=$this->query("SELECT SERVERPROPERTY('ProductLevel'), SERVERPROPERTY('Edition')");
					if ($H) {
						$J=$H->fetch_row();
						$this->server_info=$this->result("sp_server_info 2", 2)." [$J[0]] $J[1]";
					}
				} else$this->error=mssql_get_last_message();

				return (bool)$this->_link;
			}

			function quote($P) {
				return "'".str_replace("'", "''", $P)."'";
			}

			function select_db($j) {
				return mssql_select_db($j);
			}

			function query($G, $Ei=false) {
				$H=@mssql_query($G, $this->_link);
				$this->error="";
				if (!$H) {
					$this->error=mssql_get_last_message();

					return false;
				}
				if ($H===true) {
					$this->affected_rows=mssql_rows_affected($this->_link);

					return true;
				}

				return new
				Min_Result($H);
			}

			function multi_query($G) {
				return $this->_result=$this->query($G);
			}

			function store_result() {
				return $this->_result;
			}

			function next_result() {
				return mssql_next_result($this->_result->_result);
			}

			function result($G, $o=0) {
				$H=$this->query($G);
				if (!is_object($H))
					return false;

				return mssql_result($H->_result, 0, $o);
			}

		}

		class
		Min_Result {

			var $_result, $_offset=0, $_fields, $num_rows;

			function __construct($H) {
				$this->_result=$H;
				$this->num_rows=mssql_num_rows($H);
			}

			function fetch_assoc() {
				return mssql_fetch_assoc($this->_result);
			}

			function fetch_row() {
				return mssql_fetch_row($this->_result);
			}

			function num_rows() {
				return mssql_num_rows($this->_result);
			}

			function fetch_field() {
				$I=mssql_fetch_field($this->_result);
				$I->orgtable=$I->table;
				$I->orgname=$I->name;

				return $I;
			}

			function seek($kf) {
				mssql_data_seek($this->_result, $kf);
			}

			function __destruct() {
				mssql_free_result($this->_result);
			}

		}
	} elseif (extension_loaded("pdo_dblib")) {
		class
		Min_DB extends Min_PDO {

			var $extension="PDO_DBLIB";

			function connect($M, $V, $F) {
				$this->dsn("dblib:charset=utf8;host=".str_replace(":", ";unix_socket=", preg_replace('~:(\d)~', ';port=\1', $M)), $V, $F);

				return true;
			}

			function select_db($j) {
				return $this->query("USE ".idf_escape($j));
			}

		}
	}

	class
	Min_Driver extends Min_SQL {

		function insertUpdate($Q, $K, $ng) {
			foreach ($K as $N) {
				$Li=[];
				$Z=[];
				foreach ($N as $z=>$X) {
					$Li[]="$z = $X";
					if (isset($ng[idf_unescape($z)]))
						$Z[]="$z = $X";
				}
				if (!queries("MERGE ".table($Q)." USING (VALUES(".implode(", ", $N).")) AS source (c".implode(", c", range(1, count($N))).") ON ".implode(" AND ", $Z)." WHEN MATCHED THEN UPDATE SET ".implode(", ", $Li)." WHEN NOT MATCHED THEN INSERT (".implode(", ", array_keys($N)).") VALUES (".implode(", ", $N).");"))
					return false;
			}

			return true;
		}

		function begin() {
			return queries("BEGIN TRANSACTION");
		}

	}

	function idf_escape($v) {
		return "[".str_replace("]", "]]", $v)."]";
	}

	function table($v) {
		return ($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($v);
	}

	function connect() {
		global $b;
		$g=new
		Min_DB;
		$Mb=$b->credentials();
		if ($g->connect($Mb[0], $Mb[1], $Mb[2]))
			return $g;

		return $g->error;
	}

	function get_databases() {
		return get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");
	}

	function limit($G, $Z, $_, $kf=0, $kh=" ") {
		return ($_!==null?" TOP (".($_+$kf).")":"")." $G$Z";
	}

	function limit1($Q, $G, $Z, $kh="\n") {
		return limit($G, $Z, 1, 0, $kh);
	}

	function db_collation($l, $nb) {
		global $g;

		return $g->result("SELECT collation_name FROM sys.databases WHERE name = ".q($l));
	}

	function engines() {
		return [];
	}

	function logged_user() {
		global $g;

		return $g->result("SELECT SUSER_NAME()");
	}

	function tables_list() {
		return get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");
	}

	function count_tables($k) {
		global $g;
		$I=[];
		foreach ($k as $l) {
			$g->select_db($l);
			$I[$l]=$g->result("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");
		}

		return $I;
	}

	function table_status($D="") {
		$I=[];
		foreach (get_rows("SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment FROM sys.all_objects AS ao WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($D!=""?"AND name = ".q($D):"ORDER BY name")) as $J) {
			if ($D!="")
				return $J;
			$I[$J["Name"]]=$J;
		}

		return $I;
	}

	function is_view($R) {
		return $R["Engine"]=="VIEW";
	}

	function fk_support($R) {
		return true;
	}

	function fields($Q) {
		$vb=get_key_vals("SELECT objname, cast(value as varchar(max)) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($Q).", 'column', NULL)");
		$I=[];
		foreach (get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, CAST(d.definition as text) [default]
FROM sys.all_columns c
JOIN sys.all_objects o ON c.object_id = o.object_id
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.parent_column_id
WHERE o.schema_id = SCHEMA_ID(".q(get_schema()).") AND o.type IN ('S', 'U', 'V') AND o.name = ".q($Q)) as $J) {
			$T=$J["type"];
			$we=(preg_match("~char|binary~", $T)?$J["max_length"]:($T=="decimal"?"$J[precision],$J[scale]":""));
			$I[$J["name"]]=["field"=>$J["name"], "full_type"=>$T.($we?"($we)":""), "type"=>$T, "length"=>$we, "default"=>$J["default"], "null"=>$J["is_nullable"], "auto_increment"=>$J["is_identity"], "collation"=>$J["collation_name"], "privileges"=>["insert"=>1, "select"=>1, "update"=>1], "primary"=>$J["is_identity"], "comment"=>$vb[$J["name"]],];
		}

		return $I;
	}

	function indexes($Q, $h=null) {
		$I=[];
		foreach (get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($Q), $h) as $J) {
			$D=$J["name"];
			$I[$D]["type"]=($J["is_primary_key"]?"PRIMARY":($J["is_unique"]?"UNIQUE":"INDEX"));
			$I[$D]["lengths"]=[];
			$I[$D]["columns"][$J["key_ordinal"]]=$J["column_name"];
			$I[$D]["descs"][$J["key_ordinal"]]=($J["is_descending_key"]?'1':null);
		}

		return $I;
	}

	function view($D) {
		global $g;

		return ["select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU', '', $g->result("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($D)))];
	}

	function collations() {
		$I=[];
		foreach (get_vals("SELECT name FROM fn_helpcollations()") as $mb)
			$I[preg_replace('~_.*~', '', $mb)][]=$mb;

		return $I;
	}

	function information_schema($l) {
		return false;
	}

	function error() {
		global $g;

		return nl_br(h(preg_replace('~^(\[[^]]*])+~m', '', $g->error)));
	}

	function create_database($l, $mb) {
		return queries("CREATE DATABASE ".idf_escape($l).(preg_match('~^[a-z0-9_]+$~i', $mb)?" COLLATE $mb":""));
	}

	function drop_databases($k) {
		return queries("DROP DATABASE ".implode(", ", array_map('idf_escape', $k)));
	}

	function rename_database($D, $mb) {
		if (preg_match('~^[a-z0-9_]+$~i', $mb))
			queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $mb");
		queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($D));

		return true;
	}

	function auto_increment() {
		return " IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";
	}

	function alter_table($Q, $D, $p, $hd, $tb, $_c, $mb, $La, $Wf) {
		$c=[];
		$vb=[];
		foreach ($p as $o) {
			$d=idf_escape($o[0]);
			$X=$o[1];
			if (!$X)
				$c["DROP"][]=" COLUMN $d"; else {
				$X[1]=preg_replace("~( COLLATE )'(\\w+)'~", '\1\2', $X[1]);
				$vb[$o[0]]=$X[5];
				unset($X[5]);
				if ($o[0]=="")
					$c["ADD"][]="\n  ".implode("", $X).($Q==""?substr($hd[$X[0]], 16+strlen($X[0])):""); else {
					unset($X[6]);
					if ($d!=$X[0])
						queries("EXEC sp_rename ".q(table($Q).".$d").", ".q(idf_unescape($X[0])).", 'COLUMN'");
					$c["ALTER COLUMN ".implode("", $X)][]="";
				}
			}
		}
		if ($Q=="")
			return queries("CREATE TABLE ".table($D)." (".implode(",", (array)$c["ADD"])."\n)");
		if ($Q!=$D)
			queries("EXEC sp_rename ".q(table($Q)).", ".q($D));
		if ($hd)
			$c[""]=$hd;
		foreach ($c as $z=>$X) {
			if (!queries("ALTER TABLE ".idf_escape($D)." $z".implode(",", $X)))
				return false;
		}
		foreach ($vb as $z=>$X) {
			$tb=substr($X, 9);
			queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($D).", @level2type = N'Column', @level2name = ".q($z));
			queries("EXEC sp_addextendedproperty @name = N'MS_Description', @value = ".$tb.", @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($D).", @level2type = N'Column', @level2name = ".q($z));
		}

		return true;
	}

	function alter_indexes($Q, $c) {
		$w=[];
		$lc=[];
		foreach ($c as $X) {
			if ($X[2]=="DROP") {
				if ($X[0]=="PRIMARY")
					$lc[]=idf_escape($X[1]); else$w[]=idf_escape($X[1])." ON ".table($Q);
			} elseif (!queries(($X[0]!="PRIMARY"?"CREATE $X[0] ".($X[0]!="INDEX"?"INDEX ":"").idf_escape($X[1]!=""?$X[1]:uniqid($Q."_"))." ON ".table($Q):"ALTER TABLE ".table($Q)." ADD PRIMARY KEY")." (".implode(", ", $X[2]).")"))
				return false;
		}

		return (!$w||queries("DROP INDEX ".implode(", ", $w)))&&(!$lc||queries("ALTER TABLE ".table($Q)." DROP ".implode(", ", $lc)));
	}

	function last_id() {
		global $g;

		return $g->result("SELECT SCOPE_IDENTITY()");
	}

	function explain($g, $G) {
		$g->query("SET SHOWPLAN_ALL ON");
		$I=$g->query($G);
		$g->query("SET SHOWPLAN_ALL OFF");

		return $I;
	}

	function found_rows($R, $Z) {
	}

	function foreign_keys($Q) {
		$I=[];
		foreach (get_rows("EXEC sp_fkeys @fktable_name = ".q($Q)) as $J) {
			$r=&$I[$J["FK_NAME"]];
			$r["db"]=$J["PKTABLE_QUALIFIER"];
			$r["table"]=$J["PKTABLE_NAME"];
			$r["source"][]=$J["FKCOLUMN_NAME"];
			$r["target"][]=$J["PKCOLUMN_NAME"];
		}

		return $I;
	}

	function truncate_tables($S) {
		return apply_queries("TRUNCATE TABLE", $S);
	}

	function drop_views($bj) {
		return queries("DROP VIEW ".implode(", ", array_map('table', $bj)));
	}

	function drop_tables($S) {
		return queries("DROP TABLE ".implode(", ", array_map('table', $S)));
	}

	function move_tables($S, $bj, $Zh) {
		return apply_queries("ALTER SCHEMA ".idf_escape($Zh)." TRANSFER", array_merge($S, $bj));
	}

	function trigger($D) {
		if ($D=="")
			return [];
		$K=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($D));
		$I=reset($K);
		if ($I)
			$I["Statement"]=preg_replace('~^.+\s+AS\s+~isU', '', $I["text"]);

		return $I;
	}

	function triggers($Q) {
		$I=[];
		foreach (get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($Q)) as $J)
			$I[$J["name"]]=[$J["Timing"], $J["Event"]];

		return $I;
	}

	function trigger_options() {
		return ["Timing"=>["AFTER", "INSTEAD OF"], "Event"=>["INSERT", "UPDATE", "DELETE"], "Type"=>["AS"],];
	}

	function schemas() {
		return get_vals("SELECT name FROM sys.schemas");
	}

	function get_schema() {
		global $g;
		if ($_GET["ns"]!="")
			return $_GET["ns"];

		return $g->result("SELECT SCHEMA_NAME()");
	}

	function set_schema($ch) {
		return true;
	}

	function use_sql($j) {
		return "USE ".idf_escape($j);
	}

	function show_variables() {
		return [];
	}

	function show_status() {
		return [];
	}

	function convert_field($o) {
	}

	function unconvert_field($o, $I) {
		return $I;
	}

	function support($Vc) {
		return preg_match('~^(comment|columns|database|drop_col|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~', $Vc);
	}

	function driver_config() {
		$U=[];
		$Jh=[];
		foreach ([lang(27)=>["tinyint"=>3, "smallint"=>5, "int"=>10, "bigint"=>20, "bit"=>1, "decimal"=>0, "real"=>12, "float"=>53, "smallmoney"=>10, "money"=>20], lang(28)=>["date"=>10, "smalldatetime"=>19, "datetime"=>19, "datetime2"=>19, "time"=>8, "datetimeoffset"=>10], lang(25)=>["char"=>8000, "varchar"=>8000, "text"=>2147483647, "nchar"=>4000, "nvarchar"=>4000, "ntext"=>1073741823], lang(29)=>["binary"=>8000, "varbinary"=>8000, "image"=>2147483647],] as $z=>$X) {
			$U+=$X;
			$Jh[$z]=array_keys($X);
		}

		return ['possible_drivers'=>["SQLSRV", "MSSQL", "PDO_DBLIB"], 'jush'=>"mssql", 'types'=>$U, 'structured_types'=>$Jh, 'unsigned'=>[], 'operators'=>["=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "IN", "IS NULL", "NOT LIKE", "NOT IN", "IS NOT NULL"], 'functions'=>["len", "lower", "round", "upper"], 'grouping'=>["avg", "count", "count distinct", "max", "min", "sum"], 'edit_functions'=>[["date|time"=>"getdate",], ["int|decimal|real|float|money|datetime"=>"+/-", "char|text"=>"+",]],];
	}
}
$kc["mongo"]="MongoDB (alpha)";
if (isset($_GET["mongo"])) {
	define("DRIVER", "mongo");
	if (class_exists('MongoDB')) {
		class
		Min_DB {

			var $extension="Mongo", $server_info=MongoClient::VERSION, $error, $last_id, $_link, $_db;

			function connect($Mi, $_f) {
				try {
					$this->_link=new
					MongoClient($Mi, $_f);
					if ($_f["password"]!="") {
						$_f["password"]="";
						try {
							new
							MongoClient($Mi, $_f);
							$this->error=lang(22);
						} catch (Exception$rc) {
						}
					}
				} catch (Exception$rc) {
					$this->error=$rc->getMessage();
				}
			}

			function query($G) {
				return false;
			}

			function select_db($j) {
				try {
					$this->_db=$this->_link->selectDB($j);

					return true;
				} catch (Exception$Hc) {
					$this->error=$Hc->getMessage();

					return false;
				}
			}

			function quote($P) {
				return $P;
			}

		}

		class
		Min_Result {

			var $num_rows, $_rows=[], $_offset=0, $_charset=[];

			function __construct($H) {
				foreach ($H as $fe) {
					$J=[];
					foreach ($fe as $z=>$X) {
						if (is_a($X, 'MongoBinData'))
							$this->_charset[$z]=63;
						$J[$z]=(is_a($X, 'MongoId')?"ObjectId(\"$X\")":(is_a($X, 'MongoDate')?gmdate("Y-m-d H:i:s", $X->sec)." GMT":(is_a($X, 'MongoBinData')?$X->bin:(is_a($X, 'MongoRegex')?"$X":(is_object($X)?get_class($X):$X)))));
					}
					$this->_rows[]=$J;
					foreach ($J as $z=>$X) {
						if (!isset($this->_rows[0][$z]))
							$this->_rows[0][$z]=null;
					}
				}
				$this->num_rows=count($this->_rows);
			}

			function fetch_assoc() {
				$J=current($this->_rows);
				if (!$J)
					return $J;
				$I=[];
				foreach ($this->_rows[0] as $z=>$X)
					$I[$z]=$J[$z];
				next($this->_rows);

				return $I;
			}

			function fetch_row() {
				$I=$this->fetch_assoc();
				if (!$I)
					return $I;

				return array_values($I);
			}

			function fetch_field() {
				$je=array_keys($this->_rows[0]);
				$D=$je[$this->_offset++];

				return (object)['name'=>$D, 'charsetnr'=>$this->_charset[$D],];
			}

		}

		class
		Min_Driver extends Min_SQL {

			public $ng="_id";

			function select($Q, $L, $Z, $sd, $Bf=[], $_=1, $E=0, $pg=false) {
				$L=($L==["*"]?[]:array_fill_keys($L, true));
				$xh=[];
				foreach ($Bf as $X) {
					$X=preg_replace('~ DESC$~', '', $X, 1, $Ib);
					$xh[$X]=($Ib?-1:1);
				}

				return new
				Min_Result($this->_conn->_db->selectCollection($Q)->find([], $L)->sort($xh)->limit($_!=""?+$_:0)->skip($E*$_));
			}

			function insert($Q, $N) {
				try {
					$I=$this->_conn->_db->selectCollection($Q)->insert($N);
					$this->_conn->errno=$I['code'];
					$this->_conn->error=$I['err'];
					$this->_conn->last_id=$N['_id'];

					return !$I['err'];
				} catch (Exception$Hc) {
					$this->_conn->error=$Hc->getMessage();

					return false;
				}
			}

		}

		function get_databases($fd) {
			global $g;
			$I=[];
			$Wb=$g->_link->listDBs();
			foreach ($Wb['databases'] as $l)
				$I[]=$l['name'];

			return $I;
		}

		function count_tables($k) {
			global $g;
			$I=[];
			foreach ($k as $l)
				$I[$l]=count($g->_link->selectDB($l)->getCollectionNames(true));

			return $I;
		}

		function tables_list() {
			global $g;

			return array_fill_keys($g->_db->getCollectionNames(true), 'table');
		}

		function drop_databases($k) {
			global $g;
			foreach ($k as $l) {
				$Og=$g->_link->selectDB($l)->drop();
				if (!$Og['ok'])
					return false;
			}

			return true;
		}

		function indexes($Q, $h=null) {
			global $g;
			$I=[];
			foreach ($g->_db->selectCollection($Q)->getIndexInfo() as $w) {
				$ec=[];
				foreach ($w["key"] as $d=>$T)
					$ec[]=($T==-1?'1':null);
				$I[$w["name"]]=["type"=>($w["name"]=="_id_"?"PRIMARY":($w["unique"]?"UNIQUE":"INDEX")), "columns"=>array_keys($w["key"]), "lengths"=>[], "descs"=>$ec,];
			}

			return $I;
		}

		function fields($Q) {
			return fields_from_edit();
		}

		function found_rows($R, $Z) {
			global $g;

			return $g->_db->selectCollection($_GET["select"])->count($Z);
		}

		$xf=["="];
	} elseif (class_exists('MongoDB\Driver\Manager')) {
		class
		Min_DB {

			var $extension="MongoDB", $server_info=MONGODB_VERSION, $affected_rows, $error, $last_id;

			var $_link;

			var $_db, $_db_name;

			function connect($Mi, $_f) {
				$hb='MongoDB\Driver\Manager';
				$this->_link=new$hb($Mi, $_f);
				$this->executeCommand('admin', ['ping'=>1]);
			}

			function executeCommand($l, $rb) {
				$hb='MongoDB\Driver\Command';
				try {
					return $this->_link->executeCommand($l, new$hb($rb));
				} catch (Exception$rc) {
					$this->error=$rc->getMessage();

					return [];
				}
			}

			function executeBulkWrite($Ze, $Xa, $Jb) {
				try {
					$Rg=$this->_link->executeBulkWrite($Ze, $Xa);
					$this->affected_rows=$Rg->$Jb();

					return true;
				} catch (Exception$rc) {
					$this->error=$rc->getMessage();

					return false;
				}
			}

			function query($G) {
				return false;
			}

			function select_db($j) {
				$this->_db_name=$j;

				return true;
			}

			function quote($P) {
				return $P;
			}

		}

		class
		Min_Result {

			var $num_rows, $_rows=[], $_offset=0, $_charset=[];

			function __construct($H) {
				foreach ($H as $fe) {
					$J=[];
					foreach ($fe as $z=>$X) {
						if (is_a($X, 'MongoDB\BSON\Binary'))
							$this->_charset[$z]=63;
						$J[$z]=(is_a($X, 'MongoDB\BSON\ObjectID')?'MongoDB\BSON\ObjectID("'."$X\")":(is_a($X, 'MongoDB\BSON\UTCDatetime')?$X->toDateTime()->format('Y-m-d H:i:s'):(is_a($X, 'MongoDB\BSON\Binary')?$X->getData():(is_a($X, 'MongoDB\BSON\Regex')?"$X":(is_object($X)||is_array($X)?json_encode($X, 256):$X)))));
					}
					$this->_rows[]=$J;
					foreach ($J as $z=>$X) {
						if (!isset($this->_rows[0][$z]))
							$this->_rows[0][$z]=null;
					}
				}
				$this->num_rows=count($this->_rows);
			}

			function fetch_assoc() {
				$J=current($this->_rows);
				if (!$J)
					return $J;
				$I=[];
				foreach ($this->_rows[0] as $z=>$X)
					$I[$z]=$J[$z];
				next($this->_rows);

				return $I;
			}

			function fetch_row() {
				$I=$this->fetch_assoc();
				if (!$I)
					return $I;

				return array_values($I);
			}

			function fetch_field() {
				$je=array_keys($this->_rows[0]);
				$D=$je[$this->_offset++];

				return (object)['name'=>$D, 'charsetnr'=>$this->_charset[$D],];
			}

		}

		class
		Min_Driver extends Min_SQL {

			public $ng="_id";

			function select($Q, $L, $Z, $sd, $Bf=[], $_=1, $E=0, $pg=false) {
				global $g;
				$L=($L==["*"]?[]:array_fill_keys($L, 1));
				if (count($L)&&!isset($L['_id']))
					$L['_id']=0;
				$Z=where_to_query($Z);
				$xh=[];
				foreach ($Bf as $X) {
					$X=preg_replace('~ DESC$~', '', $X, 1, $Ib);
					$xh[$X]=($Ib?-1:1);
				}
				if (isset($_GET['limit'])&&is_numeric($_GET['limit'])&&$_GET['limit']>0)
					$_=$_GET['limit'];
				$_=min(200, max(1, (int)$_));
				$uh=$E*$_;
				$hb='MongoDB\Driver\Query';
				try {
					return new
					Min_Result($g->_link->executeQuery("$g->_db_name.$Q", new$hb($Z, ['projection'=>$L, 'limit'=>$_, 'skip'=>$uh, 'sort'=>$xh])));
				} catch (Exception$rc) {
					$g->error=$rc->getMessage();

					return false;
				}
			}

			function update($Q, $N, $zg, $_=0, $kh="\n") {
				global $g;
				$l=$g->_db_name;
				$Z=sql_query_where_parser($zg);
				$hb='MongoDB\Driver\BulkWrite';
				$Xa=new$hb([]);
				if (isset($N['_id']))
					unset($N['_id']);
				$Lg=[];
				foreach ($N as $z=>$Y) {
					if ($Y=='NULL') {
						$Lg[$z]=1;
						unset($N[$z]);
					}
				}
				$Li=['$set'=>$N];
				if (count($Lg))
					$Li['$unset']=$Lg;
				$Xa->update($Z, $Li, ['upsert'=>false]);

				return $g->executeBulkWrite("$l.$Q", $Xa, 'getModifiedCount');
			}

			function delete($Q, $zg, $_=0) {
				global $g;
				$l=$g->_db_name;
				$Z=sql_query_where_parser($zg);
				$hb='MongoDB\Driver\BulkWrite';
				$Xa=new$hb([]);
				$Xa->delete($Z, ['limit'=>$_]);

				return $g->executeBulkWrite("$l.$Q", $Xa, 'getDeletedCount');
			}

			function insert($Q, $N) {
				global $g;
				$l=$g->_db_name;
				$hb='MongoDB\Driver\BulkWrite';
				$Xa=new$hb([]);
				if ($N['_id']=='')
					unset($N['_id']);
				$Xa->insert($N);

				return $g->executeBulkWrite("$l.$Q", $Xa, 'getInsertedCount');
			}

		}

		function get_databases($fd) {
			global $g;
			$I=[];
			foreach ($g->executeCommand('admin', ['listDatabases'=>1]) as $Wb) {
				foreach ($Wb->databases as $l)
					$I[]=$l->name;
			}

			return $I;
		}

		function count_tables($k) {
			$I=[];

			return $I;
		}

		function tables_list() {
			global $g;
			$ob=[];
			foreach ($g->executeCommand($g->_db_name, ['listCollections'=>1]) as $H)
				$ob[$H->name]='table';

			return $ob;
		}

		function drop_databases($k) {
			return false;
		}

		function indexes($Q, $h=null) {
			global $g;
			$I=[];
			foreach ($g->executeCommand($g->_db_name, ['listIndexes'=>$Q]) as $w) {
				$ec=[];
				$e=[];
				foreach (get_object_vars($w->key) as $d=>$T) {
					$ec[]=($T==-1?'1':null);
					$e[]=$d;
				}
				$I[$w->name]=["type"=>($w->name=="_id_"?"PRIMARY":(isset($w->unique)?"UNIQUE":"INDEX")), "columns"=>$e, "lengths"=>[], "descs"=>$ec,];
			}

			return $I;
		}

		function fields($Q) {
			global $m;
			$p=fields_from_edit();
			if (!$p) {
				$H=$m->select($Q, ["*"], null, null, [], 10);
				if ($H) {
					while ($J=$H->fetch_assoc()) {
						foreach ($J as $z=>$X) {
							$J[$z]=null;
							$p[$z]=["field"=>$z, "type"=>"string", "null"=>($z!=$m->primary), "auto_increment"=>($z==$m->primary), "privileges"=>["insert"=>1, "select"=>1, "update"=>1,],];
						}
					}
				}
			}

			return $p;
		}

		function found_rows($R, $Z) {
			global $g;
			$Z=where_to_query($Z);
			$pi=$g->executeCommand($g->_db_name, ['count'=>$R['Name'], 'query'=>$Z])->toArray();

			return $pi[0]->n;
		}

		function sql_query_where_parser($zg) {
			$zg=preg_replace('~^\sWHERE \(?\(?(.+?)\)?\)?$~', '\1', $zg);
			$lj=explode(' AND ', $zg);
			$mj=explode(') OR (', $zg);
			$Z=[];
			foreach ($lj as $jj)
				$Z[]=trim($jj);
			if (count($mj)==1)
				$mj=[]; elseif (count($mj)>1)
				$Z=[];

			return where_to_query($Z, $mj);
		}

		function where_to_query($hj=[], $ij=[]) {
			global $b;
			$Rb=[];
			foreach (['and'=>$hj, 'or'=>$ij] as $T=>$Z) {
				if (is_array($Z)) {
					foreach ($Z as $Nc) {
						[$kb, $vf, $X]=explode(" ", $Nc, 3);
						if ($kb=="_id"&&preg_match('~^(MongoDB\\\\BSON\\\\ObjectID)\("(.+)"\)$~', $X, $C)) {
							[, $hb, $X]=$C;
							$X=new$hb($X);
						}
						if (!in_array($vf, $b->operators))
							continue;
						if (preg_match('~^\(f\)(.+)~', $vf, $C)) {
							$X=(float)$X;
							$vf=$C[1];
						} elseif (preg_match('~^\(date\)(.+)~', $vf, $C)) {
							$Tb=new
							DateTime($X);
							$hb='MongoDB\BSON\UTCDatetime';
							$X=new$hb($Tb->getTimestamp()*1000);
							$vf=$C[1];
						}
						switch ($vf) {
							case'=':
								$vf='$eq';
								break;
							case'!=':
								$vf='$ne';
								break;
							case'>':
								$vf='$gt';
								break;
							case'<':
								$vf='$lt';
								break;
							case'>=':
								$vf='$gte';
								break;
							case'<=':
								$vf='$lte';
								break;
							case'regex':
								$vf='$regex';
								break;
							default:
								continue
								2;
						}
						if ($T=='and')
							$Rb['$and'][]=[$kb=>[$vf=>$X]]; elseif ($T=='or')
							$Rb['$or'][]=[$kb=>[$vf=>$X]];
					}
				}
			}

			return $Rb;
		}

		$xf=["=", "!=", ">", "<", ">=", "<=", "regex", "(f)=", "(f)!=", "(f)>", "(f)<", "(f)>=", "(f)<=", "(date)=", "(date)!=", "(date)>", "(date)<", "(date)>=", "(date)<=",];
	}
	function table($v) {
		return $v;
	}

	function idf_escape($v) {
		return $v;
	}

	function table_status($D="", $Uc=false) {
		$I=[];
		foreach (tables_list() as $Q=>$T) {
			$I[$Q]=["Name"=>$Q];
			if ($D==$Q)
				return $I[$Q];
		}

		return $I;
	}

	function create_database($l, $mb) {
		return true;
	}

	function last_id() {
		global $g;

		return $g->last_id;
	}

	function error() {
		global $g;

		return h($g->error);
	}

	function collations() {
		return [];
	}

	function logged_user() {
		global $b;
		$Mb=$b->credentials();

		return $Mb[1];
	}

	function connect() {
		global $b;
		$g=new
		Min_DB;
		[$M, $V, $F]=$b->credentials();
		$_f=[];
		if ($V.$F!="") {
			$_f["username"]=$V;
			$_f["password"]=$F;
		}
		$l=$b->database();
		if ($l!="")
			$_f["db"]=$l;
		if (($Ka=getenv("MONGO_AUTH_SOURCE")))
			$_f["authSource"]=$Ka;
		$g->connect("mongodb://$M", $_f);
		if ($g->error)
			return $g->error;

		return $g;
	}

	function alter_indexes($Q, $c) {
		global $g;
		foreach ($c as $X) {
			[$T, $D, $N]=$X;
			if ($N=="DROP")
				$I=$g->_db->command(["deleteIndexes"=>$Q, "index"=>$D]); else {
				$e=[];
				foreach ($N as $d) {
					$d=preg_replace('~ DESC$~', '', $d, 1, $Ib);
					$e[$d]=($Ib?-1:1);
				}
				$I=$g->_db->selectCollection($Q)->ensureIndex($e, ["unique"=>($T=="UNIQUE"), "name"=>$D,]);
			}
			if ($I['errmsg']) {
				$g->error=$I['errmsg'];

				return false;
			}
		}

		return true;
	}

	function support($Vc) {
		return preg_match("~database|indexes|descidx~", $Vc);
	}

	function db_collation($l, $nb) {
	}

	function information_schema() {
	}

	function is_view($R) {
	}

	function convert_field($o) {
	}

	function unconvert_field($o, $I) {
		return $I;
	}

	function foreign_keys($Q) {
		return [];
	}

	function fk_support($R) {
	}

	function engines() {
		return [];
	}

	function alter_table($Q, $D, $p, $hd, $tb, $_c, $mb, $La, $Wf) {
		global $g;
		if ($Q=="") {
			$g->_db->createCollection($D);

			return true;
		}
	}

	function drop_tables($S) {
		global $g;
		foreach ($S as $Q) {
			$Og=$g->_db->selectCollection($Q)->drop();
			if (!$Og['ok'])
				return false;
		}

		return true;
	}

	function truncate_tables($S) {
		global $g;
		foreach ($S as $Q) {
			$Og=$g->_db->selectCollection($Q)->remove();
			if (!$Og['ok'])
				return false;
		}

		return true;
	}

	function driver_config() {
		global $xf;

		return ['possible_drivers'=>["mongo", "mongodb"], 'jush'=>"mongo", 'operators'=>$xf, 'functions'=>[], 'grouping'=>[], 'edit_functions'=>[["json"]],];
	}
}
$kc["elastic"]="Elasticsearch (beta)";
if (isset($_GET["elastic"])) {
	define("DRIVER", "elastic");
	if (function_exists('json_decode')&&ini_bool('allow_url_fopen')) {
		class
		Min_DB {

			var $extension="JSON", $server_info, $errno, $error, $_url, $_db;

			function rootQuery($ag, $Db=[], $Se='GET') {
				@ini_set('track_errors', 1);
				$Zc=@file_get_contents("$this->_url/".ltrim($ag, '/'), false, stream_context_create(['http'=>['method'=>$Se, 'content'=>$Db===null?$Db:json_encode($Db), 'header'=>'Content-Type: application/json', 'ignore_errors'=>1,]]));
				if (!$Zc) {
					$this->error=$php_errormsg;

					return $Zc;
				}
				if (!preg_match('~^HTTP/[0-9.]+ 2~i', $http_response_header[0])) {
					$this->error=lang(32)." $http_response_header[0]";

					return false;
				}
				$I=json_decode($Zc, true);
				if ($I===null) {
					$this->errno=json_last_error();
					if (function_exists('json_last_error_msg'))
						$this->error=json_last_error_msg(); else {
						$Bb=get_defined_constants(true);
						foreach ($Bb['json'] as $D=>$Y) {
							if ($Y==$this->errno&&preg_match('~^JSON_ERROR_~', $D)) {
								$this->error=$D;
								break;
							}
						}
					}
				}

				return $I;
			}

			function query($ag, $Db=[], $Se='GET') {
				return $this->rootQuery(($this->_db!=""?"$this->_db/":"/").ltrim($ag, '/'), $Db, $Se);
			}

			function connect($M, $V, $F) {
				preg_match('~^(https?://)?(.*)~', $M, $C);
				$this->_url=($C[1]?$C[1]:"http://")."$V:$F@$C[2]";
				$I=$this->query('');
				if ($I)
					$this->server_info=$I['version']['number'];

				return (bool)$I;
			}

			function select_db($j) {
				$this->_db=$j;

				return true;
			}

			function quote($P) {
				return $P;
			}

		}

		class
		Min_Result {

			var $num_rows, $_rows;

			function __construct($K) {
				$this->num_rows=count($K);
				$this->_rows=$K;
				reset($this->_rows);
			}

			function fetch_assoc() {
				$I=current($this->_rows);
				next($this->_rows);

				return $I;
			}

			function fetch_row() {
				return array_values($this->fetch_assoc());
			}

		}
	}

	class
	Min_Driver extends Min_SQL {

		function select($Q, $L, $Z, $sd, $Bf=[], $_=1, $E=0, $pg=false) {
			global $b;
			$Rb=[];
			$G="$Q/_search";
			if ($L!=["*"])
				$Rb["fields"]=$L;
			if ($Bf) {
				$xh=[];
				foreach ($Bf as $kb) {
					$kb=preg_replace('~ DESC$~', '', $kb, 1, $Ib);
					$xh[]=($Ib?[$kb=>"desc"]:$kb);
				}
				$Rb["sort"]=$xh;
			}
			if ($_) {
				$Rb["size"]=+$_;
				if ($E)
					$Rb["from"]=($E*$_);
			}
			foreach ($Z as $X) {
				[$kb, $vf, $X]=explode(" ", $X, 3);
				if ($kb=="_id")
					$Rb["query"]["ids"]["values"][]=$X; elseif ($kb.$X!="") {
					$ci=["term"=>[($kb!=""?$kb:"_all")=>$X]];
					if ($vf=="=")
						$Rb["query"]["filtered"]["filter"]["and"][]=$ci; else$Rb["query"]["filtered"]["query"]["bool"]["must"][]=$ci;
				}
			}
			if ($Rb["query"]&&!$Rb["query"]["filtered"]["query"]&&!$Rb["query"]["ids"])
				$Rb["query"]["filtered"]["query"]=["match_all"=>[]];
			$Fh=microtime(true);
			$eh=$this->_conn->query($G, $Rb);
			if ($pg)
				echo $b->selectQuery("$G: ".json_encode($Rb), $Fh, !$eh);
			if (!$eh)
				return false;
			$I=[];
			foreach ($eh['hits']['hits'] as $Ed) {
				$J=[];
				if ($L==["*"])
					$J["_id"]=$Ed["_id"];
				$p=$Ed['_source'];
				if ($L!=["*"]) {
					$p=[];
					foreach ($L as $z)
						$p[$z]=$Ed['fields'][$z];
				}
				foreach ($p as $z=>$X) {
					if ($Rb["fields"])
						$X=$X[0];
					$J[$z]=(is_array($X)?json_encode($X):$X);
				}
				$I[]=$J;
			}

			return new
			Min_Result($I);
		}

		function update($T, $Cg, $zg, $_=0, $kh="\n") {
			$Yf=preg_split('~ *= *~', $zg);
			if (count($Yf)==2) {
				$u=trim($Yf[1]);
				$G="$T/$u";

				return $this->_conn->query($G, $Cg, 'POST');
			}

			return false;
		}

		function insert($T, $Cg) {
			$u="";
			$G="$T/$u";
			$Og=$this->_conn->query($G, $Cg, 'POST');
			$this->_conn->last_id=$Og['_id'];

			return $Og['created'];
		}

		function delete($T, $zg, $_=0) {
			$Id=[];
			if (is_array($_GET["where"])&&$_GET["where"]["_id"])
				$Id[]=$_GET["where"]["_id"];
			if (is_array($_POST['check'])) {
				foreach ($_POST['check'] as $bb) {
					$Yf=preg_split('~ *= *~', $bb);
					if (count($Yf)==2)
						$Id[]=trim($Yf[1]);
				}
			}
			$this->_conn->affected_rows=0;
			foreach ($Id as $u) {
				$G="{$T}/{$u}";
				$Og=$this->_conn->query($G, '{}', 'DELETE');
				if (is_array($Og)&&$Og['found']==true)
					$this->_conn->affected_rows++;
			}

			return $this->_conn->affected_rows;
		}

	}

	function connect() {
		global $b;
		$g=new
		Min_DB;
		[$M, $V, $F]=$b->credentials();
		if ($F!=""&&$g->connect($M, $V, ""))
			return lang(22);
		if ($g->connect($M, $V, $F))
			return $g;

		return $g->error;
	}

	function support($Vc) {
		return preg_match("~database|table|columns~", $Vc);
	}

	function logged_user() {
		global $b;
		$Mb=$b->credentials();

		return $Mb[1];
	}

	function get_databases() {
		global $g;
		$I=$g->rootQuery('_aliases');
		if ($I) {
			$I=array_keys($I);
			sort($I, SORT_STRING);
		}

		return $I;
	}

	function collations() {
		return [];
	}

	function db_collation($l, $nb) {
	}

	function engines() {
		return [];
	}

	function count_tables($k) {
		global $g;
		$I=[];
		$H=$g->query('_stats');
		if ($H&&$H['indices']) {
			$Qd=$H['indices'];
			foreach ($Qd as $Pd=>$Gh) {
				$Od=$Gh['total']['indexing'];
				$I[$Pd]=$Od['index_total'];
			}
		}

		return $I;
	}

	function tables_list() {
		global $g;
		if (min_version(6))
			return ['_doc'=>'table'];
		$I=$g->query('_mapping');
		if ($I)
			$I=array_fill_keys(array_keys($I[$g->_db]["mappings"]), 'table');

		return $I;
	}

	function table_status($D="", $Uc=false) {
		global $g;
		$eh=$g->query("_search", ["size"=>0, "aggregations"=>["count_by_type"=>["terms"=>["field"=>"_type"]]]], "POST");
		$I=[];
		if ($eh) {
			$S=$eh["aggregations"]["count_by_type"]["buckets"];
			foreach ($S as $Q) {
				$I[$Q["key"]]=["Name"=>$Q["key"], "Engine"=>"table", "Rows"=>$Q["doc_count"],];
				if ($D!=""&&$D==$Q["key"])
					return $I[$D];
			}
		}

		return $I;
	}

	function error() {
		global $g;

		return h($g->error);
	}

	function information_schema() {
	}

	function is_view($R) {
	}

	function indexes($Q, $h=null) {
		return [["type"=>"PRIMARY", "columns"=>["_id"]],];
	}

	function fields($Q) {
		global $g;
		$Be=[];
		if (min_version(6)) {
			$H=$g->query("_mapping");
			if ($H)
				$Be=$H[$g->_db]['mappings']['properties'];
		} else {
			$H=$g->query("$Q/_mapping");
			if ($H) {
				$Be=$H[$Q]['properties'];
				if (!$Be)
					$Be=$H[$g->_db]['mappings'][$Q]['properties'];
			}
		}
		$I=[];
		if ($Be) {
			foreach ($Be as $D=>$o) {
				$I[$D]=["field"=>$D, "full_type"=>$o["type"], "type"=>$o["type"], "privileges"=>["insert"=>1, "select"=>1, "update"=>1],];
				if ($o["properties"]) {
					unset($I[$D]["privileges"]["insert"]);
					unset($I[$D]["privileges"]["update"]);
				}
			}
		}

		return $I;
	}

	function foreign_keys($Q) {
		return [];
	}

	function table($v) {
		return $v;
	}

	function idf_escape($v) {
		return $v;
	}

	function convert_field($o) {
	}

	function unconvert_field($o, $I) {
		return $I;
	}

	function fk_support($R) {
	}

	function found_rows($R, $Z) {
		return null;
	}

	function create_database($l) {
		global $g;

		return $g->rootQuery(urlencode($l), null, 'PUT');
	}

	function drop_databases($k) {
		global $g;

		return $g->rootQuery(urlencode(implode(',', $k)), [], 'DELETE');
	}

	function alter_table($Q, $D, $p, $hd, $tb, $_c, $mb, $La, $Wf) {
		global $g;
		$vg=[];
		foreach ($p as $Sc) {
			$Xc=trim($Sc[1][0]);
			$Yc=trim($Sc[1][1]?$Sc[1][1]:"text");
			$vg[$Xc]=['type'=>$Yc];
		}
		if (!empty($vg))
			$vg=['properties'=>$vg];

		return $g->query("_mapping/{$D}", $vg, 'PUT');
	}

	function drop_tables($S) {
		global $g;
		$I=true;
		foreach ($S as $Q)
			$I=$I&&$g->query(urlencode($Q), [], 'DELETE');

		return $I;
	}

	function last_id() {
		global $g;

		return $g->last_id;
	}

	function driver_config() {
		$U=[];
		$Jh=[];
		foreach ([lang(27)=>["long"=>3, "integer"=>5, "short"=>8, "byte"=>10, "double"=>20, "float"=>66, "half_float"=>12, "scaled_float"=>21], lang(28)=>["date"=>10], lang(25)=>["string"=>65535, "text"=>65535], lang(29)=>["binary"=>255],] as $z=>$X) {
			$U+=$X;
			$Jh[$z]=array_keys($X);
		}

		return ['possible_drivers'=>["json + allow_url_fopen"], 'jush'=>"elastic", 'operators'=>["=", "query"], 'functions'=>[], 'grouping'=>[], 'edit_functions'=>[["json"]], 'types'=>$U, 'structured_types'=>$Jh,];
	}
}

class
Adminer {

	var $operators;

	function name() {
		return "<a href='https://www.adminer.org/'".target_blank()." id='h1'>Adminer</a>";
	}

	function credentials() {
		return [SERVER, $_GET["username"], get_password()];
	}

	function connectSsl() {
	}

	function permanentLogin($i=false) {
		return password_file($i);
	}

	function bruteForceKey() {
		return $_SERVER["REMOTE_ADDR"];
	}

	function serverName($M) {
		return h($M);
	}

	function database() {
		return DB;
	}

	function databases($fd=true) {
		return get_databases($fd);
	}

	function schemas() {
		return schemas();
	}

	function queryTimeout() {
		return 2;
	}

	function headers() {
	}

	function csp() {
		return csp();
	}

	function head() {
		return true;
	}

	function css() {
		$I=[];
		$q="adminer.css";
		if (file_exists($q))
			$I[]="$q?v=".crc32(file_get_contents($q));

		return $I;
	}

	function loginForm() {
		global $kc;
		echo "<table cellspacing='0' class='layout'>\n", $this->loginFormField('driver', '<tr><th>'.lang(33).'<td>', html_select("auth[driver]", $kc, DRIVER, "loginDriver(this);")."\n"), $this->loginFormField('server', '<tr><th>'.lang(34).'<td>', '<input name="auth[server]" value="'.h(SERVER).'" title="hostname[:port]" placeholder="localhost" autocapitalize="off">'."\n"), $this->loginFormField('username', '<tr><th>'.lang(35).'<td>', '<input name="auth[username]" id="username" value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("focus(qs('#username')); qs('#username').form['auth[driver]'].onchange();")), $this->loginFormField('password', '<tr><th>'.lang(36).'<td>', '<input type="password" name="auth[password]" autocomplete="current-password">'."\n"), $this->loginFormField('db', '<tr><th>'.lang(37).'<td>', '<input name="auth[db]" value="'.h($_GET["db"]).'" autocapitalize="off">'."\n"), "</table>\n", "<p><input type='submit' value='".lang(38)."'>\n", checkbox("auth[permanent]", 1, $_COOKIE["adminer_permanent"], lang(39))."\n";
	}

	function loginFormField($D, $Bd, $Y) {
		return $Bd.$Y;
	}

	function login($_e, $F) {
		if ($F=="")
			return lang(40, target_blank());

		return true;
	}

	function tableName($Qh) {
		return h($Qh["Name"]);
	}

	function fieldName($o, $Bf=0) {
		return '<span title="'.h($o["full_type"]).'">'.h($o["field"]).'</span>';
	}

	function selectLinks($Qh, $N="") {
		global $y, $m;
		echo '<p class="links">';
		$ze=["select"=>lang(41)];
		if (support("table")||support("indexes"))
			$ze["table"]=lang(42);
		if (support("table")) {
			if (is_view($Qh))
				$ze["view"]=lang(43); else$ze["create"]=lang(44);
		}
		if ($N!==null)
			$ze["edit"]=lang(45);
		$D=$Qh["Name"];
		foreach ($ze as $z=>$X)
			echo " <a href='".h(ME)."$z=".urlencode($D).($z=="edit"?$N:"")."'".bold(isset($_GET[$z])).">$X</a>";
		echo doc_link([$y=>$m->tableHelp($D)], "?"), "\n";
	}

	function foreignKeys($Q) {
		return foreign_keys($Q);
	}

	function backwardKeys($Q, $Ph) {
		return [];
	}

	function backwardKeysPrint($Oa, $J) {
	}

	function selectQuery($G, $Fh, $Tc=false) {
		global $y, $m;
		$I="</p>\n";
		if (!$Tc&&($ej=$m->warnings())) {
			$u="warnings";
			$I=", <a href='#$u'>".lang(46)."</a>".script("qsl('a').onclick = partial(toggle, '$u');", "")."$I<div id='$u' class='hidden'>\n$ej</div>\n";
		}

		return "<p><code class='jush-$y'>".h(str_replace("\n", " ", $G))."</code> <span class='time'>(".format_time($Fh).")</span>".(support("sql")?" <a href='".h(ME)."sql=".urlencode($G)."'>".lang(10)."</a>":"").$I;
	}

	function sqlCommandQuery($G) {
		return shorten_utf8(trim($G), 1000);
	}

	function rowDescription($Q) {
		return "";
	}

	function rowDescriptions($K, $id) {
		return $K;
	}

	function selectLink($X, $o) {
	}

	function selectVal($X, $A, $o, $Jf) {
		$I=($X===null?"<i>NULL</i>":(preg_match("~char|binary|boolean~", $o["type"])&&!preg_match("~var~", $o["type"])?"<code>$X</code>":$X));
		if (preg_match('~blob|bytea|raw|file~', $o["type"])&&!is_utf8($X))
			$I="<i>".lang(47, strlen($Jf))."</i>";
		if (preg_match('~json~', $o["type"]))
			$I="<code class='jush-js'>$I</code>";

		return ($A?"<a href='".h($A)."'".(is_url($A)?target_blank():"").">$I</a>":$I);
	}

	function editVal($X, $o) {
		return $X;
	}

	function tableStructurePrint($p) {
		echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap'>\n", "<thead><tr><th>".lang(48)."<td>".lang(49).(support("comment")?"<td>".lang(50):"")."</thead>\n";
		foreach ($p as $o) {
			echo "<tr".odd()."><th>".h($o["field"]), "<td><span title='".h($o["collation"])."'>".h($o["full_type"])."</span>", ($o["null"]?" <i>NULL</i>":""), ($o["auto_increment"]?" <i>".lang(51)."</i>":""), (isset($o["default"])?" <span title='".lang(52)."'>[<b>".h($o["default"])."</b>]</span>":""), (support("comment")?"<td>".h($o["comment"]):""), "\n";
		}
		echo "</table>\n", "</div>\n";
	}

	function tableIndexesPrint($x) {
		echo "<table cellspacing='0'>\n";
		foreach ($x as $D=>$w) {
			ksort($w["columns"]);
			$pg=[];
			foreach ($w["columns"] as $z=>$X)
				$pg[]="<i>".h($X)."</i>".($w["lengths"][$z]?"(".$w["lengths"][$z].")":"").($w["descs"][$z]?" DESC":"");
			echo "<tr title='".h($D)."'><th>$w[type]<td>".implode(", ", $pg)."\n";
		}
		echo "</table>\n";
	}

	function selectColumnsPrint($L, $e) {
		global $pd, $vd;
		print_fieldset("select", lang(53), $L);
		$t=0;
		$L[""]=[];
		foreach ($L as $z=>$X) {
			$X=$_GET["columns"][$z];
			$d=select_input(" name='columns[$t][col]'", $e, $X["col"], ($z!==""?"selectFieldChange":"selectAddRow"));
			echo "<div>".($pd||$vd?"<select name='columns[$t][fun]'>".optionlist([-1=>""]+array_filter([lang(54)=>$pd, lang(55)=>$vd]), $X["fun"])."</select>".on_help("getTarget(event).value && getTarget(event).value.replace(/ |\$/, '(') + ')'", 1).script("qsl('select').onchange = function () { helpClose();".($z!==""?"":" qsl('select, input', this.parentNode).onchange();")." };", "")."($d)":$d)."</div>\n";
			$t++;
		}
		echo "</div></fieldset>\n";
	}

	function selectSearchPrint($Z, $e, $x) {
		print_fieldset("search", lang(56), $Z);
		foreach ($x as $t=>$w) {
			if ($w["type"]=="FULLTEXT") {
				echo "<div>(<i>".implode("</i>, <i>", array_map('h', $w["columns"]))."</i>) AGAINST", " <input type='search' name='fulltext[$t]' value='".h($_GET["fulltext"][$t])."'>", script("qsl('input').oninput = selectFieldChange;", ""), checkbox("boolean[$t]", 1, isset($_GET["boolean"][$t]), "BOOL"), "</div>\n";
			}
		}
		$Za="this.parentNode.firstChild.onchange();";
		foreach (array_merge((array)$_GET["where"], [[]]) as $t=>$X) {
			if (!$X||("$X[col]$X[val]"!=""&&in_array($X["op"], $this->operators))) {
				echo "<div>".select_input(" name='where[$t][col]'", $e, $X["col"], ($X?"selectFieldChange":"selectAddRow"), "(".lang(57).")"), html_select("where[$t][op]", $this->operators, $X["op"], $Za), "<input type='search' name='where[$t][val]' value='".h($X["val"])."'>", script("mixin(qsl('input'), {oninput: function () { $Za }, onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});", ""), "</div>\n";
			}
		}
		echo "</div></fieldset>\n";
	}

	function selectOrderPrint($Bf, $e, $x) {
		print_fieldset("sort", lang(58), $Bf);
		$t=0;
		foreach ((array)$_GET["order"] as $z=>$X) {
			if ($X!="") {
				echo "<div>".select_input(" name='order[$t]'", $e, $X, "selectFieldChange"), checkbox("desc[$t]", 1, isset($_GET["desc"][$z]), lang(59))."</div>\n";
				$t++;
			}
		}
		echo "<div>".select_input(" name='order[$t]'", $e, "", "selectAddRow"), checkbox("desc[$t]", 1, false, lang(59))."</div>\n", "</div></fieldset>\n";
	}

	function selectLimitPrint($_) {
		echo "<fieldset><legend>".lang(60)."</legend><div>";
		echo "<input type='number' name='limit' class='size' value='".h($_)."'>", script("qsl('input').oninput = selectFieldChange;", ""), "</div></fieldset>\n";
	}

	function selectLengthPrint($fi) {
		if ($fi!==null) {
			echo "<fieldset><legend>".lang(61)."</legend><div>", "<input type='number' name='text_length' class='size' value='".h($fi)."'>", "</div></fieldset>\n";
		}
	}

	function selectActionPrint($x) {
		echo "<fieldset><legend>".lang(62)."</legend><div>", "<input type='submit' value='".lang(53)."'>", " <span id='noindex' title='".lang(63)."'></span>", "<script".nonce().">\n", "var indexColumns = ";
		$e=[];
		foreach ($x as $w) {
			$Qb=reset($w["columns"]);
			if ($w["type"]!="FULLTEXT"&&$Qb)
				$e[$Qb]=1;
		}
		$e[""]=1;
		foreach ($e as $z=>$X)
			json_row($z);
		echo ";\n", "selectFieldChange.call(qs('#form')['select']);\n", "</script>\n", "</div></fieldset>\n";
	}

	function selectCommandPrint() {
		return !information_schema(DB);
	}

	function selectImportPrint() {
		return !information_schema(DB);
	}

	function selectEmailPrint($xc, $e) {
	}

	function selectColumnsProcess($e, $x) {
		global $pd, $vd;
		$L=[];
		$sd=[];
		foreach ((array)$_GET["columns"] as $z=>$X) {
			if ($X["fun"]=="count"||($X["col"]!=""&&(!$X["fun"]||in_array($X["fun"], $pd)||in_array($X["fun"], $vd)))) {
				$L[$z]=apply_sql_function($X["fun"], ($X["col"]!=""?idf_escape($X["col"]):"*"));
				if (!in_array($X["fun"], $vd))
					$sd[]=$L[$z];
			}
		}

		return [$L, $sd];
	}

	function selectSearchProcess($p, $x) {
		global $g, $m;
		$I=[];
		foreach ($x as $t=>$w) {
			if ($w["type"]=="FULLTEXT"&&$_GET["fulltext"][$t]!="")
				$I[]="MATCH (".implode(", ", array_map('idf_escape', $w["columns"])).") AGAINST (".q($_GET["fulltext"][$t]).(isset($_GET["boolean"][$t])?" IN BOOLEAN MODE":"").")";
		}
		foreach ((array)$_GET["where"] as $z=>$X) {
			if ("$X[col]$X[val]"!=""&&in_array($X["op"], $this->operators)) {
				$lg="";
				$wb=" $X[op]";
				if (preg_match('~IN$~', $X["op"])) {
					$Ld=process_length($X["val"]);
					$wb.=" ".($Ld!=""?$Ld:"(NULL)");
				} elseif ($X["op"]=="SQL")
					$wb=" $X[val]";
				elseif ($X["op"]=="LIKE %%")
					$wb=" LIKE ".$this->processInput($p[$X["col"]], "%$X[val]%");
				elseif ($X["op"]=="ILIKE %%")
					$wb=" ILIKE ".$this->processInput($p[$X["col"]], "%$X[val]%");
				elseif ($X["op"]=="FIND_IN_SET") {
					$lg="$X[op](".q($X["val"]).", ";
					$wb=")";
				} elseif (!preg_match('~NULL$~', $X["op"]))
					$wb.=" ".$this->processInput($p[$X["col"]], $X["val"]);
				if ($X["col"]!="")
					$I[]=$lg.$m->convertSearch(idf_escape($X["col"]), $X, $p[$X["col"]]).$wb; else {
					$pb=[];
					foreach ($p as $D=>$o) {
						if ((preg_match('~^[-\d.'.(preg_match('~IN$~', $X["op"])?',':'').']+$~', $X["val"])||!preg_match('~'.number_type().'|bit~', $o["type"]))&&(!preg_match("~[\x80-\xFF]~", $X["val"])||preg_match('~char|text|enum|set~', $o["type"]))&&(!preg_match('~date|timestamp~', $o["type"])||preg_match('~^\d+-\d+-\d+~', $X["val"])))
							$pb[]=$lg.$m->convertSearch(idf_escape($D), $X, $o).$wb;
					}
					$I[]=($pb?"(".implode(" OR ", $pb).")":"1 = 0");
				}
			}
		}

		return $I;
	}

	function selectOrderProcess($p, $x) {
		$I=[];
		foreach ((array)$_GET["order"] as $z=>$X) {
			if ($X!="")
				$I[]=(preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~', $X)?$X:idf_escape($X)).(isset($_GET["desc"][$z])?" DESC":"");
		}

		return $I;
	}

	function selectLimitProcess() {
		return (isset($_GET["limit"])?$_GET["limit"]:"50");
	}

	function selectLengthProcess() {
		return (isset($_GET["text_length"])?$_GET["text_length"]:"100");
	}

	function selectEmailProcess($Z, $id) {
		return false;
	}

	function selectQueryBuild($L, $Z, $sd, $Bf, $_, $E) {
		return "";
	}

	function messageQuery($G, $gi, $Tc=false) {
		global $y, $m;
		restart_session();
		$Cd=&get_session("queries");
		if (!$Cd[$_GET["db"]])
			$Cd[$_GET["db"]]=[];
		if (strlen($G)>1e6)
			$G=preg_replace('~[\x80-\xFF]+$~', '', substr($G, 0, 1e6))."\n…";
		$Cd[$_GET["db"]][]=[$G, time(), $gi];
		$Ch="sql-".count($Cd[$_GET["db"]]);
		$I="<a href='#$Ch' class='toggle'>".lang(64)."</a>\n";
		if (!$Tc&&($ej=$m->warnings())) {
			$u="warnings-".count($Cd[$_GET["db"]]);
			$I="<a href='#$u' class='toggle'>".lang(46)."</a>, $I<div id='$u' class='hidden'>\n$ej</div>\n";
		}

		return " <span class='time'>".@date("H:i:s")."</span>"." $I<div id='$Ch' class='hidden'><pre><code class='jush-$y'>".shorten_utf8($G, 1000)."</code></pre>".($gi?" <span class='time'>($gi)</span>":'').(support("sql")?'<p><a href="'.h(str_replace("db=".urlencode(DB), "db=".urlencode($_GET["db"]), ME).'sql=&history='.(count($Cd[$_GET["db"]])-1)).'">'.lang(10).'</a>':'').'</div>';
	}

	function editRowPrint($Q, $p, $J, $Li) {
	}

	function editFunctions($o) {
		global $sc;
		$I=($o["null"]?"NULL/":"");
		$Li=isset($_GET["select"])||where($_GET);
		foreach ($sc as $z=>$pd) {
			if (!$z||(!isset($_GET["call"])&&$Li)) {
				foreach ($pd as $cg=>$X) {
					if (!$cg||preg_match("~$cg~", $o["type"]))
						$I.="/$X";
				}
			}
			if ($z&&!preg_match('~set|blob|bytea|raw|file|bool~', $o["type"]))
				$I.="/SQL";
		}
		if ($o["auto_increment"]&&!$Li)
			$I=lang(51);

		return explode("/", $I);
	}

	function editInput($Q, $o, $Ia, $Y) {
		if ($o["type"]=="enum")
			return (isset($_GET["select"])?"<label><input type='radio'$Ia value='-1' checked><i>".lang(8)."</i></label> ":"").($o["null"]?"<label><input type='radio'$Ia value=''".($Y!==null||isset($_GET["select"])?"":" checked")."><i>NULL</i></label> ":"").enum_input("radio", $Ia, $o, $Y, 0);

		return "";
	}

	function editHint($Q, $o, $Y) {
		return "";
	}

	function processInput($o, $Y, $s="") {
		if ($s=="SQL")
			return $Y;
		$D=$o["field"];
		$I=q($Y);
		if (preg_match('~^(now|getdate|uuid)$~', $s))
			$I="$s()"; elseif (preg_match('~^current_(date|timestamp)$~', $s))
			$I=$s;
		elseif (preg_match('~^([+-]|\|\|)$~', $s))
			$I=idf_escape($D)." $s $I";
		elseif (preg_match('~^[+-] interval$~', $s))
			$I=idf_escape($D)." $s ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i", $Y)?$Y:$I);
		elseif (preg_match('~^(addtime|subtime|concat)$~', $s))
			$I="$s(".idf_escape($D).", $I)";
		elseif (preg_match('~^(md5|sha1|password|encrypt)$~', $s))
			$I="$s($I)";

		return unconvert_field($o, $I);
	}

	function dumpOutput() {
		$I=['text'=>lang(65), 'file'=>lang(66)];
		if (function_exists('gzencode'))
			$I['gz']='gzip';

		return $I;
	}

	function dumpFormat() {
		return ['sql'=>'SQL', 'csv'=>'CSV,', 'csv;'=>'CSV;', 'tsv'=>'TSV'];
	}

	function dumpDatabase($l) {
	}

	function dumpTable($Q, $Kh, $ee=0) {
		if ($_POST["format"]!="sql") {
			echo "\xef\xbb\xbf";
			if ($Kh)
				dump_csv(array_keys(fields($Q)));
		} else {
			if ($ee==2) {
				$p=[];
				foreach (fields($Q) as $D=>$o)
					$p[]=idf_escape($D)." $o[full_type]";
				$i="CREATE TABLE ".table($Q)." (".implode(", ", $p).")";
			} else$i=create_sql($Q, $_POST["auto_increment"], $Kh);
			set_utf8mb4($i);
			if ($Kh&&$i) {
				if ($Kh=="DROP+CREATE"||$ee==1)
					echo "DROP ".($ee==2?"VIEW":"TABLE")." IF EXISTS ".table($Q).";\n";
				if ($ee==1)
					$i=remove_definer($i);
				echo "$i;\n\n";
			}
		}
	}

	function dumpData($Q, $Kh, $G) {
		global $g, $y;
		$He=($y=="sqlite"?0:1048576);
		if ($Kh) {
			if ($_POST["format"]=="sql") {
				if ($Kh=="TRUNCATE+INSERT")
					echo truncate_sql($Q).";\n";
				$p=fields($Q);
			}
			$H=$g->query($G, 1);
			if ($H) {
				$Xd="";
				$Wa="";
				$je=[];
				$Mh="";
				$Wc=($Q!=''?'fetch_assoc':'fetch_row');
				while ($J=$H->$Wc()) {
					if (!$je) {
						$Wi=[];
						foreach ($J as $X) {
							$o=$H->fetch_field();
							$je[]=$o->name;
							$z=idf_escape($o->name);
							$Wi[]="$z = VALUES($z)";
						}
						$Mh=($Kh=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ", $Wi):"").";\n";
					}
					if ($_POST["format"]!="sql") {
						if ($Kh=="table") {
							dump_csv($je);
							$Kh="INSERT";
						}
						dump_csv($J);
					} else {
						if (!$Xd)
							$Xd="INSERT INTO ".table($Q)." (".implode(", ", array_map('idf_escape', $je)).") VALUES";
						foreach ($J as $z=>$X) {
							$o=$p[$z];
							$J[$z]=($X!==null?unconvert_field($o, preg_match(number_type(), $o["type"])&&!preg_match('~\[~', $o["full_type"])&&is_numeric($X)?$X:q(($X===false?0:$X))):"NULL");
						}
						$ah=($He?"\n":" ")."(".implode(",\t", $J).")";
						if (!$Wa)
							$Wa=$Xd.$ah; elseif (strlen($Wa)+4+strlen($ah)+strlen($Mh)<$He)
							$Wa.=",$ah";
						else {
							echo $Wa.$Mh;
							$Wa=$Xd.$ah;
						}
					}
				}
				if ($Wa)
					echo $Wa.$Mh;
			} elseif ($_POST["format"]=="sql")
				echo "-- ".str_replace("\n", " ", $g->error)."\n";
		}
	}

	function dumpFilename($Hd) {
		return friendly_url($Hd!=""?$Hd:(SERVER!=""?SERVER:"localhost"));
	}

	function dumpHeaders($Hd, $Ve=false) {
		$Mf=$_POST["output"];
		$Oc=(preg_match('~sql~', $_POST["format"])?"sql":($Ve?"tar":"csv"));
		header("Content-Type: ".($Mf=="gz"?"application/x-gzip":($Oc=="tar"?"application/x-tar":($Oc=="sql"||$Mf!="file"?"text/plain":"text/csv")."; charset=utf-8")));
		if ($Mf=="gz")
			ob_start('ob_gzencode', 1e6);

		return $Oc;
	}

	function importServerPath() {
		return "adminer.sql";
	}

	function homepage() {
		echo '<p class="links">'.($_GET["ns"]==""&&support("database")?'<a href="'.h(ME).'database=">'.lang(67)."</a>\n":""), (support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?lang(68):lang(69))."</a>\n":""), ($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.lang(70)."</a>\n":""), (support("privileges")?"<a href='".h(ME)."privileges='>".lang(71)."</a>\n":"");

		return true;
	}

	function navigation($Ue) {
		global $ia, $y, $kc, $g;
		echo '<h1>
', $this->name(), ' <span class="version">', $ia, '</span>
<a href="https://www.adminer.org/#download"', target_blank(), ' id="version">', (version_compare($ia, $_COOKIE["adminer_version"])<0?h($_COOKIE["adminer_version"]):""), '</a>
</h1>
';
		if ($Ue=="auth") {
			$Mf="";
			foreach ((array)$_SESSION["pwds"] as $Yi=>$oh) {
				foreach ($oh as $M=>$Ti) {
					foreach ($Ti as $V=>$F) {
						if ($F!==null) {
							$Wb=$_SESSION["db"][$Yi][$M][$V];
							foreach (($Wb?array_keys($Wb):[""]) as $l)
								$Mf.="<li><a href='".h(auth_url($Yi, $M, $V, $l))."'>($kc[$Yi]) ".h($V.($M!=""?"@".$this->serverName($M):"").($l!=""?" - $l":""))."</a>\n";
						}
					}
				}
			}
			if ($Mf)
				echo "<ul id='logins'>\n$Mf</ul>\n".script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");
		} else {
			$S=[];
			if ($_GET["ns"]!==""&&!$Ue&&DB!="") {
				$g->select_db(DB);
				$S=table_status('', true);
			}
			echo script_src(preg_replace("~\\?.*~", "", ME)."?file=jush.js&version=4.8.1");
			if (support("sql")) {
				echo '<script', nonce(), '>
';
				if ($S) {
					$ze=[];
					foreach ($S as $Q=>$T)
						$ze[]=preg_quote($Q, '/');
					echo "var jushLinks = { $y: [ '".js_escape(ME).(support("table")?"table=":"select=")."\$&', /\\b(".implode("|", $ze).")\\b/g ] };\n";
					foreach (["bac", "bra", "sqlite_quo", "mssql_bra"] as $X)
						echo "jushLinks.$X = jushLinks.$y;\n";
				}
				$nh=$g->server_info;
				echo 'bodyLoad(\'', (is_object($g)?preg_replace('~^(\d\.?\d).*~s', '\1', $nh):""), '\'', (preg_match('~MariaDB~', $nh)?", true":""), ');
</script>
';
			}
			$this->databasesPrint($Ue);
			if (DB==""||!$Ue) {
				echo "<p class='links'>".(support("sql")?"<a href='".h(ME)."sql='".bold(isset($_GET["sql"])&&!isset($_GET["import"])).">".lang(64)."</a>\n<a href='".h(ME)."import='".bold(isset($_GET["import"])).">".lang(72)."</a>\n":"")."";
				if (support("dump"))
					echo "<a href='".h(ME)."dump=".urlencode(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".lang(73)."</a>\n";
			}
			if ($_GET["ns"]!==""&&!$Ue&&DB!="") {
				echo '<a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".lang(74)."</a>\n";
				if (!$S)
					echo "<p class='message'>".lang(9)."\n"; else$this->tablesPrint($S);
			}
		}
	}

	function databasesPrint($Ue) {
		global $b, $g;
		$k=$this->databases();
		if (DB&&$k&&!in_array(DB, $k))
			array_unshift($k, DB);
		echo '<form action="">
<p id="dbs">
';
		hidden_fields_get();
		$Ub=script("mixin(qsl('select'), {onmousedown: dbMouseDown, onchange: dbChange});");
		echo "<span title='".lang(75)."'>".lang(76)."</span>: ".($k?"<select name='db'>".optionlist([""=>""]+$k, DB)."</select>$Ub":"<input name='db' value='".h(DB)."' autocapitalize='off'>\n"), "<input type='submit' value='".lang(20)."'".($k?" class='hidden'":"").">\n";
		if (support("scheme")) {
			if ($Ue!="db"&&DB!=""&&$g->select_db(DB)) {
				echo "<br>".lang(77).": <select name='ns'>".optionlist([""=>""]+$b->schemas(), $_GET["ns"])."</select>$Ub";
				if ($_GET["ns"]!="")
					set_schema($_GET["ns"]);
			}
		}
		foreach (["import", "sql", "schema", "dump", "privileges"] as $X) {
			if (isset($_GET[$X])) {
				echo "<input type='hidden' name='$X' value=''>";
				break;
			}
		}
		echo "</p></form>\n";
	}

	function tablesPrint($S) {
		echo "<ul id='tables'>".script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");
		foreach ($S as $Q=>$O) {
			$D=$this->tableName($O);
			if ($D!="") {
				echo '<li><a href="'.h(ME).'select='.urlencode($Q).'"'.bold($_GET["select"]==$Q||$_GET["edit"]==$Q, "select")." title='".lang(41)."'>".lang(78)."</a> ", (support("table")||support("indexes")?'<a href="'.h(ME).'table='.urlencode($Q).'"'.bold(in_array($Q, [$_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"]]), (is_view($O)?"view":"structure"))." title='".lang(42)."'>$D</a>":"<span>$D</span>")."\n";
			}
		}
		echo "</ul>\n";
	}

}

$b=(function_exists('adminer_object')?adminer_object():new
Adminer);
$kc=["server"=>"MySQL"]+$kc;
if (!defined("DRIVER")) {
	define("DRIVER", "server");
	if (extension_loaded("mysqli")) {
		class
		Min_DB extends MySQLi {

			var $extension="MySQLi";

			function __construct() {
				parent::init();
			}

			function connect($M="", $V="", $F="", $j=null, $gg=null, $wh=null) {
				global $b;
				mysqli_report(MYSQLI_REPORT_OFF);
				[$Fd, $gg]=explode(":", $M, 2);
				$Eh=$b->connectSsl();
				if ($Eh)
					$this->ssl_set($Eh['key'], $Eh['cert'], $Eh['ca'], '', '');
				$I=@$this->real_connect(($M!=""?$Fd:ini_get("mysqli.default_host")), ($M.$V!=""?$V:ini_get("mysqli.default_user")), ($M.$V.$F!=""?$F:ini_get("mysqli.default_pw")), $j, (is_numeric($gg)?$gg:ini_get("mysqli.default_port")), (!is_numeric($gg)?$gg:$wh), ($Eh?64:0));
				$this->options(MYSQLI_OPT_LOCAL_INFILE, false);

				return $I;
			}

			function set_charset($ab) {
				if (parent::set_charset($ab))
					return true;
				parent::set_charset('utf8');

				return $this->query("SET NAMES $ab");
			}

			function result($G, $o=0) {
				$H=$this->query($G);
				if (!$H)
					return false;
				$J=$H->fetch_array();

				return $J[$o];
			}

			function quote($P) {
				return "'".$this->escape_string($P)."'";
			}

		}
	} elseif (extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))) {
		class
		Min_DB {

			var $extension="MySQL", $server_info, $affected_rows, $errno, $error, $_link, $_result;

			function connect($M, $V, $F) {
				if (ini_bool("mysql.allow_local_infile")) {
					$this->error=lang(79, "'mysql.allow_local_infile'", "MySQLi", "PDO_MySQL");

					return false;
				}
				$this->_link=@mysql_connect(($M!=""?$M:ini_get("mysql.default_host")), ("$M$V"!=""?$V:ini_get("mysql.default_user")), ("$M$V$F"!=""?$F:ini_get("mysql.default_password")), true, 131072);
				if ($this->_link)
					$this->server_info=mysql_get_server_info($this->_link); else$this->error=mysql_error();

				return (bool)$this->_link;
			}

			function set_charset($ab) {
				if (function_exists('mysql_set_charset')) {
					if (mysql_set_charset($ab, $this->_link))
						return true;
					mysql_set_charset('utf8', $this->_link);
				}

				return $this->query("SET NAMES $ab");
			}

			function quote($P) {
				return "'".mysql_real_escape_string($P, $this->_link)."'";
			}

			function select_db($j) {
				return mysql_select_db($j, $this->_link);
			}

			function query($G, $Ei=false) {
				$H=@($Ei?mysql_unbuffered_query($G, $this->_link):mysql_query($G, $this->_link));
				$this->error="";
				if (!$H) {
					$this->errno=mysql_errno($this->_link);
					$this->error=mysql_error($this->_link);

					return false;
				}
				if ($H===true) {
					$this->affected_rows=mysql_affected_rows($this->_link);
					$this->info=mysql_info($this->_link);

					return true;
				}

				return new
				Min_Result($H);
			}

			function multi_query($G) {
				return $this->_result=$this->query($G);
			}

			function store_result() {
				return $this->_result;
			}

			function next_result() {
				return false;
			}

			function result($G, $o=0) {
				$H=$this->query($G);
				if (!$H||!$H->num_rows)
					return false;

				return mysql_result($H->_result, 0, $o);
			}

		}

		class
		Min_Result {

			var $num_rows, $_result, $_offset=0;

			function __construct($H) {
				$this->_result=$H;
				$this->num_rows=mysql_num_rows($H);
			}

			function fetch_assoc() {
				return mysql_fetch_assoc($this->_result);
			}

			function fetch_row() {
				return mysql_fetch_row($this->_result);
			}

			function fetch_field() {
				$I=mysql_fetch_field($this->_result, $this->_offset++);
				$I->orgtable=$I->table;
				$I->orgname=$I->name;
				$I->charsetnr=($I->blob?63:0);

				return $I;
			}

			function __destruct() {
				mysql_free_result($this->_result);
			}

		}
	} elseif (extension_loaded("pdo_mysql")) {
		class
		Min_DB extends Min_PDO {

			var $extension="PDO_MySQL";

			function connect($M, $V, $F) {
				global $b;
				$_f=[PDO::MYSQL_ATTR_LOCAL_INFILE=>false];
				$Eh=$b->connectSsl();
				if ($Eh) {
					if (!empty($Eh['key']))
						$_f[PDO::MYSQL_ATTR_SSL_KEY]=$Eh['key'];
					if (!empty($Eh['cert']))
						$_f[PDO::MYSQL_ATTR_SSL_CERT]=$Eh['cert'];
					if (!empty($Eh['ca']))
						$_f[PDO::MYSQL_ATTR_SSL_CA]=$Eh['ca'];
				}
				$this->dsn("mysql:charset=utf8;host=".str_replace(":", ";unix_socket=", preg_replace('~:(\d)~', ';port=\1', $M)), $V, $F, $_f);

				return true;
			}

			function set_charset($ab) {
				$this->query("SET NAMES $ab");
			}

			function select_db($j) {
				return $this->query("USE ".idf_escape($j));
			}

			function query($G, $Ei=false) {
				$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, !$Ei);

				return parent::query($G, $Ei);
			}

		}
	}

	class
	Min_Driver extends Min_SQL {

		function insert($Q, $N) {
			return ($N?parent::insert($Q, $N):queries("INSERT INTO ".table($Q)." ()\nVALUES ()"));
		}

		function insertUpdate($Q, $K, $ng) {
			$e=array_keys(reset($K));
			$lg="INSERT INTO ".table($Q)." (".implode(", ", $e).") VALUES\n";
			$Wi=[];
			foreach ($e as $z)
				$Wi[$z]="$z = VALUES($z)";
			$Mh="\nON DUPLICATE KEY UPDATE ".implode(", ", $Wi);
			$Wi=[];
			$we=0;
			foreach ($K as $N) {
				$Y="(".implode(", ", $N).")";
				if ($Wi&&(strlen($lg)+$we+strlen($Y)+strlen($Mh)>1e6)) {
					if (!queries($lg.implode(",\n", $Wi).$Mh))
						return false;
					$Wi=[];
					$we=0;
				}
				$Wi[]=$Y;
				$we+=strlen($Y)+2;
			}

			return queries($lg.implode(",\n", $Wi).$Mh);
		}

		function slowQuery($G, $hi) {
			if (min_version('5.7.8', '10.1.2')) {
				if (preg_match('~MariaDB~', $this->_conn->server_info))
					return "SET STATEMENT max_statement_time=$hi FOR $G"; elseif (preg_match('~^(SELECT\b)(.+)~is', $G, $C))
					return "$C[1] /*+ MAX_EXECUTION_TIME(".($hi*1000).") */ $C[2]";
			}
		}

		function convertSearch($v, $X, $o) {
			return (preg_match('~char|text|enum|set~', $o["type"])&&!preg_match("~^utf8~", $o["collation"])&&preg_match('~[\x80-\xFF]~', $X['val'])?"CONVERT($v USING ".charset($this->_conn).")":$v);
		}

		function warnings() {
			$H=$this->_conn->query("SHOW WARNINGS");
			if ($H&&$H->num_rows) {
				ob_start();
				select($H);

				return ob_get_clean();
			}
		}

		function tableHelp($D) {
			$Ce=preg_match('~MariaDB~', $this->_conn->server_info);
			if (information_schema(DB))
				return strtolower(($Ce?"information-schema-$D-table/":str_replace("_", "-", $D)."-table.html"));
			if (DB=="mysql")
				return ($Ce?"mysql$D-table/":"system-database.html");
		}

	}

	function idf_escape($v) {
		return "`".str_replace("`", "``", $v)."`";
	}

	function table($v) {
		return idf_escape($v);
	}

	function connect() {
		global $b, $U, $Jh;
		$g=new
		Min_DB;
		$Mb=$b->credentials();
		if ($g->connect($Mb[0], $Mb[1], $Mb[2])) {
			$g->set_charset(charset($g));
			$g->query("SET sql_quote_show_create = 1, autocommit = 1");
			if (min_version('5.7.8', 10.2, $g)) {
				$Jh[lang(25)][]="json";
				$U["json"]=4294967295;
			}

			return $g;
		}
		$I=$g->error;
		if (function_exists('iconv')&&!is_utf8($I)&&strlen($ah=iconv("windows-1250", "utf-8", $I))>strlen($I))
			$I=$ah;

		return $I;
	}

	function get_databases($fd) {
		$I=get_session("dbs");
		if ($I===null) {
			$G=(min_version(5)?"SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME":"SHOW DATABASES");
			$I=($fd?slow_query($G):get_vals($G));
			restart_session();
			set_session("dbs", $I);
			stop_session();
		}

		return $I;
	}

	function limit($G, $Z, $_, $kf=0, $kh=" ") {
		return " $G$Z".($_!==null?$kh."LIMIT $_".($kf?" OFFSET $kf":""):"");
	}

	function limit1($Q, $G, $Z, $kh="\n") {
		return limit($G, $Z, 1, 0, $kh);
	}

	function db_collation($l, $nb) {
		global $g;
		$I=null;
		$i=$g->result("SHOW CREATE DATABASE ".idf_escape($l), 1);
		if (preg_match('~ COLLATE ([^ ]+)~', $i, $C))
			$I=$C[1]; elseif (preg_match('~ CHARACTER SET ([^ ]+)~', $i, $C))
			$I=$nb[$C[1]][-1];

		return $I;
	}

	function engines() {
		$I=[];
		foreach (get_rows("SHOW ENGINES") as $J) {
			if (preg_match("~YES|DEFAULT~", $J["Support"]))
				$I[]=$J["Engine"];
		}

		return $I;
	}

	function logged_user() {
		global $g;

		return $g->result("SELECT USER()");
	}

	function tables_list() {
		return get_key_vals(min_version(5)?"SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME":"SHOW TABLES");
	}

	function count_tables($k) {
		$I=[];
		foreach ($k as $l)
			$I[$l]=count(get_vals("SHOW TABLES IN ".idf_escape($l)));

		return $I;
	}

	function table_status($D="", $Uc=false) {
		$I=[];
		foreach (get_rows($Uc&&min_version(5)?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($D!=""?"AND TABLE_NAME = ".q($D):"ORDER BY Name"):"SHOW TABLE STATUS".($D!=""?" LIKE ".q(addcslashes($D, "%_\\")):"")) as $J) {
			if ($J["Engine"]=="InnoDB")
				$J["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~', '\1', $J["Comment"]);
			if (!isset($J["Engine"]))
				$J["Comment"]="";
			if ($D!="")
				return $J;
			$I[$J["Name"]]=$J;
		}

		return $I;
	}

	function is_view($R) {
		return $R["Engine"]===null;
	}

	function fk_support($R) {
		return preg_match('~InnoDB|IBMDB2I~i', $R["Engine"])||(preg_match('~NDB~i', $R["Engine"])&&min_version(5.6));
	}

	function fields($Q) {
		$I=[];
		foreach (get_rows("SHOW FULL COLUMNS FROM ".table($Q)) as $J) {
			preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~', $J["Type"], $C);
			$I[$J["Field"]]=["field"=>$J["Field"], "full_type"=>$J["Type"], "type"=>$C[1], "length"=>$C[2], "unsigned"=>ltrim($C[3].$C[4]), "default"=>($J["Default"]!=""||preg_match("~char|set~", $C[1])?(preg_match('~text~', $C[1])?stripslashes(preg_replace("~^'(.*)'\$~", '\1', $J["Default"])):$J["Default"]):null), "null"=>($J["Null"]=="YES"), "auto_increment"=>($J["Extra"]=="auto_increment"), "on_update"=>(preg_match('~^on update (.+)~i', $J["Extra"], $C)?$C[1]:""), "collation"=>$J["Collation"], "privileges"=>array_flip(preg_split('~, *~', $J["Privileges"])), "comment"=>$J["Comment"], "primary"=>($J["Key"]=="PRI"), "generated"=>preg_match('~^(VIRTUAL|PERSISTENT|STORED)~', $J["Extra"]),];
		}

		return $I;
	}

	function indexes($Q, $h=null) {
		$I=[];
		foreach (get_rows("SHOW INDEX FROM ".table($Q), $h) as $J) {
			$D=$J["Key_name"];
			$I[$D]["type"]=($D=="PRIMARY"?"PRIMARY":($J["Index_type"]=="FULLTEXT"?"FULLTEXT":($J["Non_unique"]?($J["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));
			$I[$D]["columns"][]=$J["Column_name"];
			$I[$D]["lengths"][]=($J["Index_type"]=="SPATIAL"?null:$J["Sub_part"]);
			$I[$D]["descs"][]=null;
		}

		return $I;
	}

	function foreign_keys($Q) {
		global $g, $sf;
		static $cg='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';
		$I=[];
		$Kb=$g->result("SHOW CREATE TABLE ".table($Q), 1);
		if ($Kb) {
			preg_match_all("~CONSTRAINT ($cg) FOREIGN KEY ?\\(((?:$cg,? ?)+)\\) REFERENCES ($cg)(?:\\.($cg))? \\(((?:$cg,? ?)+)\\)(?: ON DELETE ($sf))?(?: ON UPDATE ($sf))?~", $Kb, $Fe, PREG_SET_ORDER);
			foreach ($Fe as $C) {
				preg_match_all("~$cg~", $C[2], $yh);
				preg_match_all("~$cg~", $C[5], $Zh);
				$I[idf_unescape($C[1])]=["db"=>idf_unescape($C[4]!=""?$C[3]:$C[4]), "table"=>idf_unescape($C[4]!=""?$C[4]:$C[3]), "source"=>array_map('idf_unescape', $yh[0]), "target"=>array_map('idf_unescape', $Zh[0]), "on_delete"=>($C[6]?$C[6]:"RESTRICT"), "on_update"=>($C[7]?$C[7]:"RESTRICT"),];
			}
		}

		return $I;
	}

	function view($D) {
		global $g;

		return ["select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU', '', $g->result("SHOW CREATE VIEW ".table($D), 1))];
	}

	function collations() {
		$I=[];
		foreach (get_rows("SHOW COLLATION") as $J) {
			if ($J["Default"])
				$I[$J["Charset"]][-1]=$J["Collation"]; else$I[$J["Charset"]][]=$J["Collation"];
		}
		ksort($I);
		foreach ($I as $z=>$X)
			asort($I[$z]);

		return $I;
	}

	function information_schema($l) {
		return (min_version(5)&&$l=="information_schema")||(min_version(5.5)&&$l=="performance_schema");
	}

	function error() {
		global $g;

		return h(preg_replace('~^You have an error.*syntax to use~U', "Syntax error", $g->error));
	}

	function create_database($l, $mb) {
		return queries("CREATE DATABASE ".idf_escape($l).($mb?" COLLATE ".q($mb):""));
	}

	function drop_databases($k) {
		$I=apply_queries("DROP DATABASE", $k, 'idf_escape');
		restart_session();
		set_session("dbs", null);

		return $I;
	}

	function rename_database($D, $mb) {
		$I=false;
		if (create_database($D, $mb)) {
			$S=[];
			$bj=[];
			foreach (tables_list() as $Q=>$T) {
				if ($T=='VIEW')
					$bj[]=$Q; else$S[]=$Q;
			}
			$I=(!$S&&!$bj)||move_tables($S, $bj, $D);
			drop_databases($I?[DB]:[]);
		}

		return $I;
	}

	function auto_increment() {
		$Ma=" PRIMARY KEY";
		if ($_GET["create"]!=""&&$_POST["auto_increment_col"]) {
			foreach (indexes($_GET["create"]) as $w) {
				if (in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"], $w["columns"], true)) {
					$Ma="";
					break;
				}
				if ($w["type"]=="PRIMARY")
					$Ma=" UNIQUE";
			}
		}

		return " AUTO_INCREMENT$Ma";
	}

	function alter_table($Q, $D, $p, $hd, $tb, $_c, $mb, $La, $Wf) {
		$c=[];
		foreach ($p as $o)
			$c[]=($o[1]?($Q!=""?($o[0]!=""?"CHANGE ".idf_escape($o[0]):"ADD"):" ")." ".implode($o[1]).($Q!=""?$o[2]:""):"DROP ".idf_escape($o[0]));
		$c=array_merge($c, $hd);
		$O=($tb!==null?" COMMENT=".q($tb):"").($_c?" ENGINE=".q($_c):"").($mb?" COLLATE ".q($mb):"").($La!=""?" AUTO_INCREMENT=$La":"");
		if ($Q=="")
			return queries("CREATE TABLE ".table($D)." (\n".implode(",\n", $c)."\n)$O$Wf");
		if ($Q!=$D)
			$c[]="RENAME TO ".table($D);
		if ($O)
			$c[]=ltrim($O);

		return ($c||$Wf?queries("ALTER TABLE ".table($Q)."\n".implode(",\n", $c).$Wf):true);
	}

	function alter_indexes($Q, $c) {
		foreach ($c as $z=>$X)
			$c[$z]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"")."(".implode(", ", $X[2]).")");

		return queries("ALTER TABLE ".table($Q).implode(",", $c));
	}

	function truncate_tables($S) {
		return apply_queries("TRUNCATE TABLE", $S);
	}

	function drop_views($bj) {
		return queries("DROP VIEW ".implode(", ", array_map('table', $bj)));
	}

	function drop_tables($S) {
		return queries("DROP TABLE ".implode(", ", array_map('table', $S)));
	}

	function move_tables($S, $bj, $Zh) {
		global $g;
		$Mg=[];
		foreach ($S as $Q)
			$Mg[]=table($Q)." TO ".idf_escape($Zh).".".table($Q);
		if (!$Mg||queries("RENAME TABLE ".implode(", ", $Mg))) {
			$bc=[];
			foreach ($bj as $Q)
				$bc[table($Q)]=view($Q);
			$g->select_db($Zh);
			$l=idf_escape(DB);
			foreach ($bc as $D=>$aj) {
				if (!queries("CREATE VIEW $D AS ".str_replace(" $l.", " ", $aj["select"]))||!queries("DROP VIEW $l.$D"))
					return false;
			}

			return true;
		}

		return false;
	}

	function copy_tables($S, $bj, $Zh) {
		queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
		foreach ($S as $Q) {
			$D=($Zh==DB?table("copy_$Q"):idf_escape($Zh).".".table($Q));
			if (($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $D"))||!queries("CREATE TABLE $D LIKE ".table($Q))||!queries("INSERT INTO $D SELECT * FROM ".table($Q)))
				return false;
			foreach (get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q, "%_\\"))) as $J) {
				$zi=$J["Trigger"];
				if (!queries("CREATE TRIGGER ".($Zh==DB?idf_escape("copy_$zi"):idf_escape($Zh).".".idf_escape($zi))." $J[Timing] $J[Event] ON $D FOR EACH ROW\n$J[Statement];"))
					return false;
			}
		}
		foreach ($bj as $Q) {
			$D=($Zh==DB?table("copy_$Q"):idf_escape($Zh).".".table($Q));
			$aj=view($Q);
			if (($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $D"))||!queries("CREATE VIEW $D AS $aj[select]"))
				return false;
		}

		return true;
	}

	function trigger($D) {
		if ($D=="")
			return [];
		$K=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($D));

		return reset($K);
	}

	function triggers($Q) {
		$I=[];
		foreach (get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q, "%_\\"))) as $J)
			$I[$J["Trigger"]]=[$J["Timing"], $J["Event"]];

		return $I;
	}

	function trigger_options() {
		return ["Timing"=>["BEFORE", "AFTER"], "Event"=>["INSERT", "UPDATE", "DELETE"], "Type"=>["FOR EACH ROW"],];
	}

	function routine($D, $T) {
		global $g, $Bc, $Vd, $U;
		$Ca=["bool", "boolean", "integer", "double precision", "real", "dec", "numeric", "fixed", "national char", "national varchar"];
		$zh="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
		$Di="((".implode("|", array_merge(array_keys($U), $Ca)).")\\b(?:\\s*\\(((?:[^'\")]|$Bc)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";
		$cg="$zh*(".($T=="FUNCTION"?"":$Vd).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Di";
		$i=$g->result("SHOW CREATE $T ".idf_escape($D), 2);
		preg_match("~\\(((?:$cg\\s*,?)*)\\)\\s*".($T=="FUNCTION"?"RETURNS\\s+$Di\\s+":"")."(.*)~is", $i, $C);
		$p=[];
		preg_match_all("~$cg\\s*,?~is", $C[1], $Fe, PREG_SET_ORDER);
		foreach ($Fe as $Qf)
			$p[]=["field"=>str_replace("``", "`", $Qf[2]).$Qf[3], "type"=>strtolower($Qf[5]), "length"=>preg_replace_callback("~$Bc~s", 'normalize_enum', $Qf[6]), "unsigned"=>strtolower(preg_replace('~\s+~', ' ', trim("$Qf[8] $Qf[7]"))), "null"=>1, "full_type"=>$Qf[4], "inout"=>strtoupper($Qf[1]), "collation"=>strtolower($Qf[9]),];
		if ($T!="FUNCTION")
			return ["fields"=>$p, "definition"=>$C[11]];

		return ["fields"=>$p, "returns"=>["type"=>$C[12], "length"=>$C[13], "unsigned"=>$C[15], "collation"=>$C[16]], "definition"=>$C[17], "language"=>"SQL",];
	}

	function routines() {
		return get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = ".q(DB));
	}

	function routine_languages() {
		return [];
	}

	function routine_id($D, $J) {
		return idf_escape($D);
	}

	function last_id() {
		global $g;

		return $g->result("SELECT LAST_INSERT_ID()");
	}

	function explain($g, $G) {
		return $g->query("EXPLAIN ".(min_version(5.1)&&!min_version(5.7)?"PARTITIONS ":"").$G);
	}

	function found_rows($R, $Z) {
		return ($Z||$R["Engine"]!="InnoDB"?null:$R["Rows"]);
	}

	function types() {
		return [];
	}

	function schemas() {
		return [];
	}

	function get_schema() {
		return "";
	}

	function set_schema($ch, $h=null) {
		return true;
	}

	function create_sql($Q, $La, $Kh) {
		global $g;
		$I=$g->result("SHOW CREATE TABLE ".table($Q), 1);
		if (!$La)
			$I=preg_replace('~ AUTO_INCREMENT=\d+~', '', $I);

		return $I;
	}

	function truncate_sql($Q) {
		return "TRUNCATE ".table($Q);
	}

	function use_sql($j) {
		return "USE ".idf_escape($j);
	}

	function trigger_sql($Q) {
		$I="";
		foreach (get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($Q, "%_\\")), null, "-- ") as $J)
			$I.="\nCREATE TRIGGER ".idf_escape($J["Trigger"])." $J[Timing] $J[Event] ON ".table($J["Table"])." FOR EACH ROW\n$J[Statement];;\n";

		return $I;
	}

	function show_variables() {
		return get_key_vals("SHOW VARIABLES");
	}

	function process_list() {
		return get_rows("SHOW FULL PROCESSLIST");
	}

	function show_status() {
		return get_key_vals("SHOW STATUS");
	}

	function convert_field($o) {
		if (preg_match("~binary~", $o["type"]))
			return "HEX(".idf_escape($o["field"]).")";
		if ($o["type"]=="bit")
			return "BIN(".idf_escape($o["field"])." + 0)";
		if (preg_match("~geometry|point|linestring|polygon~", $o["type"]))
			return (min_version(8)?"ST_":"")."AsWKT(".idf_escape($o["field"]).")";
	}

	function unconvert_field($o, $I) {
		if (preg_match("~binary~", $o["type"]))
			$I="UNHEX($I)";
		if ($o["type"]=="bit")
			$I="CONV($I, 2, 10) + 0";
		if (preg_match("~geometry|point|linestring|polygon~", $o["type"]))
			$I=(min_version(8)?"ST_":"")."GeomFromText($I, SRID($o[field]))";

		return $I;
	}

	function support($Vc) {
		return !preg_match("~scheme|sequence|type|view_trigger|materializedview".(min_version(8)?"":"|descidx".(min_version(5.1)?"":"|event|partitioning".(min_version(5)?"":"|routine|trigger|view")))."~", $Vc);
	}

	function kill_process($X) {
		return queries("KILL ".number($X));
	}

	function connection_id() {
		return "SELECT CONNECTION_ID()";
	}

	function max_connections() {
		global $g;

		return $g->result("SELECT @@max_connections");
	}

	function driver_config() {
		$U=[];
		$Jh=[];
		foreach ([lang(27)=>["tinyint"=>3, "smallint"=>5, "mediumint"=>8, "int"=>10, "bigint"=>20, "decimal"=>66, "float"=>12, "double"=>21], lang(28)=>["date"=>10, "datetime"=>19, "timestamp"=>19, "time"=>10, "year"=>4], lang(25)=>["char"=>255, "varchar"=>65535, "tinytext"=>255, "text"=>65535, "mediumtext"=>16777215, "longtext"=>4294967295], lang(80)=>["enum"=>65535, "set"=>64], lang(29)=>["bit"=>20, "binary"=>255, "varbinary"=>65535, "tinyblob"=>255, "blob"=>65535, "mediumblob"=>16777215, "longblob"=>4294967295], lang(31)=>["geometry"=>0, "point"=>0, "linestring"=>0, "polygon"=>0, "multipoint"=>0, "multilinestring"=>0, "multipolygon"=>0, "geometrycollection"=>0],] as $z=>$X) {
			$U+=$X;
			$Jh[$z]=array_keys($X);
		}

		return ['possible_drivers'=>["MySQLi", "MySQL", "PDO_MySQL"], 'jush'=>"sql", 'types'=>$U, 'structured_types'=>$Jh, 'unsigned'=>["unsigned", "zerofill", "unsigned zerofill"], 'operators'=>["=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "REGEXP", "IN", "FIND_IN_SET", "IS NULL", "NOT LIKE", "NOT REGEXP", "NOT IN", "IS NOT NULL", "SQL"], 'functions'=>["char_length", "date", "from_unixtime", "lower", "round", "floor", "ceil", "sec_to_time", "time_to_sec", "upper"], 'grouping'=>["avg", "count", "count distinct", "group_concat", "max", "min", "sum"], 'edit_functions'=>[["char"=>"md5/sha1/password/encrypt/uuid", "binary"=>"md5/sha1", "date|time"=>"now",], [number_type()=>"+/-", "date"=>"+ interval/- interval", "time"=>"addtime/subtime", "char|text"=>"concat",]],];
	}
}
$xb=driver_config();
$kg=$xb['possible_drivers'];
$y=$xb['jush'];
$U=$xb['types'];
$Jh=$xb['structured_types'];
$Ki=$xb['unsigned'];
$xf=$xb['operators'];
$pd=$xb['functions'];
$vd=$xb['grouping'];
$sc=$xb['edit_functions'];
if ($b->operators===null)
	$b->operators=$xf;
define("SERVER", $_GET[DRIVER]);
define("DB", $_GET["db"]);
define("ME", preg_replace('~\?.*~', '', relative_uri()).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));
$ia="4.8.1";
function page_header($ji, $n="", $Va=[], $ki="") {
	global $ca, $ia, $b, $kc, $y;
	page_headers();
	if (is_ajax()&&$n) {
		page_messages($n);
		exit;
	}
	$li=$ji.($ki!=""?": $ki":"");
	$mi=strip_tags($li.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".$b->name());
	echo '<!DOCTYPE html>
<html lang="', $ca, '" dir="', lang(81), '">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>', $mi, '</title>
<link rel="stylesheet" type="text/css" href="', h(preg_replace("~\\?.*~", "", ME)."?file=default.css&version=4.8.1"), '">
', script_src(preg_replace("~\\?.*~", "", ME)."?file=functions.js&version=4.8.1");
	if ($b->head()) {
		echo '<link rel="shortcut icon" type="image/x-icon" href="', h(preg_replace("~\\?.*~", "", ME)."?file=favicon.ico&version=4.8.1"), '">
<link rel="apple-touch-icon" href="', h(preg_replace("~\\?.*~", "", ME)."?file=favicon.ico&version=4.8.1"), '">
';
		foreach ($b->css() as $Ob) {
			echo '<link rel="stylesheet" type="text/css" href="', h($Ob), '">
';
		}
	}
	echo '
<body class="', lang(81), ' nojs">
';
	$q=get_temp_dir()."/adminer.version";
	if (!$_COOKIE["adminer_version"]&&function_exists('openssl_verify')&&file_exists($q)&&filemtime($q)+86400>time()) {
		$Zi=unserialize(file_get_contents($q));
		$wg="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";
		if (openssl_verify($Zi["version"], base64_decode($Zi["signature"]), $wg)==1)
			$_COOKIE["adminer_version"]=$Zi["version"];
	}
	echo '<script', nonce(), '>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick', (isset($_COOKIE["adminer_version"])?"":", onload: partial(verifyVersion, '$ia', '".js_escape(ME)."', '".get_token()."')"); ?>});document.body.className = document.body.className.replace(/ nojs/, ' js');var offlineMessage = '<?php echo js_escape(lang(82)), '\';
var thousandsSeparator = \'', js_escape(lang(5)), '\';
</script>

<div id="help" class="jush-', $y, ' jsonly hidden"></div>
', script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"), '
<div id="content">
';
	if ($Va!==null) {
		$A=substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1);
		echo '<p id="breadcrumb"><a href="'.h($A?$A:".").'">'.$kc[DRIVER].'</a> &raquo; ';
		$A=substr(preg_replace('~\b(db|ns)=[^&]*&~', '', ME), 0, -1);
		$M=$b->serverName(SERVER);
		$M=($M!=""?$M:lang(34));
		if ($Va===false)
			echo "$M\n"; else {
			echo "<a href='".h($A)."' accesskey='1' title='Alt+Shift+1'>$M</a> &raquo; ";
			if ($_GET["ns"]!=""||(DB!=""&&is_array($Va)))
				echo '<a href="'.h($A."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> &raquo; ';
			if (is_array($Va)) {
				if ($_GET["ns"]!="")
					echo '<a href="'.h(substr(ME, 0, -1)).'">'.h($_GET["ns"]).'</a> &raquo; ';
				foreach ($Va as $z=>$X) {
					$dc=(is_array($X)?$X[1]:h($X));
					if ($dc!="")
						echo "<a href='".h(ME."$z=").urlencode(is_array($X)?$X[0]:$X)."'>$dc</a> &raquo; ";
				}
			}
			echo "$ji\n";
		}
	}
	echo "<h2>$li</h2>\n", "<div id='ajaxstatus' class='jsonly hidden'></div>\n";
	restart_session();
	page_messages($n);
	$k=&get_session("dbs");
	if (DB!=""&&$k&&!in_array(DB, $k, true))
		$k=null;
	stop_session();
	define("PAGE_HEADER", 1);
}

function page_headers() {
	global $b;
	header("Content-Type: text/html; charset=utf-8");
	header("Cache-Control: no-cache");
	header("X-Frame-Options: deny");
	header("X-XSS-Protection: 0");
	header("X-Content-Type-Options: nosniff");
	header("Referrer-Policy: origin-when-cross-origin");
	foreach ($b->csp() as $Nb) {
		$Ad=[];
		foreach ($Nb as $z=>$X)
			$Ad[]="$z $X";
		header("Content-Security-Policy: ".implode("; ", $Ad));
	}
	$b->headers();
}

function csp() {
	return [["script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'", "connect-src"=>"'self'", "frame-src"=>"https://www.adminer.org", "object-src"=>"'none'", "base-uri"=>"'none'", "form-action"=>"'self'",],];
}

function get_nonce() {
	static $ef;
	if (!$ef)
		$ef=base64_encode(rand_string());

	return $ef;
}

function page_messages($n) {
	$Mi=preg_replace('~^[^?]*~', '', $_SERVER["REQUEST_URI"]);
	$Re=$_SESSION["messages"][$Mi];
	if ($Re) {
		echo "<div class='message'>".implode("</div>\n<div class='message'>", $Re)."</div>".script("messagesPrint();");
		unset($_SESSION["messages"][$Mi]);
	}
	if ($n)
		echo "<div class='error'>$n</div>\n";
}

function page_footer($Ue="") {
	global $b, $qi;
	echo '</div>

';
	switch_lang();
	if ($Ue!="auth") {
		echo '<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="', lang(83), '" id="logout">
<input type="hidden" name="token" value="', $qi, '">
</p>
</form>
';
	}
	echo '<div id="menu">
';
	$b->navigation($Ue);
	echo '</div>
', script("setupSubmitHighlight(document);");
}

function int32($Xe) {
	while ($Xe>=2147483648)
		$Xe-=4294967296;
	while ($Xe<=-2147483649)
		$Xe+=4294967296;

	return (int)$Xe;
}

function long2str($W, $dj) {
	$ah='';
	foreach ($W as $X)
		$ah.=pack('V', $X);
	if ($dj)
		return substr($ah, 0, end($W));

	return $ah;
}

function str2long($ah, $dj) {
	$W=array_values(unpack('V*', str_pad($ah, 4*ceil(strlen($ah)/4), "\0")));
	if ($dj)
		$W[]=strlen($ah);

	return $W;
}

function xxtea_mx($pj, $oj, $Nh, $he) {
	return int32((($pj>>5&0x7FFFFFF)^$oj<<2)+(($oj>>3&0x1FFFFFFF)^$pj<<4))^int32(($Nh^$oj)+($he^$pj));
}

function encrypt_string($Ih, $z) {
	if ($Ih=="")
		return "";
	$z=array_values(unpack("V*", pack("H*", md5($z))));
	$W=str2long($Ih, true);
	$Xe=count($W)-1;
	$pj=$W[$Xe];
	$oj=$W[0];
	$xg=floor(6+52/($Xe+1));
	$Nh=0;
	while ($xg-->0) {
		$Nh=int32($Nh+0x9E3779B9);
		$rc=$Nh>>2&3;
		for ($Of=0; $Of<$Xe; $Of++) {
			$oj=$W[$Of+1];
			$We=xxtea_mx($pj, $oj, $Nh, $z[$Of&3^$rc]);
			$pj=int32($W[$Of]+$We);
			$W[$Of]=$pj;
		}
		$oj=$W[0];
		$We=xxtea_mx($pj, $oj, $Nh, $z[$Of&3^$rc]);
		$pj=int32($W[$Xe]+$We);
		$W[$Xe]=$pj;
	}

	return long2str($W, false);
}

function decrypt_string($Ih, $z) {
	if ($Ih=="")
		return "";
	if (!$z)
		return false;
	$z=array_values(unpack("V*", pack("H*", md5($z))));
	$W=str2long($Ih, false);
	$Xe=count($W)-1;
	$pj=$W[$Xe];
	$oj=$W[0];
	$xg=floor(6+52/($Xe+1));
	$Nh=int32($xg*0x9E3779B9);
	while ($Nh) {
		$rc=$Nh>>2&3;
		for ($Of=$Xe; $Of>0; $Of--) {
			$pj=$W[$Of-1];
			$We=xxtea_mx($pj, $oj, $Nh, $z[$Of&3^$rc]);
			$oj=int32($W[$Of]-$We);
			$W[$Of]=$oj;
		}
		$pj=$W[$Xe];
		$We=xxtea_mx($pj, $oj, $Nh, $z[$Of&3^$rc]);
		$oj=int32($W[0]-$We);
		$W[0]=$oj;
		$Nh=int32($Nh-0x9E3779B9);
	}

	return long2str($W, true);
}

$g='';
$_d=$_SESSION["token"];
if (!$_d)
	$_SESSION["token"]=rand(1, 1e6);
$qi=get_token();
$eg=[];
if ($_COOKIE["adminer_permanent"]) {
	foreach (explode(" ", $_COOKIE["adminer_permanent"]) as $X) {
		[$z]=explode(":", $X);
		$eg[$z]=$X;
	}
}
function add_invalid_login() {
	global $b;
	$nd=file_open_lock(get_temp_dir()."/adminer.invalid");
	if (!$nd)
		return;
	$ae=unserialize(stream_get_contents($nd));
	$gi=time();
	if ($ae) {
		foreach ($ae as $be=>$X) {
			if ($X[0]<$gi)
				unset($ae[$be]);
		}
	}
	$Zd=&$ae[$b->bruteForceKey()];
	if (!$Zd)
		$Zd=[$gi+30*60, 0];
	$Zd[1]++;
	file_write_unlock($nd, serialize($ae));
}

function check_invalid_login() {
	global $b;
	$ae=unserialize(@file_get_contents(get_temp_dir()."/adminer.invalid"));
	$Zd=($ae?$ae[$b->bruteForceKey()]:[]);
	$df=($Zd[1]>29?$Zd[0]-time():0);
	if ($df>0)
		auth_error(lang(84, ceil($df/60)));
}

$Ja=$_POST["auth"];
if ($Ja) {
	session_regenerate_id();
	$Yi=$Ja["driver"];
	$M=$Ja["server"];
	$V=$Ja["username"];
	$F=(string)$Ja["password"];
	$l=$Ja["db"];
	set_password($Yi, $M, $V, $F);
	$_SESSION["db"][$Yi][$M][$V][$l]=true;
	if ($Ja["permanent"]) {
		$z=base64_encode($Yi)."-".base64_encode($M)."-".base64_encode($V)."-".base64_encode($l);
		$qg=$b->permanentLogin(true);
		$eg[$z]="$z:".base64_encode($qg?encrypt_string($F, $qg):"");
		cookie("adminer_permanent", implode(" ", $eg));
	}
	if (count($_POST)==1||DRIVER!=$Yi||SERVER!=$M||$_GET["username"]!==$V||DB!=$l)
		redirect(auth_url($Yi, $M, $V, $l));
} elseif ($_POST["logout"]&&(!$_d||verify_token())) {
	foreach (["pwds", "db", "dbs", "queries"] as $z)
		set_session($z, null);
	unset_permanent();
	redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1), lang(85).' '.lang(86));
} elseif ($eg&&!$_SESSION["pwds"]) {
	session_regenerate_id();
	$qg=$b->permanentLogin();
	foreach ($eg as $z=>$X) {
		[, $gb]=explode(":", $X);
		[$Yi, $M, $V, $l]=array_map('base64_decode', explode("-", $z));
		set_password($Yi, $M, $V, decrypt_string(base64_decode($gb), $qg));
		$_SESSION["db"][$Yi][$M][$V][$l]=true;
	}
}
function unset_permanent() {
	global $eg;
	foreach ($eg as $z=>$X) {
		[$Yi, $M, $V, $l]=array_map('base64_decode', explode("-", $z));
		if ($Yi==DRIVER&&$M==SERVER&&$V==$_GET["username"]&&$l==DB)
			unset($eg[$z]);
	}
	cookie("adminer_permanent", implode(" ", $eg));
}

function auth_error($n) {
	global $b, $_d;
	$ph=session_name();
	if (isset($_GET["username"])) {
		header("HTTP/1.1 403 Forbidden");
		if (($_COOKIE[$ph]||$_GET[$ph])&&!$_d)
			$n=lang(87); else {
			restart_session();
			add_invalid_login();
			$F=get_password();
			if ($F!==null) {
				if ($F===false)
					$n.=($n?'<br>':'').lang(88, target_blank(), '<code>permanentLogin()</code>');
				set_password(DRIVER, SERVER, $_GET["username"], null);
			}
			unset_permanent();
		}
	}
	if (!$_COOKIE[$ph]&&$_GET[$ph]&&ini_bool("session.use_only_cookies"))
		$n=lang(89);
	$Rf=session_get_cookie_params();
	cookie("adminer_key", ($_COOKIE["adminer_key"]?$_COOKIE["adminer_key"]:rand_string()), $Rf["lifetime"]);
	page_header(lang(38), $n, null);
	echo "<form action='' method='post'>\n", "<div>";
	if (hidden_fields($_POST, ["auth"]))
		echo "<p class='message'>".lang(90)."\n";
	echo "</div>\n";
	$b->loginForm();
	echo "</form>\n";
	page_footer("auth");
	exit;
}

if (isset($_GET["username"])&&!class_exists("Min_DB")) {
	unset($_SESSION["pwds"][DRIVER]);
	unset_permanent();
	page_header(lang(91), lang(92, implode(", ", $kg)), false);
	page_footer("auth");
	exit;
}
stop_session(true);
if (isset($_GET["username"])&&is_string(get_password())) {
	[$Fd, $gg]=explode(":", SERVER, 2);
	if (preg_match('~^\s*([-+]?\d+)~', $gg, $C)&&($C[1]<1024||$C[1]>65535))
		auth_error(lang(93));
	check_invalid_login();
	$g=connect();
	$m=new
	Min_Driver($g);
}
$_e=null;
if (!is_object($g)||($_e=$b->login($_GET["username"], get_password()))!==true) {
	$n=(is_string($g)?h($g):(is_string($_e)?$_e:lang(32)));
	auth_error($n.(preg_match('~^ | $~', get_password())?'<br>'.lang(94):''));
}
if ($_POST["logout"]&&$_d&&!verify_token()) {
	page_header(lang(83), lang(95));
	page_footer("db");
	exit;
}
if ($Ja&&$_POST["token"])
	$_POST["token"]=$qi;
$n='';
if ($_POST) {
	if (!verify_token()) {
		$Ud="max_input_vars";
		$Le=ini_get($Ud);
		if (extension_loaded("suhosin")) {
			foreach (["suhosin.request.max_vars", "suhosin.post.max_vars"] as $z) {
				$X=ini_get($z);
				if ($X&&(!$Le||$X<$Le)) {
					$Ud=$z;
					$Le=$X;
				}
			}
		}
		$n=(!$_POST["token"]&&$Le?lang(96, "'$Ud'"):lang(95).' '.lang(97));
	}
} elseif ($_SERVER["REQUEST_METHOD"]=="POST") {
	$n=lang(98, "'post_max_size'");
	if (isset($_GET["sql"]))
		$n.=' '.lang(99);
}
function select($H, $h=null, $Ef=[], $_=0) {
	global $y;
	$ze=[];
	$x=[];
	$e=[];
	$Ta=[];
	$U=[];
	$I=[];
	odd('');
	for ($t=0; (!$_||$t<$_)&&($J=$H->fetch_row()); $t++) {
		if (!$t) {
			echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap'>\n", "<thead><tr>";
			for ($ge=0; $ge<count($J); $ge++) {
				$o=$H->fetch_field();
				$D=$o->name;
				$Df=$o->orgtable;
				$Cf=$o->orgname;
				$I[$o->table]=$Df;
				if ($Ef&&$y=="sql")
					$ze[$ge]=($D=="table"?"table=":($D=="possible_keys"?"indexes=":null)); elseif ($Df!="") {
					if (!isset($x[$Df])) {
						$x[$Df]=[];
						foreach (indexes($Df, $h) as $w) {
							if ($w["type"]=="PRIMARY") {
								$x[$Df]=array_flip($w["columns"]);
								break;
							}
						}
						$e[$Df]=$x[$Df];
					}
					if (isset($e[$Df][$Cf])) {
						unset($e[$Df][$Cf]);
						$x[$Df][$Cf]=$ge;
						$ze[$ge]=$Df;
					}
				}
				if ($o->charsetnr==63)
					$Ta[$ge]=true;
				$U[$ge]=$o->type;
				echo "<th".($Df!=""||$o->name!=$Cf?" title='".h(($Df!=""?"$Df.":"").$Cf)."'":"").">".h($D).($Ef?doc_link(['sql'=>"explain-output.html#explain_".strtolower($D), 'mariadb'=>"explain/#the-columns-in-explain-select",]):"");
			}
			echo "</thead>\n";
		}
		echo "<tr".odd().">";
		foreach ($J as $z=>$X) {
			$A="";
			if (isset($ze[$z])&&!$e[$ze[$z]]) {
				if ($Ef&&$y=="sql") {
					$Q=$J[array_search("table=", $ze)];
					$A=ME.$ze[$z].urlencode($Ef[$Q]!=""?$Ef[$Q]:$Q);
				} else {
					$A=ME."edit=".urlencode($ze[$z]);
					foreach ($x[$ze[$z]] as $kb=>$ge)
						$A.="&where".urlencode("[".bracket_escape($kb)."]")."=".urlencode($J[$ge]);
				}
			} elseif (is_url($X))
				$A=$X;
			if ($X===null)
				$X="<i>NULL</i>"; elseif ($Ta[$z]&&!is_utf8($X))
				$X="<i>".lang(47, strlen($X))."</i>";
			else {
				$X=h($X);
				if ($U[$z]==254)
					$X="<code>$X</code>";
			}
			if ($A)
				$X="<a href='".h($A)."'".(is_url($A)?target_blank():'').">$X</a>";
			echo "<td>$X";
		}
	}
	echo ($t?"</table>\n</div>":"<p class='message'>".lang(12))."\n";

	return $I;
}

function referencable_primary($ih) {
	$I=[];
	foreach (table_status('', true) as $Rh=>$Q) {
		if ($Rh!=$ih&&fk_support($Q)) {
			foreach (fields($Rh) as $o) {
				if ($o["primary"]) {
					if ($I[$Rh]) {
						unset($I[$Rh]);
						break;
					}
					$I[$Rh]=$o;
				}
			}
		}
	}

	return $I;
}

function adminer_settings() {
	parse_str($_COOKIE["adminer_settings"], $rh);

	return $rh;
}

function adminer_setting($z) {
	$rh=adminer_settings();

	return $rh[$z];
}

function set_adminer_settings($rh) {
	return cookie("adminer_settings", http_build_query($rh+adminer_settings()));
}

function textarea($D, $Y, $K=10, $pb=80) {
	global $y;
	echo "<textarea name='$D' rows='$K' cols='$pb' class='sqlarea jush-$y' spellcheck='false' wrap='off'>";
	if (is_array($Y)) {
		foreach ($Y as $X)
			echo h($X[0])."\n\n\n";
	} else
		echo h($Y);
	echo "</textarea>";
}

function edit_type($z, $o, $nb, $jd=[], $Rc=[]) {
	global $Jh, $U, $Ki, $sf;
	$T=$o["type"];
	echo '<td><select name="', h($z), '[type]" class="type" aria-labelledby="label-type">';
	if ($T&&!isset($U[$T])&&!isset($jd[$T])&&!in_array($T, $Rc))
		$Rc[]=$T;
	if ($jd)
		$Jh[lang(100)]=$jd;
	echo optionlist(array_merge($Rc, $Jh), $T), '</select><td><input name="', h($z), '[length]" value="', h($o["length"]), '" size="3"', (!$o["length"]&&preg_match('~var(char|binary)$~', $T)?" class='required'":"");
	echo ' aria-labelledby="label-length"><td class="options">', "<select name='".h($z)."[collation]'".(preg_match('~(char|text|enum|set)$~', $T)?"":" class='hidden'").'><option value="">('.lang(101).')'.optionlist($nb, $o["collation"]).'</select>', ($Ki?"<select name='".h($z)."[unsigned]'".(!$T||preg_match(number_type(), $T)?"":" class='hidden'").'><option>'.optionlist($Ki, $o["unsigned"]).'</select>':''), (isset($o['on_update'])?"<select name='".h($z)."[on_update]'".(preg_match('~timestamp|datetime~', $T)?"":" class='hidden'").'>'.optionlist([""=>"(".lang(102).")", "CURRENT_TIMESTAMP"], (preg_match('~^CURRENT_TIMESTAMP~i', $o["on_update"])?"CURRENT_TIMESTAMP":$o["on_update"])).'</select>':''), ($jd?"<select name='".h($z)."[on_delete]'".(preg_match("~`~", $T)?"":" class='hidden'")."><option value=''>(".lang(103).")".optionlist(explode("|", $sf), $o["on_delete"])."</select> ":" ");
}

function process_length($we) {
	global $Bc;

	return (preg_match("~^\\s*\\(?\\s*$Bc(?:\\s*,\\s*$Bc)*+\\s*\\)?\\s*\$~", $we)&&preg_match_all("~$Bc~", $we, $Fe)?"(".implode(",", $Fe[0]).")":preg_replace('~^[0-9].*~', '(\0)', preg_replace('~[^-0-9,+()[\]]~', '', $we)));
}

function process_type($o, $lb="COLLATE") {
	global $Ki;

	return " $o[type]".process_length($o["length"]).(preg_match(number_type(), $o["type"])&&in_array($o["unsigned"], $Ki)?" $o[unsigned]":"").(preg_match('~char|text|enum|set~', $o["type"])&&$o["collation"]?" $lb ".q($o["collation"]):"");
}

function process_field($o, $Ci) {
	return [idf_escape(trim($o["field"])), process_type($Ci), ($o["null"]?" NULL":" NOT NULL"), default_value($o), (preg_match('~timestamp|datetime~', $o["type"])&&$o["on_update"]?" ON UPDATE $o[on_update]":""), (support("comment")&&$o["comment"]!=""?" COMMENT ".q($o["comment"]):""), ($o["auto_increment"]?auto_increment():null),];
}

function default_value($o) {
	$Yb=$o["default"];

	return ($Yb===null?"":" DEFAULT ".(preg_match('~char|binary|text|enum|set~', $o["type"])||preg_match('~^(?![a-z])~i', $Yb)?q($Yb):$Yb));
}

function type_class($T) {
	foreach (['char'=>'text', 'date'=>'time|year', 'binary'=>'blob', 'enum'=>'set',] as $z=>$X) {
		if (preg_match("~$z|$X~", $T))
			return " class='$z'";
	}
}

function edit_fields($p, $nb, $T="TABLE", $jd=[]) {
	global $Vd;
	$p=array_values($p);
	$Zb=(($_POST?$_POST["defaults"]:adminer_setting("defaults"))?"":" class='hidden'");
	$ub=(($_POST?$_POST["comments"]:adminer_setting("comments"))?"":" class='hidden'");
	echo '<thead><tr>
';
	if ($T=="PROCEDURE") {
		echo '<td>';
	}
	echo '<th id="label-name">', ($T=="TABLE"?lang(104):lang(105)), '<td id="label-type">', lang(49), '<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;"></textarea>', script("qs('#enum-edit').onblur = editingLengthBlur;"), '<td id="label-length">', lang(106), '<td>', lang(107);
	if ($T=="TABLE") {
		echo '<td id="label-null">NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym id="label-ai" title="', lang(51), '">AI</acronym>', doc_link(['sql'=>"example-auto-increment.html", 'mariadb'=>"auto_increment/", 'sqlite'=>"autoinc.html", 'pgsql'=>"datatype.html#DATATYPE-SERIAL", 'mssql'=>"ms186775.aspx",]), '<td id="label-default"', $Zb, '>', lang(52), (support("comment")?"<td id='label-comment'$ub>".lang(50):"");
	}
	echo '<td>', "<input type='image' class='icon' name='add[".(support("move_col")?0:count($p))."]' src='".h(preg_replace("~\\?.*~", "", ME)."?file=plus.gif&version=4.8.1")."' alt='+' title='".lang(108)."'>".script("row_count = ".count($p).";"), '</thead>
<tbody>
', script("mixin(qsl('tbody'), {onclick: editingClick, onkeydown: editingKeydown, oninput: editingInput});");
	foreach ($p as $t=>$o) {
		$t++;
		$Ff=$o[($_POST?"orig":"field")];
		$hc=(isset($_POST["add"][$t-1])||(isset($o["field"])&&!$_POST["drop_col"][$t]))&&(support("drop_col")||$Ff=="");
		echo '<tr', ($hc?"":" style='display: none;'"), '>
', ($T=="PROCEDURE"?"<td>".html_select("fields[$t][inout]", explode("|", $Vd), $o["inout"]):""), '<th>';
		if ($hc) {
			echo '<input name="fields[', $t, '][field]" value="', h($o["field"]), '" data-maxlength="64" autocapitalize="off" aria-labelledby="label-name">';
		}
		echo '<input type="hidden" name="fields[', $t, '][orig]" value="', h($Ff), '">';
		edit_type("fields[$t]", $o, $nb, $jd);
		if ($T=="TABLE") {
			echo '<td>', checkbox("fields[$t][null]", 1, $o["null"], "", "", "block", "label-null"), '<td><label class="block"><input type="radio" name="auto_increment_col" value="', $t, '"';
			if ($o["auto_increment"]) {
				echo ' checked';
			}
			echo ' aria-labelledby="label-ai"></label><td', $Zb, '>', checkbox("fields[$t][has_default]", 1, $o["has_default"], "", "", "", "label-default"), '<input name="fields[', $t, '][default]" value="', h($o["default"]), '" aria-labelledby="label-default">', (support("comment")?"<td$ub><input name='fields[$t][comment]' value='".h($o["comment"])."' data-maxlength='".(min_version(5.5)?1024:255)."' aria-labelledby='label-comment'>":"");
		}
		echo "<td>", (support("move_col")?"<input type='image' class='icon' name='add[$t]' src='".h(preg_replace("~\\?.*~", "", ME)."?file=plus.gif&version=4.8.1")."' alt='+' title='".lang(108)."'> "."<input type='image' class='icon' name='up[$t]' src='".h(preg_replace("~\\?.*~", "", ME)."?file=up.gif&version=4.8.1")."' alt='↑' title='".lang(109)."'> "."<input type='image' class='icon' name='down[$t]' src='".h(preg_replace("~\\?.*~", "", ME)."?file=down.gif&version=4.8.1")."' alt='↓' title='".lang(110)."'> ":""), ($Ff==""||support("drop_col")?"<input type='image' class='icon' name='drop_col[$t]' src='".h(preg_replace("~\\?.*~", "", ME)."?file=cross.gif&version=4.8.1")."' alt='x' title='".lang(111)."'>":"");
	}
}

function process_fields(&$p) {
	$kf=0;
	if ($_POST["up"]) {
		$qe=0;
		foreach ($p as $z=>$o) {
			if (key($_POST["up"])==$z) {
				unset($p[$z]);
				array_splice($p, $qe, 0, [$o]);
				break;
			}
			if (isset($o["field"]))
				$qe=$kf;
			$kf++;
		}
	} elseif ($_POST["down"]) {
		$ld=false;
		foreach ($p as $z=>$o) {
			if (isset($o["field"])&&$ld) {
				unset($p[key($_POST["down"])]);
				array_splice($p, $kf, 0, [$ld]);
				break;
			}
			if (key($_POST["down"])==$z)
				$ld=$o;
			$kf++;
		}
	} elseif ($_POST["add"]) {
		$p=array_values($p);
		array_splice($p, key($_POST["add"]), 0, [[]]);
	} elseif (!$_POST["drop_col"])
		return false;

	return true;
}

function normalize_enum($C) {
	return "'".str_replace("'", "''", addcslashes(stripcslashes(str_replace($C[0][0].$C[0][0], $C[0][0], substr($C[0], 1, -1))), '\\'))."'";
}

function grant($qd, $sg, $e, $rf) {
	if (!$sg)
		return true;
	if ($sg==["ALL PRIVILEGES", "GRANT OPTION"])
		return ($qd=="GRANT"?queries("$qd ALL PRIVILEGES$rf WITH GRANT OPTION"):queries("$qd ALL PRIVILEGES$rf")&&queries("$qd GRANT OPTION$rf"));

	return queries("$qd ".preg_replace('~(GRANT OPTION)\([^)]*\)~', '\1', implode("$e, ", $sg).$e).$rf);
}

function drop_create($lc, $i, $mc, $di, $oc, $B, $Qe, $Oe, $Pe, $of, $bf) {
	if ($_POST["drop"])
		query_redirect($lc, $B, $Qe); elseif ($of=="")
		query_redirect($i, $B, $Pe);
	elseif ($of!=$bf) {
		$Lb=queries($i);
		queries_redirect($B, $Oe, $Lb&&queries($lc));
		if ($Lb)
			queries($mc);
	} else
		queries_redirect($B, $Oe, queries($di)&&queries($oc)&&queries($lc)&&queries($i));
}

function create_trigger($rf, $J) {
	global $y;
	$ii=" $J[Timing] $J[Event]".(preg_match('~ OF~', $J["Event"])?" $J[Of]":"");

	return "CREATE TRIGGER ".idf_escape($J["Trigger"]).($y=="mssql"?$rf.$ii:$ii.$rf).rtrim(" $J[Type]\n$J[Statement]", ";").";";
}

function create_routine($Wg, $J) {
	global $Vd, $y;
	$N=[];
	$p=(array)$J["fields"];
	ksort($p);
	foreach ($p as $o) {
		if ($o["field"]!="")
			$N[]=(preg_match("~^($Vd)\$~", $o["inout"])?"$o[inout] ":"").idf_escape($o["field"]).process_type($o, "CHARACTER SET");
	}
	$ac=rtrim("\n$J[definition]", ";");

	return "CREATE $Wg ".idf_escape(trim($J["name"]))." (".implode(", ", $N).")".(isset($_GET["function"])?" RETURNS".process_type($J["returns"], "CHARACTER SET"):"").($J["language"]?" LANGUAGE $J[language]":"").($y=="pgsql"?" AS ".q($ac):"$ac;");
}

function remove_definer($G) {
	return preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~', '`@`(%|\1)', logged_user()).'`~', '\1', $G);
}

function format_foreign_key($r) {
	global $sf;
	$l=$r["db"];
	$ff=$r["ns"];

	return " FOREIGN KEY (".implode(", ", array_map('idf_escape', $r["source"])).") REFERENCES ".($l!=""&&$l!=$_GET["db"]?idf_escape($l).".":"").($ff!=""&&$ff!=$_GET["ns"]?idf_escape($ff).".":"").table($r["table"])." (".implode(", ", array_map('idf_escape', $r["target"])).")".(preg_match("~^($sf)\$~", $r["on_delete"])?" ON DELETE $r[on_delete]":"").(preg_match("~^($sf)\$~", $r["on_update"])?" ON UPDATE $r[on_update]":"");
}

function tar_file($q, $ni) {
	$I=pack("a100a8a8a8a12a12", $q, 644, 0, 0, decoct($ni->size), decoct(time()));
	$fb=8*32;
	for ($t=0; $t<strlen($I); $t++)
		$fb+=ord($I[$t]);
	$I.=sprintf("%06o", $fb)."\0 ";
	echo $I, str_repeat("\0", 512-strlen($I));
	$ni->send();
	echo str_repeat("\0", 511-($ni->size+511)%512);
}

function ini_bytes($Ud) {
	$X=ini_get($Ud);
	switch (strtolower(substr($X, -1))) {
		case'g':
			$X*=1024;
		case'm':
			$X*=1024;
		case'k':
			$X*=1024;
	}

	return $X;
}

function doc_link($bg, $ei="<sup>?</sup>") {
	global $y, $g;
	$nh=$g->server_info;
	$Zi=preg_replace('~^(\d\.?\d).*~s', '\1', $nh);
	$Oi=['sql'=>"https://dev.mysql.com/doc/refman/$Zi/en/", 'sqlite'=>"https://www.sqlite.org/", 'pgsql'=>"https://www.postgresql.org/docs/$Zi/", 'mssql'=>"https://msdn.microsoft.com/library/", 'oracle'=>"https://www.oracle.com/pls/topic/lookup?ctx=db".preg_replace('~^.* (\d+)\.(\d+)\.\d+\.\d+\.\d+.*~s', '\1\2', $nh)."&id=",];
	if (preg_match('~MariaDB~', $nh)) {
		$Oi['sql']="https://mariadb.com/kb/en/library/";
		$bg['sql']=(isset($bg['mariadb'])?$bg['mariadb']:str_replace(".html", "/", $bg['sql']));
	}

	return ($bg[$y]?"<a href='".h($Oi[$y].$bg[$y])."'".target_blank().">$ei</a>":"");
}

function ob_gzencode($P) {
	return gzencode($P);
}

function db_size($l) {
	global $g;
	if (!$g->select_db($l))
		return "?";
	$I=0;
	foreach (table_status() as $R)
		$I+=$R["Data_length"]+$R["Index_length"];

	return format_number($I);
}

function set_utf8mb4($i) {
	global $g;
	static $N=false;
	if (!$N&&preg_match('~\butf8mb4~i', $i)) {
		$N=true;
		echo "SET NAMES ".charset($g).";\n\n";
	}
}

function connect_error() {
	global $b, $g, $qi, $n, $kc;
	if (DB!="") {
		header("HTTP/1.1 404 Not Found");
		page_header(lang(37).": ".h(DB), lang(112), true);
	} else {
		if ($_POST["db"]&&!$n)
			queries_redirect(substr(ME, 0, -1), lang(113), drop_databases($_POST["db"]));
		page_header(lang(114), $n, false);
		echo "<p class='links'>\n";
		foreach (['database'=>lang(115), 'privileges'=>lang(71), 'processlist'=>lang(116), 'variables'=>lang(117), 'status'=>lang(118),] as $z=>$X) {
			if (support($z))
				echo "<a href='".h(ME)."$z='>$X</a>\n";
		}
		echo "<p>".lang(119, $kc[DRIVER], "<b>".h($g->server_info)."</b>", "<b>$g->extension</b>")."\n", "<p>".lang(120, "<b>".h(logged_user())."</b>")."\n";
		$k=$b->databases();
		if ($k) {
			$dh=support("scheme");
			$nb=collations();
			echo "<form action='' method='post'>\n", "<table cellspacing='0' class='checkable'>\n", script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"), "<thead><tr>".(support("database")?"<td>":"")."<th>".lang(37)." - <a href='".h(ME)."refresh=1'>".lang(121)."</a>"."<td>".lang(122)."<td>".lang(123)."<td>".lang(124)." - <a href='".h(ME)."dbsize=1'>".lang(125)."</a>".script("qsl('a').onclick = partial(ajaxSetHtml, '".js_escape(ME)."script=connect');", "")."</thead>\n";
			$k=($_GET["dbsize"]?count_tables($k):array_flip($k));
			foreach ($k as $l=>$S) {
				$Vg=h(ME)."db=".urlencode($l);
				$u=h("Db-".$l);
				echo "<tr".odd().">".(support("database")?"<td>".checkbox("db[]", $l, in_array($l, (array)$_POST["db"]), "", "", "", $u):""), "<th><a href='$Vg' id='$u'>".h($l)."</a>";
				$mb=h(db_collation($l, $nb));
				echo "<td>".(support("database")?"<a href='$Vg".($dh?"&amp;ns=":"")."&amp;database=' title='".lang(67)."'>$mb</a>":$mb), "<td align='right'><a href='$Vg&amp;schema=' id='tables-".h($l)."' title='".lang(70)."'>".($_GET["dbsize"]?$S:"?")."</a>", "<td align='right' id='size-".h($l)."'>".($_GET["dbsize"]?db_size($l):"?"), "\n";
			}
			echo "</table>\n", (support("database")?"<div class='footer'><div>\n"."<fieldset><legend>".lang(126)." <span id='selected'></span></legend><div>\n"."<input type='hidden' name='all' value=''>".script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^db/)); };")."<input type='submit' name='drop' value='".lang(127)."'>".confirm()."\n"."</div></fieldset>\n"."</div></div>\n":""), "<input type='hidden' name='token' value='$qi'>\n", "</form>\n", script("tableCheck();");
		}
	}
	page_footer("db");
}

if (isset($_GET["status"]))
	$_GET["variables"]=$_GET["status"];
if (isset($_GET["import"]))
	$_GET["sql"]=$_GET["import"];
if (!(DB!=""?$g->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")) {
	if (DB!=""||$_GET["refresh"]) {
		restart_session();
		set_session("dbs", null);
	}
	connect_error();
	exit;
}
if (support("scheme")) {
	if (DB!=""&&$_GET["ns"]!=="") {
		if (!isset($_GET["ns"]))
			redirect(preg_replace('~ns=[^&]*&~', '', ME)."ns=".get_schema());
		if (!set_schema($_GET["ns"])) {
			header("HTTP/1.1 404 Not Found");
			page_header(lang(77).": ".h($_GET["ns"]), lang(128), true);
			page_footer("ns");
			exit;
		}
	}
}
$sf="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";

class
TmpFile {

	var $handler;

	var $size;

	function __construct() {
		$this->handler=tmpfile();
	}

	function write($Eb) {
		$this->size+=strlen($Eb);
		fwrite($this->handler, $Eb);
	}

	function send() {
		fseek($this->handler, 0);
		fpassthru($this->handler);
		fclose($this->handler);
	}

}

$Bc="'(?:''|[^'\\\\]|\\\\.)*'";
$Vd="IN|OUT|INOUT";
if (isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])
	$_GET["edit"]=$_GET["select"];
if (isset($_GET["callf"]))
	$_GET["call"]=$_GET["callf"];
if (isset($_GET["function"]))
	$_GET["procedure"]=$_GET["function"];
if (isset($_GET["download"])) {
	$a=$_GET["download"];
	$p=fields($a);
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_", $_GET["where"])).".".friendly_url($_GET["field"]));
	$L=[idf_escape($_GET["field"])];
	$H=$m->select($a, $L, [where($_GET, $p)], $L);
	$J=($H?$H->fetch_row():[]);
	echo $m->value($J[0], $p[$_GET["field"]]);
	exit;
} elseif (isset($_GET["table"])) {
	$a=$_GET["table"];
	$p=fields($a);
	if (!$p)
		$n=error();
	$R=table_status1($a, true);
	$D=$b->tableName($R);
	page_header(($p&&is_view($R)?$R['Engine']=='materialized view'?lang(129):lang(130):lang(131)).": ".($D!=""?$D:h($a)), $n);
	$b->selectLinks($R);
	$tb=$R["Comment"];
	if ($tb!="")
		echo "<p class='nowrap'>".lang(50).": ".h($tb)."\n";
	if ($p)
		$b->tableStructurePrint($p);
	if (!is_view($R)) {
		if (support("indexes")) {
			echo "<h3 id='indexes'>".lang(132)."</h3>\n";
			$x=indexes($a);
			if ($x)
				$b->tableIndexesPrint($x);
			echo '<p class="links"><a href="'.h(ME).'indexes='.urlencode($a).'">'.lang(133)."</a>\n";
		}
		if (fk_support($R)) {
			echo "<h3 id='foreign-keys'>".lang(100)."</h3>\n";
			$jd=foreign_keys($a);
			if ($jd) {
				echo "<table cellspacing='0'>\n", "<thead><tr><th>".lang(134)."<td>".lang(135)."<td>".lang(103)."<td>".lang(102)."<td></thead>\n";
				foreach ($jd as $D=>$r) {
					echo "<tr title='".h($D)."'>", "<th><i>".implode("</i>, <i>", array_map('h', $r["source"]))."</i>", "<td><a href='".h($r["db"]!=""?preg_replace('~db=[^&]*~', "db=".urlencode($r["db"]), ME):($r["ns"]!=""?preg_replace('~ns=[^&]*~', "ns=".urlencode($r["ns"]), ME):ME))."table=".urlencode($r["table"])."'>".($r["db"]!=""?"<b>".h($r["db"])."</b>.":"").($r["ns"]!=""?"<b>".h($r["ns"])."</b>.":"").h($r["table"])."</a>", "(<i>".implode("</i>, <i>", array_map('h', $r["target"]))."</i>)", "<td>".h($r["on_delete"])."\n", "<td>".h($r["on_update"])."\n", '<td><a href="'.h(ME.'foreign='.urlencode($a).'&name='.urlencode($D)).'">'.lang(136).'</a>';
				}
				echo "</table>\n";
			}
			echo '<p class="links"><a href="'.h(ME).'foreign='.urlencode($a).'">'.lang(137)."</a>\n";
		}
	}
	if (support(is_view($R)?"view_trigger":"trigger")) {
		echo "<h3 id='triggers'>".lang(138)."</h3>\n";
		$Bi=triggers($a);
		if ($Bi) {
			echo "<table cellspacing='0'>\n";
			foreach ($Bi as $z=>$X)
				echo "<tr valign='top'><td>".h($X[0])."<td>".h($X[1])."<th>".h($z)."<td><a href='".h(ME.'trigger='.urlencode($a).'&name='.urlencode($z))."'>".lang(136)."</a>\n";
			echo "</table>\n";
		}
		echo '<p class="links"><a href="'.h(ME).'trigger='.urlencode($a).'">'.lang(139)."</a>\n";
	}
} elseif (isset($_GET["schema"])) {
	page_header(lang(70), "", [], h(DB.($_GET["ns"]?".$_GET[ns]":"")));
	$Th=[];
	$Uh=[];
	$ea=($_GET["schema"]?$_GET["schema"]:$_COOKIE["adminer_schema-".str_replace(".", "_", DB)]);
	preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~', $ea, $Fe, PREG_SET_ORDER);
	foreach ($Fe as $t=>$C) {
		$Th[$C[1]]=[$C[2], $C[3]];
		$Uh[]="\n\t'".js_escape($C[1])."': [ $C[2], $C[3] ]";
	}
	$ri=0;
	$Qa=-1;
	$ch=[];
	$Hg=[];
	$ue=[];
	foreach (table_status('', true) as $Q=>$R) {
		if (is_view($R))
			continue;
		$hg=0;
		$ch[$Q]["fields"]=[];
		foreach (fields($Q) as $D=>$o) {
			$hg+=1.25;
			$o["pos"]=$hg;
			$ch[$Q]["fields"][$D]=$o;
		}
		$ch[$Q]["pos"]=($Th[$Q]?$Th[$Q]:[$ri, 0]);
		foreach ($b->foreignKeys($Q) as $X) {
			if (!$X["db"]) {
				$se=$Qa;
				if ($Th[$Q][1]||$Th[$X["table"]][1])
					$se=min(floatval($Th[$Q][1]), floatval($Th[$X["table"]][1]))-1; else$Qa-=.1;
				while ($ue[(string)$se])
					$se-=.0001;
				$ch[$Q]["references"][$X["table"]][(string)$se]=[$X["source"], $X["target"]];
				$Hg[$X["table"]][$Q][(string)$se]=$X["target"];
				$ue[(string)$se]=true;
			}
		}
		$ri=max($ri, $ch[$Q]["pos"][0]+2.5+$hg);
	}
	echo '<div id="schema" style="height: ', $ri, 'em;">
<script', nonce(), '>
qs(\'#schema\').onselectstart = function () { return false; };
var tablePos = {', implode(",", $Uh)."\n", '};
var em = qs(\'#schema\').offsetHeight / ', $ri, ';
document.onmousemove = schemaMousemove;
document.onmouseup = partialArg(schemaMouseup, \'', js_escape(DB), '\');
</script>
';
	foreach ($ch as $D=>$Q) {
		echo "<div class='table' style='top: ".$Q["pos"][0]."em; left: ".$Q["pos"][1]."em;'>", '<a href="'.h(ME).'table='.urlencode($D).'"><b>'.h($D)."</b></a>", script("qsl('div').onmousedown = schemaMousedown;");
		foreach ($Q["fields"] as $o) {
			$X='<span'.type_class($o["type"]).' title="'.h($o["full_type"].($o["null"]?" NULL":'')).'">'.h($o["field"]).'</span>';
			echo "<br>".($o["primary"]?"<i>$X</i>":$X);
		}
		foreach ((array)$Q["references"] as $ai=>$Ig) {
			foreach ($Ig as $se=>$Eg) {
				$te=$se-$Th[$D][1];
				$t=0;
				foreach ($Eg[0] as $yh)
					echo "\n<div class='references' title='".h($ai)."' id='refs$se-".($t++)."' style='left: $te"."em; top: ".$Q["fields"][$yh]["pos"]."em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: ".(-$te)."em;'></div></div>";
			}
		}
		foreach ((array)$Hg[$D] as $ai=>$Ig) {
			foreach ($Ig as $se=>$e) {
				$te=$se-$Th[$D][1];
				$t=0;
				foreach ($e as $Zh)
					echo "\n<div class='references' title='".h($ai)."' id='refd$se-".($t++)."' style='left: $te"."em; top: ".$Q["fields"][$Zh]["pos"]."em; height: 1.25em; background: url(".h(preg_replace("~\\?.*~", "", ME)."?file=arrow.gif) no-repeat right center;&version=4.8.1")."'><div style='height: .5em; border-bottom: 1px solid Gray; width: ".(-$te)."em;'></div></div>";
			}
		}
		echo "\n</div>\n";
	}
	foreach ($ch as $D=>$Q) {
		foreach ((array)$Q["references"] as $ai=>$Ig) {
			foreach ($Ig as $se=>$Eg) {
				$Te=$ri;
				$Je=-10;
				foreach ($Eg[0] as $z=>$yh) {
					$ig=$Q["pos"][0]+$Q["fields"][$yh]["pos"];
					$jg=$ch[$ai]["pos"][0]+$ch[$ai]["fields"][$Eg[1][$z]]["pos"];
					$Te=min($Te, $ig, $jg);
					$Je=max($Je, $ig, $jg);
				}
				echo "<div class='references' id='refl$se' style='left: $se"."em; top: $Te"."em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: ".($Je-$Te)."em;'></div></div>\n";
			}
		}
	}
	echo '</div>
<p class="links"><a href="', h(ME."schema=".urlencode($ea)), '" id="schema-link">', lang(140), '</a>
';
} elseif (isset($_GET["dump"])) {
	$a=$_GET["dump"];
	if ($_POST&&!$n) {
		$Hb="";
		foreach (["output", "format", "db_style", "routines", "events", "table_style", "auto_increment", "triggers", "data_style"] as $z)
			$Hb.="&$z=".urlencode($_POST[$z]);
		cookie("adminer_export", substr($Hb, 1));
		$S=array_flip((array)$_POST["tables"])+array_flip((array)$_POST["data"]);
		$Oc=dump_headers((count($S)==1?key($S):DB), (DB==""||count($S)>1));
		$de=preg_match('~sql~', $_POST["format"]);
		if ($de) {
			echo "-- Adminer $ia ".$kc[DRIVER]." ".str_replace("\n", " ", $g->server_info)." dump\n\n";
			if ($y=="sql") {
				echo "SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
".($_POST["data_style"]?"SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
";
				$g->query("SET time_zone = '+00:00'");
				$g->query("SET sql_mode = ''");
			}
		}
		$Kh=$_POST["db_style"];
		$k=[DB];
		if (DB=="") {
			$k=$_POST["databases"];
			if (is_string($k))
				$k=explode("\n", rtrim(str_replace("\r", "", $k), "\n"));
		}
		foreach ((array)$k as $l) {
			$b->dumpDatabase($l);
			if ($g->select_db($l)) {
				if ($de&&preg_match('~CREATE~', $Kh)&&($i=$g->result("SHOW CREATE DATABASE ".idf_escape($l), 1))) {
					set_utf8mb4($i);
					if ($Kh=="DROP+CREATE")
						echo "DROP DATABASE IF EXISTS ".idf_escape($l).";\n";
					echo "$i;\n";
				}
				if ($de) {
					if ($Kh)
						echo use_sql($l).";\n\n";
					$Lf="";
					if ($_POST["routines"]) {
						foreach (["FUNCTION", "PROCEDURE"] as $Wg) {
							foreach (get_rows("SHOW $Wg STATUS WHERE Db = ".q($l), null, "-- ") as $J) {
								$i=remove_definer($g->result("SHOW CREATE $Wg ".idf_escape($J["Name"]), 2));
								set_utf8mb4($i);
								$Lf.=($Kh!='DROP+CREATE'?"DROP $Wg IF EXISTS ".idf_escape($J["Name"]).";;\n":"")."$i;;\n\n";
							}
						}
					}
					if ($_POST["events"]) {
						foreach (get_rows("SHOW EVENTS", null, "-- ") as $J) {
							$i=remove_definer($g->result("SHOW CREATE EVENT ".idf_escape($J["Name"]), 3));
							set_utf8mb4($i);
							$Lf.=($Kh!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($J["Name"]).";;\n":"")."$i;;\n\n";
						}
					}
					if ($Lf)
						echo "DELIMITER ;;\n\n$Lf"."DELIMITER ;\n\n";
				}
				if ($_POST["table_style"]||$_POST["data_style"]) {
					$bj=[];
					foreach (table_status('', true) as $D=>$R) {
						$Q=(DB==""||in_array($D, (array)$_POST["tables"]));
						$Rb=(DB==""||in_array($D, (array)$_POST["data"]));
						if ($Q||$Rb) {
							if ($Oc=="tar") {
								$ni=new
								TmpFile;
								ob_start([$ni, 'write'], 1e5);
							}
							$b->dumpTable($D, ($Q?$_POST["table_style"]:""), (is_view($R)?2:0));
							if (is_view($R))
								$bj[]=$D; elseif ($Rb) {
								$p=fields($D);
								$b->dumpData($D, $_POST["data_style"], "SELECT *".convert_fields($p, $p)." FROM ".table($D));
							}
							if ($de&&$_POST["triggers"]&&$Q&&($Bi=trigger_sql($D)))
								echo "\nDELIMITER ;;\n$Bi\nDELIMITER ;\n";
							if ($Oc=="tar") {
								ob_end_flush();
								tar_file((DB!=""?"":"$l/")."$D.csv", $ni);
							} elseif ($de)
								echo "\n";
						}
					}
					if (function_exists('foreign_keys_sql')) {
						foreach (table_status('', true) as $D=>$R) {
							$Q=(DB==""||in_array($D, (array)$_POST["tables"]));
							if ($Q&&!is_view($R))
								echo foreign_keys_sql($D);
						}
					}
					foreach ($bj as $aj)
						$b->dumpTable($aj, $_POST["table_style"], 1);
					if ($Oc=="tar")
						echo pack("x512");
				}
			}
		}
		if ($de)
			echo "-- ".$g->result("SELECT NOW()")."\n";
		exit;
	}
	page_header(lang(73), $n, ($_GET["export"]!=""?["table"=>$_GET["export"]]:[]), h(DB));
	echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
';
	$Vb=['', 'USE', 'DROP+CREATE', 'CREATE'];
	$Vh=['', 'DROP+CREATE', 'CREATE'];
	$Sb=['', 'TRUNCATE+INSERT', 'INSERT'];
	if ($y=="sql")
		$Sb[]='INSERT+UPDATE';
	parse_str($_COOKIE["adminer_export"], $J);
	if (!$J)
		$J=["output"=>"text", "format"=>"sql", "db_style"=>(DB!=""?"":"CREATE"), "table_style"=>"DROP+CREATE", "data_style"=>"INSERT"];
	if (!isset($J["events"])) {
		$J["routines"]=$J["events"]=($_GET["dump"]=="");
		$J["triggers"]=$J["table_style"];
	}
	echo "<tr><th>".lang(141)."<td>".html_select("output", $b->dumpOutput(), $J["output"], 0)."\n";
	echo "<tr><th>".lang(142)."<td>".html_select("format", $b->dumpFormat(), $J["format"], 0)."\n";
	echo($y=="sqlite"?"":"<tr><th>".lang(37)."<td>".html_select('db_style', $Vb, $J["db_style"]).(support("routine")?checkbox("routines", 1, $J["routines"], lang(143)):"").(support("event")?checkbox("events", 1, $J["events"], lang(144)):"")), "<tr><th>".lang(123)."<td>".html_select('table_style', $Vh, $J["table_style"]).checkbox("auto_increment", 1, $J["auto_increment"], lang(51)).(support("trigger")?checkbox("triggers", 1, $J["triggers"], lang(138)):""), "<tr><th>".lang(145)."<td>".html_select('data_style', $Sb, $J["data_style"]), '</table>
<p><input type="submit" value="', lang(73), '">
<input type="hidden" name="token" value="', $qi, '">

<table cellspacing="0">
', script("qsl('table').onclick = dumpClick;");
	$mg=[];
	if (DB!="") {
		$db=($a!=""?"":" checked");
		echo "<thead><tr>", "<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$db>".lang(123)."</label>".script("qs('#check-tables').onclick = partial(formCheck, /^tables\\[/);", ""), "<th style='text-align: right;'><label class='block'>".lang(145)."<input type='checkbox' id='check-data'$db></label>".script("qs('#check-data').onclick = partial(formCheck, /^data\\[/);", ""), "</thead>\n";
		$bj="";
		$Wh=tables_list();
		foreach ($Wh as $D=>$T) {
			$lg=preg_replace('~_.*~', '', $D);
			$db=($a==""||$a==(substr($a, -1)=="%"?"$lg%":$D));
			$pg="<tr><td>".checkbox("tables[]", $D, $db, $D, "", "block");
			if ($T!==null&&!preg_match('~table~i', $T))
				$bj.="$pg\n"; else
				echo "$pg<td align='right'><label class='block'><span id='Rows-".h($D)."'></span>".checkbox("data[]", $D, $db)."</label>\n";
			$mg[$lg]++;
		}
		echo $bj;
		if ($Wh)
			echo script("ajaxSetHtml('".js_escape(ME)."script=db');");
	} else {
		echo "<thead><tr><th style='text-align: left;'>", "<label class='block'><input type='checkbox' id='check-databases'".($a==""?" checked":"").">".lang(37)."</label>", script("qs('#check-databases').onclick = partial(formCheck, /^databases\\[/);", ""), "</thead>\n";
		$k=$b->databases();
		if ($k) {
			foreach ($k as $l) {
				if (!information_schema($l)) {
					$lg=preg_replace('~_.*~', '', $l);
					echo "<tr><td>".checkbox("databases[]", $l, $a==""||$a=="$lg%", $l, "", "block")."\n";
					$mg[$lg]++;
				}
			}
		} else
			echo "<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";
	}
	echo '</table>
</form>
';
	$bd=true;
	foreach ($mg as $z=>$X) {
		if ($z!=""&&$X>1) {
			echo ($bd?"<p>":" ")."<a href='".h(ME)."dump=".urlencode("$z%")."'>".h($z)."</a>";
			$bd=false;
		}
	}
} elseif (isset($_GET["privileges"])) {
	page_header(lang(71));
	echo '<p class="links"><a href="'.h(ME).'user=">'.lang(146)."</a>";
	$H=$g->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");
	$qd=$H;
	if (!$H)
		$H=$g->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");
	echo "<form action=''><p>\n";
	hidden_fields_get();
	echo "<input type='hidden' name='db' value='".h(DB)."'>\n", ($qd?"":"<input type='hidden' name='grant' value=''>\n"), "<table cellspacing='0'>\n", "<thead><tr><th>".lang(35)."<th>".lang(34)."<th></thead>\n";
	while ($J=$H->fetch_assoc())
		echo '<tr'.odd().'><td>'.h($J["User"])."<td>".h($J["Host"]).'<td><a href="'.h(ME.'user='.urlencode($J["User"]).'&host='.urlencode($J["Host"])).'">'.lang(10)."</a>\n";
	if (!$qd||DB!="")
		echo "<tr".odd()."><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='".lang(10)."'>\n";
	echo "</table>\n", "</form>\n";
} elseif (isset($_GET["sql"])) {
	if (!$n&&$_POST["export"]) {
		dump_headers("sql");
		$b->dumpTable("", "");
		$b->dumpData("", "table", $_POST["query"]);
		exit;
	}
	restart_session();
	$Dd=&get_session("queries");
	$Cd=&$Dd[DB];
	if (!$n&&$_POST["clear"]) {
		$Cd=[];
		redirect(remove_from_uri("history"));
	}
	page_header((isset($_GET["import"])?lang(72):lang(64)), $n);
	if (!$n&&$_POST) {
		$nd=false;
		if (!isset($_GET["import"]))
			$G=$_POST["query"]; elseif ($_POST["webfile"]) {
			$Bh=$b->importServerPath();
			$nd=@fopen((file_exists($Bh)?$Bh:"compress.zlib://$Bh.gz"), "rb");
			$G=($nd?fread($nd, 1e6):false);
		} else$G=get_file("sql_file", true);
		if (is_string($G)) {
			if (function_exists('memory_get_usage'))
				@ini_set("memory_limit", max(ini_bytes("memory_limit"), 2*strlen($G)+memory_get_usage()+8e6));
			if ($G!=""&&strlen($G)<1e6) {
				$xg=$G.(preg_match("~;[ \t\r\n]*\$~", $G)?"":";");
				if (!$Cd||reset(end($Cd))!=$xg) {
					restart_session();
					$Cd[]=[$xg, time()];
					set_session("queries", $Dd);
					stop_session();
				}
			}
			$zh="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
			$cc=";";
			$kf=0;
			$zc=true;
			$h=connect();
			if (is_object($h)&&DB!="") {
				$h->select_db(DB);
				if ($_GET["ns"]!="")
					set_schema($_GET["ns"], $h);
			}
			$sb=0;
			$Dc=[];
			$Sf='[\'"'.($y=="sql"?'`#':($y=="sqlite"?'`[':($y=="mssql"?'[':''))).']|/\*|-- |$'.($y=="pgsql"?'|\$[^$]*\$':'');
			$si=microtime(true);
			parse_str($_COOKIE["adminer_export"], $ya);
			$qc=$b->dumpFormat();
			unset($qc["sql"]);
			while ($G!="") {
				if (!$kf&&preg_match("~^$zh*+DELIMITER\\s+(\\S+)~i", $G, $C)) {
					$cc=$C[1];
					$G=substr($G, strlen($C[0]));
				} else {
					preg_match('('.preg_quote($cc)."\\s*|$Sf)", $G, $C, PREG_OFFSET_CAPTURE, $kf);
					[$ld, $hg]=$C[0];
					if (!$ld&&$nd&&!feof($nd))
						$G.=fread($nd, 1e5); else {
						if (!$ld&&rtrim($G)=="")
							break;
						$kf=$hg+strlen($ld);
						if ($ld&&rtrim($ld)!=$cc) {
							while (preg_match('('.($ld=='/*'?'\*/':($ld=='['?']':(preg_match('~^-- |^#~', $ld)?"\n":preg_quote($ld)."|\\\\."))).'|$)s', $G, $C, PREG_OFFSET_CAPTURE, $kf)) {
								$ah=$C[0][0];
								if (!$ah&&$nd&&!feof($nd))
									$G.=fread($nd, 1e5); else {
									$kf=$C[0][1]+strlen($ah);
									if ($ah[0]!="\\")
										break;
								}
							}
						} else {
							$zc=false;
							$xg=substr($G, 0, $hg);
							$sb++;
							$pg="<pre id='sql-$sb'><code class='jush-$y'>".$b->sqlCommandQuery($xg)."</code></pre>\n";
							if ($y=="sqlite"&&preg_match("~^$zh*+ATTACH\\b~i", $xg, $C)) {
								echo $pg, "<p class='error'>".lang(147)."\n";
								$Dc[]=" <a href='#sql-$sb'>$sb</a>";
								if ($_POST["error_stops"])
									break;
							} else {
								if (!$_POST["only_errors"]) {
									echo $pg;
									ob_flush();
									flush();
								}
								$Fh=microtime(true);
								if ($g->multi_query($xg)&&is_object($h)&&preg_match("~^$zh*+USE\\b~i", $xg))
									$h->query($xg);
								do {
									$H=$g->store_result();
									if ($g->error) {
										echo($_POST["only_errors"]?$pg:""), "<p class='error'>".lang(148).($g->errno?" ($g->errno)":"").": ".error()."\n";
										$Dc[]=" <a href='#sql-$sb'>$sb</a>";
										if ($_POST["error_stops"])
											break
											2;
									} else {
										$gi=" <span class='time'>(".format_time($Fh).")</span>".(strlen($xg)<1000?" <a href='".h(ME)."sql=".urlencode(trim($xg))."'>".lang(10)."</a>":"");
										$_a=$g->affected_rows;
										$ej=($_POST["only_errors"]?"":$m->warnings());
										$fj="warnings-$sb";
										if ($ej)
											$gi.=", <a href='#$fj'>".lang(46)."</a>".script("qsl('a').onclick = partial(toggle, '$fj');", "");
										$Lc=null;
										$Mc="explain-$sb";
										if (is_object($H)) {
											$_=$_POST["limit"];
											$Ef=select($H, $h, [], $_);
											if (!$_POST["only_errors"]) {
												echo "<form action='' method='post'>\n";
												$gf=$H->num_rows;
												echo "<p>".($gf?($_&&$gf>$_?lang(149, $_):"").lang(150, $gf):""), $gi;
												if ($h&&preg_match("~^($zh|\\()*+SELECT\\b~i", $xg)&&($Lc=explain($h, $xg)))
													echo ", <a href='#$Mc'>Explain</a>".script("qsl('a').onclick = partial(toggle, '$Mc');", "");
												$u="export-$sb";
												echo ", <a href='#$u'>".lang(73)."</a>".script("qsl('a').onclick = partial(toggle, '$u');", "")."<span id='$u' class='hidden'>: ".html_select("output", $b->dumpOutput(), $ya["output"])." ".html_select("format", $qc, $ya["format"])."<input type='hidden' name='query' value='".h($xg)."'>"." <input type='submit' name='export' value='".lang(73)."'><input type='hidden' name='token' value='$qi'></span>\n"."</form>\n";
											}
										} else {
											if (preg_match("~^$zh*+(CREATE|DROP|ALTER)$zh++(DATABASE|SCHEMA)\\b~i", $xg)) {
												restart_session();
												set_session("dbs", null);
												stop_session();
											}
											if (!$_POST["only_errors"])
												echo "<p class='message' title='".h($g->info)."'>".lang(151, $_a)."$gi\n";
										}
										echo($ej?"<div id='$fj' class='hidden'>\n$ej</div>\n":"");
										if ($Lc) {
											echo "<div id='$Mc' class='hidden'>\n";
											select($Lc, $h, $Ef);
											echo "</div>\n";
										}
									}
									$Fh=microtime(true);
								} while ($g->next_result());
							}
							$G=substr($G, $kf);
							$kf=0;
						}
					}
				}
			}
			if ($zc)
				echo "<p class='message'>".lang(152)."\n"; elseif ($_POST["only_errors"]) {
				echo "<p class='message'>".lang(153, $sb-count($Dc)), " <span class='time'>(".format_time($si).")</span>\n";
			} elseif ($Dc&&$sb>1)
				echo "<p class='error'>".lang(148).": ".implode("", $Dc)."\n";
		} else
			echo "<p class='error'>".upload_error($G)."\n";
	}
	echo '
<form action="" method="post" enctype="multipart/form-data" id="form">
';
	$Jc="<input type='submit' value='".lang(154)."' title='Ctrl+Enter'>";
	if (!isset($_GET["import"])) {
		$xg=$_GET["sql"];
		if ($_POST)
			$xg=$_POST["query"]; elseif ($_GET["history"]=="all")
			$xg=$Cd;
		elseif ($_GET["history"]!="")
			$xg=$Cd[$_GET["history"]][0];
		echo "<p>";
		textarea("query", $xg, 20);
		echo script(($_POST?"":"qs('textarea').focus();\n")."qs('#form').onsubmit = partial(sqlSubmit, qs('#form'), '".js_escape(remove_from_uri("sql|limit|error_stops|only_errors|history"))."');"), "<p>$Jc\n", lang(155).": <input type='number' name='limit' class='size' value='".h($_POST?$_POST["limit"]:$_GET["limit"])."'>\n";
	} else {
		echo "<fieldset><legend>".lang(156)."</legend><div>";
		$wd=(extension_loaded("zlib")?"[.gz]":"");
		echo(ini_bool("file_uploads")?"SQL$wd (&lt; ".ini_get("upload_max_filesize")."B): <input type='file' name='sql_file[]' multiple>\n$Jc":lang(157)), "</div></fieldset>\n";
		$Kd=$b->importServerPath();
		if ($Kd) {
			echo "<fieldset><legend>".lang(158)."</legend><div>", lang(159, "<code>".h($Kd)."$wd</code>"), ' <input type="submit" name="webfile" value="'.lang(160).'">', "</div></fieldset>\n";
		}
		echo "<p>";
	}
	echo checkbox("error_stops", 1, ($_POST?$_POST["error_stops"]:isset($_GET["import"])||$_GET["error_stops"]), lang(161))."\n", checkbox("only_errors", 1, ($_POST?$_POST["only_errors"]:isset($_GET["import"])||$_GET["only_errors"]), lang(162))."\n", "<input type='hidden' name='token' value='$qi'>\n";
	if (!isset($_GET["import"])&&$Cd) {
		print_fieldset("history", lang(163), $_GET["history"]!="");
		for ($X=end($Cd); $X; $X=prev($Cd)) {
			$z=key($Cd);
			[$xg, $gi, $uc]=$X;
			echo '<a href="'.h(ME."sql=&history=$z").'">'.lang(10)."</a>"." <span class='time' title='".@date('Y-m-d', $gi)."'>".@date("H:i:s", $gi)."</span>"." <code class='jush-$y'>".shorten_utf8(ltrim(str_replace("\n", " ", str_replace("\r", "", preg_replace('~^(#|-- ).*~m', '', $xg)))), 80, "</code>").($uc?" <span class='time'>($uc)</span>":"")."<br>\n";
		}
		echo "<input type='submit' name='clear' value='".lang(164)."'>\n", "<a href='".h(ME."sql=&history=all")."'>".lang(165)."</a>\n", "</div></fieldset>\n";
	}
	echo '</form>
';
} elseif (isset($_GET["edit"])) {
	$a=$_GET["edit"];
	$p=fields($a);
	$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0], $p):""):where($_GET, $p));
	$Li=(isset($_GET["select"])?$_POST["edit"]:$Z);
	foreach ($p as $D=>$o) {
		if (!isset($o["privileges"][$Li?"update":"insert"])||$b->fieldName($o)==""||$o["generated"])
			unset($p[$D]);
	}
	if ($_POST&&!$n&&!isset($_GET["select"])) {
		$B=$_POST["referer"];
		if ($_POST["insert"])
			$B=($Li?null:$_SERVER["REQUEST_URI"]); elseif (!preg_match('~^.+&select=.+$~', $B))
			$B=ME."select=".urlencode($a);
		$x=indexes($a);
		$Gi=unique_array($_GET["where"], $x);
		$_g="\nWHERE $Z";
		if (isset($_POST["delete"]))
			queries_redirect($B, lang(166), $m->delete($a, $_g, !$Gi)); else {
			$N=[];
			foreach ($p as $D=>$o) {
				$X=process_input($o);
				if ($X!==false&&$X!==null)
					$N[idf_escape($D)]=$X;
			}
			if ($Li) {
				if (!$N)
					redirect($B);
				queries_redirect($B, lang(167), $m->update($a, $N, $_g, !$Gi));
				if (is_ajax()) {
					page_headers();
					page_messages($n);
					exit;
				}
			} else {
				$H=$m->insert($a, $N);
				$re=($H?last_id():0);
				queries_redirect($B, lang(168, ($re?" $re":"")), $H);
			}
		}
	}
	$J=null;
	if ($_POST["save"])
		$J=(array)$_POST["fields"]; elseif ($Z) {
		$L=[];
		foreach ($p as $D=>$o) {
			if (isset($o["privileges"]["select"])) {
				$Ga=convert_field($o);
				if ($_POST["clone"]&&$o["auto_increment"])
					$Ga="''";
				if ($y=="sql"&&preg_match("~enum|set~", $o["type"]))
					$Ga="1*".idf_escape($D);
				$L[]=($Ga?"$Ga AS ":"").idf_escape($D);
			}
		}
		$J=[];
		if (!support("table"))
			$L=["*"];
		if ($L) {
			$H=$m->select($a, $L, [$Z], $L, [], (isset($_GET["select"])?2:1));
			if (!$H)
				$n=error(); else {
				$J=$H->fetch_assoc();
				if (!$J)
					$J=false;
			}
			if (isset($_GET["select"])&&(!$J||$H->fetch_assoc()))
				$J=null;
		}
	}
	if (!support("table")&&!$p) {
		if (!$Z) {
			$H=$m->select($a, ["*"], $Z, ["*"]);
			$J=($H?$H->fetch_assoc():false);
			if (!$J)
				$J=[$m->primary=>""];
		}
		if ($J) {
			foreach ($J as $z=>$X) {
				if (!$Z)
					$J[$z]=null;
				$p[$z]=["field"=>$z, "null"=>($z!=$m->primary), "auto_increment"=>($z==$m->primary)];
			}
		}
	}
	edit_form($a, $p, $J, $Li);
} elseif (isset($_GET["create"])) {
	$a=$_GET["create"];
	$Uf=[];
	foreach (['HASH', 'LINEAR HASH', 'KEY', 'LINEAR KEY', 'RANGE', 'LIST'] as $z)
		$Uf[$z]=$z;
	$Gg=referencable_primary($a);
	$jd=[];
	foreach ($Gg as $Rh=>$o)
		$jd[str_replace("`", "``", $Rh)."`".str_replace("`", "``", $o["field"])]=$Rh;
	$Hf=[];
	$R=[];
	if ($a!="") {
		$Hf=fields($a);
		$R=table_status($a);
		if (!$R)
			$n=lang(9);
	}
	$J=$_POST;
	$J["fields"]=(array)$J["fields"];
	if ($J["auto_increment_col"])
		$J["fields"][$J["auto_increment_col"]]["auto_increment"]=true;
	if ($_POST)
		set_adminer_settings(["comments"=>$_POST["comments"], "defaults"=>$_POST["defaults"]]);
	if ($_POST&&!process_fields($J["fields"])&&!$n) {
		if ($_POST["drop"])
			queries_redirect(substr(ME, 0, -1), lang(169), drop_tables([$a])); else {
			$p=[];
			$Da=[];
			$Pi=false;
			$hd=[];
			$Gf=reset($Hf);
			$Ba=" FIRST";
			foreach ($J["fields"] as $z=>$o) {
				$r=$jd[$o["type"]];
				$Ci=($r!==null?$Gg[$r]:$o);
				if ($o["field"]!="") {
					if (!$o["has_default"])
						$o["default"]=null;
					if ($z==$J["auto_increment_col"])
						$o["auto_increment"]=true;
					$ug=process_field($o, $Ci);
					$Da[]=[$o["orig"], $ug, $Ba];
					if (!$Gf||$ug!=process_field($Gf, $Gf)) {
						$p[]=[$o["orig"], $ug, $Ba];
						if ($o["orig"]!=""||$Ba)
							$Pi=true;
					}
					if ($r!==null)
						$hd[idf_escape($o["field"])]=($a!=""&&$y!="sqlite"?"ADD":" ").format_foreign_key(['table'=>$jd[$o["type"]], 'source'=>[$o["field"]], 'target'=>[$Ci["field"]], 'on_delete'=>$o["on_delete"],]);
					$Ba=" AFTER ".idf_escape($o["field"]);
				} elseif ($o["orig"]!="") {
					$Pi=true;
					$p[]=[$o["orig"]];
				}
				if ($o["orig"]!="") {
					$Gf=next($Hf);
					if (!$Gf)
						$Ba="";
				}
			}
			$Wf="";
			if ($Uf[$J["partition_by"]]) {
				$Xf=[];
				if ($J["partition_by"]=='RANGE'||$J["partition_by"]=='LIST') {
					foreach (array_filter($J["partition_names"]) as $z=>$X) {
						$Y=$J["partition_values"][$z];
						$Xf[]="\n  PARTITION ".idf_escape($X)." VALUES ".($J["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");
					}
				}
				$Wf.="\nPARTITION BY $J[partition_by]($J[partition])".($Xf?" (".implode(",", $Xf)."\n)":($J["partitions"]?" PARTITIONS ".(+$J["partitions"]):""));
			} elseif (support("partitioning")&&preg_match("~partitioned~", $R["Create_options"]))
				$Wf.="\nREMOVE PARTITIONING";
			$Ne=lang(170);
			if ($a=="") {
				cookie("adminer_engine", $J["Engine"]);
				$Ne=lang(171);
			}
			$D=trim($J["name"]);
			queries_redirect(ME.(support("table")?"table=":"select=").urlencode($D), $Ne, alter_table($a, $D, ($y=="sqlite"&&($Pi||$hd)?$Da:$p), $hd, ($J["Comment"]!=$R["Comment"]?$J["Comment"]:null), ($J["Engine"]&&$J["Engine"]!=$R["Engine"]?$J["Engine"]:""), ($J["Collation"]&&$J["Collation"]!=$R["Collation"]?$J["Collation"]:""), ($J["Auto_increment"]!=""?number($J["Auto_increment"]):""), $Wf));
		}
	}
	page_header(($a!=""?lang(44):lang(74)), $n, ["table"=>$a], h($a));
	if (!$_POST) {
		$J=["Engine"=>$_COOKIE["adminer_engine"], "fields"=>[["field"=>"", "type"=>(isset($U["int"])?"int":(isset($U["integer"])?"integer":"")), "on_update"=>""]], "partition_names"=>[""],];
		if ($a!="") {
			$J=$R;
			$J["name"]=$a;
			$J["fields"]=[];
			if (!$_GET["auto_increment"])
				$J["Auto_increment"]="";
			foreach ($Hf as $o) {
				$o["has_default"]=isset($o["default"]);
				$J["fields"][]=$o;
			}
			if (support("partitioning")) {
				$od="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($a);
				$H=$g->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $od ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");
				[$J["partition_by"], $J["partitions"], $J["partition"]]=$H->fetch_row();
				$Xf=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $od AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");
				$Xf[""]="";
				$J["partition_names"]=array_keys($Xf);
				$J["partition_values"]=array_values($Xf);
			}
		}
	}
	$nb=collations();
	$Ac=engines();
	foreach ($Ac as $_c) {
		if (!strcasecmp($_c, $J["Engine"])) {
			$J["Engine"]=$_c;
			break;
		}
	}
	echo '
<form action="" method="post" id="form">
<p>
';
	if (support("columns")||$a=="") {
		echo lang(172), ': <input name="name" data-maxlength="64" value="', h($J["name"]), '" autocapitalize="off">
';
		if ($a==""&&!$_POST)
			echo script("focus(qs('#form')['name']);");
		echo($Ac?"<select name='Engine'>".optionlist([""=>"(".lang(173).")"]+$Ac, $J["Engine"])."</select>".on_help("getTarget(event).value", 1).script("qsl('select').onchange = helpClose;"):""), ' ', ($nb&&!preg_match("~sqlite|mssql~", $y)?html_select("Collation", [""=>"(".lang(101).")"]+$nb, $J["Collation"]):""), ' <input type="submit" value="', lang(14), '">
';
	}
	echo '
';
	if (support("columns")) {
		echo '<div class="scrollable">
<table cellspacing="0" id="edit-fields" class="nowrap">
';
		edit_fields($J["fields"], $nb, "TABLE", $jd);
		echo '</table>
', script("editFields();"), '</div>
<p>
', lang(51), ': <input type="number" name="Auto_increment" size="6" value="', h($J["Auto_increment"]), '">
', checkbox("defaults", 1, ($_POST?$_POST["defaults"]:adminer_setting("defaults")), lang(174), "columnShow(this.checked, 5)", "jsonly"), (support("comment")?checkbox("comments", 1, ($_POST?$_POST["comments"]:adminer_setting("comments")), lang(50), "editingCommentsClick(this, true);", "jsonly").' <input name="Comment" value="'.h($J["Comment"]).'" data-maxlength="'.(min_version(5.5)?2048:60).'">':''), '<p>
<input type="submit" value="', lang(14), '">
';
	}
	echo '
';
	if ($a!="") {
		echo '<input type="submit" name="drop" value="', lang(127), '">', confirm(lang(175, $a));
	}
	if (support("partitioning")) {
		$Vf=preg_match('~RANGE|LIST~', $J["partition_by"]);
		print_fieldset("partition", lang(176), $J["partition_by"]);
		echo '<p>
', "<select name='partition_by'>".optionlist([""=>""]+$Uf, $J["partition_by"])."</select>".on_help("getTarget(event).value.replace(/./, 'PARTITION BY \$&')", 1).script("qsl('select').onchange = partitionByChange;"), '(<input name="partition" value="', h($J["partition"]), '">)
', lang(177), ': <input type="number" name="partitions" class="size', ($Vf||!$J["partition_by"]?" hidden":""), '" value="', h($J["partitions"]), '">
<table cellspacing="0" id="partition-table"', ($Vf?"":" class='hidden'"), '>
<thead><tr><th>', lang(178), '<th>', lang(179), '</thead>
';
		foreach ($J["partition_names"] as $z=>$X) {
			echo '<tr>', '<td><input name="partition_names[]" value="'.h($X).'" autocapitalize="off">', ($z==count($J["partition_names"])-1?script("qsl('input').oninput = partitionNameChange;"):''), '<td><input name="partition_values[]" value="'.h($J["partition_values"][$z]).'">';
		}
		echo '</table>
</div></fieldset>
';
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["indexes"])) {
	$a=$_GET["indexes"];
	$Nd=["PRIMARY", "UNIQUE", "INDEX"];
	$R=table_status($a, true);
	if (preg_match('~MyISAM|M?aria'.(min_version(5.6, '10.0.5')?'|InnoDB':'').'~i', $R["Engine"]))
		$Nd[]="FULLTEXT";
	if (preg_match('~MyISAM|M?aria'.(min_version(5.7, '10.2.2')?'|InnoDB':'').'~i', $R["Engine"]))
		$Nd[]="SPATIAL";
	$x=indexes($a);
	$ng=[];
	if ($y=="mongo") {
		$ng=$x["_id_"];
		unset($Nd[0]);
		unset($x["_id_"]);
	}
	$J=$_POST;
	if ($_POST&&!$n&&!$_POST["add"]&&!$_POST["drop_col"]) {
		$c=[];
		foreach ($J["indexes"] as $w) {
			$D=$w["name"];
			if (in_array($w["type"], $Nd)) {
				$e=[];
				$xe=[];
				$ec=[];
				$N=[];
				ksort($w["columns"]);
				foreach ($w["columns"] as $z=>$d) {
					if ($d!="") {
						$we=$w["lengths"][$z];
						$dc=$w["descs"][$z];
						$N[]=idf_escape($d).($we?"(".(+$we).")":"").($dc?" DESC":"");
						$e[]=$d;
						$xe[]=($we?$we:null);
						$ec[]=$dc;
					}
				}
				if ($e) {
					$Kc=$x[$D];
					if ($Kc) {
						ksort($Kc["columns"]);
						ksort($Kc["lengths"]);
						ksort($Kc["descs"]);
						if ($w["type"]==$Kc["type"]&&array_values($Kc["columns"])===$e&&(!$Kc["lengths"]||array_values($Kc["lengths"])===$xe)&&array_values($Kc["descs"])===$ec) {
							unset($x[$D]);
							continue;
						}
					}
					$c[]=[$w["type"], $D, $N];
				}
			}
		}
		foreach ($x as $D=>$Kc)
			$c[]=[$Kc["type"], $D, "DROP"];
		if (!$c)
			redirect(ME."table=".urlencode($a));
		queries_redirect(ME."table=".urlencode($a), lang(180), alter_indexes($a, $c));
	}
	page_header(lang(132), $n, ["table"=>$a], h($a));
	$p=array_keys(fields($a));
	if ($_POST["add"]) {
		foreach ($J["indexes"] as $z=>$w) {
			if ($w["columns"][count($w["columns"])]!="")
				$J["indexes"][$z]["columns"][]="";
		}
		$w=end($J["indexes"]);
		if ($w["type"]||array_filter($w["columns"], 'strlen'))
			$J["indexes"][]=["columns"=>[1=>""]];
	}
	if (!$J) {
		foreach ($x as $z=>$w) {
			$x[$z]["name"]=$z;
			$x[$z]["columns"][]="";
		}
		$x[]=["columns"=>[1=>""]];
		$J["indexes"]=$x;
	}
	echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
<thead><tr>
<th id="label-type">', lang(181), '<th><input type="submit" class="wayoff">', lang(182), '<th id="label-name">', lang(183), '<th><noscript>', "<input type='image' class='icon' name='add[0]' src='".h(preg_replace("~\\?.*~", "", ME)."?file=plus.gif&version=4.8.1")."' alt='+' title='".lang(108)."'>", '</noscript>
</thead>
';
	if ($ng) {
		echo "<tr><td>PRIMARY<td>";
		foreach ($ng["columns"] as $z=>$d) {
			echo select_input(" disabled", $p, $d), "<label><input disabled type='checkbox'>".lang(59)."</label> ";
		}
		echo "<td><td>\n";
	}
	$ge=1;
	foreach ($J["indexes"] as $w) {
		if (!$_POST["drop_col"]||$ge!=key($_POST["drop_col"])) {
			echo "<tr><td>".html_select("indexes[$ge][type]", [-1=>""]+$Nd, $w["type"], ($ge==count($J["indexes"])?"indexesAddRow.call(this);":1), "label-type"), "<td>";
			ksort($w["columns"]);
			$t=1;
			foreach ($w["columns"] as $z=>$d) {
				echo "<span>".select_input(" name='indexes[$ge][columns][$t]' title='".lang(48)."'", ($p?array_combine($p, $p):$p), $d, "partial(".($t==count($w["columns"])?"indexesAddColumn":"indexesChangeColumn").", '".js_escape($y=="sql"?"":$_GET["indexes"]."_")."')"), ($y=="sql"||$y=="mssql"?"<input type='number' name='indexes[$ge][lengths][$t]' class='size' value='".h($w["lengths"][$z])."' title='".lang(106)."'>":""), (support("descidx")?checkbox("indexes[$ge][descs][$t]", 1, $w["descs"][$z], lang(59)):""), " </span>";
				$t++;
			}
			echo "<td><input name='indexes[$ge][name]' value='".h($w["name"])."' autocapitalize='off' aria-labelledby='label-name'>\n", "<td><input type='image' class='icon' name='drop_col[$ge]' src='".h(preg_replace("~\\?.*~", "", ME)."?file=cross.gif&version=4.8.1")."' alt='x' title='".lang(111)."'>".script("qsl('input').onclick = partial(editingRemoveRow, 'indexes\$1[type]');");
		}
		$ge++;
	}
	echo '</table>
</div>
<p>
<input type="submit" value="', lang(14), '">
<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["database"])) {
	$J=$_POST;
	if ($_POST&&!$n&&!isset($_POST["add_x"])) {
		$D=trim($J["name"]);
		if ($_POST["drop"]) {
			$_GET["db"]="";
			queries_redirect(remove_from_uri("db|database"), lang(184), drop_databases([DB]));
		} elseif (DB!==$D) {
			if (DB!="") {
				$_GET["db"]=$D;
				queries_redirect(preg_replace('~\bdb=[^&]*&~', '', ME)."db=".urlencode($D), lang(185), rename_database($D, $J["collation"]));
			} else {
				$k=explode("\n", str_replace("\r", "", $D));
				$Lh=true;
				$qe="";
				foreach ($k as $l) {
					if (count($k)==1||$l!="") {
						if (!create_database($l, $J["collation"]))
							$Lh=false;
						$qe=$l;
					}
				}
				restart_session();
				set_session("dbs", null);
				queries_redirect(ME."db=".urlencode($qe), lang(186), $Lh);
			}
		} else {
			if (!$J["collation"])
				redirect(substr(ME, 0, -1));
			query_redirect("ALTER DATABASE ".idf_escape($D).(preg_match('~^[a-z0-9_]+$~i', $J["collation"])?" COLLATE $J[collation]":""), substr(ME, 0, -1), lang(187));
		}
	}
	page_header(DB!=""?lang(67):lang(115), $n, [], h(DB));
	$nb=collations();
	$D=DB;
	if ($_POST)
		$D=$J["name"]; elseif (DB!="")
		$J["collation"]=db_collation(DB, $nb);
	elseif ($y=="sql") {
		foreach (get_vals("SHOW GRANTS") as $qd) {
			if (preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~', $qd, $C)&&$C[1]) {
				$D=stripcslashes(idf_unescape("`$C[2]`"));
				break;
			}
		}
	}
	echo '
<form action="" method="post">
<p>
', ($_POST["add_x"]||strpos($D, "\n")?'<textarea id="name" name="name" rows="10" cols="40">'.h($D).'</textarea><br>':'<input name="name" id="name" value="'.h($D).'" data-maxlength="64" autocapitalize="off">')."\n".($nb?html_select("collation", [""=>"(".lang(101).")"]+$nb, $J["collation"]).doc_link(['sql'=>"charset-charsets.html", 'mariadb'=>"supported-character-sets-and-collations/", 'mssql'=>"ms187963.aspx",]):""), script("focus(qs('#name'));"), '<input type="submit" value="', lang(14), '">
';
	if (DB!="")
		echo "<input type='submit' name='drop' value='".lang(127)."'>".confirm(lang(175, DB))."\n"; elseif (!$_POST["add_x"]&&$_GET["db"]=="")
		echo "<input type='image' class='icon' name='add' src='".h(preg_replace("~\\?.*~", "", ME)."?file=plus.gif&version=4.8.1")."' alt='+' title='".lang(108)."'>\n";
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["scheme"])) {
	$J=$_POST;
	if ($_POST&&!$n) {
		$A=preg_replace('~ns=[^&]*&~', '', ME)."ns=";
		if ($_POST["drop"])
			query_redirect("DROP SCHEMA ".idf_escape($_GET["ns"]), $A, lang(188)); else {
			$D=trim($J["name"]);
			$A.=urlencode($D);
			if ($_GET["ns"]=="")
				query_redirect("CREATE SCHEMA ".idf_escape($D), $A, lang(189)); elseif ($_GET["ns"]!=$D)
				query_redirect("ALTER SCHEMA ".idf_escape($_GET["ns"])." RENAME TO ".idf_escape($D), $A, lang(190));
			else
				redirect($A);
		}
	}
	page_header($_GET["ns"]!=""?lang(68):lang(69), $n);
	if (!$J)
		$J["name"]=$_GET["ns"];
	echo '
<form action="" method="post">
<p><input name="name" id="name" value="', h($J["name"]), '" autocapitalize="off">
', script("focus(qs('#name'));"), '<input type="submit" value="', lang(14), '">
';
	if ($_GET["ns"]!="")
		echo "<input type='submit' name='drop' value='".lang(127)."'>".confirm(lang(175, $_GET["ns"]))."\n";
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["call"])) {
	$da=($_GET["name"]?$_GET["name"]:$_GET["call"]);
	page_header(lang(191).": ".h($da), $n);
	$Wg=routine($_GET["call"], (isset($_GET["callf"])?"FUNCTION":"PROCEDURE"));
	$Ld=[];
	$Lf=[];
	foreach ($Wg["fields"] as $t=>$o) {
		if (substr($o["inout"], -3)=="OUT")
			$Lf[$t]="@".idf_escape($o["field"])." AS ".idf_escape($o["field"]);
		if (!$o["inout"]||substr($o["inout"], 0, 2)=="IN")
			$Ld[]=$t;
	}
	if (!$n&&$_POST) {
		$Ya=[];
		foreach ($Wg["fields"] as $z=>$o) {
			if (in_array($z, $Ld)) {
				$X=process_input($o);
				if ($X===false)
					$X="''";
				if (isset($Lf[$z]))
					$g->query("SET @".idf_escape($o["field"])." = $X");
			}
			$Ya[]=(isset($Lf[$z])?"@".idf_escape($o["field"]):$X);
		}
		$G=(isset($_GET["callf"])?"SELECT":"CALL")." ".table($da)."(".implode(", ", $Ya).")";
		$Fh=microtime(true);
		$H=$g->multi_query($G);
		$_a=$g->affected_rows;
		echo $b->selectQuery($G, $Fh, !$H);
		if (!$H)
			echo "<p class='error'>".error()."\n"; else {
			$h=connect();
			if (is_object($h))
				$h->select_db(DB);
			do {
				$H=$g->store_result();
				if (is_object($H))
					select($H, $h); else
					echo "<p class='message'>".lang(192, $_a)." <span class='time'>".@date("H:i:s")."</span>\n";
			} while ($g->next_result());
			if ($Lf)
				select($g->query("SELECT ".implode(", ", $Lf)));
		}
	}
	echo '
<form action="" method="post">
';
	if ($Ld) {
		echo "<table cellspacing='0' class='layout'>\n";
		foreach ($Ld as $z) {
			$o=$Wg["fields"][$z];
			$D=$o["field"];
			echo "<tr><th>".$b->fieldName($o);
			$Y=$_POST["fields"][$D];
			if ($Y!="") {
				if ($o["type"]=="enum")
					$Y=+$Y;
				if ($o["type"]=="set")
					$Y=array_sum($Y);
			}
			input($o, $Y, (string)$_POST["function"][$D]);
			echo "\n";
		}
		echo "</table>\n";
	}
	echo '<p>
<input type="submit" value="', lang(191), '">
<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["foreign"])) {
	$a=$_GET["foreign"];
	$D=$_GET["name"];
	$J=$_POST;
	if ($_POST&&!$n&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]) {
		$Ne=($_POST["drop"]?lang(193):($D!=""?lang(194):lang(195)));
		$B=ME."table=".urlencode($a);
		if (!$_POST["drop"]) {
			$J["source"]=array_filter($J["source"], 'strlen');
			ksort($J["source"]);
			$Zh=[];
			foreach ($J["source"] as $z=>$X)
				$Zh[$z]=$J["target"][$z];
			$J["target"]=$Zh;
		}
		if ($y=="sqlite")
			queries_redirect($B, $Ne, recreate_table($a, $a, [], [], [" $D"=>($_POST["drop"]?"":" ".format_foreign_key($J))])); else {
			$c="ALTER TABLE ".table($a);
			$lc="\nDROP ".($y=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($D);
			if ($_POST["drop"])
				query_redirect($c.$lc, $B, $Ne); else {
				query_redirect($c.($D!=""?"$lc,":"")."\nADD".format_foreign_key($J), $B, $Ne);
				$n=lang(196)."<br>$n";
			}
		}
	}
	page_header(lang(197), $n, ["table"=>$a], h($a));
	if ($_POST) {
		ksort($J["source"]);
		if ($_POST["add"])
			$J["source"][]=""; elseif ($_POST["change"]||$_POST["change-js"])
			$J["target"]=[];
	} elseif ($D!="") {
		$jd=foreign_keys($a);
		$J=$jd[$D];
		$J["source"][]="";
	} else {
		$J["table"]=$a;
		$J["source"]=[""];
	}
	echo '
<form action="" method="post">
';
	$yh=array_keys(fields($a));
	if ($J["db"]!="")
		$g->select_db($J["db"]);
	if ($J["ns"]!="")
		set_schema($J["ns"]);
	$Fg=array_keys(array_filter(table_status('', true), 'fk_support'));
	$Zh=array_keys(fields(in_array($J["table"], $Fg)?$J["table"]:reset($Fg)));
	$tf="this.form['change-js'].value = '1'; this.form.submit();";
	echo "<p>".lang(198).": ".html_select("table", $Fg, $J["table"], $tf)."\n";
	if ($y=="pgsql")
		echo lang(77).": ".html_select("ns", $b->schemas(), $J["ns"]!=""?$J["ns"]:$_GET["ns"], $tf); elseif ($y!="sqlite") {
		$Wb=[];
		foreach ($b->databases() as $l) {
			if (!information_schema($l))
				$Wb[]=$l;
		}
		echo lang(76).": ".html_select("db", $Wb, $J["db"]!=""?$J["db"]:$_GET["db"], $tf);
	}
	echo '<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="', lang(199), '"></noscript>
<table cellspacing="0">
<thead><tr><th id="label-source">', lang(134), '<th id="label-target">', lang(135), '</thead>
';
	$ge=0;
	foreach ($J["source"] as $z=>$X) {
		echo "<tr>", "<td>".html_select("source[".(+$z)."]", [-1=>""]+$yh, $X, ($ge==count($J["source"])-1?"foreignAddRow.call(this);":1), "label-source"), "<td>".html_select("target[".(+$z)."]", $Zh, $J["target"][$z], 1, "label-target");
		$ge++;
	}
	echo '</table>
<p>
', lang(103), ': ', html_select("on_delete", [-1=>""]+explode("|", $sf), $J["on_delete"]), ' ', lang(102), ': ', html_select("on_update", [-1=>""]+explode("|", $sf), $J["on_update"]), doc_link(['sql'=>"innodb-foreign-key-constraints.html", 'mariadb'=>"foreign-keys/", 'pgsql'=>"sql-createtable.html#SQL-CREATETABLE-REFERENCES", 'mssql'=>"ms174979.aspx", 'oracle'=>"https://docs.oracle.com/cd/B19306_01/server.102/b14200/clauses002.htm#sthref2903",]), '<p>
<input type="submit" value="', lang(14), '">
<noscript><p><input type="submit" name="add" value="', lang(200), '"></noscript>
';
	if ($D!="") {
		echo '<input type="submit" name="drop" value="', lang(127), '">', confirm(lang(175, $D));
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["view"])) {
	$a=$_GET["view"];
	$J=$_POST;
	$If="VIEW";
	if ($y=="pgsql"&&$a!="") {
		$O=table_status($a);
		$If=strtoupper($O["Engine"]);
	}
	if ($_POST&&!$n) {
		$D=trim($J["name"]);
		$Ga=" AS\n$J[select]";
		$B=ME."table=".urlencode($D);
		$Ne=lang(201);
		$T=($_POST["materialized"]?"MATERIALIZED VIEW":"VIEW");
		if (!$_POST["drop"]&&$a==$D&&$y!="sqlite"&&$T=="VIEW"&&$If=="VIEW")
			query_redirect(($y=="mssql"?"ALTER":"CREATE OR REPLACE")." VIEW ".table($D).$Ga, $B, $Ne); else {
			$bi=$D."_adminer_".uniqid();
			drop_create("DROP $If ".table($a), "CREATE $T ".table($D).$Ga, "DROP $T ".table($D), "CREATE $T ".table($bi).$Ga, "DROP $T ".table($bi), ($_POST["drop"]?substr(ME, 0, -1):$B), lang(202), $Ne, lang(203), $a, $D);
		}
	}
	if (!$_POST&&$a!="") {
		$J=view($a);
		$J["name"]=$a;
		$J["materialized"]=($If!="VIEW");
		if (!$n)
			$n=error();
	}
	page_header(($a!=""?lang(43):lang(204)), $n, ["table"=>$a], h($a));
	echo '
<form action="" method="post">
<p>', lang(183), ': <input name="name" value="', h($J["name"]), '" data-maxlength="64" autocapitalize="off">
', (support("materializedview")?" ".checkbox("materialized", 1, $J["materialized"], lang(129)):""), '<p>';
	textarea("select", $J["select"]);
	echo '<p>
<input type="submit" value="', lang(14), '">
';
	if ($a!="") {
		echo '<input type="submit" name="drop" value="', lang(127), '">', confirm(lang(175, $a));
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["event"])) {
	$aa=$_GET["event"];
	$Yd=["YEAR", "QUARTER", "MONTH", "DAY", "HOUR", "MINUTE", "WEEK", "SECOND", "YEAR_MONTH", "DAY_HOUR", "DAY_MINUTE", "DAY_SECOND", "HOUR_MINUTE", "HOUR_SECOND", "MINUTE_SECOND"];
	$Hh=["ENABLED"=>"ENABLE", "DISABLED"=>"DISABLE", "SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE"];
	$J=$_POST;
	if ($_POST&&!$n) {
		if ($_POST["drop"])
			query_redirect("DROP EVENT ".idf_escape($aa), substr(ME, 0, -1), lang(205)); elseif (in_array($J["INTERVAL_FIELD"], $Yd)&&isset($Hh[$J["STATUS"]])) {
			$bh="\nON SCHEDULE ".($J["INTERVAL_VALUE"]?"EVERY ".q($J["INTERVAL_VALUE"])." $J[INTERVAL_FIELD]".($J["STARTS"]?" STARTS ".q($J["STARTS"]):"").($J["ENDS"]?" ENDS ".q($J["ENDS"]):""):"AT ".q($J["STARTS"]))." ON COMPLETION".($J["ON_COMPLETION"]?"":" NOT")." PRESERVE";
			queries_redirect(substr(ME, 0, -1), ($aa!=""?lang(206):lang(207)), queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$bh.($aa!=$J["EVENT_NAME"]?"\nRENAME TO ".idf_escape($J["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($J["EVENT_NAME"]).$bh)."\n".$Hh[$J["STATUS"]]." COMMENT ".q($J["EVENT_COMMENT"]).rtrim(" DO\n$J[EVENT_DEFINITION]", ";").";"));
		}
	}
	page_header(($aa!=""?lang(208).": ".h($aa):lang(209)), $n);
	if (!$J&&$aa!="") {
		$K=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));
		$J=reset($K);
	}
	echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>', lang(183), '<td><input name="EVENT_NAME" value="', h($J["EVENT_NAME"]), '" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">', lang(210), '<td><input name="STARTS" value="', h("$J[EXECUTE_AT]$J[STARTS]"), '">
<tr><th title="datetime">', lang(211), '<td><input name="ENDS" value="', h($J["ENDS"]), '">
<tr><th>', lang(212), '<td><input type="number" name="INTERVAL_VALUE" value="', h($J["INTERVAL_VALUE"]), '" class="size"> ', html_select("INTERVAL_FIELD", $Yd, $J["INTERVAL_FIELD"]), '<tr><th>', lang(118), '<td>', html_select("STATUS", $Hh, $J["STATUS"]), '<tr><th>', lang(50), '<td><input name="EVENT_COMMENT" value="', h($J["EVENT_COMMENT"]), '" data-maxlength="64">
<tr><th><td>', checkbox("ON_COMPLETION", "PRESERVE", $J["ON_COMPLETION"]=="PRESERVE", lang(213)), '</table>
<p>';
	textarea("EVENT_DEFINITION", $J["EVENT_DEFINITION"]);
	echo '<p>
<input type="submit" value="', lang(14), '">
';
	if ($aa!="") {
		echo '<input type="submit" name="drop" value="', lang(127), '">', confirm(lang(175, $aa));
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["procedure"])) {
	$da=($_GET["name"]?$_GET["name"]:$_GET["procedure"]);
	$Wg=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");
	$J=$_POST;
	$J["fields"]=(array)$J["fields"];
	if ($_POST&&!process_fields($J["fields"])&&!$n) {
		$Ff=routine($_GET["procedure"], $Wg);
		$bi="$J[name]_adminer_".uniqid();
		drop_create("DROP $Wg ".routine_id($da, $Ff), create_routine($Wg, $J), "DROP $Wg ".routine_id($J["name"], $J), create_routine($Wg, ["name"=>$bi]+$J), "DROP $Wg ".routine_id($bi, $J), substr(ME, 0, -1), lang(214), lang(215), lang(216), $da, $J["name"]);
	}
	page_header(($da!=""?(isset($_GET["function"])?lang(217):lang(218)).": ".h($da):(isset($_GET["function"])?lang(219):lang(220))), $n);
	if (!$_POST&&$da!="") {
		$J=routine($_GET["procedure"], $Wg);
		$J["name"]=$da;
	}
	$nb=get_vals("SHOW CHARACTER SET");
	sort($nb);
	$Xg=routine_languages();
	echo '
<form action="" method="post" id="form">
<p>', lang(183), ': <input name="name" value="', h($J["name"]), '" data-maxlength="64" autocapitalize="off">
', ($Xg?lang(19).": ".html_select("language", $Xg, $J["language"])."\n":""), '<input type="submit" value="', lang(14), '">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
';
	edit_fields($J["fields"], $nb, $Wg);
	if (isset($_GET["function"])) {
		echo "<tr><td>".lang(221);
		edit_type("returns", $J["returns"], $nb, [], ($y=="pgsql"?["void", "trigger"]:[]));
	}
	echo '</table>
', script("editFields();"), '</div>
<p>';
	textarea("definition", $J["definition"]);
	echo '<p>
<input type="submit" value="', lang(14), '">
';
	if ($da!="") {
		echo '<input type="submit" name="drop" value="', lang(127), '">', confirm(lang(175, $da));
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["sequence"])) {
	$fa=$_GET["sequence"];
	$J=$_POST;
	if ($_POST&&!$n) {
		$A=substr(ME, 0, -1);
		$D=trim($J["name"]);
		if ($_POST["drop"])
			query_redirect("DROP SEQUENCE ".idf_escape($fa), $A, lang(222)); elseif ($fa=="")
			query_redirect("CREATE SEQUENCE ".idf_escape($D), $A, lang(223));
		elseif ($fa!=$D)
			query_redirect("ALTER SEQUENCE ".idf_escape($fa)." RENAME TO ".idf_escape($D), $A, lang(224));
		else
			redirect($A);
	}
	page_header($fa!=""?lang(225).": ".h($fa):lang(226), $n);
	if (!$J)
		$J["name"]=$fa;
	echo '
<form action="" method="post">
<p><input name="name" value="', h($J["name"]), '" autocapitalize="off">
<input type="submit" value="', lang(14), '">
';
	if ($fa!="")
		echo "<input type='submit' name='drop' value='".lang(127)."'>".confirm(lang(175, $fa))."\n";
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["type"])) {
	$ga=$_GET["type"];
	$J=$_POST;
	if ($_POST&&!$n) {
		$A=substr(ME, 0, -1);
		if ($_POST["drop"])
			query_redirect("DROP TYPE ".idf_escape($ga), $A, lang(227)); else
			query_redirect("CREATE TYPE ".idf_escape(trim($J["name"]))." $J[as]", $A, lang(228));
	}
	page_header($ga!=""?lang(229).": ".h($ga):lang(230), $n);
	if (!$J)
		$J["as"]="AS ";
	echo '
<form action="" method="post">
<p>
';
	if ($ga!="")
		echo "<input type='submit' name='drop' value='".lang(127)."'>".confirm(lang(175, $ga))."\n"; else {
		echo "<input name='name' value='".h($J['name'])."' autocapitalize='off'>\n";
		textarea("as", $J["as"]);
		echo "<p><input type='submit' value='".lang(14)."'>\n";
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["trigger"])) {
	$a=$_GET["trigger"];
	$D=$_GET["name"];
	$Ai=trigger_options();
	$J=(array)trigger($D, $a)+["Trigger"=>$a."_bi"];
	if ($_POST) {
		if (!$n&&in_array($_POST["Timing"], $Ai["Timing"])&&in_array($_POST["Event"], $Ai["Event"])&&in_array($_POST["Type"], $Ai["Type"])) {
			$rf=" ON ".table($a);
			$lc="DROP TRIGGER ".idf_escape($D).($y=="pgsql"?$rf:"");
			$B=ME."table=".urlencode($a);
			if ($_POST["drop"])
				query_redirect($lc, $B, lang(231)); else {
				if ($D!="")
					queries($lc);
				queries_redirect($B, ($D!=""?lang(232):lang(233)), queries(create_trigger($rf, $_POST)));
				if ($D!="")
					queries(create_trigger($rf, $J+["Type"=>reset($Ai["Type"])]));
			}
		}
		$J=$_POST;
	}
	page_header(($D!=""?lang(234).": ".h($D):lang(235)), $n, ["table"=>$a]);
	echo '
<form action="" method="post" id="form">
<table cellspacing="0" class="layout">
<tr><th>', lang(236), '<td>', html_select("Timing", $Ai["Timing"], $J["Timing"], "triggerChange(/^".preg_quote($a, "/")."_[ba][iud]$/, '".js_escape($a)."', this.form);"), '<tr><th>', lang(237), '<td>', html_select("Event", $Ai["Event"], $J["Event"], "this.form['Timing'].onchange();"), (in_array("UPDATE OF", $Ai["Event"])?" <input name='Of' value='".h($J["Of"])."' class='hidden'>":""), '<tr><th>', lang(49), '<td>', html_select("Type", $Ai["Type"], $J["Type"]), '</table>
<p>', lang(183), ': <input name="Trigger" value="', h($J["Trigger"]), '" data-maxlength="64" autocapitalize="off">
', script("qs('#form')['Timing'].onchange();"), '<p>';
	textarea("Statement", $J["Statement"]);
	echo '<p>
<input type="submit" value="', lang(14), '">
';
	if ($D!="") {
		echo '<input type="submit" name="drop" value="', lang(127), '">', confirm(lang(175, $D));
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["user"])) {
	$ha=$_GET["user"];
	$sg=[""=>["All privileges"=>""]];
	foreach (get_rows("SHOW PRIVILEGES") as $J) {
		foreach (explode(",", ($J["Privilege"]=="Grant option"?"":$J["Context"])) as $Fb)
			$sg[$Fb][$J["Privilege"]]=$J["Comment"];
	}
	$sg["Server Admin"]+=$sg["File access on server"];
	$sg["Databases"]["Create routine"]=$sg["Procedures"]["Create routine"];
	unset($sg["Procedures"]["Create routine"]);
	$sg["Columns"]=[];
	foreach (["Select", "Insert", "Update", "References"] as $X)
		$sg["Columns"][$X]=$sg["Tables"][$X];
	unset($sg["Server Admin"]["Usage"]);
	foreach ($sg["Tables"] as $z=>$X)
		unset($sg["Databases"][$z]);
	$af=[];
	if ($_POST) {
		foreach ($_POST["objects"] as $z=>$X)
			$af[$X]=(array)$af[$X]+(array)$_POST["grants"][$z];
	}
	$rd=[];
	$pf="";
	if (isset($_GET["host"])&&($H=$g->query("SHOW GRANTS FOR ".q($ha)."@".q($_GET["host"])))) {
		while ($J=$H->fetch_row()) {
			if (preg_match('~GRANT (.*) ON (.*) TO ~', $J[0], $C)&&preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~', $C[1], $Fe, PREG_SET_ORDER)) {
				foreach ($Fe as $X) {
					if ($X[1]!="USAGE")
						$rd["$C[2]$X[2]"][$X[1]]=true;
					if (preg_match('~ WITH GRANT OPTION~', $J[0]))
						$rd["$C[2]$X[2]"]["GRANT OPTION"]=true;
				}
			}
			if (preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~", $J[0], $C))
				$pf=$C[1];
		}
	}
	if ($_POST&&!$n) {
		$qf=(isset($_GET["host"])?q($ha)."@".q($_GET["host"]):"''");
		if ($_POST["drop"])
			query_redirect("DROP USER $qf", ME."privileges=", lang(238)); else {
			$cf=q($_POST["user"])."@".q($_POST["host"]);
			$Zf=$_POST["pass"];
			if ($Zf!=''&&!$_POST["hashed"]&&!min_version(8)) {
				$Zf=$g->result("SELECT PASSWORD(".q($Zf).")");
				$n=!$Zf;
			}
			$Lb=false;
			if (!$n) {
				if ($qf!=$cf) {
					$Lb=queries((min_version(5)?"CREATE USER":"GRANT USAGE ON *.* TO")." $cf IDENTIFIED BY ".(min_version(8)?"":"PASSWORD ").q($Zf));
					$n=!$Lb;
				} elseif ($Zf!=$pf)
					queries("SET PASSWORD FOR $cf = ".q($Zf));
			}
			if (!$n) {
				$Tg=[];
				foreach ($af as $if=>$qd) {
					if (isset($_GET["grant"]))
						$qd=array_filter($qd);
					$qd=array_keys($qd);
					if (isset($_GET["grant"]))
						$Tg=array_diff(array_keys(array_filter($af[$if], 'strlen')), $qd); elseif ($qf==$cf) {
						$nf=array_keys((array)$rd[$if]);
						$Tg=array_diff($nf, $qd);
						$qd=array_diff($qd, $nf);
						unset($rd[$if]);
					}
					if (preg_match('~^(.+)\s*(\(.*\))?$~U', $if, $C)&&(!grant("REVOKE", $Tg, $C[2], " ON $C[1] FROM $cf")||!grant("GRANT", $qd, $C[2], " ON $C[1] TO $cf"))) {
						$n=true;
						break;
					}
				}
			}
			if (!$n&&isset($_GET["host"])) {
				if ($qf!=$cf)
					queries("DROP USER $qf"); elseif (!isset($_GET["grant"])) {
					foreach ($rd as $if=>$Tg) {
						if (preg_match('~^(.+)(\(.*\))?$~U', $if, $C))
							grant("REVOKE", array_keys($Tg), $C[2], " ON $C[1] FROM $cf");
					}
				}
			}
			queries_redirect(ME."privileges=", (isset($_GET["host"])?lang(239):lang(240)), !$n);
			if ($Lb)
				$g->query("DROP USER $cf");
		}
	}
	page_header((isset($_GET["host"])?lang(35).": ".h("$ha@$_GET[host]"):lang(146)), $n, ["privileges"=>['', lang(71)]]);
	if ($_POST) {
		$J=$_POST;
		$rd=$af;
	} else {
		$J=$_GET+["host"=>$g->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)")];
		$J["pass"]=$pf;
		if ($pf!="")
			$J["hashed"]=true;
		$rd[(DB==""||$rd?"":idf_escape(addcslashes(DB, "%_\\"))).".*"]=[];
	}
	echo '<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>', lang(34), '<td><input name="host" data-maxlength="60" value="', h($J["host"]), '" autocapitalize="off">
<tr><th>', lang(35), '<td><input name="user" data-maxlength="80" value="', h($J["user"]), '" autocapitalize="off">
<tr><th>', lang(36), '<td><input name="pass" id="pass" value="', h($J["pass"]), '" autocomplete="new-password">
';
	if (!$J["hashed"])
		echo script("typePassword(qs('#pass'));");
	echo(min_version(8)?"":checkbox("hashed", 1, $J["hashed"], lang(241), "typePassword(this.form['pass'], this.checked);")), '</table>

';
	echo "<table cellspacing='0'>\n", "<thead><tr><th colspan='2'>".lang(71).doc_link(['sql'=>"grant.html#priv_level"]);
	$t=0;
	foreach ($rd as $if=>$qd) {
		echo '<th>'.($if!="*.*"?"<input name='objects[$t]' value='".h($if)."' size='10' autocapitalize='off'>":"<input type='hidden' name='objects[$t]' value='*.*' size='10'>*.*");
		$t++;
	}
	echo "</thead>\n";
	foreach ([""=>"", "Server Admin"=>lang(34), "Databases"=>lang(37), "Tables"=>lang(131), "Columns"=>lang(48), "Procedures"=>lang(242),] as $Fb=>$dc) {
		foreach ((array)$sg[$Fb] as $rg=>$tb) {
			echo "<tr".odd()."><td".($dc?">$dc<td":" colspan='2'").' lang="en" title="'.h($tb).'">'.h($rg);
			$t=0;
			foreach ($rd as $if=>$qd) {
				$D="'grants[$t][".h(strtoupper($rg))."]'";
				$Y=$qd[strtoupper($rg)];
				if ($Fb=="Server Admin"&&$if!=(isset($rd["*.*"])?"*.*":".*"))
					echo "<td>"; elseif (isset($_GET["grant"]))
					echo "<td><select name=$D><option><option value='1'".($Y?" selected":"").">".lang(243)."<option value='0'".($Y=="0"?" selected":"").">".lang(244)."</select>";
				else {
					echo "<td align='center'><label class='block'>", "<input type='checkbox' name=$D value='1'".($Y?" checked":"").($rg=="All privileges"?" id='grants-$t-all'>":">".($rg=="Grant option"?"":script("qsl('input').onclick = function () { if (this.checked) formUncheck('grants-$t-all'); };"))), "</label>";
				}
				$t++;
			}
		}
	}
	echo "</table>\n", '<p>
<input type="submit" value="', lang(14), '">
';
	if (isset($_GET["host"])) {
		echo '<input type="submit" name="drop" value="', lang(127), '">', confirm(lang(175, "$ha@$_GET[host]"));
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
';
} elseif (isset($_GET["processlist"])) {
	if (support("kill")) {
		if ($_POST&&!$n) {
			$le=0;
			foreach ((array)$_POST["kill"] as $X) {
				if (kill_process($X))
					$le++;
			}
			queries_redirect(ME."processlist=", lang(245, $le), $le||!$_POST["kill"]);
		}
	}
	page_header(lang(116), $n);
	echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap checkable">
', script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});");
	$t=-1;
	foreach (process_list() as $t=>$J) {
		if (!$t) {
			echo "<thead><tr lang='en'>".(support("kill")?"<th>":"");
			foreach ($J as $z=>$X)
				echo "<th>$z".doc_link(['sql'=>"show-processlist.html#processlist_".strtolower($z), 'pgsql'=>"monitoring-stats.html#PG-STAT-ACTIVITY-VIEW", 'oracle'=>"REFRN30223",]);
			echo "</thead>\n";
		}
		echo "<tr".odd().">".(support("kill")?"<td>".checkbox("kill[]", $J[$y=="sql"?"Id":"pid"], 0):"");
		foreach ($J as $z=>$X)
			echo "<td>".(($y=="sql"&&$z=="Info"&&preg_match("~Query|Killed~", $J["Command"])&&$X!="")||($y=="pgsql"&&$z=="current_query"&&$X!="<IDLE>")||($y=="oracle"&&$z=="sql_text"&&$X!="")?"<code class='jush-$y'>".shorten_utf8($X, 100, "</code>").' <a href="'.h(ME.($J["db"]!=""?"db=".urlencode($J["db"])."&":"")."sql=".urlencode($X)).'">'.lang(246).'</a>':h($X));
		echo "\n";
	}
	echo '</table>
</div>
<p>
';
	if (support("kill")) {
		echo ($t+1)."/".lang(247, max_connections()), "<p><input type='submit' value='".lang(248)."'>\n";
	}
	echo '<input type="hidden" name="token" value="', $qi, '">
</form>
', script("tableCheck();");
} elseif (isset($_GET["select"])) {
	$a=$_GET["select"];
	$R=table_status1($a);
	$x=indexes($a);
	$p=fields($a);
	$jd=column_foreign_keys($a);
	$lf=$R["Oid"];
	parse_str($_COOKIE["adminer_import"], $za);
	$Ug=[];
	$e=[];
	$fi=null;
	foreach ($p as $z=>$o) {
		$D=$b->fieldName($o);
		if (isset($o["privileges"]["select"])&&$D!="") {
			$e[$z]=html_entity_decode(strip_tags($D), ENT_QUOTES);
			if (is_shortable($o))
				$fi=$b->selectLengthProcess();
		}
		$Ug+=$o["privileges"];
	}
	[$L, $sd]=$b->selectColumnsProcess($e, $x);
	$ce=count($sd)<count($L);
	$Z=$b->selectSearchProcess($p, $x);
	$Bf=$b->selectOrderProcess($p, $x);
	$_=$b->selectLimitProcess();
	if ($_GET["val"]&&is_ajax()) {
		header("Content-Type: text/plain; charset=utf-8");
		foreach ($_GET["val"] as $Hi=>$J) {
			$Ga=convert_field($p[key($J)]);
			$L=[$Ga?$Ga:idf_escape(key($J))];
			$Z[]=where_check($Hi, $p);
			$I=$m->select($a, $L, $Z, $L);
			if ($I)
				echo reset($I->fetch_row());
		}
		exit;
	}
	$ng=$Ji=null;
	foreach ($x as $w) {
		if ($w["type"]=="PRIMARY") {
			$ng=array_flip($w["columns"]);
			$Ji=($L?$ng:[]);
			foreach ($Ji as $z=>$X) {
				if (in_array(idf_escape($z), $L))
					unset($Ji[$z]);
			}
			break;
		}
	}
	if ($lf&&!$ng) {
		$ng=$Ji=[$lf=>0];
		$x[]=["type"=>"PRIMARY", "columns"=>[$lf]];
	}
	if ($_POST&&!$n) {
		$kj=$Z;
		if (!$_POST["all"]&&is_array($_POST["check"])) {
			$eb=[];
			foreach ($_POST["check"] as $bb)
				$eb[]=where_check($bb, $p);
			$kj[]="((".implode(") OR (", $eb)."))";
		}
		$kj=($kj?"\nWHERE ".implode(" AND ", $kj):"");
		if ($_POST["export"]) {
			cookie("adminer_import", "output=".urlencode($_POST["output"])."&format=".urlencode($_POST["format"]));
			dump_headers($a);
			$b->dumpTable($a, "");
			$od=($L?implode(", ", $L):"*").convert_fields($e, $p, $L)."\nFROM ".table($a);
			$ud=($sd&&$ce?"\nGROUP BY ".implode(", ", $sd):"").($Bf?"\nORDER BY ".implode(", ", $Bf):"");
			if (!is_array($_POST["check"])||$ng)
				$G="SELECT $od$kj$ud"; else {
				$Fi=[];
				foreach ($_POST["check"] as $X)
					$Fi[]="(SELECT".limit($od, "\nWHERE ".($Z?implode(" AND ", $Z)." AND ":"").where_check($X, $p).$ud, 1).")";
				$G=implode(" UNION ALL ", $Fi);
			}
			$b->dumpData($a, "table", $G);
			exit;
		}
		if (!$b->selectEmailProcess($Z, $jd)) {
			if ($_POST["save"]||$_POST["delete"]) {
				$H=true;
				$_a=0;
				$N=[];
				if (!$_POST["delete"]) {
					foreach ($e as $D=>$X) {
						$X=process_input($p[$D]);
						if ($X!==null&&($_POST["clone"]||$X!==false))
							$N[idf_escape($D)]=($X!==false?$X:idf_escape($D));
					}
				}
				if ($_POST["delete"]||$N) {
					if ($_POST["clone"])
						$G="INTO ".table($a)." (".implode(", ", array_keys($N)).")\nSELECT ".implode(", ", $N)."\nFROM ".table($a);
					if ($_POST["all"]||($ng&&is_array($_POST["check"]))||$ce) {
						$H=($_POST["delete"]?$m->delete($a, $kj):($_POST["clone"]?queries("INSERT $G$kj"):$m->update($a, $N, $kj)));
						$_a=$g->affected_rows;
					} else {
						foreach ((array)$_POST["check"] as $X) {
							$gj="\nWHERE ".($Z?implode(" AND ", $Z)." AND ":"").where_check($X, $p);
							$H=($_POST["delete"]?$m->delete($a, $gj, 1):($_POST["clone"]?queries("INSERT".limit1($a, $G, $gj)):$m->update($a, $N, $gj, 1)));
							if (!$H)
								break;
							$_a+=$g->affected_rows;
						}
					}
				}
				$Ne=lang(249, $_a);
				if ($_POST["clone"]&&$H&&$_a==1) {
					$re=last_id();
					if ($re)
						$Ne=lang(168, " $re");
				}
				queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""), $Ne, $H);
				if (!$_POST["delete"]) {
					edit_form($a, $p, (array)$_POST["fields"], !$_POST["clone"]);
					page_footer();
					exit;
				}
			} elseif (!$_POST["import"]) {
				if (!$_POST["val"])
					$n=lang(250); else {
					$H=true;
					$_a=0;
					foreach ($_POST["val"] as $Hi=>$J) {
						$N=[];
						foreach ($J as $z=>$X) {
							$z=bracket_escape($z, 1);
							$N[idf_escape($z)]=(preg_match('~char|text~', $p[$z]["type"])||$X!=""?$b->processInput($p[$z], $X):"NULL");
						}
						$H=$m->update($a, $N, " WHERE ".($Z?implode(" AND ", $Z)." AND ":"").where_check($Hi, $p), !$ce&&!$ng, " ");
						if (!$H)
							break;
						$_a+=$g->affected_rows;
					}
					queries_redirect(remove_from_uri(), lang(249, $_a), $H);
				}
			} elseif (!is_string($Zc=get_file("csv_file", true)))
				$n=upload_error($Zc);
			elseif (!preg_match('~~u', $Zc))
				$n=lang(251);
			else {
				cookie("adminer_import", "output=".urlencode($za["output"])."&format=".urlencode($_POST["separator"]));
				$H=true;
				$pb=array_keys($p);
				preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~', $Zc, $Fe);
				$_a=count($Fe[0]);
				$m->begin();
				$kh=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));
				$K=[];
				foreach ($Fe[0] as $z=>$X) {
					preg_match_all("~((?>\"[^\"]*\")+|[^$kh]*)$kh~", $X.$kh, $Ge);
					if (!$z&&!array_diff($Ge[1], $pb)) {
						$pb=$Ge[1];
						$_a--;
					} else {
						$N=[];
						foreach ($Ge[1] as $t=>$kb)
							$N[idf_escape($pb[$t])]=($kb==""&&$p[$pb[$t]]["null"]?"NULL":q(str_replace('""', '"', preg_replace('~^"|"$~', '', $kb))));
						$K[]=$N;
					}
				}
				$H=(!$K||$m->insertUpdate($a, $K, $ng));
				if ($H)
					$H=$m->commit();
				queries_redirect(remove_from_uri("page"), lang(252, $_a), $H);
				$m->rollback();
			}
		}
	}
	$Rh=$b->tableName($R);
	if (is_ajax()) {
		page_headers();
		ob_start();
	} else
		page_header(lang(53).": $Rh", $n);
	$N=null;
	if (isset($Ug["insert"])||!support("table")) {
		$N="";
		foreach ((array)$_GET["where"] as $X) {
			if ($jd[$X["col"]]&&count($jd[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&!preg_match('~[_%]~', $X["val"]))))
				$N.="&set".urlencode("[".bracket_escape($X["col"])."]")."=".urlencode($X["val"]);
		}
	}
	$b->selectLinks($R, $N);
	if (!$e&&support("table"))
		echo "<p class='error'>".lang(253).($p?".":": ".error())."\n"; else {
		echo "<form action='' id='form'>\n", "<div style='display: none;'>";
		hidden_fields_get();
		echo(DB!=""?'<input type="hidden" name="db" value="'.h(DB).'">'.(isset($_GET["ns"])?'<input type="hidden" name="ns" value="'.h($_GET["ns"]).'">':""):"");
		echo '<input type="hidden" name="select" value="'.h($a).'">', "</div>\n";
		$b->selectColumnsPrint($L, $e);
		$b->selectSearchPrint($Z, $e, $x);
		$b->selectOrderPrint($Bf, $e, $x);
		$b->selectLimitPrint($_);
		$b->selectLengthPrint($fi);
		$b->selectActionPrint($x);
		echo "</form>\n";
		$E=$_GET["page"];
		if ($E=="last") {
			$md=$g->result(count_rows($a, $Z, $ce, $sd));
			$E=floor(max(0, $md-1)/$_);
		}
		$fh=$L;
		$td=$sd;
		if (!$fh) {
			$fh[]="*";
			$Gb=convert_fields($e, $p, $L);
			if ($Gb)
				$fh[]=substr($Gb, 2);
		}
		foreach ($L as $z=>$X) {
			$o=$p[idf_unescape($X)];
			if ($o&&($Ga=convert_field($o)))
				$fh[$z]="$Ga AS $X";
		}
		if (!$ce&&$Ji) {
			foreach ($Ji as $z=>$X) {
				$fh[]=idf_escape($z);
				if ($td)
					$td[]=idf_escape($z);
			}
		}
		$H=$m->select($a, $fh, $Z, $td, $Bf, $_, $E, true);
		if (!$H)
			echo "<p class='error'>".error()."\n"; else {
			if ($y=="mssql"&&$E)
				$H->seek($_*$E);
			$yc=[];
			echo "<form action='' method='post' enctype='multipart/form-data'>\n";
			$K=[];
			while ($J=$H->fetch_assoc()) {
				if ($E&&$y=="oracle")
					unset($J["RNUM"]);
				$K[]=$J;
			}
			if ($_GET["page"]!="last"&&$_!=""&&$sd&&$ce&&$y=="sql")
				$md=$g->result(" SELECT FOUND_ROWS()");
			if (!$K)
				echo "<p class='message'>".lang(12)."\n"; else {
				$Pa=$b->backwardKeys($a, $Rh);
				echo "<div class='scrollable'>", "<table id='table' cellspacing='0' class='nowrap checkable'>", script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"), "<thead><tr>".(!$sd&&$L?"":"<td><input type='checkbox' id='all-page' class='jsonly'>".script("qs('#all-page').onclick = partial(formCheck, /check/);", "")." <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".lang(254)."</a>");
				$Ye=[];
				$pd=[];
				reset($L);
				$Bg=1;
				foreach ($K[0] as $z=>$X) {
					if (!isset($Ji[$z])) {
						$X=$_GET["columns"][key($L)];
						$o=$p[$L?($X?$X["col"]:current($L)):$z];
						$D=($o?$b->fieldName($o, $Bg):($X["fun"]?"*":$z));
						if ($D!="") {
							$Bg++;
							$Ye[$z]=$D;
							$d=idf_escape($z);
							$Gd=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($z);
							$dc="&desc%5B0%5D=1";
							echo "<th id='th[".h(bracket_escape($z))."]'>".script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});", ""), '<a href="'.h($Gd.($Bf[0]==$d||$Bf[0]==$z||(!$Bf&&$ce&&$sd[0]==$d)?$dc:'')).'">';
							echo apply_sql_function($X["fun"], $D)."</a>";
							echo "<span class='column hidden'>", "<a href='".h($Gd.$dc)."' title='".lang(59)."' class='text'> ↓</a>";
							if (!$X["fun"]) {
								echo '<a href="#fieldset-search" title="'.lang(56).'" class="text jsonly"> =</a>', script("qsl('a').onclick = partial(selectSearch, '".js_escape($z)."');");
							}
							echo "</span>";
						}
						$pd[$z]=$X["fun"];
						next($L);
					}
				}
				$xe=[];
				if ($_GET["modify"]) {
					foreach ($K as $J) {
						foreach ($J as $z=>$X)
							$xe[$z]=max($xe[$z], min(40, strlen(utf8_decode($X))));
					}
				}
				echo ($Pa?"<th>".lang(255):"")."</thead>\n";
				if (is_ajax()) {
					if ($_%2==1&&$E%2==1)
						odd();
					ob_end_clean();
				}
				foreach ($b->rowDescriptions($K, $jd) as $Xe=>$J) {
					$Gi=unique_array($K[$Xe], $x);
					if (!$Gi) {
						$Gi=[];
						foreach ($K[$Xe] as $z=>$X) {
							if (!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~', $z))
								$Gi[$z]=$X;
						}
					}
					$Hi="";
					foreach ($Gi as $z=>$X) {
						if (($y=="sql"||$y=="pgsql")&&preg_match('~char|text|enum|set~', $p[$z]["type"])&&strlen($X)>64) {
							$z=(strpos($z, '(')?$z:idf_escape($z));
							$z="MD5(".($y!='sql'||preg_match("~^utf8~", $p[$z]["collation"])?$z:"CONVERT($z USING ".charset($g).")").")";
							$X=md5($X);
						}
						$Hi.="&".($X!==null?urlencode("where[".bracket_escape($z)."]")."=".urlencode($X):"null%5B%5D=".urlencode($z));
					}
					echo "<tr".odd().">".(!$sd&&$L?"":"<td>".checkbox("check[]", substr($Hi, 1), in_array(substr($Hi, 1), (array)$_POST["check"])).($ce||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Hi)."' class='edit'>".lang(256)."</a>"));
					foreach ($J as $z=>$X) {
						if (isset($Ye[$z])) {
							$o=$p[$z];
							$X=$m->value($X, $o);
							if ($X!=""&&(!isset($yc[$z])||$yc[$z]!=""))
								$yc[$z]=(is_mail($X)?$Ye[$z]:"");
							$A="";
							if (preg_match('~blob|bytea|raw|file~', $o["type"])&&$X!="")
								$A=ME.'download='.urlencode($a).'&field='.urlencode($z).$Hi;
							if (!$A&&$X!==null) {
								foreach ((array)$jd[$z] as $r) {
									if (count($jd[$z])==1||end($r["source"])==$z) {
										$A="";
										foreach ($r["source"] as $t=>$yh)
											$A.=where_link($t, $r["target"][$t], $K[$Xe][$yh]);
										$A=($r["db"]!=""?preg_replace('~([?&]db=)[^&]+~', '\1'.urlencode($r["db"]), ME):ME).'select='.urlencode($r["table"]).$A;
										if ($r["ns"])
											$A=preg_replace('~([?&]ns=)[^&]+~', '\1'.urlencode($r["ns"]), $A);
										if (count($r["source"])==1)
											break;
									}
								}
							}
							if ($z=="COUNT(*)") {
								$A=ME."select=".urlencode($a);
								$t=0;
								foreach ((array)$_GET["where"] as $W) {
									if (!array_key_exists($W["col"], $Gi))
										$A.=where_link($t++, $W["col"], $W["val"], $W["op"]);
								}
								foreach ($Gi as $he=>$W)
									$A.=where_link($t++, $he, $W);
							}
							$X=select_value($X, $A, $o, $fi);
							$u=h("val[$Hi][".bracket_escape($z)."]");
							$Y=$_POST["val"][$Hi][bracket_escape($z)];
							$tc=!is_array($J[$z])&&is_utf8($X)&&$K[$Xe][$z]==$J[$z]&&!$pd[$z];
							$ei=preg_match('~text|lob~', $o["type"]);
							echo "<td id='$u'";
							if (($_GET["modify"]&&$tc)||$Y!==null) {
								$xd=h($Y!==null?$Y:$J[$z]);
								echo ">".($ei?"<textarea name='$u' cols='30' rows='".(substr_count($J[$z], "\n")+1)."'>$xd</textarea>":"<input name='$u' value='$xd' size='$xe[$z]'>");
							} else {
								$Ae=strpos($X, "<i>…</i>");
								echo " data-text='".($Ae?2:($ei?1:0))."'".($tc?"":" data-warning='".h(lang(257))."'").">$X</td>";
							}
						}
					}
					if ($Pa)
						echo "<td>";
					$b->backwardKeysPrint($Pa, $K[$Xe]);
					echo "</tr>\n";
				}
				if (is_ajax())
					exit;
				echo "</table>\n", "</div>\n";
			}
			if (!is_ajax()) {
				if ($K||$E) {
					$Ic=true;
					if ($_GET["page"]!="last") {
						if ($_==""||(count($K)<$_&&($K||!$E)))
							$md=($E?$E*$_:0)+count($K); elseif ($y!="sql"||!$ce) {
							$md=($ce?false:found_rows($R, $Z));
							if ($md<max(1e4, 2*($E+1)*$_))
								$md=reset(slow_query(count_rows($a, $Z, $ce, $sd))); else$Ic=false;
						}
					}
					$Pf=($_!=""&&($md===false||$md>$_||$E));
					if ($Pf) {
						echo(($md===false?count($K)+1:$md-$E*$_)>$_?'<p><a href="'.h(remove_from_uri("page")."&page=".($E+1)).'" class="loadmore">'.lang(258).'</a>'.script("qsl('a').onclick = partial(selectLoadMore, ".(+$_).", '".lang(259)."…');", ""):''), "\n";
					}
				}
				echo "<div class='footer'><div>\n";
				if ($K||$E) {
					if ($Pf) {
						$Ie=($md===false?$E+(count($K)>=$_?2:1):floor(($md-1)/$_));
						echo "<fieldset>";
						if ($y!="simpledb") {
							echo "<legend><a href='".h(remove_from_uri("page"))."'>".lang(260)."</a></legend>", script("qsl('a').onclick = function () { pageClick(this.href, +prompt('".lang(260)."', '".($E+1)."')); return false; };"), pagination(0, $E).($E>5?" …":"");
							for ($t=max(1, $E-4); $t<min($Ie, $E+5); $t++)
								echo pagination($t, $E);
							if ($Ie>0) {
								echo($E+5<$Ie?" …":""), ($Ic&&$md!==false?pagination($Ie, $E):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Ie'>".lang(261)."</a>");
							}
						} else {
							echo "<legend>".lang(260)."</legend>", pagination(0, $E).($E>1?" …":""), ($E?pagination($E, $E):""), ($Ie>$E?pagination($E+1, $E).($Ie>$E+1?" …":""):"");
						}
						echo "</fieldset>\n";
					}
					echo "<fieldset>", "<legend>".lang(262)."</legend>";
					$ic=($Ic?"":"~ ").$md;
					echo checkbox("all", 1, 0, ($md!==false?($Ic?"":"~ ").lang(150, $md):""), "var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$ic' : checked); selectCount('selected2', this.checked || !checked ? '$ic' : checked);")."\n", "</fieldset>\n";
					if ($b->selectCommandPrint()) {
						echo '<fieldset', ($_GET["modify"]?'':' class="jsonly"'), '><legend>', lang(254), '</legend><div>
<input type="submit" value="', lang(14), '"', ($_GET["modify"]?'':' title="'.lang(250).'"'), '>
</div></fieldset>
<fieldset><legend>', lang(126), ' <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="', lang(10), '">
<input type="submit" name="clone" value="', lang(246), '">
<input type="submit" name="delete" value="', lang(18), '">', confirm(), '</div></fieldset>
';
					}
					$kd=$b->dumpFormat();
					foreach ((array)$_GET["columns"] as $d) {
						if ($d["fun"]) {
							unset($kd['sql']);
							break;
						}
					}
					if ($kd) {
						print_fieldset("export", lang(73)." <span id='selected2'></span>");
						$Mf=$b->dumpOutput();
						echo($Mf?html_select("output", $Mf, $za["output"])." ":""), html_select("format", $kd, $za["format"]), " <input type='submit' name='export' value='".lang(73)."'>\n", "</div></fieldset>\n";
					}
					$b->selectEmailPrint(array_filter($yc, 'strlen'), $e);
				}
				echo "</div></div>\n";
				if ($b->selectImportPrint()) {
					echo "<div>", "<a href='#import'>".lang(72)."</a>", script("qsl('a').onclick = partial(toggle, 'import');", ""), "<span id='import' class='hidden'>: ", "<input type='file' name='csv_file'> ", html_select("separator", ["csv"=>"CSV,", "csv;"=>"CSV;", "tsv"=>"TSV"], $za["format"], 1);
					echo " <input type='submit' name='import' value='".lang(72)."'>", "</span>", "</div>";
				}
				echo "<input type='hidden' name='token' value='$qi'>\n", "</form>\n", (!$sd&&$L?"":script("tableCheck();"));
			}
		}
	}
	if (is_ajax()) {
		ob_end_clean();
		exit;
	}
} elseif (isset($_GET["variables"])) {
	$O=isset($_GET["status"]);
	page_header($O?lang(118):lang(117));
	$Xi=($O?show_status():show_variables());
	if (!$Xi)
		echo "<p class='message'>".lang(12)."\n"; else {
		echo "<table cellspacing='0'>\n";
		foreach ($Xi as $z=>$X) {
			echo "<tr>", "<th><code class='jush-".$y.($O?"status":"set")."'>".h($z)."</code>", "<td>".h($X);
		}
		echo "</table>\n";
	}
} elseif (isset($_GET["script"])) {
	header("Content-Type: text/javascript; charset=utf-8");
	if ($_GET["script"]=="db") {
		$Oh=["Data_length"=>0, "Index_length"=>0, "Data_free"=>0];
		foreach (table_status() as $D=>$R) {
			json_row("Comment-$D", h($R["Comment"]));
			if (!is_view($R)) {
				foreach (["Engine", "Collation"] as $z)
					json_row("$z-$D", h($R[$z]));
				foreach ($Oh+["Auto_increment"=>0, "Rows"=>0] as $z=>$X) {
					if ($R[$z]!="") {
						$X=format_number($R[$z]);
						json_row("$z-$D", ($z=="Rows"&&$X&&$R["Engine"]==($Ah=="pgsql"?"table":"InnoDB")?"~ $X":$X));
						if (isset($Oh[$z]))
							$Oh[$z]+=($R["Engine"]!="InnoDB"||$z!="Data_free"?$R[$z]:0);
					} elseif (array_key_exists($z, $R))
						json_row("$z-$D");
				}
			}
		}
		foreach ($Oh as $z=>$X)
			json_row("sum-$z", format_number($X));
		json_row("");
	} elseif ($_GET["script"]=="kill")
		$g->query("KILL ".number($_POST["kill"]));
	else {
		foreach (count_tables($b->databases()) as $l=>$X) {
			json_row("tables-$l", $X);
			json_row("size-$l", db_size($l));
		}
		json_row("");
	}
	exit;
} else {
	$Xh=array_merge((array)$_POST["tables"], (array)$_POST["views"]);
	if ($Xh&&!$n&&!$_POST["search"]) {
		$H=true;
		$Ne="";
		if ($y=="sql"&&$_POST["tables"]&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))
			queries("SET foreign_key_checks = 0");
		if ($_POST["truncate"]) {
			if ($_POST["tables"])
				$H=truncate_tables($_POST["tables"]);
			$Ne=lang(263);
		} elseif ($_POST["move"]) {
			$H=move_tables((array)$_POST["tables"], (array)$_POST["views"], $_POST["target"]);
			$Ne=lang(264);
		} elseif ($_POST["copy"]) {
			$H=copy_tables((array)$_POST["tables"], (array)$_POST["views"], $_POST["target"]);
			$Ne=lang(265);
		} elseif ($_POST["drop"]) {
			if ($_POST["views"])
				$H=drop_views($_POST["views"]);
			if ($H&&$_POST["tables"])
				$H=drop_tables($_POST["tables"]);
			$Ne=lang(266);
		} elseif ($y!="sql") {
			$H=($y=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?"":" ANALYZE"), $_POST["tables"]));
			$Ne=lang(267);
		} elseif (!$_POST["tables"])
			$Ne=lang(9);
		elseif ($H=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ", array_map('idf_escape', $_POST["tables"])))) {
			while ($J=$H->fetch_assoc())
				$Ne.="<b>".h($J["Table"])."</b>: ".h($J["Msg_text"])."<br>";
		}
		queries_redirect(substr(ME, 0, -1), $Ne, $H);
	}
	page_header(($_GET["ns"]==""?lang(37).": ".h(DB):lang(77).": ".h($_GET["ns"])), $n, true);
	if ($b->homepage()) {
		if ($_GET["ns"]!=="") {
			echo "<h3 id='tables-views'>".lang(268)."</h3>\n";
			$Wh=tables_list();
			if (!$Wh)
				echo "<p class='message'>".lang(9)."\n"; else {
				echo "<form action='' method='post'>\n";
				if (support("table")) {
					echo "<fieldset><legend>".lang(269)." <span id='selected2'></span></legend><div>", "<input type='search' name='query' value='".h($_POST["query"])."'>", script("qsl('input').onkeydown = partialArg(bodyKeydown, 'search');", ""), " <input type='submit' name='search' value='".lang(56)."'>\n", "</div></fieldset>\n";
					if ($_POST["search"]&&$_POST["query"]!="") {
						$_GET["where"][0]["op"]="LIKE %%";
						search_tables();
					}
				}
				echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap checkable'>\n", script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"), '<thead><tr class="wrap">', '<td><input id="check-all" type="checkbox" class="jsonly">'.script("qs('#check-all').onclick = partial(formCheck, /^(tables|views)\[/);", ""), '<th>'.lang(131), '<td>'.lang(270).doc_link(['sql'=>'storage-engines.html']), '<td>'.lang(122).doc_link(['sql'=>'charset-charsets.html', 'mariadb'=>'supported-character-sets-and-collations/']), '<td>'.lang(271).doc_link(['sql'=>'show-table-status.html', 'pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT', 'oracle'=>'REFRN20286']), '<td>'.lang(272).doc_link(['sql'=>'show-table-status.html', 'pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT']), '<td>'.lang(273).doc_link(['sql'=>'show-table-status.html']), '<td>'.lang(51).doc_link(['sql'=>'example-auto-increment.html', 'mariadb'=>'auto_increment/']), '<td>'.lang(274).doc_link(['sql'=>'show-table-status.html', 'pgsql'=>'catalog-pg-class.html#CATALOG-PG-CLASS', 'oracle'=>'REFRN20286']), (support("comment")?'<td>'.lang(50).doc_link(['sql'=>'show-table-status.html', 'pgsql'=>'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE']):''), "</thead>\n";
				$S=0;
				foreach ($Wh as $D=>$T) {
					$aj=($T!==null&&!preg_match('~table|sequence~i', $T));
					$u=h("Table-".$D);
					echo '<tr'.odd().'><td>'.checkbox(($aj?"views[]":"tables[]"), $D, in_array($D, $Xh, true), "", "", "", $u), '<th>'.(support("table")||support("indexes")?"<a href='".h(ME)."table=".urlencode($D)."' title='".lang(42)."' id='$u'>".h($D).'</a>':h($D));
					if ($aj) {
						echo '<td colspan="6"><a href="'.h(ME)."view=".urlencode($D).'" title="'.lang(43).'">'.(preg_match('~materialized~i', $T)?lang(129):lang(130)).'</a>', '<td align="right"><a href="'.h(ME)."select=".urlencode($D).'" title="'.lang(41).'">?</a>';
					} else {
						foreach (["Engine"=>[], "Collation"=>[], "Data_length"=>["create", lang(44)], "Index_length"=>["indexes", lang(133)], "Data_free"=>["edit", lang(45)], "Auto_increment"=>["auto_increment=1&create", lang(44)], "Rows"=>["select", lang(41)],] as $z=>$A) {
							$u=" id='$z-".h($D)."'";
							echo($A?"<td align='right'>".(support("table")||$z=="Rows"||(support("indexes")&&$z!="Data_length")?"<a href='".h(ME."$A[0]=").urlencode($D)."'$u title='$A[1]'>?</a>":"<span$u>?</span>"):"<td id='$z-".h($D)."'>");
						}
						$S++;
					}
					echo(support("comment")?"<td id='Comment-".h($D)."'>":"");
				}
				echo "<tr><td><th>".lang(247, count($Wh)), "<td>".h($y=="sql"?$g->result("SELECT @@default_storage_engine"):""), "<td>".h(db_collation(DB, collations()));
				foreach (["Data_length", "Index_length", "Data_free"] as $z)
					echo "<td align='right' id='sum-$z'>";
				echo "</table>\n", "</div>\n";
				if (!information_schema(DB)) {
					echo "<div class='footer'><div>\n";
					$Ui="<input type='submit' value='".lang(275)."'> ".on_help("'VACUUM'");
					$yf="<input type='submit' name='optimize' value='".lang(276)."'> ".on_help($y=="sql"?"'OPTIMIZE TABLE'":"'VACUUM OPTIMIZE'");
					echo "<fieldset><legend>".lang(126)." <span id='selected'></span></legend><div>".($y=="sqlite"?$Ui:($y=="pgsql"?$Ui.$yf:($y=="sql"?"<input type='submit' value='".lang(277)."'> ".on_help("'ANALYZE TABLE'").$yf."<input type='submit' name='check' value='".lang(278)."'> ".on_help("'CHECK TABLE'")."<input type='submit' name='repair' value='".lang(279)."'> ".on_help("'REPAIR TABLE'"):"")))."<input type='submit' name='truncate' value='".lang(280)."'> ".on_help($y=="sqlite"?"'DELETE'":"'TRUNCATE".($y=="pgsql"?"'":" TABLE'")).confirm()."<input type='submit' name='drop' value='".lang(127)."'>".on_help("'DROP TABLE'").confirm()."\n";
					$k=(support("scheme")?$b->schemas():$b->databases());
					if (count($k)!=1&&$y!="sqlite") {
						$l=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));
						echo "<p>".lang(281).": ", ($k?html_select("target", $k, $l):'<input name="target" value="'.h($l).'" autocapitalize="off">'), " <input type='submit' name='move' value='".lang(282)."'>", (support("copy")?" <input type='submit' name='copy' value='".lang(283)."'> ".checkbox("overwrite", 1, $_POST["overwrite"], lang(284)):""), "\n";
					}
					echo "<input type='hidden' name='all' value=''>";
					echo script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^(tables|views)\[/));".(support("table")?" selectCount('selected2', formChecked(this, /^tables\[/) || $S);":"")." }"), "<input type='hidden' name='token' value='$qi'>\n", "</div></fieldset>\n", "</div></div>\n";
				}
				echo "</form>\n", script("tableCheck();");
			}
			echo '<p class="links"><a href="'.h(ME).'create=">'.lang(74)."</a>\n", (support("view")?'<a href="'.h(ME).'view=">'.lang(204)."</a>\n":"");
			if (support("routine")) {
				echo "<h3 id='routines'>".lang(143)."</h3>\n";
				$Yg=routines();
				if ($Yg) {
					echo "<table cellspacing='0'>\n", '<thead><tr><th>'.lang(183).'<td>'.lang(49).'<td>'.lang(221)."<td></thead>\n";
					odd('');
					foreach ($Yg as $J) {
						$D=($J["SPECIFIC_NAME"]==$J["ROUTINE_NAME"]?"":"&name=".urlencode($J["ROUTINE_NAME"]));
						echo '<tr'.odd().'>', '<th><a href="'.h(ME.($J["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').urlencode($J["SPECIFIC_NAME"]).$D).'">'.h($J["ROUTINE_NAME"]).'</a>', '<td>'.h($J["ROUTINE_TYPE"]), '<td>'.h($J["DTD_IDENTIFIER"]), '<td><a href="'.h(ME.($J["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').urlencode($J["SPECIFIC_NAME"]).$D).'">'.lang(136)."</a>";
					}
					echo "</table>\n";
				}
				echo '<p class="links">'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.lang(220).'</a>':'').'<a href="'.h(ME).'function=">'.lang(219)."</a>\n";
			}
			if (support("sequence")) {
				echo "<h3 id='sequences'>".lang(285)."</h3>\n";
				$mh=get_vals("SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = current_schema() ORDER BY sequence_name");
				if ($mh) {
					echo "<table cellspacing='0'>\n", "<thead><tr><th>".lang(183)."</thead>\n";
					odd('');
					foreach ($mh as $X)
						echo "<tr".odd()."><th><a href='".h(ME)."sequence=".urlencode($X)."'>".h($X)."</a>\n";
					echo "</table>\n";
				}
				echo "<p class='links'><a href='".h(ME)."sequence='>".lang(226)."</a>\n";
			}
			if (support("type")) {
				echo "<h3 id='user-types'>".lang(26)."</h3>\n";
				$Si=types();
				if ($Si) {
					echo "<table cellspacing='0'>\n", "<thead><tr><th>".lang(183)."</thead>\n";
					odd('');
					foreach ($Si as $X)
						echo "<tr".odd()."><th><a href='".h(ME)."type=".urlencode($X)."'>".h($X)."</a>\n";
					echo "</table>\n";
				}
				echo "<p class='links'><a href='".h(ME)."type='>".lang(230)."</a>\n";
			}
			if (support("event")) {
				echo "<h3 id='events'>".lang(144)."</h3>\n";
				$K=get_rows("SHOW EVENTS");
				if ($K) {
					echo "<table cellspacing='0'>\n", "<thead><tr><th>".lang(183)."<td>".lang(286)."<td>".lang(210)."<td>".lang(211)."<td></thead>\n";
					foreach ($K as $J) {
						echo "<tr>", "<th>".h($J["Name"]), "<td>".($J["Execute at"]?lang(287)."<td>".$J["Execute at"]:lang(212)." ".$J["Interval value"]." ".$J["Interval field"]."<td>$J[Starts]"), "<td>$J[Ends]", '<td><a href="'.h(ME).'event='.urlencode($J["Name"]).'">'.lang(136).'</a>';
					}
					echo "</table>\n";
					$Gc=$g->result("SELECT @@event_scheduler");
					if ($Gc&&$Gc!="ON")
						echo "<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($Gc)."\n";
				}
				echo '<p class="links"><a href="'.h(ME).'event=">'.lang(209)."</a>\n";
			}
			if ($Wh)
				echo script("ajaxSetHtml('".js_escape(ME)."script=db');");
		}
	}
}
page_footer();