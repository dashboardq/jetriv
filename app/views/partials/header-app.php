        <header class="app">
            <div class="box">
                <h2>
                    <span class="logo">
                        <span class="one">~</span>
                        <span class="two">~</span>
                    </span>
                    <a href="/"><?php esc(ao()->env('APP_NAME')); ?></a>
                </h2>
                <span><?php esc($req->user->data['name']); ?></span>
            </div>
        </header>
