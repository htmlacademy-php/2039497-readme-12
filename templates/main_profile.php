<h1 class="visually-hidden">Профиль</h1>
<?php $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>
<div class="profile profile--default">
<div class="profile__user-wrapper">
    <div class="profile__user user container">
    <div class="profile__user-info user__info">
        <div class="profile__avatar user__avatar">
        <img class="profile__picture user__picture" src="uploads/<?=htmlspecialchars($user_profile['avatar']);?>" alt="Аватар пользователя">
        </div>
        <div class="profile__name-wrapper user__name-wrapper">
        <span class="profile__name user__name"><?=htmlspecialchars($user_profile['login']);?></span>
        <time class="profile__user-time user__time" datetime="<?=date('d.m.Y H:i', strtotime($user_profile['created_at']));?>"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($user_profile['created_at']))), "%d %s на сайте");?></time>
        </div>
    </div>
    <div class="profile__rating user__rating">
        <p class="profile__rating-item user__rating-item user__rating-item--publications">
        <span class="user__rating-amount"><?=$count_post_user;?></span>
        <span class="profile__rating-text user__rating-text">публикаций</span>
        </p>
        <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
        <span class="user__rating-amount"><?=$count_subscriptions_user;?></span>
        <span class="profile__rating-text user__rating-text">подписчиков</span>
        </p>
    </div>
    <form action="<?=$url;?>" method="post">
        <div class="profile__user-buttons user__buttons">
            <?php if(!isset_subscription($link, $registration_user_id, $user_profile['id'])):?>
                <input type="text" name="action" value="sub" hidden>
                <input type="text" name="destination-user" value="<?=$user_profile['id'];?>" hidden>
                <button class="profile__user-button user__button user__button--subscription button button--main" type="submit">Подписаться</button>
            <?php else:?>
                <input type="text" name="action" value="desub" hidden>
                <input type="text" name="destination-user" value="<?=$user_profile['id'];?>" hidden>
                <button class="profile__user-button user__button user__button--subscription button button--quartz" type="submit">Отписаться</button>
            <?php endif;?>
            <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
        </div>
    </form>
    </div>
