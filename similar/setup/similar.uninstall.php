<?php
defined('COT_CODE') or die('Wrong URL');

global $db_pages;

$res = Cot::$db->query("SHOW INDEXES FROM $db_pages WHERE Key_name = 'page_title_fulltext' AND Column_name = 'page_title';");
if ($res->rowCount() > 0)
{
	Cot::$db->query("ALTER TABLE $db_pages DROP INDEX page_title_fulltext");
}