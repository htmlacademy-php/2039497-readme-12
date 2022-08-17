<?php
/**
 * Функция возвращает список популярных постов
 * Имеет необязательный параметр. Если он передан, то возвращается
 * список популярных постов определенного типа.
 * @param msqli
 * @param string $type_post
 * @param int $page_items
 * @param int $offset
 * @param string $sorted
 * @return array
 */
function get_popular_posts(mysqli $link, int $page_items, int $offset, string $sorted = null, string $type_post = null): array {

    $where = "";

    if (!is_null($type_post)) {
        $safe_type_post = mysqli_real_escape_string($link, $type_post);
        $where = "WHERE `tc`.`class_name` = '$safe_type_post'";
    }

    if ($sorted === "popular") {
        $sort = "ORDER BY `count_views` DESC";
    } elseif ($sorted === "like") {
        $sort = "ORDER BY `count_likes` DESC";
    } elseif($sorted === "date") {
        $sort = "ORDER BY `p`.`created_at` DESC";
    } else {
        $sort = "";
    }

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`class_name`,
            CASE
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` AS `author`,
                `u`.`login` AS `name_user`,
                `u`.`avatar`,
                `p`.`created_at`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `comments` `c`
                    WHERE
                        `c`.post_id = `p`.`id`
                )
                AS count_comment,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `likes` `l`
                    WHERE
                        `l`.post_id = `p`.`id`
                )
                AS count_likes
            FROM
                `posts` `p`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
            $where
            $sort
            LIMIT $page_items OFFSET $offset;";

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
 * @param string $tag
 * @return string
 *
 */
function get_class_active(string $type_post = null, string $tag = null): string {

    if (isset($_GET['type_post'])) {

        if ($_GET['type_post'] === $type_post) {
            if ($tag === "section") {
                return "tabs__content--active";
            }
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
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` AS `author`,
                `u`.`login` AS `name_user`,
                `u`.`avatar`,
                `u`.`id` AS `user_id`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `comments` `c`
                    WHERE
                        `c`.post_id = `p`.`id`
                )
                AS count_comment,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `likes` `l`
                    WHERE
                        `l`.post_id = `p`.`id`
                )
                AS count_likes,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `reposts` `rp`
                    WHERE
                        `rp`.post_id = `p`.`id`
                )
                AS count_reposts,
                `p`.`id`
            FROM
                `posts` `p`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
            WHERE
                `p`.`id` = '$id';";

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
 * @param string $id
 * @return bool
 */
function isset_post(mysqli $link, string $id): bool {

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
                COUNT(*) + (
                            SELECT
                                COUNT(*)
                            FROM
                                `reposts` `rp`
                                JOIN `users` `u` ON `u`.`id` = `rp`.`user_id`
                            WHERE
                                `u`.`login` = '$safe_name_user'
                ) AS `count`
            FROM
                `posts` `p`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                LEFT OUTER JOIN `reposts` `rp` ON `rp`.`user_id` = `u`.`id`
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
                COUNT(*) AS `count`
            FROM
                `subscriptions` `s`
                JOIN `users` `u` ON `u`.`id` = `s`.`destination_user_id`
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

/**
 * Валидация тега
 * @param string $value
 * @return string|null
 */
function validate_tags($value) {

    $words = explode(" ", $value);
    $pattern = '/^[[:alpha:]]+$/isu';

    foreach ($words as $val) {
        if (!$val) {
            return "Каждый тег состоит только из одного слова. Теги разделяются пробелом.";
        } else {
            preg_match($pattern, $val, $matches);
            if (count($matches) === 0) {
                return "Каждый тег состоит только из одного слова. Теги разделяются пробелом.";
            }
        }
    }
    return null;
}

/**
 * Валидация картинки
 * @param string $field
 * @return string|null
 */
function validate_file_photo($field, &$post) {

	if (!empty($_FILES[$field]['name'])) {

		$tmp_name = $_FILES[$field]['tmp_name'];
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$file_type = finfo_file($finfo, $tmp_name);

        $file_name = $_FILES[$field]['name'];
        $extension = ( new SplFileInfo($file_name) )->getExtension();

        if (!in_array($file_type, ["image/gif", "image/png", "image/jpeg"])  || !in_array($extension, ["jpeg", "png", "gif", "jpg"])) {
            return 'Картинка должна соответствовать форматам: gif, jpeg, png.';
        }

        $post[$field] = $file_name;

	} else {
        return "Выберете фото или укажите ссылку из интернета.";

    }

    return null;
}

/**
 * Валидация поля ссылка на картинку
 * @param string $value
 * @return string|null
 */
function validate_link_photo($value, &$post) {

    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
        return "Некорректная ссылка.";
    }

    $image = file_get_contents($value);

    if (!$image) {
        return "Файл не удалось загрузить.";
    } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_name_tmp = explode("/", $value);
        $file_name = end($file_name_tmp);
        $extension = ( new SplFileInfo($file_name) )->getExtension();
        $path = sys_get_temp_dir();

        file_put_contents($path . $file_name, $image);

        $file_type = finfo_file($finfo, $path . $file_name);

        if (!in_array($file_type, ["image/gif", "image/png", "image/jpeg"]) || !in_array($extension, ["jpeg", "png", "gif", "jpg"])) {
            unlink($path . $file_name);
            return 'Картинка должна соответствовать форматам: gif, jpeg, png. И иметь соответствующее расширение.';
        }
    }

    $post['photo'] = $file_name;

    return null;
}

