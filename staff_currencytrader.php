<?php
/**
 * Currency Trader
 * Copyright (C) Kyle
 * All rights reserved.
 * 
 * Do not remove this copyright header
 *
 * File: staff_currencytrader.php
 * Date: Oct 25, 2014 4:57:19 PM
 */
include_once(__DIR__ . '/sglobals.php');

if (!isset($_GET['action'])) {
    $_GET['action'] = 'index';
}
switch ($_GET['action']) {
    case 'add':
        add();
        break;
    case 'edit':
        edit();
        break;
    case 'delete':
        delete();
        break;
    default :
        index();
}

function index() {
    global $db;
    $q = $db->query("select id, name, description from currency_trader");
    echo "<table class='table' width='90%' cellpadding='3'>";
    echo "<th colspan='100%' align='center'>Currency Trader</th>";
    echo "<tr style='text-align:center;'><th>&nbsp;</th><th>Name</th><th>Description</th><th>Actions</th></tr>";
    if (!$db->num_rows($q)) {
        echo "<tr>";
        echo "<td colspan='100%' style='text-align:center; font-weight:bold; color:#ff0000;'>Nothing to display</td>";
        echo"</tr>";
    }
    else {
        $i = 0;
        while ($r = $db->fetch_row($q)) {
            $i++;
            echo "<tr>";
            echo "<td>" . $i . "</td>";
            echo "<td>" . $r["name"] . "</td>";
            echo "<td>" . htmlentities($r["description"]) . "</td>";
            echo "<td><a href='?action=edit&id=" . $r["id"] . "'>[Edit]</a>&nbsp;&nbsp;<a href='?action=delete&id=" . $r["id"] . "'>[Delete]</a></td>";
            echo "</tr>";
            echo "<tr'>";
        }
    }
    echo "<th colspan='100%' style='width:100%; text-align:center;'><a href='?action=add'>[Add&nbsp;New]</a>&nbsp;&nbsp;<a href='staff.php'>[Back]</a</th>";
    echo "</tr>";
    echo "</table>";
}

function add() {
    global $db;
    echo "<table class='table' cellpadding='3'>";
    if (isset($_POST['add_trade'])) {
        $_POST['name'] = trim($db->escape(strip_tags(stripslashes($_POST['name']))));
        $_POST['stat_name'] = trim($db->escape(strip_tags(stripslashes($_POST['stat_name']))));
        $_POST['stat_name_cost'] = trim($_POST['stat_name_cost'] + 0);
        $_POST['description'] = trim($db->escape(strip_tags(stripslashes($_POST['description']))));
        $_POST['call_back'] = trim(htmlentities(strip_tags($_POST['call_back']), ENT_NOQUOTES, "UTF-8"));

        $q = $db->query("select id from currency_trader where name = '{$_POST['name']}'");
        if ($db->num_rows($q)) {
            echo "<span style='color:red;'>You are making a duplicate</span";
        }
        else if (empty($_POST['name']) || empty($_POST['stat_name']) || empty($_POST['stat_name_cost'])) {
            echo "<span style='color:red;'>Some of your required fields are blank</span>";
        }
        else {
            $update = $db->query("insert into currency_trader values(NULL,'{$_POST['name']}','{$_POST['stat_name']}','{$_POST['stat_name_cost']}','{$_POST['description']}','{$_POST['call_back']}')");
            if ($update) {
                echo "<span style='color:green;'>A new trader was successfully created</span>";
            }
            else {
                echo "<span style='color:red;'>There was an error inserting into the database</span>";
            }
        }
    }
    $name = (isset($_POST['name']) ? htmlentities($_POST['name']) : NULL);
    $statName = (isset($_POST['stat_name']) ? htmlentities($_POST['stat_name']) : '');
    $cost = (isset($_POST['stat_name_cost']) ? htmlentities($_POST['stat_name_cost']) : '');
    $desc = (isset($_POST['description']) ? htmlentities($_POST['description']) : '');
    $callBack = (isset($_POST['call_back']) ? htmlentities($_POST['call_back'], ENT_NOQUOTES, "UTF-8") : '');
    echo "<form action='?action=add' method='POST'>";
    echo "<th colspan='100%' align='center'>Add New Site</th>";
    echo "<tr>
			<td>Name</td>
   <td><input type='text' name='name' value='{$name}' /></td>
		</tr>
		<tr>
			<td>Stat Name</td>
			<td>" . usertable_dropdown("stat_name", $statName) . "<br />
		</tr>
		<tr>
			<td>Stat Cost</td>
   <td><input type='text' name='stat_name_cost' value='{$cost}' /></td>
		</tr>
		<tr>
			<td>Description</td>
   <td><textarea name='description' cols='80' rows='5'>{$desc}</textarea></td>
		</tr>
		<tr>
			<td>Call Back</td>
   <td><textarea name='call_back' cols='80' rows='5'>{$callBack}</textarea></td>
		</tr>";
    echo "<tr>
			<th colspan='100%'><input type='submit' value='Add Trade' name='add_trade' /></th>
		</tr>";

    echo "</form></table>";
}

