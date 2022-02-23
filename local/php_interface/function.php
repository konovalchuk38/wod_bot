<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


CModule::IncludeModule('iblock');
CModule::IncludeModule('highloadblock');

function pre($str)
{
	echo "<pre>";
	print_r($str);
	echo "</pre><br>";
}

function registration($arr)
{

	$isRegister = isRegister($arr['message']['chat']['id']);
	if ($isRegister) {
		return false;
	}else{
		$el = new CIBlockElement;
		$PROP = array();
		$PROP["tg_id"] = $arr['message']['chat']['id']; //tg_id
		$PROP["User_name"] = '@'.$arr['message']['chat']['username']; //user_name
		$PROP['currency'] = '';
		$arLoadProductArray = Array(  
		   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
		   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
		   'IBLOCK_ID' => IBLOCK_ID_USER,
		   'PROPERTY_VALUES' => $PROP,  
		   'NAME' => 'user_@'.$arr['message']['chat']['username'],  
		   'ACTIVE' => 'Y', // активен
		);
		$PRODUCT_ID = $el->Add($arLoadProductArray);
		return true;
	}
}

function isRegister($id)
{	
	$tg_id = null;
	$arSelect = Array("ID");
	$arFilter = Array("IBLOCK_ID"=>IBLOCK_ID_USER, "ACTIVE"=>"Y", "PROPERTY_tg_id"=>$id);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$tg_id = $arFields['ID'];
	}
	if ($tg_id) {
		return $tg_id;
	}else{
		return null;
	}
}

function getUser($tg_id)
{
	$arSelect = Array("ID", "NAME", "PROPERTY_currency", "PROPERTY_tg_id", "PROPERTY_User_name", "PROPERTY_role", "PROPERTY_race", "DETAIL_TEXT");
	$arFilter = Array("IBLOCK_ID"=>IBLOCK_ID_USER, "ACTIVE"=>"Y", "PROPERTY_tg_id"=>$tg_id);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
	}
	return $arFields;
}

function setRatesHeader($obj = '', $tg_id, $value = '')
{
	$user = getUser($tg_id);
	if ($obj == 'horde') {
		$iblock = IBLOCK_ID_HORDE;
		if (!checkRow($obj, $tg_id)) {
			add($iblock, $user);
		}	
	}elseif ($obj == 'alliance') {
		$iblock = 7;
		if (!checkRow($obj, $tg_id)) {
			add($iblock, $user);
		}
	}elseif ($obj == 'shield') {
		$iblock = IBLOCK_ID_SHIELT;
		if (!checkRow($obj, $tg_id)) {
			add($iblock, $user);
		}
	}elseif ($obj == 'heal') {
		$iblock = IBLOCK_ID_HEAL;
		if (!checkRow($obj, $tg_id)) {
			add($iblock, $user);
		}
	}elseif ($obj == 'dd') {
		$iblock = IBLOCK_ID_DD;
		if (!checkRow($obj, $tg_id)) {
			add($iblock, $user);
		}
	}
	
}

function checkRow($obj = '', $tg_id)
{
	if ($obj == 'horde') {
		$iblock = IBLOCK_ID_HORDE;
		$user_id = getUser($tg_id)['ID'];
	}elseif ($obj == 'alliance') {
		$iblock = 7;
		$user_id = getUser($tg_id)['ID'];
	}elseif ($obj == 'shield') {
		$iblock = IBLOCK_ID_SHIELT;
		$user_id = getUser($tg_id)['ID'];
	}elseif ($obj == 'heal') {
		$iblock = IBLOCK_ID_HEAL;
		$user_id = getUser($tg_id)['ID'];
	}elseif ($obj == 'dd') {
		$iblock = IBLOCK_ID_DD;
		$user_id = getUser($tg_id)['ID'];
	}
	$arSelect = Array("ID");
	$arFilter = Array("IBLOCK_ID"=>$iblock, "ACTIVE"=>"Y", "PROPERTY_user_id"=>$user_id);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$id = $arFields['ID'];
	}
	if ($id) {
		return $id;
	}else{
		return false;
	}

}

