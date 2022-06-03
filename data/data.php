<?php
$is_auth = rand(0, 1);
$user_name = 'Евгений';
$title = "readme: популярное";
$posts_array = get_popular_posts($link);
$content_type_array = get_all_type_conyent($link);

