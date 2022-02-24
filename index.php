<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
// алгоритм подсчёта коофа:
// (1/(сумму всех ставок на игрока / сумму ВСЕХ ставок)*90)/100
$body = file_get_contents('php://input');

$arr = json_decode($body, true); 

try {
    $bot = new \TelegramBot\Api\Client(TOKEN);
    
    $bot->command('start', function ($message) use ($bot) {
    	$tg_id = $message->getChat()->getId();
    	$sms_rev = "Здравствуйте, Вас приветсвует официальный бот ставок игры World Of Dogs!\nЧто бы поставить ставку введи команду /start_rates";
        $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardRemove(true);
		$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$isReg = registration($arr);
		if ($isReg) {
			$sms_rev = "Вы успешно зарегистрировались!";
			$bot->sendMessage($tg_id, $sms_rev);
		}else{
			$sms_rev = "Вы уже зарегистрировались!";
			$bot->sendMessage($tg_id, $sms_rev);
		}
    });

    $bot->command('me', function ($message) use ($bot) {
    	$tg_id = $message->getChat()->getId();
        $sms_rev = '<code>' . getRates($tg_id) . '</code>';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Сделать ставку")), true, true);
		$bot->sendMessage($tg_id, $sms_rev, 'html');
		$user = getUser($tg_id);
		$playerStr = '<code>' . getRatesPlayers(IBLOCK_ID_PLAYERS_RATES, $user['ID']) . '</code>';
		$bot->sendMessage($tg_id, $playerStr, 'html', false, null, $keyboard);
    });

    $bot->command('help', function ($message) use ($bot) {
    	$tg_id = $message->getChat()->getId();
        $sms_rev = "<code>Доступные команды:\n/start - Запустить бота\n/start_rates - Сделать ставку\n/help - Помощь по командам\n/me - Мои ставки";
		if (in_array($tg_id, ADMINS)) {
			$sms_rev .= "\n \n \nКоманды для админа:\n/getFinal - получить финальные значения\n/setPlayers - записать участников турнира\n	Пример:\n/setPlayers Игрок1 Игрок2 и тд.\n/getUserRates - получисть ставки юзера по юзерке\nПример: /getUserRates @i_am_so_good";
		}
		$sms_rev .= "</code>";
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Сделать ставку")), true, true);
		$bot->sendMessage($tg_id, $sms_rev, 'html', false, null, $keyboard);
    });

    $bot->command('start_rates', function ($message) use ($bot) {
    	$tg_id = $message->getChat()->getId();
        $sms_rev = 'На что будем ставить?';
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Игрок", "Раса", "Роль")), true, true);
		$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
    });

    $bot->command('setPlayers', function ($message) use ($bot) {
    	$message_text = $message->getText();
    	$tg_id = $message->getChat()->getId();
        $arr = explode('/setPlayers', $message_text);
		$isSet = setPlayers($arr[1]);
		if (!$isSet) {
			$sms_rev = "Участники уже были внесены!\nЧто бы отредактировать участников обратитесь к создателю бота @i_am_so_good";
		} else {
			$sms_rev = 'Спасибо, участники внесены!';
		}
		
		
		$bot->sendMessage($tg_id, $sms_rev);
    });

    $bot->callbackQuery(function ($message) use ($bot) {

	if ($message->getData() == "/1")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
		// clearUser($tg_id, 5);
		$player = getPlayer($message->getData());
		setPlayersHeader($player, $tg_id, 5);
		
		
		
	}
	elseif ($message->getData() == "/2")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
	}
	elseif ($message->getData() == "/3")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
	}
	elseif ($message->getData() == "/4")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
	}
	elseif ($message->getData() == "/5")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
	}
	elseif ($message->getData() == "/6")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
	}
	elseif ($message->getData() == "/7")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
	}
	elseif ($message->getData() == "/8")
	{
		$tg_id = $message->getFrom()->getId();
		$sms_rev = "Выберите валюту:";
		
		$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			
		$temp_message = $bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		$bot->deleteMessage(
			$temp_message->getChat()->getId(),
			getMsgId($temp_message->getChat()->getId())
		);
	}
});
    
    //Handle text messages
    $bot->on(function (\TelegramBot\Api\Types\Update $update) use ($bot) {
        $message = $update->getMessage();
        $id = $message->getChat()->getId();
        $msg = $message->getText();

        switch($msg){
        	case 'Сделать ставку':
				$sms_rev = 'На что будем ставить?';
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Игрок", "Раса", "Роль")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
			break;
			case 'Игрок':
				$btns = preparePlayersBtns();
				$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($btns);
				$temp_message = $bot->sendMessage($id, 'Your message: ' . $message->getText(), null, false, null, $keyboard);
		        setMsgIdForDel($temp_message->getMessageId(), $message->getChat()->getId());
		  //       setRatesHeader('player', $id);
				// setPlayersHeader('player', $id, 5);
			break;
			case 'Раса':
				$sms_rev = 'Выберите расу:';
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Орда", "Альянс")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
			break;
			case 'Роль':
				$sms_rev = 'Выберите роль:';
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Щит", "Хил", "Дд")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
			break;
			case 'Орда':
				$sms_rev = "Выберите валюту:";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
				setRatesHeader('horde', $id);
				setRace('horde', $id, 5);
			break;
			case 'Альянс':
				$sms_rev = "Выберите валюту:";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
				setRatesHeader('alliance', $id);
				setRace('alliance', $id, 5);
			break;
			case 'Щит':
				$sms_rev = "Выберите валюту:";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
				setRatesHeader('shield', $id);
				setRole('shield', $id, 5);
			break;
			case 'Хил':
				$sms_rev = "Выберите валюту:";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
				setRatesHeader('heal', $id);
				setRole('heal', $id, 5);
			break;
			case 'Дд':
				$sms_rev = "Выберите валюту:";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
				setRatesHeader('dd', $id);
				setRole('dd', $id, 5);
			break;
			case 'Coin':
				$sms_rev = "Введите значение:\nМинимальная ставка 0.01";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardRemove(true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
				setCurrency('coin', $id, 5);
			break;
			case 'Gold':
				$sms_rev = "Введите значение:\nМинимальная ставка 1000\nМаксимальная 50 000 000";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardRemove(true);
				$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
				setCurrency('gold', $id, 5);
			break;
			default:
				$user = getUser($id);
				$obj = '';
				if ($user['PROPERTY_ROLE_VALUE']) {
					$obj = $user['PROPERTY_ROLE_VALUE'];
				}elseif ($user['PROPERTY_RACE_VALUE']) {
					$obj = $user['PROPERTY_RACE_VALUE'];
				}elseif ($user['PROPERTY_PLAYER_ID_VALUE']) {
					$obj = $user['PROPERTY_PLAYER_ID_VALUE'];
				}
				if (!is_numeric($msg)) {
					$sms_rev ="Ведите число!";
					$bot->sendMessage($id, $sms_rev);
				}elseif ($msg<0) {
					$sms_rev ="Введите число больше 0!";
					$bot->sendMessage($id, $sms_rev);
				}elseif (empty($obj)) {
					$sms_rev ="Сначала выберите на что ставить!\nКоманда /start_rates";
					$bot->sendMessage($id, $sms_rev);
				}elseif (is_numeric($obj)) {
					$sms_rev ="Ставка принята\nБлагодарим за участие!\nПосмотреть свои ставки - /me";
					$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Сделать ставку")), true, true);
					$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
					
					// setValue($obj, $id, $msg, $user['PROPERTY_CURRENCY_VALUE']);
					setPlayerRates($obj, IBLOCK_ID_PLAYERS_RATES, $msg, $user['PROPERTY_CURRENCY_VALUE'], $id);

					clearUser($id, 5);
				}else{
					$sms_rev ="Ставка принята\nБлагодарим за участие!\nПосмотреть свои ставки - /me";
					$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Сделать ставку")), true, true);
					$bot->sendMessage($id, $sms_rev, null, false, null, $keyboard);
					
					setValue($obj, $id, $msg, $user['PROPERTY_CURRENCY_VALUE']);

					clearUser($id, 5);
				}
			break;	
    	}
        
    }, function () {
        return true;
    });
    
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>