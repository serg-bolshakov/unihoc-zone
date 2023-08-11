<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Всё для флорбола. Найти, выбрать и купить лучшую экипировку для флорбола для детей и взрослых от ведущего мирового производителя." />
    <meta name="keywords" content="Товары для флорбола, флорбольная экипировка для взрослых и детей. Флорбольные клюшки, мячи, борта, ворота. Для вратарей." />
    <meta name="robots" content="INDEX,FOLLOW" />
    <link type="image/png" sizes="16x16" rel="icon" href="/icon/favicon-16x16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="/icons/favicon-32x32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="/icons/favicon-96x96.png">
    <link type="image/png" sizes="120x120" rel="icon" href="/icons/favicon-120x120.png">
    <link type="image/png" sizes="256x256" rel="icon" href="/icons/favicon-256x256.png"
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/styles/style.css" />
    <link rel="stylesheet" href="/styles/main.css" />
    <link rel="stylesheet" href="/styles/products.css"> 
    <link rel="stylesheet" href="/styles/calendar.css"> 
    <title>UnihocZoneRussia. Календарь. Флорбол. Unihoc и Zonefloorball экипировка</title>
</head>

<body>

<?php 

/* PHP-класс для вывода календаря на месяц, год или любой другой интервал 
с возможностью выделить отдельные даты и вывести к ним подсказки.*/

class Calendar 
{
	/**
	 * Вывод календаря на один месяц.
     * echo Calendar::getMonth(date('n'), date('Y'));
	 */

     public static function  getMonth($month, $year, $events = array())
	{
		$months = array(
			1  => 'Январь',
			2  => 'Февраль',
			3  => 'Март',
			4  => 'Апрель',
			5  => 'Май',
			6  => 'Июнь',
			7  => 'Июль',
			8  => 'Август',
			9  => 'Сентябрь',
			10 => 'Октябрь',
			11 => 'Ноябрь',
			12 => 'Декабрь'
		);

        $month = intval($month); // intval — Возвращает целое значение переменной
        $out = '
        <div class="calendar-item">
        <div class="calendar-head">'. $months[$month]. ' ' . $year .'</div>
        <table>
        <tr>
            <th>Пн</th>
            <th>Вт</th>
            <th>Ср</th>
            <th>Чт</th>
            <th>Пт</th>
            <th>Сб</th>
            <th>Вс</th>
        </tr>
        ';

        $day_week = date('N', mktime(0, 0, 0, $month, 1, $year)); // выведет 6 (для mktime(0, 0, 0, 7, 1, 2023)).  N - Порядковый номер дня недели в соответствии со стандартом ISO-8601 (добавлен в версии PHP 5.1.0)	от 1 (понедельник) до 7 (воскресенье)
        $day_week--;

        $out .= '<tr>';

        for ($x = 0; $x < $day_week; $x++) {
            $out.= '<td></td>';
        }

        $days_counter = 0;
        $days_month = date('t', mktime(0, 0, 0, $month, 1, $year)); // 't' - количество дней в указанном месяце

        for ($day = 1; $day <= $days_month; $day++) {
            if (date('j.n.Y') == $day . '.' . $month . '.' . $year) { // j - номер для в месяце без предваряющего нуля, n - номер месяца без нуля впереди
            $class = 'today';
            } elseif (time() > strtotime($day. '.' . $month . '.' . $year)) {
                $class = 'last';
            } else {
                $class = '';
            }

            $event_show = false;
            $event_text = array();
            
            /*
                В массиве $events даты могут быть в следующем формате:

                d – день месяца, при таком формате, заданный день будет выделятся каждый месяц.
                d.m – день и месяц, такая дата будет выделятся раз в год.
                d.m.Y или d.m.Y H:i – точная дата. // H - часы в 24-часовом формате, i - минуты

                $events = array(
                    '16'    => 'Заплатить ипотеку',
                    '23.02' => 'День защитника Отечества',
                    '08.03' => 'Международный женский день',
                    '31.12' => 'Новый год'
                );
            */
            
            if (!empty($events)) {
                foreach($events as $date => $text) {
                    $date = explode('.', $date); // explode — Разбивает строку с помощью разделителя
                    if (count($date) == 3) {
                        $y = explode(' ', $date[2]);
                        if (count($y) == 2) {
                            $date[2] = $y[0];
                        }
                        if ($day == intval($date[0]) && $month == intval($date[1]) && $year == $date[2]) {
                            $event_show = true;
                            $event_text[] = $text;
                        }
                    } elseif (count($date) == 2) {
                        if ($day == intval($date[0]) && $month == intval($date[1])) {
                            $event_show = true;
                            $event_text[] = $text;
                        }
                    } elseif ($day == intval($date[0])) {
                        $event_show = true;
                        $event_text[] = $text;
                    }
                }
            }

            if ($event_show) {
                $out.= '<td class="calendar-day '. $class . ' event">' . $day;
                if (!empty($event_text)) {
                    $out.= '<div class="calendar-popup">' . implode('<br>', $event_text) . '</div>';
                }
                $out.= '</td>';
            } else {
                $out.= '<td class="calendar-day ' . $class . '">' . $day . '</td>';
            }

            if ($day_week == 6) {
                $out.= '</tr>';
                if (($days_counter + 1) != $days_month) {
                    $out.= '<tr>';
                }
                $day_week = -1;
            }

            $day_week++;
            $days_counter++;
        }
        $out .= '</tr></table></div>';
        return $out;
    }

