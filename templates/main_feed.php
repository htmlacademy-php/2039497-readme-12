<div class="container">
    <h1 class="page__title page__title--feed">Моя лента</h1>
</div>
<div class="page__main-wrapper container">
    <section class="feed">
        <h2 class="visually-hidden">Лента</h2>
        <div class="feed__main-wrapper">
            <div class="feed__wrapper">
                <?php foreach($posts_array as $post):?>
                    <article class="feed__post post post-<?=$post['class_name'];?>">
                    <header class="post__header post__author">
                        <a class="post__author-link" href="#" title="Автор">
                        <div class="post__avatar-wrapper">
                            <img class="post__author-avatar" src="uploads/<?=htmlspecialchars($post['avatar']);?>" alt="Аватар пользователя" width="60" height="60">
                        </div>
                        <div class="post__info">
                            <b class="post__author-name"><?=htmlspecialchars($post['login']);?></b>
                            <span class="post__time"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($post['created_at']))));?></span>
                        </div>
                        </a>
                    </header>
                    <div class="post__main">
                        <?php if($post['class_name'] === 'photo'):?>
                            <h2><a href="post.php?id=<?=$post['id'];?>"><?=htmlspecialchars($post['header']);?></a></h2>
                            <div class="post-photo__image-wrapper">
                                <img src="<?=htmlspecialchars($post['content']);?>" alt="Фото от пользователя" width="760" height="396">
                            </div>
                        <?php elseif($post['class_name'] === 'text'):?>
                            <h2><a href="post.php?id=<?=$post['id'];?>"><?=htmlspecialchars($post['header']);?></a></h2>
                            <p>
                            <?=cut_text_post(htmlspecialchars($post['content']), $post['id']);?>
                            </p>
                            <a class="post-text__more-link" href="post.php?id=<?=$post['id'];?>">Читать далее</a>
                        <?php elseif($post['class_name'] === 'video'):?>
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?=embed_youtube_cover(htmlspecialchars($post['content']));?>
                                </div>
                                <div class="post-video__control">
                                    <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                                    <div class="post-video__scale-wrapper">
                                    <div class="post-video__scale">
                                        <div class="post-video__bar">
                                        <div class="post-video__toggle"></div>
                                        </div>
                                    </div>
                                    </div>
                                    <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                                </div>
                                <button class="post-video__play-big button" type="button">
                                    <svg class="post-video__play-big-icon" width="27" height="28">
                                    <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                    <span class="visually-hidden">Запустить проигрыватель</span>
                                </button>
                            </div>
                        <?php elseif($post['class_name'] === 'quote'):?>
                            <blockquote>
                            <p>
                            <?=htmlspecialchars($post['content']);?>
                            </p>
                            <cite><?=htmlspecialchars($post['author']);?></cite>
                            </blockquote>
                        <?php elseif($post['class_name'] === 'link'):?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://<?=htmlspecialchars($post['content']);?>" title="Перейти по ссылке">
                                    <div class="post-link__icon-wrapper">
                                    <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                    <h3><?=htmlspecialchars($post['header']);?></h3>
                                    <span><?=htmlspecialchars($post['content']);?></span>
                                    </div>
                                    <svg class="post-link__arrow" width="11" height="16">
                                    <use xlink:href="#icon-arrow-right-ad"></use>
                                    </svg>
                                </a>
                            </div>
                        <?php endif;?>
                    </div>
                    <footer class="post__footer post__indicators">
                        <div class="post__buttons">
                            <a class="post__indicator post__indicator--likes button" href="?like_post=<?=$post['id'];?>" title="Лайк">
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
                            <a class="post__indicator post__indicator--repost button" href="?repost=<?=$post['id'];?>" title="Репост">
                                <svg class="post__indicator-icon" width="19" height="17">
                                <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?=$post['count_repost'];?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                    </footer>
                </article>
                <?php endforeach;?>
            </div>
        </div>
        <ul class="feed__filters filters">
            <li class="feed__filters-item filters__item">
                <a class="filters__button <?=get_class_active();?>" href="feed.php">
                <span>Все</span>
                </a>
            </li>
            <?php foreach($content_type_array as $type_content):?>
                <li class="feed__filters-item filters__item">
                    <a class="filters__button filters__button--<?=$type_content['class_name'];?> button <?=get_class_active($type_content['class_name']);?>" href="?type_post=<?=$type_content['class_name'];?>">
                        <?php if($type_content['class_name'] === 'photo'):?>
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-photo"></use>
                            </svg>
                        <?php elseif($type_content['class_name'] === 'quote'):?>
                            <span class="visually-hidden">Цитата</span>
                            <svg class="filters__icon" width="21" height="20">
                                <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                        <?php elseif($type_content['class_name'] === 'link'):?>
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="filters__icon" width="21" height="18">
                                <use xlink:href="#icon-filter-link"></use>
                            </svg>
                        <?php elseif($type_content['class_name'] === 'video'):?>
                            <span class="visually-hidden">Видео</span>
                            <svg class="filters__icon" width="24" height="16">
                                <use xlink:href="#icon-filter-video"></use>
                            </svg>
                        <?php elseif($type_content['class_name'] === 'text'):?>
                            <span class="visually-hidden">Текст</span>
                            <svg class="filters__icon" width="20" height="21">
                                <use xlink:href="#icon-filter-text"></use>
                            </svg>
                        <?php endif;?>
                    </a>
                </li>
            <?php endforeach;?>
        </ul>
    </section>
    <aside class="promo">
        <article class="promo__block promo__block--barbershop">
        <h2 class="visually-hidden">Рекламный блок</h2>
        <p class="promo__text">
            Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
        </p>
        <a class="promo__link" href="#">
            Подробнее
        </a>
        </article>
        <article class="promo__block promo__block--technomart">
        <h2 class="visually-hidden">Рекламный блок</h2>
        <p class="promo__text">
            Товары будущего уже сегодня в онлайн-сторе Техномарт!
        </p>
        <a class="promo__link" href="#">
            Перейти в магазин
        </a>
        </article>
        <article class="promo__block">
        <h2 class="visually-hidden">Рекламный блок</h2>
        <p class="promo__text">
            Здесь<br> могла быть<br> ваша реклама
        </p>
        <a class="promo__link" href="#">
            Разместить
        </a>
        </article>
    </aside>
</div
