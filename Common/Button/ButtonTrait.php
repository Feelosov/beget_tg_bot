<?php

namespace dev_bots_ru\Common\Button;

/**
 * Вспомогательные методы для кнопок
 */
trait ButtonTrait
{
    /**
     * Добавление еще одной кнопки в массив кнопок
     * 
     * - Если позиция не указана или -1, то добавляет в конец.
     * - Если позиция указано 0 (ноль), то добавляет в начало.
     * - Или добавляет в нужную позицию.
     * - если указан $same_row, то добавит в этот же ряд
     *
     * @param array $buttons
     * @param array $new_button
     * @param integer $row
     * @return array
     */
    public static function __merge_buttons(array $buttons, array $new_button, bool $same_row = false, int $row = -1): array
    {
        $same_row_num = (count($buttons) - 1) < 0 ? 0 : (count($buttons) - 1);

        if (
            $same_row
            && isset($buttons[$same_row_num])
            && $buttons[$same_row_num]
            && count($buttons[$same_row_num]) < 4
        ) :
            $buttons[$same_row_num][] = $new_button[0][0];
        else :
            if ($row == -1) :
                $buttons = array_merge($buttons, $new_button);
            else :
                $buttons_before = array_slice($buttons, 0, $row);
                $buttons_after = array_slice($buttons, $row, null);
                $buttons = array_merge($buttons_before, $new_button, $buttons_after);
            endif;
        endif;

        return $buttons;
    }

    /**
     * Создание кнопки
     *
     * @param  string $type
     * @param  string $text
     * @param  string $data
     * @return array
     */
    public static function __create_button(string $type = '', string $text, string $data): array
    {
        $button[0][0]['text'] = $text;

        if ($type !== '') :
            $button[0][0][$type] = $data;
        endif;

        return $button;
    }
}
