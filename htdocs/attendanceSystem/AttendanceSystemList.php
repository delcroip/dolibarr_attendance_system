<?php
/*
 * Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018 delcroip <patrick@pmpd.eu>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
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
 *        \file       dev/AttendanceSystems/AttendanceSystemPage.php
 *                \ingroup    attendanceSystem othermodule1 othermodule2
 *                \brief      This file is an example of a php page
 *                                        Initialy built by build_class_from_table on 2019-01-30 16:24
 */
//if(! defined('NOREQUIREUSER'))  define('NOREQUIREUSER', '1');
//if(! defined('NOREQUIREDB'))    define('NOREQUIREDB', '1');
//if(! defined('NOREQUIRESOC'))   define('NOREQUIRESOC', '1');
//if(! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN', '1');
//if(! defined('NOCSRFCHECK'))    define('NOCSRFCHECK', '1');                        // Do not check anti CSRF attack test
//if(! defined('NOSTYLECHECK'))   define('NOSTYLECHECK', '1');                        // Do not check style html tag into posted data
//if(! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', '1');                // Do not check anti POST attack test
//if(! defined('NOREQUIREMENU'))  define('NOREQUIREMENU', '1');                        // If there is no need to load and show top and left menu
//if(! defined('NOREQUIREHTML'))  define('NOREQUIREHTML', '1');                        // If we don't need to load the html.form.class.php
//if(! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX', '1');
//if(! defined("NOLOGIN"))        define("NOLOGIN", '1');                                // If this page is public (can be called outside logged session)
// Change this following line to use the correct relative path (../, ../../, etc)
include 'core/lib/includeMain.lib.php';
// Change this following line to use the correct relative path from htdocs
//include_once(DOL_DOCUMENT_ROOT.'/core/class/formcompany.class.php');
//require_once 'lib/attendanceSystem.lib.php';
require_once 'class/AttendanceSystem.class.php';
require_once 'core/lib/generic.lib.php';
require_once 'core/lib/AttendanceSystem.lib.php';
include_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
//document handling
include_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
//include_once DOL_DOCUMENT_ROOT.'/core/lib/images.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formother.class.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.formprojet.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/project.class.php';
require_once DOL_DOCUMENT_ROOT.'/projet/class/task.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';
$PHP_SELF = $_SERVER['PHP_SELF'];
// Load traductions files requiredby by page
//$langs->load("companies");
$langs->load("AttendanceSystem@attendanceSystem");
// Get parameter
$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');
$action = GETPOST('action', 'alpha');
$backtopage = GETPOST('backtopage');
$cancel = GETPOST('cancel');
$confirm = GETPOST('confirm');
$tms = GETPOST('tms', 'alpha');
//// Get parameters
$sortfield = GETPOST('sortfield', 'alpha');
$sortorder = GETPOST('sortorder', 'alpha')?GETPOST('sortorder', 'alpha'):'ASC';
$removefilter = isset($_POST["removefilter_x"]) || isset($_POST["removefilter"]);
//$applyfilter = isset($_POST["search_x"]) ;//|| isset($_POST["search"]);
if(!$removefilter) {
    // Both test must be present to be compatible with all browsers {
    $ls_label = GETPOST('ls_label', 'alpha');
    $ls_ip = GETPOST('ls_ip', 'alpha');
    $ls_port = GETPOST('ls_port', 'int');
    $ls_note = GETPOST('ls_note', 'alpha');
    $ls_third_party = GETPOST('ls_third_party', 'int');
    $ls_task = GETPOST('ls_task', 'int');
    $ls_project = GETPOST('ls_project', 'int');
    $ls_serial_nb = GETPOST('ls_serial_nb', 'int');
    $ls_zone = GETPOST('ls_zone', 'int');
    $ls_passwd = GETPOST('ls_passwd', 'alpha');
    $ls_status = GETPOST('ls_status', 'int');
    $ls_mode = GETPOST('ls_mode', 'int');
}
$page = GETPOST('page', 'int');
if($page <= 0){
    $page = 0;
}
$limit = GETPOST('limit', 'int')?GETPOST('limit', 'int'):$conf->liste_limit;
$offset = $limit * $page;
$pageprev = $page - 1;
$pagenext = $page + 1;
 // uncomment to avoid resubmision
//if(isset($_SESSION['AttendanceSystem_class'][$tms]))
//{
 //   $cancel = true;
 //  setEventMessages('Internal error, POST not exptected', null, 'errors');