function setValue($obj = '', $tg_id, $value = '', $currency)
{
	$user = getUser($tg_id);
	if ($obj == 'horde') {
		$iblock = IBLOCK_ID_HORDE;
		$itemId = checkRow($obj, $tg_id);
		$oldVal = getValue('horde', $tg_id, $currency);
		$a = update($iblock, $user, $value, $currency, $itemId, $oldVal);
		return $a;
	}
	if ($obj == 'alliance') {
		$iblock = IBLOCK_ID_ALLIANCE;
		$itemId = checkRow($obj, $tg_id);
		$oldVal = getValue('alliance', $tg_id, $currency);
		$a = update($iblock, $user, $value, $currency, $itemId, $oldVal);
		return $a;
	}
	if ($obj == 'shield') {
		$iblock = IBLOCK_ID_SHIELT;
		$itemId = checkRow($obj, $tg_id);
		$oldVal = getValue('shield', $tg_id, $currency);
		$a = update($iblock, $user, $value, $currency, $itemId, $oldVal);
		return $a;
	}
	if ($obj == 'heal') {
		$iblock = IBLOCK_ID_HEAL;
		$itemId = checkRow($obj, $tg_id);
		$oldVal = getValue('heal', $tg_id, $currency);
		$a = update($iblock, $user, $value, $currency, $itemId, $oldVal);
		return $a;
	}
	if ($obj == 'dd') {
		$iblock = IBLOCK_ID_DD;
		$itemId = checkRow($obj, $tg_id);
		$oldVal = getValue('dd', $tg_id, $currency);
		$a = update($iblock, $user, $value, $currency, $itemId, $oldVal);
		return $a;
	}
}

function add($iblock_id, $user)
{
	$el = new CIBlockElement;
	$PROP = array();
	$PROP['user_id'] = $user['ID']; //user_id
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,  
	   'NAME' => $user['NAME'],  
	   'ACTIVE' => 'Y', // активен
	);
	$PRODUCT_ID = $el->Add($arLoadProductArray);
}

function update($iblock_id, $user, $value = '', $currency = '',  $itemId, $oldVal)
{
	$el = new CIBlockElement;
	$PROP = array();
	$PROP['user_id'] = $user['ID']; //user_id
	$PROP[$currency] = $oldVal['PROPERTY_'.strtoupper($currency).'_VALUE'] + $value;
	if ($currency == 'gold') {
		$currency = 'coin';
		$PROP[$currency] = $oldVal['PROPERTY_'.strtoupper($currency).'_VALUE'];
	}else{
		$currency = 'gold';
		$PROP[$currency] = $oldVal['PROPERTY_'.strtoupper($currency).'_VALUE'];
	}
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,
	);
	$res = $el->Update( $itemId, $arLoadProductArray);
}

