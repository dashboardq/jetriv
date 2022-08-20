<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body>
        <?php $res->partial('header'); ?>
        <main>
            <section class="box">
                <h2>Account</h2>

                <?php if(ao()->env('APP_LOGIN_TYPE') == 'db'): ?>
                    <?php $res->html->messages(); ?>
                    <form method="POST">
                        <?php $res->html->text('Full Name', 'name'); ?>

                        <?php $res->html->text('Email'); ?>

                        <?php $res->html->submit('Update'); ?>
                    </form>
                <?php else: ?>
                    <form method="POST">
                        <?php $res->html->text('Email', '', '', '', 'disabled'); ?>
                    </form>
                <?php endif; ?>

            </section>
        </main>
		<?php $res->partial('footer'); ?>
    </body>
</html>

