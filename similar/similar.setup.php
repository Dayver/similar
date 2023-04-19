<?php
/* ====================
[BEGIN_COT_EXT]
Name=Similar Pages
Category=misc-ext
Description=Displays pages which are relevant to current
Version=1.0.2
Date=2023-04-19
Author=Raveex & Dayver
Copyright=Created by Raveex - www.NovaMobile.net
Notes=Add {SIMILAR_PAGES} to page.tpl
Auth_guests=R
Lock_guests=W12345A
Auth_members=R
Lock_members=W12345A
Requires_modules=page
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
max_sim=01:select:1,2,3,4,5,6,7,8,9,10:5:Max. similar pages for output
relev=02:select:0,1,2,3,4,5:2:Relevance strictness
cutstr=03:string::100:Max. title length
[END_COT_EXT_CONFIG]
==================== */
defined('COT_CODE') or die('Wrong URL.');
