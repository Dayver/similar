<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.tags
Tags=similar.tpl:{SIMILAR_ROW_NUMBER},{SIMILAR_ROW_URL},{SIMILAR_ROW_TITLE},{SIMILAR_ROW_AUTHOR},{SIMILAR_ROW_CATEGORY},{SIMILAR_ROW_DATE};page.tpl:{SIMILAR_PAGES}
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('similar');

$limit = Cot::$cfg['plugin']['similar']['max_sim'];

$title = preg_replace('#[^\p{L}0-9\-_ ]#u', ' ', $pag['page_title']);

$t1 = new XTemplate(cot_tplfile('similar', 'plug'));

$l3 = $limit * 3;
$sql_sim = Cot::$db->query("SELECT p.*, u.*
	FROM $db_pages AS p
		LEFT JOIN $db_users AS u ON u.user_id=p.page_ownerid
	WHERE (p.page_state='0' OR p.page_state='2') AND p.page_id != ".$pag['page_id']."
		AND MATCH (page_title) AGAINST ('$title') > ".Cot::$cfg['plugin']['similar']['relev']." LIMIT $l3");
if ($sql_sim->rowCount() > 0)
{
	$samesubcat = $samecat = $samesite = [];
	foreach ($sql_sim->fetchAll() as $row)
	{
		if ($row['page_cat'] == $pag['page_cat'])
		{
			$samesubcat[] = $row;
		}
		elseif (mb_strstr(Cot::$structure['page'][$pag['page_cat']]['path'], $row['page_cat']))
		{
			$samecat[] = $row;
		}
		else
		{
			$samesite[] = $row;
		}
	}
	$i = 1;
	$j = 0;
	$k = 1;
	while ($i <= $limit)
	{
		if ($k == 1 && $j >= count($samesubcat))
		{
			$k = 2;
			$j = 0;
		}
		if ($k == 2 && $j >= count($samecat))
		{
			$k = 3;
			$j = 0;
		}
		if ($k == 3 && $j >= count($samesite))
		{
			break;
		}
		if ($k == 1)
		{
			$row = $samesubcat[$j];
		}
		elseif($k == 2)
		{
			$row = $samecat[$j];
		}
		else
		{
			$row = $samesite[$j];
		}
		$row['page_pageurl'] = (empty($row['page_alias'])) ? cot_url('page', ['c' => $row['page_cat'], 'id' => $row['page_id']]) : cot_url('page', ['c' => $row['page_cat'], 'al' => $row['page_alias']]);
		$t1->assign('SIMILAR_ROW_NUMBER', $i);
		$t1->assign(
			cot_generate_pagetags(
				$row,
				'SIMILAR_ROW_',
				0,
				Cot::$usr['isadmin'],
				Cot::$cfg['homebreadcrumb'],
				'',
				$row['page_pageurl']
			)
		);
		$t1->assign('SIMILAR_ROW_OWNER', cot_build_user($row['page_ownerid'], $row['user_name']));
		$t1->assign(cot_generate_usertags($row, 'SIMILAR_ROW_OWNER_'));
		$i++;
		$j++;
		$t1->parse('MAIN.SIMILAR_ROW');
	}
	$t1->parse('MAIN');
	$plugin_out = $t1->text('MAIN');
}
else
{
	$t1->parse('NOTFOUND');
	$plugin_out = $t1->text('NOTFOUND');
}

$t->assign('SIMILAR_PAGES', $plugin_out);
