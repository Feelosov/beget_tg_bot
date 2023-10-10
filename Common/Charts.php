<?php

namespace dev_bots_ru\Common;

use dev_bots_ru\General\Config;

/**
 * Этот класс написан GPT на 80%
 */
class Charts
{
    /**
     * Рисует диаграмму с вертикальными столбцами и сохраняет в папку charts аккаунта
     *
     * @param  string $account
     * @param  string $chart_name
     * @param  array  $axis_x
     * @param  array  $axis_y
     * @return string
     */
    public static function draw_v(string $account, string $chart_name, array $axis_x, array $axis_y): string
    {
        $account_charts_dir = Config::get__db_dir__bot_charts_dir($account);
        if (!is_dir($account_charts_dir)) {
            mkdir($account_charts_dir, 0755, true);
        }

        // Создаем изображение и устанавливаем цвета
        $width = 50 * count($axis_x); // Ширина изображения
        $width = $width < 600 ? 600 : $width;
        $height = $width * 0.3; // Высота изображения
        $height = $height < 400 ? 400 : $height;
        $image = imagecreatetruecolor($width, $height);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $axis_color = imagecolorallocate($image, 77, 77, 77);
        $bar_color = imagecolorallocate($image, 127, 199, 252);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $text_on_bg_color = imagecolorallocate($image, 255, 255, 255);

        // Заливаем фон белым цветом
        imagefill($image, 0, 0, $background_color);

        // Рисуем оси X и Y
        imageline($image, 50, 50, 50, $height - 50, $axis_color); // Вертикальная ось Y
        imageline($image, 50, $height - 50, $width - 50, $height - 50, $axis_color); // Горизонтальная ось X

        // Определяем ширину столбцов в зависимости от количества данных
        $num_data_points = count($axis_x);
        $bar_width = ($width - 100) / $num_data_points;

        // Максимальная высота столбцов
        $max_bar_height = $height - 120;

        // Вычисляем максимальное значение из $axis_y
        $max_value = max($axis_y);

        // Вычисляем интервал для меток на оси Y
        $num_y_labels = 5; // Количество меток
        $y_label_interval = ceil($max_value / $num_y_labels);

        // Рисуем метки на оси Y и подписываем их
        for ($i = 0; $i <= $num_y_labels; $i++) {
            $y = $height - 50 - ($i * $max_bar_height / $num_y_labels);
            imageline($image, 45, $y, 55, $y, $axis_color); // Отметки на оси Y
            $label = $i * $y_label_interval;
            imagestring($image, 3, 10, $y - 10, $label, $text_color);
        }

        // Рисуем столбцы диаграммы и подписываем их значения и подписи из $axis_x
        foreach ($axis_x as $index => $label) {
            $normalized_value = $axis_y[$index] / $max_value;

            // Вычисляем высоту столбца как произведение нормализованного значения и максимальной высоты
            $bar_height = $normalized_value * $max_bar_height;

            $x1 = 50 + $index * $bar_width + 10;
            $x2 = $x1 + $bar_width - 20;
            $y1 = $height - 50;
            $y2 = $y1 - $bar_height;
            imagefilledrectangle($image, $x1, $y1, $x2, $y2, $bar_color);

            // Подписываем столбцы по оси X
            $label_x = $x1 + ($x2 - $x1) / 2 - 20; // Центрируем текст по столбцу
            $label_y = $y1 + 20; // Подпись снизу столбца
            if ($index % 2 == 0) :
                imagestring($image, 2, $label_x - strlen($label) * 0.7, $label_y, $label, $text_color);
            else :
                imagestring($image, 2, $label_x - strlen($label) * 0.7, $label_y - 13, $label, $text_color);
            endif;
            // imagestring($image, 2, $label_x + 13, $label_y - 15, ), $text_color);

            // Подписываем столбцы над ними
            $axis_y[$index] = str_pad(number_format($axis_y[$index], 2, '.', ' '), 5, '0', STR_PAD_LEFT);
            imagestring($image, 2, $label_x + 6, $y2 - 20, $axis_y[$index], $text_color);
        }

        // Подписываем оси и название диаграммы
        $chart_name_x = $width / 2 - strlen($chart_name) * 3; // Центрируем название диаграммы
        imagestring($image, 3, $chart_name_x, 10, $chart_name, $axis_color); // Подписываем название диаграммы над диаграммой

        $filename = time() . '_' . $chart_name . '.jpg';
        $file_path = $account_charts_dir . '/' . $filename;

        imagejpeg($image, $file_path);

        imagedestroy($image);

        return $filename;
    }