function edit() {
    global $db;
    $id = abs((int) $_REQUEST['id']);
    $q = $db->query("select id from currency_trader where id = " . $id);
    if (isset($_POST['edit_trade'])) {
        $_POST['name'] = trim($db->escape(strip_tags(stripslashes($_POST['name']))));
        $_POST['stat_name'] = trim($db->escape(strip_tags(stripslashes($_POST['stat_name']))));
        $_POST['stat_name_cost'] = trim($_POST['stat_name_cost'] + 0);
        $_POST['description'] = trim($db->escape(strip_tags(stripslashes($_POST['description']))));
        $_POST['call_back'] = trim(htmlentities(strip_tags($_POST['call_back']), ENT_NOQUOTES, "UTF-8"));

        if (!$db->num_rows($q)) {
            echo "<span style='color:red;'>This trade doesnt exist</span>";
            return;
        }
        else if (empty($_POST['name']) || empty($_POST['stat_name']) || empty($_POST['stat_name_cost'])) {
            echo "<span style='color:red;'>Some of your required fields are blank</span>";
        }
        else {
            $update = $db->query("update currency_trader set name = '{$_POST['name']}', stat_name = '{$_POST['stat_name']}', stat_name_cost = '{$_POST['stat_name_cost']}', description = '{$_POST['description']}', call_back = '{$_POST['call_back']}' where id  = {$id}");
            if ($update) {
                echo "<span style='color:green;'>{$_POST['name']} successfully edited</span>";
            }
            else {
                echo "<span style='color:red;'>There was an error inserting into the database</span>";
            }
        }
    }
    $q = $db->query("select id, name, stat_name, stat_name_cost, description, call_back from currency_trader where id = " . $id);
    while ($r = $db->fetch_row($q)) {
        echo "<table class='table' cellpadding='3'>";
        echo "<th colspan='100%' align='center'>Editing: " . htmlentities($r['name']) . "</th>";
        echo "<form action='?action=edit&id={$id}' method='POST'>";
        echo "<input type='hidden' name='id' value='{$id}'/>";
        echo "<tr>
			<td>Name</td>
   <td><input type='text' name='name' value='{$r['name']}' /></td>
		</tr>
		<tr>
			<td>Stat Name</td>
			<td>" . usertable_dropdown($ddname = "stat_name", $r['stat_name']) . "<br />
		</tr>
		<tr>
			<td>Stat Cost</td>
			<td><input type='text' name='stat_name_cost' value='{$r['stat_name_cost']}' /></td>
		</tr>
		<tr>
			<td>Description</td>
   <td><textarea name='description' cols='80' rows='5'>" . htmlentities($r['description']) . "</textarea></td>
		</tr>
		<tr>
			<td>Call Back</td>
			<td><textarea name='call_back' cols='80' rows='5'>" . html_entity_decode($r['call_back']) . "</textarea></td>
		</tr>";
        echo "<tr>
			<th colspan='100%'><input type='submit' value='Add Trade' name='edit_trade' /></th>
		</tr>";

        echo "</form></table>";
    }
}

function delete() {
    global $db;
    $id = abs((int) $_REQUEST['id']);
    $q = $db->query("select id, name, stat_name from currency_trader where id = " . $id);
    if(!$db->num_rows($q)) {
        echo "<span style='color:red;'>This currency trade doesnt seem to exist</span>";      
    }
    else {
        $r = $db->fetch_row($q);
        $delete = $db->query("delete from currency_trader where id = ".$id);
        if($delete) {
            echo "<span style='color:green;'>".htmlentities($r['name'])." was successfully deleted</span>";  
        }
        else {
            echo "<span style='color:red;'>There was an error deleting in from the database</span>"; 
        }
    }
    index();
}

function usertable_dropdown($ddname = "stats", $selected = -1) {
    global $db;
    $ret = "<select name='$ddname' type='dropdown'>";
    $q = $db->query("show columns from users");
    if ($selected == -1) {
        $first = 0;
    }
    else {
        $first = 1;
    }
    while ($r = $db->fetch_row($q)) {
        if ($r['Field'] != "userpass") {
            $ret .= "\n<option value='{$r['Field']}'";
            if ($selected == $r['Field'] || $first == 0) {
                $ret .= " selected='selected'";
                $first = 1;
            }
            $ret .= ">{$r['Field']}</option>";
        }
    }
    $db->free_result($q);
    $ret .= "\n</select>";
    return $ret;
}
?>
<style type="text/css">
    input[type=text] {
        width: 98%;
    }
    select {
        width: 98%;
    }

</style>
