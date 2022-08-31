<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
ALTER TABLE `user_settings`
  ADD COLUMN `home_replies` tinyint(1) NOT NULL DEFAULT '0',
  ADD COLUMN `home_retweets` tinyint(1) NOT NULL DEFAULT '0';
SQL;

    $db->query($sql);
};

// Down
$down = function($db) {
    $sql = <<<'SQL'
ALTER TABLE `user_settings`
  DROP COLUMN `home_replies`,
  DROP COLUMN `home_retweets`;
SQL;

    $db->query($sql);
};