function getValue($obj = '', $tg_id, $currency)
{
	$user = getUser($tg_id);
	if ($obj == 'horde') {
		$iblock = IBLOCK_ID_HORDE;
		$itemId = checkRow($obj, $tg_id);
		$arSelect = Array("ID", "NAME", "PROPERTY_user_id", "PROPERTY_gold", "PROPERTY_coin");
		$arFilter = Array("IBLOCK_ID"=>$iblock, "ID"=>$itemId);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
		}
		return $arFields;
	}elseif ($obj == 'alliance') {
		$iblock = IBLOCK_ID_ALLIANCE;
		$itemId = checkRow($obj, $tg_id);
		$arSelect = Array("ID", "NAME", "PROPERTY_user_id", "PROPERTY_gold", "PROPERTY_coin");
		$arFilter = Array("IBLOCK_ID"=>$iblock, "ID"=>$itemId);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
		}
		return $arFields;
	}elseif ($obj == 'shield') {
		$iblock = IBLOCK_ID_SHIELT;
		$itemId = checkRow($obj, $tg_id);
		$arSelect = Array("ID", "NAME", "PROPERTY_user_id", "PROPERTY_gold", "PROPERTY_coin");
		$arFilter = Array("IBLOCK_ID"=>$iblock, "ID"=>$itemId);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
		}
		return $arFields;
		
	}elseif ($obj == 'heal') {
		$iblock = IBLOCK_ID_HEAL;
		$itemId = checkRow($obj, $tg_id);
		$arSelect = Array("ID", "NAME", "PROPERTY_user_id", "PROPERTY_gold", "PROPERTY_coin");
		$arFilter = Array("IBLOCK_ID"=>$iblock, "ID"=>$itemId);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
		}
		return $arFields;
		
	}elseif ($obj == 'dd') {
		$iblock = IBLOCK_ID_DD;
		$itemId = checkRow($obj, $tg_id);
		$arSelect = Array("ID", "NAME", "PROPERTY_user_id", "PROPERTY_gold", "PROPERTY_coin");
		$arFilter = Array("IBLOCK_ID"=>$iblock, "ID"=>$itemId);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
		}
		return $arFields;
		
	}
	
}

function setCurrency($str, $tg_id, $iblock_id)
{
	$el = new CIBlockElement;
	$user = getUser($tg_id);
	$PROP = array();
	$PROP['currency'] = $str; //currency
	$PROP["tg_id"] = $user['PROPERTY_TG_ID_VALUE']; //tg_id
	$PROP["User_name"] = $user['PROPERTY_USER_NAME_VALUE']; //user_name
	$PROP["role"] = $user['PROPERTY_ROLE_VALUE']; //role
	$PROP["race"] = $user['PROPERTY_RACE_VALUE']; //race
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,
	);
	$res = $el->Update( $user['ID'], $arLoadProductArray);

}
function setRole($str, $tg_id, $iblock_id)
{
	$el = new CIBlockElement;
	$user = getUser($tg_id);
	$PROP = array();
	$PROP['currency'] = $user['PROPERTY_CURRENCY_VALUE']; //currency
	$PROP["tg_id"] = $user['PROPERTY_TG_ID_VALUE']; //tg_id
	$PROP["User_name"] = $user['PROPERTY_USER_NAME_VALUE']; //user_name
	$PROP["role"] = $str; //role
	$PROP["race"] = $user['PROPERTY_RACE_VALUE']; //race
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,
	);
	$res = $el->Update( $user['ID'], $arLoadProductArray);

}
function setRace($str, $tg_id, $iblock_id)
{
	$el = new CIBlockElement;
	$user = getUser($tg_id);
	$PROP = array();
	$PROP['currency'] = $user['PROPERTY_CURRENCY_VALUE']; //currency
	$PROP["tg_id"] = $user['PROPERTY_TG_ID_VALUE']; //tg_id
	$PROP["User_name"] = $user['PROPERTY_USER_NAME_VALUE']; //user_name
	$PROP["role"] = $user['PROPERTY_ROLE_VALUE']; //role
	$PROP["race"] = $str; //race
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,
	);
	$res = $el->Update( $user['ID'], $arLoadProductArray);

}

function clearUser($tg_id, $iblock_id)
{
	$el = new CIBlockElement;
	$user = getUser($tg_id);
	$PROP = array();
	$PROP["tg_id"] = $user['PROPERTY_TG_ID_VALUE']; //tg_id
	$PROP["User_name"] = $user['PROPERTY_USER_NAME_VALUE']; //user_name
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,
	);
	$res = $el->Update( $user['ID'], $arLoadProductArray);
}

function getRates($tg_id)
{
	$str = "🏋️‍♀️Ваши ставки:\n";
	$user = getUser($tg_id);
	$str .= getRatesHorde(IBLOCK_ID_HORDE, $user['ID']);
	$str .= "============\n";
	$str .= getRatesAlliance(IBLOCK_ID_ALLIANCE, $user['ID']);
	$str .= "============\n";
	$str .= getRatesShield(IBLOCK_ID_SHIELT, $user['ID']);
	$str .= "============\n";
	$str .= getRatesHeal(IBLOCK_ID_HEAL, $user['ID']);
	$str .= "============\n";
	$str .= getRatesDd(IBLOCK_ID_DD, $user['ID']);
	return $str;
}