/**
 * Валидация поля видео
 * @param string $value
 * @return string|null
 */
function validate_video($value) {
    if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
        if (gettype(check_youtube_url($value)) === "string") {
            return check_youtube_url($value);
        }
    }
    return null;
}

/**
 * Возвращает класс ошибки для поля, которое некорреткно заполнено
 * @param array $errors
 * @param string $field
 * @return string
 */
function get_class_error($errors, $field) {
    if (isset($errors[$field])) {
        return "form__input-section--error";
    }
    return "";
}

/**
 * Возвращает значение поля из запроса
 * @param string $name
 * @return string
 */
function get_post_val($name) {
    return filter_input(INPUT_POST, $name);
}

/**
 * Возвращает запрос для добавления поста в бд
 * @param string $type_content
 * @return string
 */
function get_sql_add_post($type_content) : string {

    $sql = "";

    if ($type_content == 'quote') {
        $sql = "INSERT INTO `posts` (`user_id`, `created_at`, `header`, `content_text`, `author_quote`, `type_content_id`) VALUES (?, NOW(),
        ?, ?, ?, ?)";
    } elseif ($type_content == 'text') {
        $sql = "INSERT INTO `posts` (`user_id`, `created_at`, `header`, `content_text`, `type_content_id`) VALUES (?, NOW(),
        ?, ?, ?)";
    } elseif ($type_content == 'photo') {
        $sql = "INSERT INTO `posts` (`user_id`, `created_at`, `header`, `content_photo`, `type_content_id`) VALUES (?, NOW(),
        ?, ?, ?)";
    } elseif ($type_content == 'link') {
        $sql = "INSERT INTO `posts` (`user_id`, `created_at`, `header`, `content_link`, `type_content_id`) VALUES (?, NOW(),
        ?, ?, ?)";
    } elseif ($type_content == 'video') {
        $sql = "INSERT INTO `posts` (`user_id`, `created_at`, `header`, `content_video`, `type_content_id`) VALUES (?, NOW(),
        ?, ?, ?)";
    }

    return $sql;
}

/**
 * Возвращает id поста
 * @param msqli
 * @param string $type_content
 * @return int
 */
function get_id_type_post(mysqli $link, string $type_content): int {

    $safe_type_content = mysqli_real_escape_string($link, $type_content);

    $sql = "SELECT
                `tc`.`id`
            FROM
                `type_content` `tc`
            WHERE
                `tc`.`class_name` = '$safe_type_content';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result)['id'];
}

/**
 * Зпрос проверку сущетствования тега в бд
 * @param msqli
 * @param string $type_content
 * @return array
 */
