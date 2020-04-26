<?php
/*
 * This program is free software;you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation;either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY;without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 *  \file       htdocs/admin/project.php
 *  \ingroup    project
 *  \brief      Page to setup project module
 */
include '../core/lib/includeMain.lib.php';
include '../core/lib/generic.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';
$langs->load("admin");
$langs->load("errors");
$langs->load("other");
$langs->load("attendanceSystem@attendanceSystem");
if(!$user->admin) {
    $accessforbidden = accessforbidden("you need to be admin");
}
$action = getpost('action', 'alpha');
$minbreak = $conf->global->ATTENDANCE_MIN_OVERDAY_BREAK;

//hide/show
$maxhoursperday = $conf->global->ATTENDANCE_DAY_MAX_DURATION;
//event
$maxhoursperevent = $conf->global->ATTENDANCE_EVENT_MAX_DURATION;
$minsecondsperevent = $conf->global->ATTENDANCE_EVENT_MIN_DURATION;
$defaulthoursperevent = $conf->global->ATTENDANCE_EVENT_DEFAULT_DURATION;
#$blockTimespent = $conf->global->TIMESHEET_EVENT_NOT_CREATE_TIMESPENT;

//advanced
$tsRound = intval($conf->global->TIMESHEET_ROUND);


$error = 0;
/** make sure that there is a 0 iso null
 *
 * @param mixed $var var can be an int of empty string
 * @param type $int defautl value is var is null
 * @return int
 */
function null2int($var, $int = 0)
{
    return($var == '' || !is_numeric($var))?$int:$var;
}

switch($action) {
    case "save":
        //general option
   
        $maxhoursperevent = getpost('maxhoursperevent', 'int');
        dolibarr_set_const($db, "ATTENDANCE_EVENT_MAX_DURATION", $maxhoursperevent, 'int', 0, '', $conf->entity);
        $minsecondsperevent = getpost('minSecondsPerEvent', 'int');
        dolibarr_set_const($db, "ATTENDANCE_EVENT_MIN_DURATION", $minsecondsperevent, 'int', 0, '', $conf->entity);
        $defaulthoursperevent = getpost('defaulthoursperevent', 'int');
        dolibarr_set_const($db, "ATTENDANCE_EVENT_DEFAULT_DURATION", $defaulthoursperevent, 'int', 0, '', $conf->entity);
        $maxhoursperday = getpost('maxhoursperday', 'int');
        dolibarr_set_const($db, "ATTENDANCE_DAY_MAX_DURATION", $maxhoursperday, 'int', 0, '', $conf->entity);
        $minbreak = getpost('minBreak', 'int');
        dolibarr_set_const($db, "ATTENDANCE_MIN_OVERDAY_BREAK", $minbreak, 'int', 0, '', $conf->entity);
        $tsRound = getpost('tsRound', 'int');
        dolibarr_set_const($db, "ATTENDANCE_ROUND", $tsRound, 'int', 0, '', $conf->entity);
        $clrAttendance = getpost('clrAttendance', 'int');
        dolibarr_set_const($db, "ATTENDANCE_CLEAR_EVENT", $clrAttendance, 'int', 0, '', $conf->entity);
        break;
    default:
        break;
}

/*
 *  VIEW
 *  */
//permet d'afficher la structure dolibarr
//$morejs = array("/attendanceSystem/core/js/attendanceSystem.js?".$conf->global->ATTENDANCE_VERSION, "/attendanceSystem/core/js/jscolor.js");
llxHeader("", $langs->trans("attendanceSystemSetup"), '', '', '', '', $morejs, '', 0, 0);
if($action = "save")echo "<script>window.history.pushState('', '', '".explode('?', $_SERVER['REQUEST_URI'], 2)[0]."');</script>";
$linkback = '<a href = "'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("attendanceSystemSetup"), $linkback, 'title_setup');
echo '<div class = "fiche"><br><br>';

/*
 * General
 */

print_titre($langs->trans("GeneralOption"));
echo '<form name = "settings" action="?action=save" method = "POST" >'."\n\t";
echo '<table class = "noborder" width = "100%">'."\n\t\t";
echo '<tr class = "liste_titre" width = "100%" ><th width = "200px">'.$langs->trans("Name").'</th><th>';
echo $langs->trans("Description").'</th><th width = "100px">'.$langs->trans("Value")."</th></tr>\n\t\t";


//max hours perdays
echo '<tr class = "oddeven"><td align = "left">'.$langs->trans("maxhoursperdays");//FIXTRAD
echo '</td><td align = "left">'.$langs->trans("maxhoursPerDaysDesc").'</td>';// FIXTRAD
echo '<td align = "left"><input type = "text" name = "maxhoursperday" value = "'.$maxhoursperday;
echo "\" size = \"4\" ></td></tr>\n\t\t";

//min break hours 
echo '<tr class = "oddeven"><td align = "left">'.$langs->trans("minBreak");//FIXTRAD
echo '</td><td align = "left">'.$langs->trans("minBreakDesc").'</td>';// FIXTRAD
echo '<td align = "left"><input type = "text" name = "minBreak" value = "'.$minbreak;
echo "\" size = \"4\" ></td></tr>\n\t\t";

//min hours per event
echo '<tr class = "oddeven"><td align = "left">'.$langs->trans("minSecondsPerEvent");
echo '</td><td align = "left">'.$langs->trans("minSecondsPerEventDesc").'</td>';
echo '<td align = "left"><input type = "text" name = "minSecondsPerEvent" value = "'.$minsecondsperevent;
echo "\" size = \"4\" ></td></tr>\n\t\t";
//max hours per event
echo '<tr class = "oddeven"><td align = "left">'.$langs->trans("maxHoursPerEvent");
echo '</td><td align = "left">'.$langs->trans("maxHoursPerEventDesc").'</td>';
echo '<td align = "left"><input type = "text" name = "maxhoursperevent" value = "'.$maxhoursperevent;
echo "\" size = \"4\" ></td></tr>\n\t\t";
//default hours per event
echo '<tr class = "oddeven"><td align = "left">'.$langs->trans("defaultHoursPerEvent");
echo '</td><td align = "left">'.$langs->trans("defaultHoursPerEventDesc").'</td>';
echo '<td align = "left"><input type = "text" name = "defaulthoursperevent" value = "'.$defaulthoursperevent;
echo "\" size = \"4\" ></td></tr>\n\t\t";

// ROUND
echo '<tr class = "oddeven" ><td align = "left">'.$langs->trans("tsRound");
echo '</td><td align = "left">'.$langs->trans("tsRoundDesc").'</td>';
echo '<td  align = "left"><input type = "text" name = "tsRound" value = "'.$tsRound;
echo "\" size = \"4\" ></td></tr>\n\t\t";

// clear attendance
echo  '<tr class = "oddeven"><td align = "left">'.$langs->trans("ClrAttendance");
echo '</td><td align = "left">'.$langs->trans("ClrAttendanceDesc").'</td>';
echo  '<td align = "left"><input type = "checkbox" name = "clrAttendance" value = "1" ';
echo (($clrAttendance == '1')?'checked':'')."></td></tr>\n\t\t";

echo '</table><input type = "submit" class = "butAction" value = "'.$langs->trans('Save')."\">\n</from>";
echo '<br><br><br>';

llxFooter();
$db->close();
