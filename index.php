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
		$bot->sendMessage($tg_id, $sms_rev, 'html', false, null, $keyboard);
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
	elseif ($message->getData() == "/1")
	{
		// If you want you can send some kind of popup message after the user clicked one of the buttons
		$bot->answerCallbackQuery($message->getId(), "You clicked on option3. Loading...");

		// $bot->sendMessage(
		// 	$message->getFrom()->getId(),
		// 	"Hi орволырарвоыароывроар1111111",
		// 	"HTML"
		// );
		$temp_message = $bot->sendMessage(
			$message->getFrom()->getId(),
			"Hi орволырарвоыароывроар1111111",
			"HTML"
		);
		// $bot->editMessageText(
		// 	$temp_message->getChat()->getId(),
		// 	$temp_message->getMessageId(),
		// 	"I'm sorry. I found <b>nothing</b>!",
		// 	"HTML"
		// );
		$bot->editMessageText(
			$temp_message->getFrom()->getId(),
			getMsgId($message->getChat()->getId()),
			"I'm sorry. I found <b>nothing</b>!",
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
        $temp_message = $bot->sendMessage($id, 'Your message: ' . $message->getText(), null, false, null, $keyboard);
        file_put_contents('log.txt', print_r($temp_message, 1), FILE_APPEND);	
		file_put_contents('log.txt', print_r("		~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~", 1)."\n", FILE_APPEND);
        setMsgIdForDel($temp_message->getMessageId(), $message->getChat()->getId());
    }, function () {
        return true;
    });
    
    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>