function isset_hashtag(mysqli $link, string $hashtag) {

    $safe_hashtag = mysqli_real_escape_string($link, $hashtag);

    $sql = "SELECT
                `h`.`id`
            FROM
                `hashtags` `h`
            WHERE
                `h`.`hashtag` = '$safe_hashtag';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Возвращает запрос для добавления тега в бд
 * @return string
 */
function get_sql_add_hashtag() {
    $sql = 'INSERT INTO `hashtags` (`hashtag`) VALUES (?)';
    return $sql;
}

/**
 * Возвращает запрос для добавления записи в бд для связи поста и тега
 * @return string
 */
function get_sql_add_posts_hashtag() : string {
    $sql = 'INSERT INTO `posts_hashtag` (`post_id`, `hashtag_id`) VALUES (?, ?)';
    return $sql;
}

/**
 * Возвращает список полей, которе необходимо заполнить
 * @param string $type_content
 * @return array
 */
function get_required_fields($type_content) : array {

    $fields = ["$type_content-heading", $type_content, "$type_content-tags"];

    if ($type_content === 'photo') {

        if (isset($_POST['userpic-file-photo']) || (!isset($_POST['userpic-file-photo']) && (!isset($_POST['photo']) || $_POST['photo'] === ''))) {
            $key = array_search('photo', $fields);
            unset($fields[$key]);
            $fields[] = 'userpic-file-photo';
        }

    } elseif ($type_content === 'quote') {
        $fields[] = 'quote-author';
    }

    return $fields;
}

/**
 * Возращается массив полей post-запроса
 * @param string $type_content
 * @return array
 */
function get_prepared_post($type_content) : array {
    return get_required_fields($type_content);
}

/**
 * Добавляет посты в БД
 * @param msqli
 * @param string $type_content
 * @param array $post_field_filter
 * @param string $user_id
 * @return string
 */
function add_post(mysqli $link, string $type_content, array $post_field_filter, string $user_id) : string {

    array_unshift($post_field_filter, $user_id);
    $stmt = db_get_prepare_stmt($link, get_sql_add_post($type_content), $post_field_filter);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        return mysqli_insert_id($link);;
    } else {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }
}

/**
 * Добавляет теги в БД
 * @param msqli
 * @param string $tags
 * @param string $post_id
 */
function add_tag(mysqli $link, string $tags, string $post_id) : void {

    foreach(explode(" ", $tags) as $tag) {

        // Тег существует?
        if (isset_hashtag($link, $tag)) {

            // Заполняем только связующую таблицу
            $hashtag_id = isset_hashtag($link, $tag)['id'];
            $stmt = db_get_prepare_stmt($link, get_sql_add_posts_hashtag(), [$post_id, $hashtag_id]);
            $res = mysqli_stmt_execute($stmt);

            if (!$res) {
                $error = mysqli_error($link);
                print("Ошибка MySQL: " . $error);
                exit();
            }

        } else {
            // Добавляем тег
            $stmt = db_get_prepare_stmt($link, get_sql_add_hashtag(), [$tag]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $hashtag_id = mysqli_insert_id($link); // id добавленного тега
                // Заполняем связующую таблицу
                $stmt = db_get_prepare_stmt($link, get_sql_add_posts_hashtag(), [$post_id, $hashtag_id]);
                $res = mysqli_stmt_execute($stmt);

                if (!$res) {
                    $error = mysqli_error($link);
                    print("Ошибка MySQL: " . $error);
                    exit();
                }

            } else {
                $error = mysqli_error($link);
                print("Ошибка MySQL: " . $error);
                exit();
            }
        }
    }
}

/**
 * Проверяет существование пользователя в базе и добавляет его, если нету
 * @param array $errors
 * @param array $form
 * @return bool
 */
function add_user(mysqli $link, &$errors, $form) : bool {

    $email = mysqli_real_escape_string($link, $form['email']);
    $login = mysqli_real_escape_string($link, $form['login']);
    $sql_email = "SELECT id FROM users WHERE email = '$email'";
    $sql_login = "SELECT id FROM users WHERE login = '$login'";
    $res_email = mysqli_query($link, $sql_email);
    $res_login = mysqli_query($link, $sql_login);

    if (mysqli_num_rows($res_email) > 0) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    } elseif (mysqli_num_rows($res_login) > 0) {
        $errors['login'] = 'Пользователь с этим login уже зарегистрирован';
    } elseif ($form['password'] !== $form['password-repeat']) {
        $errors['password-repeat'] = 'Повтор пароля введен неверно';
    } else {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
        $avatar = $form['userpic-file'] ? $form['userpic-file'] : "ava.jpg";
        $sql = 'INSERT INTO `users` (`created_at`, `email`, `login`, `password`, `avatar`) VALUES (NOW(), ?, ?, ?, ?);';
        $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['login'], $password, $avatar]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            return true;
        }

        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();

    }

    return false;
}