    /**
     * Рисует диаграмму с горизонтальными столбцами и сохраняет в папку charts аккаунта
     *
     * @param  string $account
     * @param  string $chart_name
     * @param  array  $axis_x
     * @param  array  $axis_y
     * @return string
     */
    public static function draw_h(string $account, string $chart_name, array $axis_x, array $axis_y): string
    {
        $account_charts_dir = Config::get__db_dir__bot_charts_dir($account);
        if (!is_dir($account_charts_dir)) {
            mkdir($account_charts_dir, 0755, true);
        }

        // Создаем изображение и устанавливаем цвета
        // $width = 50 * count($axis_x); // Ширина изображения
        // $width = $width < 600 ? 600 : $width;
        // $height = 50 * count($axis_x); // Высота изображения
        // $height = $height < 300 ? 300 : $height;
        $minWidth = 1200;
        $minHeight = 400;
        $width = max($minWidth, ceil(sqrt(count($axis_x) * 16 / 9)) * 9);
        $height = max($minHeight, ceil($width * 9 / 16));
        $image = imagecreatetruecolor($width, $height);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $axis_color = imagecolorallocate($image, 77, 77, 77);
        $bar_color = imagecolorallocate($image, 255, 178, 139);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $text_on_bg_color = imagecolorallocate($image, 255, 255, 255);

        // Заливаем фон белым цветом
        imagefill($image, 0, 0, $background_color);

        // Рисуем оси X и Y
        imageline($image, 50, 50, 50, $height - 50, $axis_color); // Вертикальная ось Y
        imageline($image, 50, $height - 50, $width - 50, $height - 50, $axis_color); // Горизонтальная ось X

        // Определяем высоту столбцов в зависимости от количества данных
        $num_data_points = count($axis_x);
        $bar_height = ($height - 100) / $num_data_points;
        $bar_height = $bar_height > 30 ? 30 : $bar_height;

        // Максимальная ширина столбцов
        $max_bar_width = $width - 120;

        // Вычисляем максимальное значение из $axis_x
        $max_value = (float)max($axis_x);

        // Вычисляем интервал для меток на оси X
        $num_x_labels = 5; // Количество меток
        $x_label_interval = ceil($max_value / $num_x_labels);

        // Рисуем метки на оси X и подписываем их
        for ($i = 0; $i <= $num_x_labels; $i++) {
            $x = 50 + ($i * $max_bar_width / $num_x_labels);
            imageline($image, $x, $height - 45, $x, $height - 55, $axis_color); // Отметки на оси X
            $label = number_format(round(($i * $x_label_interval), 0), 0, ".", " ");
            if ($i == 0) :
                imagestring($image, 3, $x - 10, $height - 30, $label, $text_color);
            else :
                imagestring($image, 3, $x - 20, $height - 30, $label, $text_color);
            endif;
        }

        // Рисуем столбцы диаграммы и подписываем их значения и подписи из $axis_y
        foreach ($axis_y as $index => $label) {
            $normalized_value = (float)$axis_x[$index] / $max_value;

            // Вычисляем ширину столбца как произведение нормализованного значения и максимальной ширины
            $bar_width = $normalized_value * $max_bar_width;

            $x1 = 50;
            $y1 = 50 + $index * $bar_height + 10;

            $x2 = $x1 + $bar_width;
            $y2 = $y1 + $bar_height - 10;

            imagefilledrectangle($image, $x1, $y1, $x2, $y2, $bar_color);

            // Подписываем столбцы по оси Y
            $label_x = $x1 + 10; // Подпись справа от столбца
            $label_y = $y1 + ($y2 - $y1) / 2 - 5; // Центрируем текст по столбцу

            // Ставим значение
            $axis_x[$index] = number_format($axis_x[$index], 2, '.', ' ');
            imagestring($image, 3, $label_x, $label_y, $axis_x[$index], $text_color);

            // Ставим наименование
            $label_x = $label_x + 200;
            imagestring($image, 3, $label_x, $label_y, $axis_y[$index], $text_color);
        }

        // Подписываем оси и название диаграммы
        $chart_name_x = $width / 2 - strlen($chart_name) * 3; // Отступ от левого края
        $chart_name_y = 20; // Центрируем название диаграммы
        imagestring($image, 3, $chart_name_x, $chart_name_y, $chart_name, $axis_color); // Подписываем название диаграммы слева от неё

        $filename = time() . '_' . $chart_name . '.jpg';
        $file_path = $account_charts_dir . '/' . $filename;

        imagejpeg($image, $file_path);

        imagedestroy($image);

        return $filename;
    }
}
