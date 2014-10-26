<?php

/**
 * Currency Trader
 * Copyright (C) Kyle
 * All rights reserved.
 * 
 * Do not remove this copyright header
 *
 * File: currencytrader.php
 * Date: Oct 25, 2014 4:57:19 PM
 */
include_once(__DIR__ . '/globals.php');

if (!isset($_GET['action'])) {
    $_GET['action'] = 'index';
}
switch ($_GET['action']) {
    case 'trade':
        trade();
        break;
    default :
        index();
}

function index() {
    global $db, $ir;
    $q = $db->query("select id, name, stat_name, stat_name_cost, description from currency_trader");
    echo "<table class='table' width='90%' cellpadding='3'>";
    echo "<th colspan='100%' align='center'>Currency Trader</th>";
    if (!$db->num_rows($q)) {
        echo "<tr style='text-align:center; color:red;'><td>Nothing seems to exist</td></tr>";
    }
    else {
        echo "<tr style='text-align:center;'><th>&nbsp;</th><th>Name</th><th>Description</th><th>Actions</th></tr>";
        $i = 0;
        while($r = $db->fetch_row($q)) {
            $i++;
        echo "<tr><td>{$i}</td><td>".htmlentities($r['name'])."</td><td>".html_entity_decode($r['description'])."</td><td>";
            if($ir[$r['stat_name']] >= $r['stat_name_cost']) {
                echo "<input type=\"button\" onclick=\"window.location.href='?action=trade&id={$r['id']}'\" value='Use'></input>";
            }
            else {
               echo "<button disabled='disabled'>Use</button>"; 
            }
            echo "</td></tr>";
            
        }      
    }
    echo "</table>";
}

function trade() {
    global $db, $ir,$userid;
    $q = $db->query("select name, stat_name, stat_name_cost, description, call_back from currency_trader where id = ".abs((int)$_GET['id']));
    if(!$db->num_rows($q)) {
        echo "<span style='text-align:center; color:red;'>This doesnt seem to exist!</span>";
    }
    else {
        $r = $db->fetch_row($q);
        $prettyStat = htmlentities(ucwords(str_ireplace(array("-","_"), "&nbsp;", $r['stat_name'])));
        if($ir[$r['stat_name']] < $r['stat_name_cost']) {
            echo "<span style='text-align:center; color:red;'>You dont seem to have enough {$prettyStat} to trade for this!</span>";
        }
        $format = ($r['stat_name'] == "money" ? money_formatter($r['stat_name_cost']) : number_format($r['stat_name_cost']));
        eval(html_entity_decode($r['call_back']));
        echo "<span style='text-align:center; color:green;'>You traded {$format} {$prettyStat} for this!</span>";
    }

    index();
}

function updatestat($statName, $newValue, $user = null) {
    global $db, $userid;
    if ($user == null) {
        $user = $userid;
    }
    if (is_integer($newValue)) {
        $newValue = abs((int) $newValue);
    }
    else {
        $newValue = $db->escape($newValue);
    }
    $statName = $db->escape($statName);
    $query = $db->query("select `{$statName}` from users where userid = {$user}");
    if ($db->num_rows($query)) {
        $db->query("update users set `{$statName}` = '{$newValue}' where userid = {$user}");
        return true;
    }
    else {
        return false;
    }
}