/**
 * Проверяет аватарку
 * @param string $field
 * @return string|null
 */
function validate_avatar($field) {

	if (!empty($_FILES[$field]['name'])) {

		$tmp_name = $_FILES[$field]['tmp_name'];
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$file_type = finfo_file($finfo, $tmp_name);

        $file_name = $_FILES[$field]['name'];
        $extension = ( new SplFileInfo($file_name) )->getExtension();

        if (!in_array($file_type, ["image/gif", "image/png", "image/jpeg"])  || !in_array($extension, ["jpeg", "png", "gif", "jpg"])) {
            return 'Аватар должен соответствовать форматам: gif, jpeg, png.';
        }

	}

    return null;
}


/**
 * Список постов для ленты
 * @param msqli
 * @param string $id_user
 * @param string|null $type_post
 * @return array
 */
function get_posts_subscriptions(mysqli $link, string $id_user, string $type_post = null): array {

    $safe_id_user = mysqli_real_escape_string($link, $id_user);
    $where = '';

    if (!is_null($type_post)) {
        $safe_type_post = mysqli_real_escape_string($link, $type_post);
        $where = "AND `tc`.`class_name` = '$safe_type_post'";
    }

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`class_name`,
                `u`.`avatar`,
                `u`.`login`,
            CASE
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` AS `author`,
                `u`.`login` AS `name_user`,
                `u`.`avatar`,
                `p`.`created_at`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `comments` `c`
                    WHERE
                        `c`.post_id = `p`.`id`
                )
                AS count_comment,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `likes` `l`
                    WHERE
                        `l`.post_id = `p`.`id`
                )
                AS count_likes
            FROM
                `posts` `p`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
            WHERE
                `u`.`id` IN (SELECT
                                `sb`.`destination_user_id`
                            FROM
                                `subscriptions` `sb`
                            WHERE
                                `sb`.`source_user_id` = '$safe_id_user'
                            )
                $where
            ORDER BY `p`.`created_at` DESC;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Результат поиска по постам
 * @param msqli
 * @param string $search
 * @return array
 */
function get_search_posts(mysqli $link, string $search): array {

    $safe_search = mysqli_real_escape_string($link, $search);

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`class_name`,
                `u`.`avatar`,
                `u`.`login`,
            CASE
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` AS `author`,
                `u`.`login` AS `name_user`,
                `u`.`avatar`,
                `p`.`created_at`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `comments` `c`
                    WHERE
                        `c`.post_id = `p`.`id`
                )
                AS count_comment,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `likes` `l`
                    WHERE
                        `l`.post_id = `p`.`id`
                )
                AS count_likes
            FROM
                `posts` `p`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
            WHERE
                MATCH(`p`.`header`, `p`.`content_text`, `p`.`author_quote`) AGAINST('$safe_search');";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Возвращает посты пользователя
 * @param msqli
 * @param string $id_user
 * @return array
 */
