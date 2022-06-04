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
                `tc`.`type`,
            CASE
                WHEN `tc`.`type` = 'Цитата'
                    THEN `p`.`author_quote`
                WHEN `tc`.`type` = 'Текст'
                    THEN `p`.`content_text`
                WHEN `tc`.`type` = 'Картинка'
                    THEN `p`.`content_photo`
                WHEN `tc`.`type` = 'Ссылка'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `u`.`login` as `name_user`,
                `u`.`avatar`,
                `tc`.`class_name`
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
function get_all_type_conyent(mysqli $link): array {

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
