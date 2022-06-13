<?php
/**
 * Функция возвращает список самых популярных постов
 * Примечание: пока что она выводит все посты
 * @param msqli
 * @return array
 */
function get_popular_posts(mysqli $link): array {

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
 * Функция возвращает список популярных постов выбранного типа
 * @param msqli
 * @param int $id
 * @return array
 */
function get_popular_selected_posts(mysqli $link, int $id): array {

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`type`,
                `p`.`content_text` AS `content`,
                `p`.`author_quote` as `author`,
                `u`.`login` as `name_user`,
                `u`.`avatar`,
                `tc`.`class_name`
            FROM
                `posts` `p`
                JOIN `users` `u` on `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` on `tc`.`id` = `p`.`type_content_id`
            WHERE
                `tc`.`id` = '$id'
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
 * Функция возвращает класс ссылки в зависимости от
 * наличия get запроса и id типа поста
 * @param string $type_post
 * @param int $id
 * @return string
 *
 */
function getClass(string $type_post, int $id = null): string {

    if (isset($_GET[$type_post])) {

        if ($_GET[$type_post] === (string)$id) {
            return "filters__button--active";
        }

    } elseif (is_null($id)) {
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

    $sql = "SELECT
                *
            FROM
                `posts` `p`
                JOIN `users` `u` on `u`.`id` = `p`.`user_id`
            WHERE
                `u`.`login` = '$name_user';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_num_rows($result);
}

/**
 * Функция возвращает количество подписчиков данного пользователя
 * @param msqli
 * @param string $name_user
 * @return int
 */
function get_count_subscriptions_user(mysqli $link, string $name_user): int {

    $sql = "SELECT
                *
            FROM
                `subscriptions` `s`
                JOIN `users` `u` on `u`.`id` = `s`.`destination_post_id`
            WHERE
                `u`.`login` = '$name_user';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_num_rows($result);
}

/**
 * Функция возвращает дату регистрации пользователя на сайте
 * @param msqli
 * @param string $name_user
 * @return string
 */
function get_created_at_user(mysqli $link, string $name_user): string {

    $sql = "SELECT
                `u`.`created_at`
            FROM
                `users` `u`
            WHERE
                `u`.`login` = '$name_user';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result)['created_at'];
}