function get_posts_user(mysqli $link, string $id_user): array {

    $safe_id_user = mysqli_real_escape_string($link, $id_user);

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`class_name`,
                `u`.`avatar`,
                `u`.`login`,
            CASE
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` AS `author`,
                `u`.`login` AS `name_user`,
                `u`.`avatar`,
                `p`.`created_at`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `comments` `c`
                    WHERE
                        `c`.post_id = `p`.`id`
                )
                AS count_comment,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `likes` `l`
                    WHERE
                        `l`.post_id = `p`.`id`
                )
                AS count_likes,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `reposts` `rp`
                    WHERE
                        `rp`.post_id = `p`.`id`
                )
                AS count_reposts
            FROM
                `posts` `p`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
            WHERE
                `u`.`id` = '$safe_id_user'
            ORDER BY `p`.`created_at` DESC;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Возвращает профиль пользователя
 * @param msqli
 * @param string $id_user
 * @return array
 */
function get_user_profile(mysqli $link, string $id_user) {

    $safe_id_user = mysqli_real_escape_string($link, $id_user);
    $sql = "SELECT
                `u`.`login`,
                `u`.`avatar`,
                `u`.`created_at`,
                `u`.`id`,
                `u`.`email`
            FROM
                `users` `u`
            WHERE
                `u`.`id` = '$safe_id_user';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result);
}

/**
 * Возвращает класс для разметки
 * @param string $type
 * @param bool|null $content
 * @return string
 */
function get_class_user_profile(string $type, bool $content = null): string {

    if ($content) {
        $class = "tabs__content--active";
    } else {
        $class = "tabs__item--active filters__button--active";
    }

    if (isset($_GET['type'])) {

        if ($_GET['type'] === $type) {
            return $class;
        }

    } elseif ($type === 'post') {
        return $class;
    }

    return "";
}

/**
 * Возвращает все лайки для данного пользователя
 * @param msqli
 * @param string $id_post
 * @return array
 */
function get_likes(mysqli $link, string $id_user) : array {

    $safe_id_user = mysqli_real_escape_string($link, $id_user);

    $sql = "SELECT
                `tc`.`class_name`,
                `l`.`user_id`,
                `p`.`id`,
            CASE
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `l`.`created_at`,
                `u`.`login`,
                `u`.`avatar`
            FROM
                `likes` `l`
                JOIN `posts` `p` ON `p`.`id` = `l`.`post_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
                JOIN `users` `u` ON `u`.`id` = `l`.`user_id`
            WHERE
                `p`.`id` IN (
                                SELECT
                                    `p`.`id`
                                FROM
                                    `posts` `p`
                                WHERE
                                    `p`.`user_id` = '$safe_id_user'
                )
            ORDER BY `l`.`created_at`;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает тэги к посту
 * @param msqli
 * @param string $id_post
 * @return array
 */
function get_hashtags(mysqli $link, string $id_post) :array {

    $safe_id_post = mysqli_real_escape_string($link, $id_post);

    $sql = "SELECT
                `h`.`hashtag`
            FROM
                `hashtags` `h`
                JOIN `posts_hashtag` `ph` ON `ph`.`hashtag_id` = `h`.`id`
            WHERE
                `ph`.`post_id` = '$safe_id_post';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает комментарии к посту
 * @param msqli
 * @param string $id_post
 * @return array
 */
function get_comments(mysqli $link, string $id_post) :array {

    $safe_id_post = mysqli_real_escape_string($link, $id_post);

    $sql = "SELECT
                `c`.`comment`,
                `c`.`created_at`,
                `u`.`login`,
                `u`.`avatar`
            FROM
                `comments` `c`
                JOIN `users` `u` ON `u`.`id` = `c`.`user_id`
            WHERE
                `c`.`post_id` = '$safe_id_post'
            ORDER BY `c`.`created_at`;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string|null $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover_profile(string $youtube_url = null) {
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img calss="post-mini__image" alt="youtube cover" width="109" height="109" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Возвращает подписчиков пользователя
 * @param msqli
 * @param string $id_user
 * @return array
 */
function get_subscribers(mysqli $link, string $id_user) : array {

    $safe_id_user = mysqli_real_escape_string($link, $id_user);

    $sql = "SELECT
                `u`.`login`,
                `u`.`created_at`,
                `u`.`avatar`,
                `u`.`id`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `posts` `p`
                    WHERE
                        `p`.`user_id` = `u`.`id`
                )
                AS count_posts,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `subscriptions` `sb2`
                    WHERE
                        `sb2`.`destination_user_id` = `u`.`id`
                )
                AS count_subscribers
            FROM
                `subscriptions` `sb`
                JOIN `users` `u` ON `u`.`id` = `sb`.`destination_user_id`
            WHERE
                `sb`.`source_user_id` = '$safe_id_user'";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает список репостов
 * @param msqli
 * @param string $id_user
 * @return array
 */
function get_reposts_user(mysqli $link, string $id_user) : array {

    $safe_id_user = mysqli_real_escape_string($link, $id_user);

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`class_name`,
                `u`.`avatar`,
                `u`.`login`,
                'repost' AS `repost`,
                `rp`.`created_at` AS `repost_created_at`,
            CASE
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` AS `author`,
                `u`.`login` AS `name_user`,
                `u`.`avatar`,
                `p`.`created_at`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `comments` `c`
                    WHERE
                        `c`.post_id = `p`.`id`
                )
                AS count_comment,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `likes` `l`
                    WHERE
                        `l`.post_id = `p`.`id`
                )
                AS count_likes,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `reposts` `rp`
                    WHERE
                        `rp`.post_id = `p`.`id`
                )
                AS count_reposts
            FROM
                `reposts` `rp`
                JOIN `posts` `p` ON `rp`.`post_id` = `p`.`id`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
            WHERE
                `rp`.`user_id` = '$safe_id_user'
            ORDER BY `p`.`created_at` DESC;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Добавляет комментарий к посту в базу
 * @param msqli
 * @param array $form
 * @param string $user_id
 */
function add_comment(mysqli $link, array $form, string $user_id) {

    $comment = mysqli_real_escape_string($link, trim($form['comment']));
    $post_id = mysqli_real_escape_string($link, $form['post_id']);
    $user_id = mysqli_real_escape_string($link, $user_id);

    if (isset_post($link, $post_id)) {
        $sql = 'INSERT INTO `comments` (`created_at`, `comment`, `user_id`, `post_id`) VALUES (NOW(), ?, ?, ?);';
        $stmt = db_get_prepare_stmt($link, $sql, [$comment, $user_id, $post_id]);
        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            $error = mysqli_error($link);
            print("Ошибка MySQL: " . $error);
            exit();
        }

    } else {
        print("Такой пост не существует!");
        exit();
    }
}

/**
 * Добавляет подписку в базу
 * @param msqli
 * @param array $form
 * @param string $user_id
 */
function add_subscription(mysqli $link, array $form, string $user_id) {

    $destination_user_id = mysqli_real_escape_string($link, $form['destination-user']);
    $source_user_id = mysqli_real_escape_string($link, $user_id);
    // echo $destination_user_id;exit;
    if (isset_user($link, $destination_user_id)) {
        $sql = 'INSERT INTO `subscriptions` (`source_user_id`, `destination_user_id`) VALUES (?, ?);';
        $stmt = db_get_prepare_stmt($link, $sql, [$source_user_id, $destination_user_id]);
        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            $error = mysqli_error($link);
            print("Ошибка MySQL: " . $error);
            exit();
        }

    } else {
        print("Такой пользователь не существует!");
        exit();
    }
}

/**
 * Проверяет наличие подписки
 * @param msqli
 * @param string $source_user_id
 * @param string $destination_user_id
 * @return bool
 */
function isset_subscription(mysqli $link, string $source_user_id, string $destination_user_id): bool {

    $destination_user_id = mysqli_real_escape_string($link, $destination_user_id);
    $source_user_id = mysqli_real_escape_string($link, $source_user_id);

    $sql = "SELECT
                *
            FROM
                `subscriptions` `s`
            WHERE
                `s`.source_user_id = '$source_user_id'
                AND `s`.destination_user_id = '$destination_user_id'
    ;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return (bool)mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Удаляет подписку из базы
 * @param msqli
 * @param array $form
 * @param string $user_id
 */
function del_subscription(mysqli $link, array $form, string $user_id) {

    $destination_user_id = mysqli_real_escape_string($link, $form['destination-user']);
    $source_user_id = mysqli_real_escape_string($link, $user_id);

    if (isset_user($link, $destination_user_id)) {
        $sql = 'DELETE FROM `subscriptions` WHERE `source_user_id` = ? AND `destination_user_id` = ?;';
        $stmt = db_get_prepare_stmt($link, $sql, [$source_user_id, $destination_user_id]);
        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            $error = mysqli_error($link);
            print("Ошибка MySQL: " . $error);
            exit();
        }

    } else {
        print("Такой пользователь не существует!");
        exit();
    }
}

/**
 * Добавляет лайк в базу
 * @param msqli
 * @param string $user_id
 * @param string $post_id
 */
function add_like(mysqli $link, string $user_id, string $post_id) {

    $user_id = mysqli_real_escape_string($link, $user_id);
    $post_id = mysqli_real_escape_string($link, $post_id);

    if (isset_post($link, $post_id)) {

        $sql = 'INSERT INTO `likes` (`user_id`, `post_id`, `created_at`) VALUES (?, ?, NOW());';
        $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $post_id]);
        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            $error = mysqli_error($link);
            print("Ошибка MySQL: " . $error);
            exit();
        }

    } else {
        print("Такой пост не существует!");
        exit();
    }
}

/**
 * Добавляет репост в базу
 * @param msqli
 * @param string $user_id
 * @param string $post_id
 */
function add_repost(mysqli $link, string $user_id, string $post_id) {

    $user_id = mysqli_real_escape_string($link, $user_id);
    $post_id = mysqli_real_escape_string($link, $post_id);

    if (isset_post($link, $post_id)) {

        $sql = 'INSERT INTO `reposts` (`created_at`, `user_id`, `post_id`) VALUES (NOW(), ?, ?);';
        $stmt = db_get_prepare_stmt($link, $sql, [$user_id, $post_id]);
        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            $error = mysqli_error($link);
            print("Ошибка MySQL: " . $error);
            exit();
        }

    } else {
        print("Такой пост не существует!");
        exit();
    }
}

/**
 * Возвращает общее кол-во постов
 * @param msqli
 * @param string|null $type_content
 * @return int
 */
function get_count_popular_posts(mysqli $link, string $type_content = null): int {

    $where = '';

    if ($type_content) {
        $type_content = mysqli_real_escape_string($link, $type_content);
        $where = "WHERE `tc`.`class_name` = '$type_content'";
    }

    $sql = "SELECT
                COUNT(*) AS `count`
            FROM
                `posts` `p`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
            $where;";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_assoc($result)['count'];
}

/**
 * Проверяет существование пользователя
 * @param msqli
 * @param string $user_id
 * @return bool
 */
function isset_user(mysqli $link, string $user_id): bool {

    $sql = "SELECT
                *
            FROM
                `users` `u`
            WHERE
                `u`.`id` = '$user_id';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return (bool)mysqli_fetch_assoc($result);
}

/**
 * Результат поиска по постам
 * @param msqli
 * @param string $search
 * @return array
 */
function get_search_posts_by_tag(mysqli $link, string $search): array {

    $safe_search = mysqli_real_escape_string($link, $search);

    $sql = "SELECT
                `p`.`id`,
                `p`.`header`,
                `tc`.`class_name`,
                `u`.`avatar`,
                `u`.`login`,
            CASE
                WHEN `tc`.`class_name` IN ('quote', 'text')
                    THEN `p`.`content_text`
                WHEN `tc`.`class_name` = 'photo'
                    THEN `p`.`content_photo`
                WHEN `tc`.`class_name` = 'link'
                    THEN `p`.`content_link`
                ELSE `p`.`content_video`
            END AS `content`,
                `p`.`author_quote` AS `author`,
                `u`.`login` AS `name_user`,
                `u`.`avatar`,
                `p`.`created_at`,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `comments` `c`
                    WHERE
                        `c`.post_id = `p`.`id`
                )
                AS count_comment,
                (
                    SELECT
                        COUNT(*) AS `count`
                    FROM
                        `likes` `l`
                    WHERE
                        `l`.post_id = `p`.`id`
                )
                AS count_likes
            FROM
                `posts` `p`
                JOIN `users` `u` ON `u`.`id` = `p`.`user_id`
                JOIN `type_content` `tc` ON `tc`.`id` = `p`.`type_content_id`
                JOIN `posts_hashtag` `ph` ON `ph`.`post_id` = `p`.`id`
                JOIN `hashtags` `h` ON `h`.`id` = `ph`.`hashtag_id`
            WHERE
                `h`.`hashtag` = '$safe_search';";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        $error = mysqli_error($link);
        print("Ошибка MySQL: " . $error);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 *
 * @param string $param
 * @return string
 */
function get_sorted_class(string $param) : string {

    if (isset($_GET['sorted']) && $_GET['sorted'] === $param) {
        return "sorting__link--active";
    }

    return "";
}
