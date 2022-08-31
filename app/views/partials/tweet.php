                    <article data-id="<?php esc($item->id); ?>">
                        <a href="/<?php esc($item->author->username); ?>"><img src="<?php esc($item->author->profile_image_url); ?>" alt="Profile Image" /></a>
                        <ul class="meta">
                            <?php if($item->retweeted_tweet): ?>
                            <li class="retweeted_by"><a href="/<?php esc($item->retweeter->username); ?>"><span><?php esc($item->retweeter->name); ?></span> Retweeted</a></li>
                            <?php endif; ?>
                            <li class="author_name"><a href="/<?php esc($item->author->username); ?>"><?php esc($item->author->name); ?></a></li>
                            <li class="author_username"><a href="/<?php esc($item->author->username); ?>">@<?php esc($item->author->username); ?></a></li>
                            <li class="post_created_at"><a href="/<?php esc($item->author->username . '/status/' . $item->id); ?>"><?php esc(elapsed($item->created)); ?></a></li>
                        </ul>
                        <div class="post">
                            <?php //nl2br(esc($item->text)); ?>
                            <?php echo nl2br($item->text); ?>

                            <div class="media">
                            <?php foreach($item->media as $media): ?>
                                <?php if(isset($media->preview_image_url)): ?>
                                <img src="<?php esc($media->preview_image_url); ?>" alt="Attachment" />
                                <?php elseif(isset($media->url)): ?>
                                <img src="<?php esc($media->url); ?>" alt="Attachment" />
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </div>

                            <?php if($item->quoted_tweet): ?>
                                <div class="quoted">
                                    <a href="/<?php esc($item->quoted_author->username); ?>"><img src="<?php esc($item->quoted_author->profile_image_url); ?>" alt="Profile Image" /></a>
                                    <ul class="meta">
                                        <li class="author_name"><a href="/<?php esc($item->quoted_author->username); ?>"><?php esc($item->quoted_author->name); ?></a></li>
                                        <li class="author_username"><a href="/<?php esc($item->quoted_author->username); ?>">@<?php esc($item->quoted_author->username); ?></a></li>
                                        <li class="post_created_at"><a href="/<?php esc($item->quoted_author->username . '/status/' . $item->quoted_id); ?>"><?php esc(elapsed($item->quoted_created)); ?></a></li>
                                    </ul>
                                    <div class="post">
                                        <?php echo nl2br($item->quoted_text); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
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
                            <li>
                                <form action="/ajax/like/add" class="_like" method="POST">
                                    <fieldset>
                                        <?php $res->html->hidden('tweet_id', $item->id); ?>
                                        <input type="submit" value="Like" />
                                    </fieldset>
                                </form>
                            </li>
                            <li><button class="_todo">Todo</button></li>
                            <li><button class="_bookmark">Bookmark</button></li>
                            <li><button class="_interact">Interact</button></li>
                        </ul>

                        <div class="todo _part">
                            <?php if($item->show_todo): ?>
                            <div class="view"><?php echo nl2br(_esc($item->todo)); ?><button class="_close" aria-label="Close">&times;</button></div>
                            <?php endif; ?>
                            <form action="/ajax/todo/add" class="edit" method="POST">
                                <fieldset>
                                    <?php $res->html->hidden('tweet_id', $item->id); ?>
                                    <?php $res->html->hidden('tweet_name', $item->author->name); ?>
                                    <?php $res->html->hidden('tweet_username', $item->author->username); ?>
                                    <?php $res->html->hidden('tweet_content', $item->text); ?>
                                    <textarea name="note" placeholder="Enter your todo notes (optional)..."><?php esc($item->todo); ?></textarea>
                                    <input type="submit" value="Save" />
                                    <button class="_cancel">Cancel</button>
                                </fieldset>
                            </form>
                        </div>

                        <div class="bookmark _part">
                            <?php if($item->show_bookmark): ?>
                            <div class="view"><?php echo nl2br(_esc($item->bookmark)); ?></div>
                            <?php endif; ?>
                            <form action="/ajax/bookmark/add" class="edit" method="POST">
                                <fieldset>
                                    <?php $res->html->hidden('tweet_id', $item->id); ?>
                                    <?php $res->html->hidden('tweet_name', $item->author->name); ?>
                                    <?php $res->html->hidden('tweet_username', $item->author->username); ?>
                                    <?php $res->html->hidden('tweet_content', $item->text); ?>
                                    <textarea name="note" placeholder="Enter your bookmark notes (optional)..."><?php esc($item->bookmark); ?></textarea>
                                    <input type="submit" value="Save" />
                                    <button class="_cancel">Cancel</button>
                                </fieldset>
                            </form>
                        </div>

                        <div class="interact _part">
                            <form action="/ajax/interact/add" class="edit" method="POST">
                                <fieldset>
                                    <?php $res->html->hidden('tweet_id', $item->id); ?>
                                    <textarea name="note" placeholder="Enter your reply..."></textarea>
                                    <input type="submit" value="Reply" />
                                    <button class="_cancel">Cancel</button>
                                </fieldset>
                            </form>
                        </div>

                    </article>
                    <div class="pagination">
                    <?php if(isset($item->previous_token)): ?>
                    <a href="<?php esc($req->path . '?' . $item->previous_token); ?>" class="button prev">&lt; Prev Page</a>
                    <?php endif; ?>
                    <?php if(isset($item->next_token)): ?>
                    <a href="<?php esc($req->path . '?' . $item->next_token); ?>" class="button next">Next Page &gt;</a>
                    <?php endif; ?>
                    </div>
