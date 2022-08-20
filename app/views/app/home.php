<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="timeline <?php $res->pathClass(); ?>">
        <div class="base">
            <?php $res->partial('header-app'); ?>
            <?php $res->partial('sidebar'); ?>
            <main>
                <div class="box">
                    <form class="search">
                        <input type="text" name="q" value="" placeholder="Search" />
                        <input type="submit" value="Search" />
                    </form>

                    <?php foreach($list as $item): ?>
                    <article data-id="<?php esc($item->id); ?>">
                        <a href="/<?php esc($item->author->username); ?>"><img src="<?php esc($item->author->profile_image_url); ?>" alt="Profile Image" /></a>
                        <ul class="meta">
                            <li class="author_name"><a href="/<?php esc($item->author->username); ?>"><?php esc($item->author->name); ?></a></li>
                            <li class="author_username"><a href="/<?php esc($item->author->username); ?>">@<?php esc($item->author->username); ?></a></li>
                            <li class="post_created_at"><a href="/<?php esc($item->author->username . '/status/' . $item->id); ?>"><?php esc(elapsed($item->created_at)); ?></a></li>
                        </ul>
                        <div class="post">
                            <?php //nl2br(esc($item->text)); ?>
                            <?php echo nl2br($item->text); ?>
                        </div>
                        <div class="media">
                        <?php foreach($item->media as $media): ?>
                            <img src="<?php esc($media->url); ?>" alt="Attachment" />
                        <?php endforeach; ?>
                        </div>
                        <dl class="engagement">
                            <div>
                                <dt>Replies</dt>
                                <dd><?php esc($item->public_metrics->reply_count); ?></dd>
                            </div>
                            <div>
                                <dt>Retweets</dt>
                                <dd><?php esc($item->public_metrics->retweet_count); ?></dd>
                            </div>
                            <div>
                                <dt>Quotes</dt>
                                <dd><?php esc($item->public_metrics->quote_count); ?></dd>
                            </div>
                            <div>
                                <dt>Likes</dt>
                                <dd><?php esc($item->public_metrics->like_count); ?></dd>
                            </div>
                        </dl>
                        <ul class="tools">
                            <li><button class="like">Like</button></li>
                            <li><button class="todo">Todo</button></li>
                            <li><button class="bookmark">Bookmark</button></li>
                            <li><button class="interact">Interact</button></li>
                        </ul>
                    </article>
                    <?php endforeach; ?>
                    <?php if(count($list)): ?>
                    <div class="pagination">
                        <a href="/tour1" class="button">Next Page &gt;</a>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
            <?php if(!$show_connect): ?>
            <?php $res->partial('cycles'); ?>
            <?php endif; ?>
            <?php $res->partial('footer'); ?>
            <?php if($show_connect): ?>
            <div class="overlay show">
                <div>
                    <?php $res->html->messages(); ?>
                    <p>Please connect your Twitter account by pressing the button below:</p>
                    <p><a href="/twitter/start" class="button">Connect To Twitter</a></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </body>
</html>

