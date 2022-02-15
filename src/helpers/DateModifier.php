<?php

namespace tf\helpers;

use DateTime;
use DateTimeZone;

class DateModifier
{
    /**
     * Возвращает корректную форму множественного числа
     * Ограничения: только для целых чисел
     *
     * Пример использования:
     * $remaining_minutes = 5;
     * echo "Я поставил таймер на {$remaining_minutes} " .
     *     get_noun_plural_form(
     *         $remaining_minutes,
     *         'минута',
     *         'минуты',
     *         'минут'
     *     );
     * Результат: "Я поставил таймер на 5 минут"
     *
     * @param int $number Число, по которому вычисляем форму множественного числа
     * @param string $one Форма единственного числа: яблоко, час, минута
     * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
     * @param string $many Форма множественного числа для остальных чисел
     *
     * @return string Рассчитанная форма множественнго числа
     */
    private static function getNounPluralForm(int $number, string $one, string $two, string $many): string
    {
        $number = (int)$number;
        $mod10 = $number % 10;
        $mod100 = $number % 100;

        switch (true) {
            case ($mod100 >= 11 && $mod100 <= 20):
                return $many;

            case ($mod10 > 5):
                return $many;

            case ($mod10 === 1):
                return $one;

            case ($mod10 >= 2 && $mod10 <= 4):
                return $two;

            default:
                return $many;
        }
    }

    /**
     * Приводит $postDate к заданному формату
     * @param string $postDate
     * @param string $stringEnd
     *
     * @return string
     */
    public static function getRelativeFormat(string $postDate, string $stringEnd): string
    {
        $postDate = new DateTime($postDate, new DateTimeZone('Europe/Moscow'));
        $currentDate = new DateTime('now', new DateTimeZone('Europe/Moscow'));
        $dateTimeDiff = $postDate->diff($currentDate);
        $correctDateFormat = '';

        if ($dateTimeDiff->y !== 0) {
            $years = $dateTimeDiff->y;
            $correctDateFormat = sprintf("{$years} %s {$stringEnd}",
                static::getNounPluralForm($years, 'год', 'года', 'лет'));
        } elseif ($dateTimeDiff->m !== 0) {
            $months = $dateTimeDiff->m;
            $correctDateFormat = sprintf("{$months} %s {$stringEnd}",
                static::getNounPluralForm($months, 'месяц', 'месяца', 'месяцев'));
        } elseif ($dateTimeDiff->d >= 7) {
            $weeks = floor($dateTimeDiff->d / 7);
            $correctDateFormat = sprintf("{$weeks} %s {$stringEnd}",
                static::getNounPluralForm($weeks, 'неделю', 'недели', 'недели'));
        } elseif ($dateTimeDiff->d < 7 && $dateTimeDiff->d !== 0) {
            $days = $dateTimeDiff->d;
            $correctDateFormat = sprintf("{$days} %s {$stringEnd}",
                static::getNounPluralForm($days, 'день', 'дня', 'дней'));
        } elseif ($dateTimeDiff->h !== 0) {
            $hours = $dateTimeDiff->h;
            $correctDateFormat = sprintf("{$hours} %s {$stringEnd}",
                static::getNounPluralForm($hours, 'час', 'часа', 'часов'));
        } elseif ($dateTimeDiff->i !== 0) {
            $minutes = $dateTimeDiff->i;
            $correctDateFormat = sprintf("{$minutes} %s {$stringEnd}",
                static::getNounPluralForm($minutes, 'минуту', 'минуты', 'минут'));
        }

        return $correctDateFormat;
    }
}
