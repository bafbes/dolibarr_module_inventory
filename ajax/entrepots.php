<?php
/* Copyright (C) 2006      Andre Cianfarani     <acianfa@free.fr>
 * Copyright (C) 2005-2013 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2007-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file receptions/product/ajax/products.php
 * \brief File to return Ajax response on product list request
 */

$res = @include("../../main.inc.php");                    // For root directory
if(!$res && file_exists($_SERVER['DOCUMENT_ROOT'] . "/main.inc.php"))
    $res = @include($_SERVER['DOCUMENT_ROOT'] . "/main.inc.php"); // Use on dev env only
if(!$res) $res = @include("../../../main.inc.php");        // For "custom" directory
require_once DOL_DOCUMENT_ROOT . '/core/class/html.form.class.php';

$langs->load("products");
$langs->load("main");

$htmlname = GETPOST('htmlname', 'alpha');
top_httphead();

if(empty($htmlname) || !GETPOST($htmlname)) return;
$searchkey = GETPOST($htmlname);

    $sql = "SELECT rowid,label,lieu";
    $sql .= " FROM " . MAIN_DB_PREFIX . "entrepot as p";
    $sql.= ' WHERE p.entity IN ('.getEntity('product', 1).')';
    $sql .= " AND (p.description LIKE '%".$searchkey."%' OR p.label LIKE '%".$searchkey."%' OR p.lieu LIKE '%".$searchkey."%')";
    $result = $db->query($sql);
    if($result) {
        $num = $db->num_rows($result);
        $i = 0;
        while($num && $i < $num) {
            $objp = $db->fetch_object($result);
            $optJson = array(
                'rowid'=>$objp->rowid,
                'value' => $objp->label,
                'label' => $objp->lieu,
            );
            $outarray[] = $optJson;
            $i++;
        }
    }


$db->close();

print json_encode($outarray);


