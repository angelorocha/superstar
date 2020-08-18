<form method="get" action="<?= home_url(); ?>">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="<?php _e( 'Search in site...', 'wpss' ); ?>" aria-label="<?php _e( 'Search in site...', 'wpss' ); ?>" aria-describedby="site-search" name="s">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="site-search"><?php _e( 'Go', 'wpss' ); ?></button>
        </div>
    </div>
</form>