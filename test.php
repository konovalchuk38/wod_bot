<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


// $isReg = registration(1);
// if ($isReg) {
// 	pre('успешно зарегистрировался');
// }else{
// 	pre('уже зарегистрирован');
// }
// die();
pre(setValue('shield', 173218220, 500000, 'gold'));

// setCurrency('123123', 173218220, 5);
// pre(getRates(526456686));
// $message_text = '/setPlayers d00b Art3mida LeGiOn TheBlackSun МамкинCимпотяга M̲e̲d̲v̲e̲j̲o̲n̲o̲k̲Х̲o ЛедянойДемон ЕблунтикБезТрусиков';
// $arr = explode('/setPlayers', $message_text);
// pre($arr);
// pre(setPlayers($arr[1]));
// $q = preparePlayersBtns();
// $q = getMsgId(173218220);
// pre(IBLOCK_ID_PLAYERS_RATES);
// $q = getRatesPlayers(IBLOCK_ID_PLAYERS_RATES, 33);
// pre($q);
// setRatesHeader('player', $tg_id);
// setPlayersHeader('player', $tg_id, 5);
// check();
// pre(getUser(ADMINS[0]));

pre(getPlayer('/1'));
setPlayersHeader(getPlayer('/1'), 173218220, 5);