function getRatesHorde($iblock_id, $user_id)
{
	$arSelect = Array("PROPERTY_gold", "PROPERTY_coin");
    $arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE"=>"Y", "PROPERTY_user_id"=>$user_id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
    }
    $str = "Ставки на расу Орда🐕‍🦺:\n💰Gold - " . $arFields['PROPERTY_GOLD_VALUE'] . "\n💎Coin - " . $arFields['PROPERTY_COIN_VALUE'] . "\n";
    return $str;
}
function getRatesAlliance($iblock_id, $user_id)
{
	$arSelect = Array("PROPERTY_gold", "PROPERTY_coin");
    $arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE"=>"Y", "PROPERTY_user_id"=>$user_id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
    }
    $str = "Ставки на расу Альянс🦮:\n💰Gold - " . $arFields['PROPERTY_GOLD_VALUE'] . "\n💎Coin - " . $arFields['PROPERTY_COIN_VALUE'] . "\n";
    return $str;
}
function getRatesShield($iblock_id, $user_id)
{
	$arSelect = Array("PROPERTY_gold", "PROPERTY_coin");
    $arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE"=>"Y", "PROPERTY_user_id"=>$user_id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
    }
    $str = "Ставки на роль Щит🛡:\n💰Gold - " . $arFields['PROPERTY_GOLD_VALUE'] . "\n💎Coin - " . $arFields['PROPERTY_COIN_VALUE'] . "\n";
    return $str;
}
function getRatesHeal($iblock_id, $user_id)
{
	$arSelect = Array("PROPERTY_gold", "PROPERTY_coin");
    $arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE"=>"Y", "PROPERTY_user_id"=>$user_id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
    }
    $str = "Ставки на роль Хил🔮:\n💰Gold - " . $arFields['PROPERTY_GOLD_VALUE'] . "\n💎Coin - " . $arFields['PROPERTY_COIN_VALUE'] . "\n";
    return $str;
}
function getRatesDd($iblock_id, $user_id)
{
	$arSelect = Array("PROPERTY_gold", "PROPERTY_coin");
    $arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE"=>"Y", "PROPERTY_user_id"=>$user_id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
    }
    $str = "Ставки на роль Дд⚔️:\n💰Gold - " . $arFields['PROPERTY_GOLD_VALUE'] . "\n💎Coin - " . $arFields['PROPERTY_COIN_VALUE'] . "\n";
    return $str;
}

