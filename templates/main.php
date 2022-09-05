    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link <?=!isset($_GET['sorted']) ? "sorting__link--active" : get_sorted_class("popular");?>" href="<?=$filter_posts;?>&sorted=popular">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?=get_sorted_class("like");?>" href="<?=$filter_posts;?>&sorted=like">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?=get_sorted_class("date");?>" href="<?=$filter_posts;?>&sorted=date">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all <?=get_class_active();?> <?=get_class_active("all");?>" href="<?=$filter_posts . $sorted;?>&type_post=all">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach($content_type_array as $type_content):?>
                    <li class="popular__filters-item filters__item">
                        <a class="filters__button filters__button--<?=$type_content['class_name'];?> button <?=get_class_active($type_content['class_name']);?>" href="<?=$filter_posts . $sorted;?>&type_post=<?=$type_content['class_name'];?>">
                            <?php if($type_content['class_name'] === 'photo'):?>
                                <span class="visually-hidden">Фото</span>
                                <svg class="filters__icon" width="22" height="18">
                            <?php elseif($type_content['class_name'] === 'quote'):?>
                                <span class="visually-hidden">Цитата</span>
                                <svg class="filters__icon" width="21" height="20">
                            <?php elseif($type_content['class_name'] === 'link'):?>
                                <span class="visually-hidden">Ссылка</span>
                                <svg class="filters__icon" width="21" height="18">
                            <?php elseif($type_content['class_name'] === 'video'):?>
                                <span class="visually-hidden">Видео</span>
                                <svg class="filters__icon" width="24" height="16">
                            <?php elseif($type_content['class_name'] === 'text'):?>
                                <span class="visually-hidden">Текст</span>
                                <svg class="filters__icon" width="20" height="21">
                            <?php endif;?>
                                <use xlink:href="#icon-filter-<?=$type_content['class_name'];?>"></use>
                                </svg>
                        </a>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach($posts_array as $idx => $post):?>
                <?php $date = generate_random_date($idx);?>
                <article class="popular__post post post-<?=$post['class_name'];?>">
                    <header class="post__header">
                        <h2><!--здесь заголовок--><a href="post.php?id=<?=$post['id'];?>"><?=htmlspecialchars($post['header']);?></a></h2>
                    </header>
                    <div class="post__main">
                        <!--здесь содержимое карточки-->
                        <?php if($post['class_name'] === 'quote'):?>
                            <!--содержимое для поста-цитаты-->
                            <blockquote>
                                <p>
                                    <!--здесь текст-->
                                    <?=htmlspecialchars($post['content']);?>
                                </p>
                                <cite><?=htmlspecialchars($post['author']);?></cite>
                            </blockquote>

                        <?php elseif($post['class_name'] === 'link'):?>
                            <!--содержимое для поста-ссылки-->
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><!--здесь заголовок--><?=htmlspecialchars($post['header']);?></h3>
                                        </div>
                                    </div>
                                    <span><!--здесь ссылка--><?=htmlspecialchars($post['content']);?></span>
                                </a>
                            </div>

                        <?php elseif($post['class_name'] === 'photo'):?>
                            <!--содержимое для поста-фото-->
                            <div class="post-photo__image-wrapper">
                                <img src="<?=htmlspecialchars($post['content']);?>" alt="Фото от пользователя" width="360" height="240">
                            </div>

                        <?php elseif($post['class_name'] === 'video'): ?>
                            <!--содержимое для поста-видео-->
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?=embed_youtube_cover(/* вставьте ссылку на видео */htmlspecialchars($post['content']));?>
                                </div>
                                <a href="post.php?id=<?=$post['id'];?>" class="post-video__play-big button">
                                    <svg class="post-video__play-big-icon" width="14" height="14">
                                        <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                    <span class="visually-hidden">Запустить проигрыватель</span>
                                </a>
                            </div>
                        <?php elseif($post['class_name'] === 'text'):?>
                            <!--содержимое для поста-текста-->
                            <!--здесь текст--><?=cut_text_post(htmlspecialchars($post['content']), $post['id']);?>
                        <?php endif;?>
                    </div>
                    <footer class="post__footer">
                        <div class="post__author">
                            <a class="post__author-link" href="#" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <!--укажите путь к файлу аватара-->
                                    <img class="post__author-avatar" src="uploads/<?=htmlspecialchars($post['avatar']);?>" alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><!--здесь имя пользоателя--><?=htmlspecialchars($post['name_user']);?></b>
                                    <time class="post__time" datetime="<?=date('d.m.Y H:i', strtotime($post['created_at']));?>" title="<?=date('d.m.Y H:i', strtotime($post['created_at']));?>"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($post['created_at']))));?></time>
                                </div>
                            </a>
                        </div>
                        <div class="post__indicators">
                            <div class="post__buttons">
                                <a class="post__indicator post__indicator--likes button" href="<?=$filter_posts;?>&like_post=<?=$post['id'];?>" title="Лайк">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span><?=$post['count_likes'];?></span>
                                    <span class="visually-hidden">количество лайков</span>
                                </a>
                                <a class="post__indicator post__indicator--comments button" href="post.php?id=<?=$post['id'];?>" title="Комментарии">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-comment"></use>
                                    </svg>
                                    <span><?=$post['count_comment'];?></span>
                                    <span class="visually-hidden">количество комментариев</span>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach;?>
        </div>
        <div class="popular__page-links">
            <a class="popular__page-link popular__page-link--prev button button--gray" <?=$cur_page == 1 || !$pages_count ? 'style="display: none;"' : '';?> href="<?=$filter_posts . $sorted;?>&page=<?=($cur_page - 1) > 0 ? ($cur_page - 1) : "1"?>">Предыдущая страница</a>
            <a class="popular__page-link popular__page-link--next button button--gray" <?=$cur_page == $pages_count || !$pages_count ? 'style="display: none;"' : '';?> href="<?=$filter_posts . $sorted;?>&page=<?=($cur_page + 1) < $pages_count ? ($cur_page + 1) : "$pages_count"?>">Следующая страница</a>
        </div>
    </div>
