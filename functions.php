<?php
/**
 * Функция возвращает список самых популярных постов.
 * Имеет необязательный параметр. Если он передан, то возвращается
 * список популярных постов определенного типа.
 * @param msqli
 * @param string $type_post
 * @return array
 */
function get_popular_posts(mysqli $link, string $type_post = null): array {

    $where = "";

    if (!is_null($type_post)) {
        $safe_type_post = mysqli_real_escape_string($link, $type_post);
        $where = "WHERE `tc`.`class_name` = '$safe_type_post'";
    }

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`class_name`,
            CASE
                WHEN `tc`.`class_name` in ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` as `author`,
                `u`.`login` as `name_user`,
                `u`.`avatar`
            FROM
                `posts` `p`
                JOIN `users` `u` on `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
            $where
            ORDER BY `count_views` DESC;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция возвращает список типов контента
 * @param msqli
 * @return array
 */
function get_all_type_content(mysqli $link): array {

    $sql = "SELECT
        `tc`.`type`,
        `tc`.`class_name`
    FROM
        `type_content` `tc`;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Функция возвращает класс ссылки в зависимости от наличия get запроса
 * @param string $type_post
 * @return string
 *
 */
function getClass(string $type_post = null): string {

    if (isset($_GET['type_post'])) {

        if ($_GET['type_post'] === $type_post) {
            return "filters__button--active";
        }

    } elseif (is_null($type_post)) {
        return "filters__button--active";
    }

    return "";
}

/**
 * Функция возвращает пост по его id
 * @param msqli
 * @param int $id
 * @return array
 */
function get_selected_post(mysqli $link, int $id): array {

    $sql = "SELECT
                `p`.`header`,
                `tc`.`class_name`,
            CASE
                WHEN `tc`.`class_name` in ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` as `author`,
                `u`.`login` as `name_user`,
                `u`.`avatar`
            FROM
                `posts` `p`
                JOIN `users` `u` on `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
            WHERE
                `p`.`id` = '$id'
            ORDER BY `count_views` DESC;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Функция проверяет существование поста с определенным id
 * @param msqli
 * @param int $id
 * @return bool
 */
function isset_post(mysqli $link, int $id): bool {

    $sql = "SELECT
                *
            FROM
                `posts` `p`
            WHERE
                `p`.`id` = '$id';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return (bool)mysqli_fetch_assoc($result);
}

/**
 * Функция возвращает кол-во постов данного пользователя
 * @param msqli
 * @param string $name_user
 * @return int
 */
function get_count_post_user(mysqli $link, string $name_user): int {

    $safe_name_user = mysqli_real_escape_string($link, $name_user);

    $sql = "SELECT
                COUNT(*) as `count`
            FROM
                `posts` `p`
                JOIN `users` `u` on `u`.`id` = `p`.`user_id`
            WHERE
                `u`.`login` = '$safe_name_user';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result)['count'];
}

/**
 * Функция возвращает количество подписчиков данного пользователя
 * @param msqli
 * @param string $name_user
 * @return int
 */
function get_count_subscriptions_user(mysqli $link, string $name_user): int {

    $safe_name_user = mysqli_real_escape_string($link, $name_user);

    $sql = "SELECT
                COUNT(*) as `count`
            FROM
                `subscriptions` `s`
                JOIN `users` `u` on `u`.`id` = `s`.`destination_post_id`
            WHERE
                `u`.`login` = '$safe_name_user';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result)['count'];
}

/**
 * Функция возвращает дату регистрации пользователя на сайте
 * @param msqli
 * @param string $name_user
 * @return string
 */
function get_created_at_user(mysqli $link, string $name_user): string {

    $safe_name_user = mysqli_real_escape_string($link, $name_user);

    $sql = "SELECT
                `u`.`created_at`
            FROM
                `users` `u`
            WHERE
                `u`.`login` = '$safe_name_user';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result)['created_at'];
}

/**
 * Обрезает текстовое содержимое если оно превышает заданное число символов
 * @param string $text
 * @param int $id
 * @param int $limit_letters
 * @return string
 */
function cut_text_post(string $text, int $id, int $limit_letters = 300): string
{
    $sum_count_letters = 0;
    $text_array = explode(' ', $text);

    for ($i = 0, $count = count($text_array); $i < $count; $i++) {

        if (($sum_count_letters + mb_strlen($text_array[$i]) + 1) <= ($limit_letters + 1)){
            $sum_count_letters += mb_strlen($text_array[$i]) + 1;
            // +1 - это символ пробела, чтобы учитывать все символы после конкатенации элементов массива
        } else {
            return '<p>' . implode(' ', array_slice($text_array, 0, $i)) . '...' . '</p>' .
            '<a class="post-text__more-link" href="post.php?id=' . $id . '">Читать далее</a>';
        }
    }

    return '<p>' . $text . '</p>';
}

/**
 * Возвращает разницу между текущей и переданной в аргументе датами в формате:
 * - если до текущего времени прошло меньше 60 минут, то формат будет вида «% минут назад»;
 * - если до текущего времени прошло больше 60 минут, но меньше 24 часов, то формат будет вида «% часов назад»;
 * - если до текущего времени прошло больше 24 часов, но меньше 7 дней, то формат будет вида «% дней назад»;
 * - если до текущего времени прошло больше 7 дней, но меньше 5 недель, то формат будет вида «% недель назад»;
 * - если до текущего времени прошло больше 5 недель, то формат будет вида «% месяцев назад».
 * @param DateTime $date
 * @param string $format
 * @return string
 */
function get_diff_date(DateTime $date, string $format = "%d %s назад") : string
{
    $current_date = date_create(); // текущая дата
    $diff = date_diff($current_date, $date);

    $minutes_count = $diff->i;
    $hours_count = $diff->h;
    $days_count = $diff->d;
    $all_days_count = (int)date_interval_format($diff, "%a");
    $months_count = $diff->m;
    $years_count = $diff->y;

    $weeks_count = floor($all_days_count / 7);

    if (!($years_count || $months_count || $weeks_count || $days_count || $hours_count)) {
        $result = $minutes_count;
        $form = get_noun_plural_form($result, 'минута', 'минуты', 'минут');

    } elseif (!($years_count || $months_count || $weeks_count || $days_count)) {
        $result = $minutes_count/60 >= 0.5 ? $hours_count + 1 : $hours_count;
        $form = get_noun_plural_form($result, 'час', 'часа', 'часов');

    } elseif (!($years_count || $months_count || $weeks_count)) {
        $result = ($hours_count + $minutes_count/60)/24 >= 0.5 ? $all_days_count + 1 : $all_days_count;
        $form = get_noun_plural_form($result, 'день', 'дня', 'дней');

    } elseif (!($years_count || $months_count)) {
        $all_days_count = $all_days_count + ($hours_count + $minutes_count/60)/24;
        $result = round($all_days_count / 7);
        $form = get_noun_plural_form($result, 'неделя', 'недели', 'недель');

    } else {
        // Посчитаем кол-во дней в текущем месяце
        $days_in_month_current = cal_days_in_month(CAL_GREGORIAN, (int)$current_date->format('n'), (int)$current_date->format('Y'));

        $months_count = $years_count*12 + $months_count + (
            $days_count/$days_in_month_current >= 0.5 ? 1 : 0
        );
        $result = ($days_count || $hours_count || $minutes_count) ? $months_count + 1 : $months_count;
        $form = get_noun_plural_form($result, 'месяц', 'месяца', 'месяцев');
    }

    return sprintf($format, $result, $form);
}