    /**
	* Вывод календаря на несколько месяцев.
	*/

    public static function getInterval($start, $end, $events = array())
    {
        $curent = explode('.', $start);
        $curent[0] = intval($curent[0]); // intval — Возвращает целое значение переменной

        $end = explode('.', $end);
        $end[0] = intval($end[0]);

        $begin = true;
        $out = '<div class="calendar-wrp">';
        
        /*
        PHP реализует функцию, называемую позднее статическое связывание, которая может быть использована для того, 
        чтобы получить ссылку на вызываемый класс в контексте статического наследования.

        Ограничения self:: 
        Статические ссылки на текущий класс, такие как self:: или __CLASS__, вычисляются используя класс, к которому эта функция принадлежит, как и в том месте, где она была определена:
        */
        
        do {
            $out .= self::getMonth($curent[0], $curent[1], $events);

            if ($curent[0] == $end[0] && $curent[1] == $end[1]) {
                $begin = false;
            }

            $curent[0]++;
            if ($curent[0] == 13) {
                $curent[0] = 1;
                $curent[1]++;
            } 
        } while ($begin == true);
        
        $out .= '</div>';
        return $out;
    }

}

$events = array(
    '20' => 'Заплатить ипотеку <br> Позвонить другу',
    '28' => 'Заплатить проценты Корепову'
);

echo Calendar::getMonth(date('n'), date('Y'), $events); // n - номер месяца без нуля впереди...

echo Calendar::getInterval(date('n.Y'), date('n.Y', strtotime('+2 month')));

echo Calendar::getInterval(date('01.Y'), date('12.Y'), $events);


?>

<div class="calendar-item">
    <div class="calendar-head">Июль 2023</div>
    <table>
        <tr>
            <th>Пн</th>
            <th>Вт</th>
            <th>Ср</th>
            <th>Чт</th>
            <th>Пт</th>
            <th>Сб</th>
            <th>Вс</th>
        </tr>
        <tr><td></td><td></td><td></td><td></td><td></td><td class="calendar-day last">1</td><td class="calendar-day last">2</td></tr>
        <tr><td class="calendar-day last">3</td><td class="calendar-day last">4</td><td class="calendar-day last">5</td><td class="calendar-day last">6</td><td class="calendar-day last">7</td><td class="calendar-day last">8</td><td class="calendar-day last">9</td></tr>
        <tr><td class="calendar-day last">10</td><td class="calendar-day last">11</td><td class="calendar-day last">12</td><td class="calendar-day last">13</td><td class="calendar-day last">14</td><td class="calendar-day last">15</td><td class="calendar-day last">16</td></tr>
        <tr><td class="calendar-day last">17</td><td class="calendar-day last">18</td><td class="calendar-day last">19</td><td class="calendar-day last">20</td><td class="calendar-day last">21</td><td class="calendar-day last">22</td><td class="calendar-day last">23</td></tr>
        <tr><td class="calendar-day last">24</td><td class="calendar-day last">25</td><td class="calendar-day last">26</td><td class="calendar-day last">27</td><td class="calendar-day last">28</td><td class="calendar-day last">29</td><td class="calendar-day last">30</td></tr>
        <tr><td class="calendar-day today">31</td></tr>
    </table>
</div>

</body>
</html>