function setPlayers($arr)
{
	// $isSet = check();
	// if ($isSet) {
	// 	return false;
	// } else {

		$str = '';
		$arr = trim($arr);
		$arrPlayers = explode(' ', $arr);
		$date = date("d.m.Y");
		foreach ($arrPlayers as $key => $value) {
			$hlBlock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->Fetch();
			$entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
			$dataClass = $entity->getDataClass();
			$result = $dataClass::update($key+1, ['UF_PLAYER_NAME' => $value]);
			// $el = new CIBlockElement;
			// $PROP = array();
			// $arLoadProductArray = Array(  
			//    'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
			//    'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
			//    'IBLOCK_ID' => IBLOCK_ID_PLAYERS,
			//    'PROPERTY_VALUES' => $PROP,  
			//    'NAME' => $value,  
			//    'ACTIVE' => 'Y', // активен 
			//    'DATE_ACTIVE_FROM' => date("d.m.Y"), //сегодня
			//    'DATE_ACTIVE_TO'	=> date('d.m.Y', strtotime($date .' +1 day')), //следующий день
			// );
			// $PRODUCT_ID = $el->Add($arLoadProductArray);
			$str .= $value.'-'.$key.';';
		}
		pre($str);
		// $el1 = new CIBlockElement;
		// $PROP = array();
		// $arLoadProductArray = Array(  
		//    'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
		//    'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
		//    'IBLOCK_ID' => IBLOCK_ID_USER,
		//    'PROPERTY_VALUES' => $PROP,
		//    "DETAIL_TEXT"    => $str,
		// );
		// $res = $el1->Update( 33, $arLoadProductArray);
		return true;
	// }
}
function preparePlayersBtns()
{
	
	$arrBtn = [];
	$hlblock = Bitrix\Highloadblock\HighloadBlockTable::getById(1)->fetch(); // id highload блока
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entityClass = $entity->getDataClass();
    $res = $entityClass::getList(array(
	       'select' => array('*'),
	       'filter' => array()
	       
	   ));
    	while ($row = $res->fetch()) {
    		$arrBtn[] = ["text"=>$row['UF_PLAYER_NAME'], "callback_data"=>'/'.$row['UF_PLAYER_ID']];
    	}
    $arr = array_chunk($arrBtn, 3);
	// $users = getUser(ADMINS[0])['DETAIL_TEXT'];
	// $a = explode(';', $users);
	// $count = count($a);
	// unset($a[$count-1]);
	// $arrBtn = [];
	// foreach ($a as $key => $value) {
	// 	$arrVal = explode('-', $value);
	// 	$arrBtn[] = ["text"=>$arrVal[0], "callback_data"=>$arrVal[1]];
	// }
	// $arr = array_chunk($arrBtn, 3);
return $arr;
}

function check()
{
	$arSelect = Array("ID");
    $arFilter = Array("IBLOCK_ID"=>IBLOCK_ID_PLAYERS, "ACTIVE"=>"Y", ">DATE_ACTIVE_TO" => date("d.m.Y"));
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($ob = $res->GetNextElement())
    {
        $arFields[] = $ob->GetFields();
    }
    if ($arFields) {
    	return true;
    } else {
    	return false;
    }
    
    
}
function setPlayer($str, $tg_id, $iblock_id)
{
	$el = new CIBlockElement;
	$user = getUser($tg_id);
	$PROP = array();
	$PROP['currency'] = $user['PROPERTY_CURRENCY_VALUE']; //currency
	$PROP["tg_id"] = $user['PROPERTY_TG_ID_VALUE']; //tg_id
	$PROP["User_name"] = $user['PROPERTY_USER_NAME_VALUE']; //user_name
	$PROP["role"] = $user['PROPERTY_ROLE_VALUE']; //role
	$PROP["race"] = $user['PROPERTY_RACE_VALUE']; //race
	$PROP["player_id"] = $str; //race
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,
	);
	$res = $el->Update( $user['ID'], $arLoadProductArray);

}

function setMsgIdForDel($msgId, $tg_id)
{

	$el = new CIBlockElement;
	$user = getUser($tg_id);
	$PROP = array();
	$PROP['currency'] = $user['PROPERTY_CURRENCY_VALUE']; //currency
	$PROP["tg_id"] = $user['PROPERTY_TG_ID_VALUE']; //tg_id
	$PROP["User_name"] = $user['PROPERTY_USER_NAME_VALUE']; //user_name
	$PROP["role"] = $user['PROPERTY_ROLE_VALUE']; //role
	$PROP["race"] = $user['PROPERTY_RACE_VALUE']; //race
	$PROP["msg_id"] = $msgId; //msgId
	$arLoadProductArray = Array(  
	   'MODIFIED_BY' => 1, // элемент изменен текущим пользователем  
	   'IBLOCK_SECTION_ID' => false, // элемент лежит в корне раздела  
	   'IBLOCK_ID' => $iblock_id,
	   'PROPERTY_VALUES' => $PROP,
	);
	$res = $el->Update( $user['ID'], $arLoadProductArray);
}

function getMsgId($tg_id)
{
	$user = getUser($tg_id);
	return $user['PROPERTY_MSG_ID_VALUE'];
}