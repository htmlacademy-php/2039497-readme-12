<?php
/**
 * Функция возвращает список самых популярных постов
 * Примечание: пока что она выводит все посты
 * @param msqli
 * @return array
 */
function get_popular_posts(mysqli $link): array {

    $sql = "SELECT
                `p`.`header`,
                `tc`.`class_name`,
            CASE
                WHEN `tc`.`class_name` = 'quote'
                    THEN `p`.`author_quote`
                WHEN `tc`.`class_name` = 'text'
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
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