</div>
<div class="profile__tabs-wrapper tabs">
    <div class="container">
    <div class="profile__tabs filters">
        <b class="profile__tabs-caption filters__caption">Показать:</b>
        <ul class="profile__tabs-list filters__list tabs__list">
        <li class="profile__tabs-item filters__item">
            <a class="profile__tabs-link filters__button tabs__item button <?=get_class_user_profile('post');?>" href="profile.php?id=<?=$user_profile['id'];?>">Посты</a>
        </li>
        <li class="profile__tabs-item filters__item">
            <a class="profile__tabs-link filters__button tabs__item button <?=get_class_user_profile('likes');?>" href="profile.php?id=<?=$user_profile['id'];?>&type=likes">Лайки</a>
        </li>
        <li class="profile__tabs-item filters__item">
            <a class="profile__tabs-link filters__button tabs__item button <?=get_class_user_profile('subscriptions');?>" href="profile.php?id=<?=$user_profile['id'];?>&type=subscriptions">Подписки</a>
        </li>
        </ul>
    </div>
    <div class="profile__tab-content">
        <section class="profile__posts tabs__content <?=get_class_user_profile('post', true);?>">
            <h2 class="visually-hidden">Публикации</h2>
            <?php foreach($posts_array as $post):?>
                <article class="profile__post post post-<?=$post['class_name'];?>">
                <?php if($post['repost'] === 'yes'):?>
                    <header class="post__header">
                    <div class="post__author">
                      <a class="post__author-link" href="#" title="Автор">
                        <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                          <img class="post__author-avatar" src="uploads/<?=$post['user_old_avatar'];?>" alt="Аватар пользователя">
                        </div>
                        <div class="post__info">
                          <b class="post__author-name">Репост: <?=$post['user_old_login'];?></b>
                          <time class="post__time" datetime="<?=date('d.m.Y H:i', strtotime($post['created_at']));?>"> <?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($post['created_at']))));?></time>
                        </div>
                      </a>
                    </div>
                  </header>
                <?php else:?>
                    <header class="post__header">
                        <h2 <?=$post['class_name'] == 'text' ? "style='padding-top: 29px;padding-bottom: 26px;'" : ""?>><a href="post.php?id=<?=$post['id'];?>"><?=htmlspecialchars($post['header']);?></a></h2>
                    </header>
                <?php endif;?>
                <div class="post__main">
                    <?php if($post['class_name'] === 'photo'):?>
                        <div class="post-photo__image-wrapper">
                            <img src="<?=htmlspecialchars($post['content']);?>" alt="Фото от пользователя" width="760" height="396">
                        </div>
                    <?php elseif($post['class_name'] === 'text'):?>
                        <?php if(isset($post['repost'])):?>
                            <h2><a href="#"><?=htmlspecialchars($post['header']);?></a></h2>
                        <?php endif;?>
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
                <footer class="post__footer">
                    <div class="post__indicators">
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
                            <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
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
                                <span><?=$post['count_reposts'];?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>
                        </div>
                        <time class="post__time" datetime="<?=date('d.m.Y H:i', strtotime($post['created_at']));?>"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($post['created_at']))));?></time>
                    </div>
                    <ul class="post__tags">
                        <?php foreach($post['hashtags'] as $hashtag):?>
                            <li><a href="search.php?search=<?=htmlspecialchars(urlencode("#") . $hashtag['hashtag']);?>">#<?=htmlspecialchars($hashtag['hashtag']);?></a></li>
                        <?php endforeach;?>
                    </ul>
                </footer>
                <?php if((int)$post['count_comment'] !== 0):?>
                <div class="comments">
                    <div class="comments__list-wrapper">
                        <ul class="comments__list">
                        <?php $count = 0;?>
                        <?php foreach($post['comments'] as $comment):?>
                            <?php if($count === 2 && (!isset($_GET["pc{$post['id']}"]) || $_GET["pc{$post['id']}"] !== "all")):?>
                            <?php break;?>
                            <?php endif;?>
                            <?php $count += 1;?>
                            <li class="comments__item user">
                            <div class="comments__avatar">
                            <a class="user__avatar-link" href="#">
                                <img class="comments__picture" src="uploads/<?=htmlspecialchars($comment['avatar']);?>" alt="Аватар пользователя">
                            </a>
                            </div>
                            <div class="comments__info">
                            <div class="comments__name-wrapper">
                                <a class="comments__user-name" href="#">
                                <span><?=htmlspecialchars($comment['login']);?></span>
                                </a>
                                <time class="comments__time" datetime="<?=date('d.m.Y H:i', strtotime($comment['created_at']));?>"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($comment['created_at']))));?></time>
                            </div>
                            <p class="comments__text">
                                <?=htmlspecialchars($comment['comment']);?>
                            </p>
                            </div>
                        </li>
                        <?php endforeach;?>
                        </ul>
                        <?php if(($post['count_comment'] > 2) && (!isset($_GET["pc{$post['id']}"]) || $_GET["pc{$post['id']}"] !== "all")):?>
                            <?php
                                $get = '';
                                if (!isset($_GET["pc{$post['id']}"])) {
                                    if (parse_url($url, PHP_URL_QUERY)) {
                                        $get = "&pc{$post['id']}=all";
                                    } else {
                                        $get = "?pc{$post['id']}=all";
                                    }
                                }
                            ?>
                            <a class="comments__more-link" href="<?=$url . $get;?>">
                            <span>Показать все комментарии</span>
                            <sup class="comments__amount"><?=$post['count_comment'] - 2;?></sup>
                            </a>
                        <?php endif;?>
                    </div>
                </div>
                <?php endif;?>
                <form class="comments__form form" action="#" method="post">
                    <div class="comments__my-avatar">
                        <img class="comments__picture" src="uploads/<?=$user['avatar'];?>" alt="Аватар пользователя">
                    </div>
                    <div class="form__input-section <?=array_key_exists($post['id'], $errors) ? 'form__input-section--error' : '';?>">
                        <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий" name="comment"></textarea>
                        <input type="text" value="<?=$post['id'];?>" name="post_id" hidden>
                        <label class="visually-hidden">Ваш комментарий</label>
                        <button class="form__error-button button" type="button">!</button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибка валидации</h3>
                            <p class="form__error-desc"><?=array_key_exists($post['id'], $errors) ? $errors[$post['id']]['comment'] : '';?></p>
                        </div>
                    </div>
                    <button class="comments__submit button button--green" type="submit">Отправить</button>
                </form>
                </article>
            <?php endforeach;?>
        </section>

        <section class="profile__likes tabs__content <?=get_class_user_profile('likes', true);?>">
        <h2 class="visually-hidden">Лайки</h2>
        <ul class="profile__likes-list">
            <?php foreach($likes_array as $like):?>
                <li class="post-mini post-mini--<?=$like['class_name'];?> post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link" href="profile.php?id=<?=$like['user_id'];?>">
                            <img class="post-mini__picture user__picture" src="uploads/<?=htmlspecialchars($like['avatar']);?>" alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name" href="profile.php?id=<?=$like['user_id'];?>">
                            <span><?=htmlspecialchars($like['login']);?></span>
                        </a>
                        <div class="post-mini__action">
                            <span class="post-mini__activity user__additional">Лайкнул публикацию</span>
                            <time class="post-mini__time user__additional" datetime="<?=date('d.m.Y H:i', strtotime($like['created_at']));?>"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($like['created_at']))));?></time>
                        </div>
                    </div>
                </div>
            <?php if($like['class_name'] === 'photo'):?>
                <div class="post-mini__preview">
                    <a class="post-mini__link" href="post.php?id=<?=$like['id'];?>" title="Перейти на публикацию">
                        <div class="post-mini__image-wrapper">
                            <img class="post-mini__image" src="<?=htmlspecialchars($like['content']);?>" width="109" height="109" alt="Превью публикации">
                        </div>
                        <span class="visually-hidden">Фото</span>
                    </a>
                </div>
            <?php elseif($like['class_name'] === 'text'):?>
                <div class="post-mini__preview">
                <a class="post-mini__link" href="post.php?id=<?=$like['id'];?>" title="Перейти на публикацию">
                <span class="visually-hidden">Текст</span>
                <svg class="post-mini__preview-icon" width="20" height="21">
                    <use xlink:href="#icon-filter-text"></use>
                </svg>
                </a>
            </div>
            <?php elseif($like['class_name'] === 'video'):?>
                <div class="post-mini__preview">
                <a class="post-mini__link" href="post.php?id=<?=$like['id'];?>" title="Перейти на публикацию">
                <div class="post-mini__image-wrapper">
                    <?=embed_youtube_cover_profile(htmlspecialchars($like['content']));?>
                    <span class="post-mini__play-big">
                    <svg class="post-mini__play-big-icon" width="12" height="13">
                        <use xlink:href="#icon-video-play-big"></use>
                    </svg>
                    </span>
                </div>
                <span class="visually-hidden">Видео</span>
                </a>
            </div>
            <?php elseif($like['class_name'] === 'quote'):?>
                <div class="post-mini__preview">
                    <a class="post-mini__link" href="post.php?id=<?=$like['id'];?>" title="Перейти на публикацию">
                    <span class="visually-hidden">Цитата</span>
                    <svg class="post-mini__preview-icon" width="21" height="20">
                        <use xlink:href="#icon-filter-quote"></use>
                    </svg>
                    </a>
                </div>
            <?php elseif($like['class_name'] === 'link'):?>
                <div class="post-mini__preview">
                    <a class="post-mini__link" href="post.php?id=<?=$like['id'];?>" title="Перейти на публикацию">
                    <span class="visually-hidden">Ссылка</span>
                    <svg class="post-mini__preview-icon" width="21" height="18">
                        <use xlink:href="#icon-filter-link"></use>
                    </svg>
                    </a>
                </div>
            <?php endif;?>
                </li>
            <?php endforeach;?>
        </section>

        <section class="profile__subscriptions tabs__content <?=get_class_user_profile('subscriptions', true);?>">
        <h2 class="visually-hidden">Подписки</h2>
        <ul class="profile__subscriptions-list">
            <?php foreach($subscribers_array as $subscriber):?>
                <li class="post-mini post-mini--photo post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                    <a class="user__avatar-link" href="profile.php?id=<?=$subscriber['id'];?>">
                        <img class="post-mini__picture user__picture" src="uploads/<?=htmlspecialchars($subscriber['avatar']);?>" alt="Аватар пользователя">
                    </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                    <a class="post-mini__name user__name" href="profile.php?id=<?=$subscriber['id'];?>">
                        <span><?=htmlspecialchars($subscriber['login']);?></span>
                    </a>
                    <time class="post-mini__time user__additional" datetime="<?=date('d.m.Y H:i', strtotime($subscriber['created_at']));?>"><?=get_diff_date(date_create(date('Y-m-d H:i:s', strtotime($subscriber['created_at']))), "%d %s на сайте");?></time>
                    </div>
                </div>
                <div class="post-mini__rating user__rating">
                    <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                    <span class="post-mini__rating-amount user__rating-amount"><?=$subscriber['count_posts'];?></span>
                    <span class="post-mini__rating-text user__rating-text">публикаций</span>
                    </p>
                    <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                    <span class="post-mini__rating-amount user__rating-amount"><?=$subscriber['count_subscribers'];?></span>
                    <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                    </p>
                </div>
                <div class="post-mini__user-buttons user__buttons">
                    <form action="<?=$url;?>" method="post">
                    <?php if(!isset_subscription($link, $registration_user_id, $subscriber['id'])):?>
                        <input type="text" name="action" value="sub" hidden>
                        <input type="text" name="destination-user" value="<?=$subscriber['id'];?>" hidden>
                        <button class="post-mini__user-button user__button user__button--subscription button button--main" type="submit">Подписаться</button>
                    <?php else:?>
                        <input type="text" name="action" value="desub" hidden>
                        <input type="text" name="destination-user" value="<?=$subscriber['id'];?>" hidden>
                        <button class="post-mini__user-button user__button user__button--subscription button button--quartz" type="submit">Отписаться</button>
                    <?php endif;?>
                    </form>
                </div>
                </li>
            <?php endforeach;?>
        </ul>
        </section>
    </div>
    </div>
</div>
</div>
