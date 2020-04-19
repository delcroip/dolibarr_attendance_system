<?php
/*
 * Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018           Patrick DELCROIX     <pmpdelcroix@gmail.com>
 *  * Copyright (C) ---Put here your own copyright and developer email---
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
/** fonction to come back on the previous page
 *
 * @param string $backtopage url from
 * @param int $id  object id
 * @param string $ref object ref
 * @return null
 */
function AttendanceSystemReloadPage($backtopage, $id, $ref)
{
    if(!empty($backtopage)) {
        header("Location: ".$backtopage);
    } elseif(!empty($ref)) {
        header("Location: ".dol_buildpath("/attendanceSystem/AttendanceSystemCard.php", 1).'?ref='.$ref);
    } elseif($id>0) {
        header("Location: ".dol_buildpath("/attendanceSystem/AttendanceSystemCard.php", 1).'?id='.$id);
    } else {
        header("Location: ".dol_buildpath("/attendanceSystem/AttendanceSystemList.php", 1));
    }
    ob_end_flush();
    exit();
}
/**
 * Prepare array of tabs for AttendanceSystem
 *
 * @param        AttendanceSystem        $object                AttendanceSystem
 * @return        array                                        Array of tabs
 */
function AttendanceSystemPrepareHead($object)
{
        global $db, $langs, $conf;
        $langs->load("attendanceSystem@attendanceSystem");
        $h = 0;
        $head = array();
        $head[$h][0] = dol_buildpath("/attendanceSystem/AttendanceSystemCard.php", 1).'?id='.$object->id;
        $head[$h][1] = $langs->trans("Card");
        $head[$h][2] = 'card';
        $h++;
        require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
        require_once DOL_DOCUMENT_ROOT.'/core/class/link.class.php';
        $upload_dir = $conf->attendanceSystem->dir_output . "/AttendanceSystem/" . dol_sanitizeFileName($object->ref);
        $nbFiles = count(dol_dir_list($upload_dir, 'files', 0, '', '(\.meta|_preview.*\.png)$'));
        $nbLinks = Link::count($db, $object->element, $object->id);
        $head[$h][0] = dol_buildpath("/attendanceSystem/AttendanceSystemDoc.php", 1).'?id='.$object->id;
        $head[$h][1] = $langs->trans('Documents');
        if(($nbFiles+$nbLinks) > 0) $head[$h][1].= ' <span class = "badge">'.($nbFiles+$nbLinks).'</span>';
        $head[$h][2] = 'document';
        $h++;


        // Show more tabs from modules
        // Entries must be declared in modules descriptor with line
        //$this->tabs = array(
        //        'entity:+tabname:Title:@attendanceSystem:/attendanceSystem/mypage.php?id=__ID__'
        //);// to add new tab
        //$this->tabs = array(
        //        'entity:-tabname:Title:@attendanceSystem:/attendanceSystem/mypage.php?id=__ID__'
        //);// to remove a tab
        complete_head_from_modules($conf, $langs, $object, $head, $h, 'AttendanceSystem@attendanceSystem');
        return $head;
}