//}
// Right Management
 /*
if($user->societe_id > 0 ||
       (!$user->rights->attendanceSystem->add && ($action == 'add' || $action = 'create')) ||
       (!$user->rights->attendanceSystem->view && ($action == 'list' || $action = 'view')) ||
       (!$user->rights->attendanceSystem->delete && ($action == 'confirm_delete')) ||
       (!$user->rights->attendanceSystem->edit && ($action == 'edit' || $action = 'update')))
{
        accessforbidden();
}
*/
// create object and set id or ref if provided as parameter
$object = new AttendanceSystem($db);
if($id>0) {
    $object->id = $id;
    $object->fetch($id);
    $ref = dol_sanitizeFileName($object->ref);
}
if(!empty($ref)) {
    $object->ref = $ref;
    $object->id = $id;
    $object->fetch($id, $ref);
    $ref = dol_sanitizeFileName($object->ref);
}
/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/
// Action to remove record
switch($action) {
    case 'confirm_delete':
       $result = ($confirm == 'yes')?$object->delete($user):0;
       if($result > 0) {
               // Delete OK
               setEventMessage($langs->trans('RecordDeleted'), 'mesgs');
       } else {
               // Delete NOK
               if(! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
               else setEventMessage('RecordNotDeleted', 'errors');
       }
       break;
    case 'delete':
        if($action == 'delete' && ($id>0 || $ref!="")) {
         $ret = $form->form_confirm(dol_buildpath('/attendanceSystem/AttendanceSystemCard.php', 1).'?action=confirm_delete&id='.$id, $langs->trans('DeleteAttendanceSystem'), $langs->trans('ConfirmDelete'), 'confirm_delete', '', 0, 1);
         if($ret == 'html') print '<br />';
         //to have the object to be deleted in the background\
        }
        break;
}
/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/
llxHeader('', 'AttendanceSystem', '');
print "<div> <!-- module body-->";
$form = new Form($db);
$formother = new FormOther($db);
$formproject = new FormProjets($db);
$fuser = new User($db);
// Put here content of your page
// Example : Adding jquery code
/*print '<script type = "text/javascript" language = "javascript">
jQuery(document).ready(function()
{
        function init_myfunc()
        {
                jQuery("#myid").removeAttr(\'disabled\');
                jQuery("#myid").attr(\'disabled\', \'disabled\');
        }
        init_myfunc();
        jQuery("#mybutton").click(function()
{
                init_needroot();
        });
});
</script>';*/
    $sql = 'SELECT';
    $sql .= ' t.rowid, ';
    $sql .= ' t.label,';
    $sql .= ' t.ip,';
    $sql .= ' t.port,';
    $sql .= ' t.note,';
    $sql .= ' t.fk_third_party,';
    $sql .= ' t.fk_task,';
    $sql .= ' t.fk_project,';
    $sql .= ' t.serial_nb,';
    $sql .= ' t.zone,';
    $sql .= ' t.passwd,';
    $sql .= ' t.status,';
    $sql .= ' t.mode';
    $sql .= ' FROM '.MAIN_DB_PREFIX.'attendance_system as t';
    $sqlwhere = '';
    if(isset($object->entity))
        $sqlwhere .= ' AND t.entity = '.$conf->entity;
    if($filter && $filter != -1) {
        // GETPOST('filtre') may be a string {
        $filtrearr = explode(', ', $filter);
        foreach($filtrearr as $fil) {
                $filt = explode(':', $fil);
                $sqlwhere .= ' AND ' . $filt[0] . ' = ' . $filt[1];
        }
    }
    //pass the search criteria
        if($ls_label) $sqlwhere .= natural_search('t.label', $ls_label);
        if($ls_ip) $sqlwhere .= natural_search('t.ip', $ls_ip);
        if($ls_port) $sqlwhere .= natural_search(array('t.port'), $ls_port);
        if($ls_note) $sqlwhere .= natural_search('t.note', $ls_note);
        if($ls_third_party) $sqlwhere .= natural_search(array('t.fk_third_party'), $ls_third_party);
        if($ls_task) $sqlwhere .= natural_search(array('t.fk_task'), $ls_task);
        if($ls_project) $sqlwhere .= natural_search(array('t.fk_project'), $ls_project);
	if($ls_serial_nb) $sqlwhere .= natural_search(array('t.serial_nb'), $ls_serial_nb);
	if($ls_zone) $sqlwhere .= natural_search(array('t.zone'), $ls_zone);
	if($ls_passwd) $sqlwhere .= natural_search('t.passwd', $ls_passwd);
	if($ls_status) $sqlwhere .= natural_search(array('t.status'), $ls_status);
	if($ls_mode) $sqlwhere .= natural_search(array('t.mode'), $ls_mode);
    //list limit
    if(!empty($sqlwhere))
        $sql .= ' WHERE '.substr($sqlwhere, 5);
// Count total nb of records
$nbtotalofrecords = 0;
if(empty($conf->global->MAIN_DISABLE_FULL_SCANLIST)) {
        $sqlcount = 'SELECT COUNT(*) as count FROM '.MAIN_DB_PREFIX.'attendance_system as t';
        if(!empty($sqlwhere))
            $sqlcount .= ' WHERE '.substr($sqlwhere, 5);
        $result = $db->query($sqlcount);
        $nbtotalofrecords = ($result)?$objcount = $db->fetch_object($result)->count:0;
}
    if(!empty($sortfield)) {
        $sql .= $db->order($sortfield, $sortorder);
    } else{
        $sortorder = 'ASC';
    }
    if(!empty($limit)) {
            $sql .= $db->plimit($limit+1, $offset);
    }
    //execute SQL
    dol_syslog($script_file, LOG_DEBUG);
    $resql = $db->query($sql);
    if($resql) {
        $param = '';
        if(! empty($contextpage) && $contextpage != $_SERVER["PHP_SELF"]) $param .= '&contextpage='.urlencode($contextpage);
        if($limit > 0 && $limit != $conf->liste_limit) $param .= '&limit='.urlencode($limit);
         if(!empty($ls_label))        $param .= '&ls_label='.urlencode($ls_label);
         if (!empty($ls_ip))	$param .= '&ls_ip='.urlencode($ls_ip);
         if (!empty($ls_port))	$param .= '&ls_port='.urlencode($ls_port);
         if (!empty($ls_note))	$param .= '&ls_note='.urlencode($ls_note);
         if (!empty($ls_third_party))	$param .= '&ls_third_party='.urlencode($ls_third_party);
         if (!empty($ls_task))	$param .= '&ls_task='.urlencode($ls_task);
         if (!empty($ls_project))	$param .= '&ls_project='.urlencode($ls_project);
         if (!empty($ls_serial_nb))	$param .= '&ls_serial_nb='.urlencode($ls_serial_nb);
         if (!empty($ls_zone))	$param .= '&ls_zone='.urlencode($ls_zone);
         if (!empty($ls_passwd))	$param .= '&ls_passwd='.urlencode($ls_passwd);
         if (!empty($ls_status))	$param .= '&ls_status='.urlencode($ls_status);
         if (!empty($ls_mode))	$param .= '&ls_mode='.urlencode($ls_mode);
        $num = $db->num_rows($resql);
        //print_barre_liste function defined in /core/lib/function.lib.php, possible to add a picto
        print_barre_liste($langs->trans("AttendanceSystem"), $page, $PHP_SELF, $param, $sortfield, $sortorder, '', $num, $nbtotalofrecords);
        print_barre_liste($title, $page, $_SERVER["PHP_SELF"], $param, $sortfield, $sortorder, $massactionbutton, $num, $nbtotalofrecords, 'title_companies', 0, '', '', $limit);
        print '<form method = "POST" action = "'.$_SERVER["PHP_SELF"].'">';
        print '<table class = "liste" width = "100%">'."\n";
        //TITLE
        print '<tr class = "liste_titre">';
         print_liste_field_titre($langs->trans('Label'), $PHP_SELF, 't.label', '', $param, '', $sortfield, $sortorder);
         print "\n";
         print_liste_field_titre($langs->trans('Ip'), $PHP_SELF, 't.ip','', $param,'', $sortfield, $sortorder);
         print "\n";
         print_liste_field_titre($langs->trans('Thirdparty'), $PHP_SELF, 't.fk_third_party','', $param,'', $sortfield, $sortorder);
         print "\n";
         print_liste_field_titre($langs->trans('Task'), $PHP_SELF, 't.fk_task','', $param,'', $sortfield, $sortorder);
         print "\n";
         print_liste_field_titre($langs->trans('Project'), $PHP_SELF, 't.fk_project','', $param,'', $sortfield, $sortorder);
         print "\n";
         print_liste_field_titre($langs->trans('Zone'), $PHP_SELF, 't.zone','', $param,'', $sortfield, $sortorder);
         print "\n";
         print_liste_field_titre($langs->trans('Status'), $PHP_SELF, 't.status','', $param,'', $sortfield, $sortorder);
         print "\n";
        print '</tr>';
        //SEARCH FIELDS
        print '<tr class = "liste_titre">';
        //Search field forlabel
        print '<td class = "liste_titre" colspan = "1" >';
        print '<input class = "flat" size = "16" type = "text" name = "ls_label" value = "'.$ls_label.'">';
        print '</td>';
//Search field forip
        print '<td class = "liste_titre" colspan = "1" >';
        print '<input class = "flat" size = "16" type = "text" name = "ls_ip" value = "'.$ls_ip.'">';
        print '</td>';
//Search field forthird_party
        print '<td class = "liste_titre" colspan = "1" >';
        $selected = $ls_third_party;
        $htmlname = 'ls_third_party';
        print  $form->select_company($selected, $htmlname);
        print '</td>';
//Search field fortask
        print '<td class = "liste_titre" colspan = "1" >';
        $selected = $ls_task;
        $htmlname = 'ls_task';
        $formproject->selectTasks(-1, $selected, $htmlname);
        print '</td>';
//Search field forproject
        print '<td class = "liste_titre" colspan = "1" >';
        $selected = $ls_project;
        $htmlname = 'ls_project';
        $formproject->select_projects(-1, $selected, $htmlname);
        print '</td>';
//Search field forzone
        print '<td class="liste_titre" colspan="1" >';
        print '<input class="flat" size="16" type="text" name="ls_zone" value="'.$ls_zone.'">';
        print '</td>';
//Search field status
        print '<td class="liste_titre" colspan="1" >';
        $object->status = $ls_status;
        print $object->selectLibStatut($form, 'ls_status');
        print '</td>';
// buttons
        print '<td width = "15px">';
        print '<input type = "image" class = "liste_titre" name = "search" src = "'.img_picto($langs->trans("Search"), 'search.png', '', '', 1).'" value = "'.dol_escape_htmltag($langs->trans("Search")).'" title = "'.dol_escape_htmltag($langs->trans("Search")).'">';
        print '<input type = "image" class = "liste_titre" name = "removefilter" src = "'.img_picto($langs->trans("Search"), 'searchclear.png', '', '', 1).'" value = "'.dol_escape_htmltag($langs->trans("RemoveFilter")).'" title = "'.dol_escape_htmltag($langs->trans("RemoveFilter")).'">';
        print '</td>';
        print '</tr>'."\n";
        $i = 0;
        $basedurl = dirname($PHP_SELF).'/AttendanceSystemCard.php?action=view&id=';
        while($i < $num && $i<$limit)
        {
            $obj = $db->fetch_object($resql);
            if($obj) {
                // You can use here results
                print "<tr class = \"oddeven\"  onclick = \"location.href='";
                print $basedurl.$obj->rowid."'\" >";
                print "<td>".$obj->label."</td>";
                print "<td>".$obj->ip."</td>";
                if($obj->fk_third_party>0) {
                        $StaticObject = New Societe($db);
                        $StaticObject->fetch($obj->fk_task);
                        print "<td>".$StaticObject->getNomUrl(1)."</td>";
                }else{
                        print "<td></td>";
                }
                if($obj->fk_task>0) {
                        $StaticObject = New Task($db);
                        $StaticObject->fetch($obj->fk_task);
                        print "<td>".$StaticObject->getNomUrl(1)."</td>";
                } else{
                        print "<td></td>";
                }
                if($obj->fk_project>0) {
                        $StaticObject = New Project($db);
                        $StaticObject->fetch($obj->fk_project);
                        print "<td>".$StaticObject->getNomUrl(1)."</td>";
                }else{
                        print "<td></td>";
                }
                print "<td>".$obj->zone."</td>";
                print "<td>".$object->libStatut($obj->status)."</td>";
                print '<td><a href = "AttendanceSystemCard.php?action=delete&id='.$obj->rowid.'">'.img_delete().'</a></td>';
                print "</tr>";
            }
            $i++;
        }
    } else {
        $error++;
        dol_print_error($db);
    }
    print '</table>'."\n";
    print '</form>'."\n";
    // new button
    print '<a href = "AttendanceSystemCard.php?action=create" class = "butAction" role = "button">'.$langs->trans('New');
    print ' '.$langs->trans('AttendanceSystem')."</a>\n";
// End of page
llxFooter();
$db->close();
