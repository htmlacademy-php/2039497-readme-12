<div class="container">
    <?php $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>
    <h1 class="page__title page__title--publication"><?=htmlspecialchars($post['header']);?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-<?=$post['class_name'];?>">
      <div class="post-details__main-block post post--details">
        <?php if ($post['class_name'] === 'quote'):?>
        <!-- пост-цитата -->
        <div class="post-details__image-wrapper post-quote">
        <div class="post__main">
            <blockquote>
            <p>
                <?=htmlspecialchars($post['content']);?>
            </p>
            <cite><?=htmlspecialchars($post['author']);?></cite>
            </blockquote>
        </div>
        </div>
        <?php elseif($post['class_name'] === 'text'):?>
        <!-- пост-текст -->
        <div class="post-details__image-wrapper post-text">
        <div class="post__main">
            <p>
                <?=htmlspecialchars($post['content']);?>
            </p>
        </div>
        </div>
        <?php elseif($post['class_name'] === 'link'):?>
        <!-- пост-ссылка -->
        <div class="post__main">
        <div class="post-link__wrapper">
            <a class="post-link__external" href="<?=htmlspecialchars($post['content']);?>" title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                </div>
                <div class="post-link__info">
                <h3><?=htmlspecialchars($post['header']);?></h3>
                </div>
            </div>
            </a>
        </div>
        </div>
        <?php elseif($post['class_name'] === 'photo'):?>
        <!-- пост-изображение -->
        <div class="post-details__image-wrapper post-photo__image-wrapper">
            <img src="<?=htmlspecialchars($post['content']);?>" alt="Фото от пользователя" width="760" height="507">
        </div>
        <?php elseif($post['class_name'] === 'video'):?>
        <!-- пост-видео -->
        <div class="post-details__image-wrapper post-photo__image-wrapper">
        <?=embed_youtube_video(htmlspecialchars($post['content']));?>
        </div>
        <?php endif;?>

        <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="?id=<?=$post['id'];?>&like_post=<?=$post['id'];?>" title="Лайк">
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
              <a class="post__indicator post__indicator--repost button" href="?id=<?=$post['id'];?>&repost=<?=$post['id'];?>" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span><?=$post['count_reposts'];?></span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <span class="post__view"><?=$post['count_views'];?></span>
        </div>
        <ul class="post__tags">
            <?php foreach($post['hashtags'] as $hashtag):?>
                <li><a href="search.php?search=<?=htmlspecialchars(urlencode("#") . $hashtag['hashtag']);?>">#<?=$hashtag['hashtag'];?></a></li>
            <?php endforeach;?>
        </ul>
            <div class="comments">
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
                <?php if((int)$post['count_comment'] !== 0):?>
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
                <?php endif;?>
            </div>


        </div>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post-details__avatar user__avatar">
              <a class="post-details__avatar-link user__avatar-link" href="#">
                <img class="post-details__picture user__picture" src="uploads/<?=htmlspecialchars($post['avatar']);?>" alt="Аватар пользователя">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="#">
                <span><?=htmlspecialchars($post['name_user']);?></span>
              </a>
              <time class="post-details__time user__time" datetime="2014-03-20"><?=get_diff_date(date_create($created_at_user), "%d %s на сайте");?></time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?=$count_subscriptions_user;?></span>
              <span class="post-details__rating-text user__rating-text">подписчиков</span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?=$count_post_user;?></span>
              <span class="post-details__rating-text user__rating-text">публикаций</span>
            </p>
          </div>
            <form action="<?=$url;?>" method="post">
                <div class="post-details__user-buttons user__buttons">
            <?php if(!isset_subscription($link, $registration_user_id, $post['user_id'])):?>
                <input type="text" name="action" value="sub" hidden>
                <input type="text" name="destination-user" value="<?=$post['user_id'];?>" hidden>
                <button class="user__button user__button user__button--subscription button button--main" type="submit">Подписаться</button>
            <?php else:?>
                <input type="text" name="action" value="desub" hidden>
                <input type="text" name="destination-user" value="<?=$post['user_id'];?>" hidden>
                <button class="user__button user__button user__button--subscription button button--quartz" type="submit">Отписаться</button>
            <?php endif;?>
                <a class="user__button user__button--writing button button--green" href="#">Сообщение</a>
                </div>
            </form>
           </div>
        </div>
      </div>
    </section>
</div>
