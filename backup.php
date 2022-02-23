<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
// алгоритм подсчёта коофа:
// (1/(сумму всех ставок на игрока / сумму ВСЕХ ставок)*90)/100

$bot = new \TelegramBot\Api\BotApi(TOKEN);

$body = file_get_contents('php://input');

$arr = json_decode($body, true); 


$tg_id = $arr['message']['chat']['id'];
$rez_kb = array();
$keyboard = [];
$message_text = $arr['message']['text'];
$message = $arr['message'];
$sms_rev='';
// $callback_data = $arr['callback_query']['data'];
// $tg_id_in = $callback_data['message']['chat']['id'];
// $message = $arr['callback_query'];
file_put_contents('log.txt', print_r($arr, 1), FILE_APPEND);	
file_put_contents('log.txt', print_r("		~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~", 1)."\n", FILE_APPEND);


	switch($message_text){
		case '/start':
			$sms_rev = "Здравствуйте, Вас приветсвует официальный бот ставок игры World Of Dogs!\nЧто бы поставить ставку введи команду /start_rates";
			// $bot->sendMessage($tg_id, $sms_rev);
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
			break;
		case '/start_rates':
			$sms_rev = 'На что будем ставить?';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Игрок", "Раса", "Роль")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
		break;
		case 'Игрок':
			$sms_rev = 'Тут пока ничего нет';
			$btns = preparePlayersBtns();
			$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($btns);
			// file_put_contents('log.txt', print_r($keyboard, 1), FILE_APPEND);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			break;
		case 'Раса':
			$sms_rev = 'Выберите расу:';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Орда", "Альянс")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			break;
		case 'Роль':
			$sms_rev = 'Выберите роль:';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Щит", "Хил", "Дд")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			break;
		case 'Орда':
			$sms_rev = "Выберите валюту:";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			setRatesHeader('horde', $tg_id);
			setRace('horde', $tg_id, 5);
			break;
		case 'Альянс':
			$sms_rev = "Выберите валюту:";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			setRatesHeader('alliance', $tg_id);
			setRace('alliance', $tg_id, 5);
			break;
		case 'Щит':
			$sms_rev = "Выберите валюту:";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			setRatesHeader('shield', $tg_id);
			setRole('shield', $tg_id, 5);
			break;
		case 'Хил':
			$sms_rev = "Выберите валюту:";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			setRatesHeader('heal', $tg_id);
			setRole('heal', $tg_id, 5);
			break;
		case 'Дд':
			$sms_rev = "Выберите валюту:";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Gold", "Coin")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			setRatesHeader('dd', $tg_id);
			setRole('dd', $tg_id, 5);
			break;
		case 'Gold':
			$sms_rev = "Введите значение:\nМинимальная ставка 1000\nМаксимальная 50 000 000";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardRemove(true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			setCurrency('gold', $tg_id, 5);
			break;
		case 'Coin':
			$sms_rev = "Введите значение:\nМинимальная ставка 0.01";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardRemove(true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			setCurrency('coin', $tg_id, 5);
			break;
		case '/me':
			$sms_rev = '<code>' . getRates($tg_id) . '</code>';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Сделать ставку")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, 'html', false, null, $keyboard);
			break;
		case '/help':
			$sms_rev = "<code>Доступные команды:\n/start - Запустить бота\n/start_rates - Сделать ставку\n/help - Помощь по командам\n/me - Мои ставки";
			if (in_array($tg_id, ADMINS)) {
				$sms_rev .= "\n \n \nКоманды для админа:\n/getFinal - получить финальные значения\n/setPlayers - записать участников турнира\n	Пример:\n/setPlayers Игрок1 Игрок2 и тд.\n/getUserRates - получисть ставки юзера по юзерке\nПример: /getUserRates @i_am_so_good";
			}
			$sms_rev .= "</code>";
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Сделать ставку")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, 'html', false, null, $keyboard);
			break;
		case 'Сделать ставку':
			$sms_rev = 'На что будем ставить?';
			$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Игрок", "Раса", "Роль")), true, true);
			$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
			break;
		case strstr($message_text,'/setPlayers')://ник игрока надо вводить без смайлов!
			$arr = explode('/setPlayers', $message_text);
			$isSet = setPlayers($arr[1]);
			if (!$isSet) {
				$sms_rev = "Участники уже были внесены!\nЧто бы отредактировать участников обратитесь к создателю бота @i_am_so_good";
			} else {
				$sms_rev = 'Спасибо, участники внесены!';
			}
			
			
			$bot->sendMessage($tg_id, $sms_rev);
			break;
		default:
			$user = getUser($tg_id);
			$obj = '';
			if ($user['PROPERTY_ROLE_VALUE']) {
				$obj = $user['PROPERTY_ROLE_VALUE'];
			}elseif ($user['PROPERTY_RACE_VALUE']) {
				$obj = $user['PROPERTY_RACE_VALUE'];
			}
			if (!is_numeric($message_text)) {
				$sms_rev ="Ведите число!";
				$bot->sendMessage($tg_id, $sms_rev);
			}elseif ($message_text<0) {
				$sms_rev ="Введите число больше 0!";
				$bot->sendMessage($tg_id, $sms_rev);
			}elseif (empty($obj)) {
				$sms_rev ="Сначала выберите на что ставить!\nКоманда /start_rates";
				$bot->sendMessage($tg_id, $sms_rev);
			}else{
				$sms_rev ="Ставка принята\nБлагодарим за участие!\nПосмотреть свои ставки - /me";
				$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup(array(array("Сделать ставку")), true, true);
				$bot->sendMessage($tg_id, $sms_rev, null, false, null, $keyboard);
				
				setValue($obj, $tg_id, $message_text, $user['PROPERTY_CURRENCY_VALUE']);

				clearUser($tg_id, 5);
			}
		break;	
	}
// 	// switch ($callback_data) {
// 	// 	case '/1':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// 	case '/2':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// 	case '/3':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// 	case '/4':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// 	case '/5':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// 	case '/6':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// 	case '/7':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// 	case '/8':
// 	// 		// setPlayer($callback_data, $tg_id, 5);
// 	// 		$bot->sendMessage($tg_id_in, $callback_data);
// 	// 		break;
// 	// }

// $bot->callbackQuery(function ($message) use ($bot) {

// 	if ($message->getData() == "/1")
// 	{
// 		// If you want you can send some kind of popup message after the user clicked one of the buttons
// 		$bot->answerCallbackQuery($message->getId(), "You clicked on option1. Loading...");

// 		$bot->sendMessage(
// 			$message->getFrom()->getId(),
// 			"Hi " . $message->getFrom()->getUsername() . ", you've choosen <b>Option 1</b>",
// 			"HTML"
// 		);
// 	}
// });



try {
    $bot = new \TelegramBot\Api\Client(TOKEN);
    // or initialize with botan.io tracker api key
    // $bot = new \TelegramBot\Api\Client('YOUR_BOT_API_TOKEN', 'YOUR_BOTAN_TRACKER_API_KEY');
    

    //Handle /ping command
    $bot->command('start', function ($message) use ($bot) {
        $bot->sendMessage($message->getChat()->getId(), 'pong!');
        $question = 'How can I help you?';

		$bot->sendMessage($message->getChat()->getId(), $question);
    });
    $bot->callbackQuery(function ($message) use ($bot) {

	if ($message->getData() == "option1")
	{
		// If you want you can send some kind of popup message after the user clicked one of the buttons
		$bot->answerCallbackQuery($message->getId(), "You clicked on option1. Loading...");

		$bot->sendMessage(
			$message->getFrom()->getId(),
			"Hi " . $message->getFrom()->getUsername() . ", you've choosen <b>Option 1</b>",
			"HTML"
		);
	}
	elseif ($message->getData() == "option2")
	{
		// If you want you can send some kind of popup message after the user clicked one of the buttons
		$bot->answerCallbackQuery($message->getId(), "You clicked on option2. Loading...");

		$bot->sendMessage(
			$message->getFrom()->getId(),
			"Hi " . $message->getFrom()->getUsername() . ", you've choosen <b>Option 2</b>",
			"HTML"
		);
	}
	elseif ($message->getData() == "/1")
	{
		// If you want you can send some kind of popup message after the user clicked one of the buttons
		$bot->answerCallbackQuery($message->getId(), "You clicked on option3. Loading...");

		$bot->sendMessage(
			$message->getFrom()->getId(),
			"Hi орволырарвоыароывроар",
			"HTML"
		);
	}
});
    
    //Handle text messages
    $bot->on(function (\TelegramBot\Api\Types\Update $update) use ($bot) {
        $message = $update->getMessage();
        $id = $message->getChat()->getId();
        if ($message->getText() == 'Игрок') {
        	$btns = preparePlayersBtns();
			$keyboard = new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($btns);

        }
        $bot->sendMessage($id, 'Your message: ' . $message->getText(), null, false, null, $keyboard);
    }, function () {
        return true;
    });
    
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>