

<div id="homeboy" class="container">
    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
        <?php displayPostBox(); ?>
        <?php displayNavlist(); ?>

        <div id="postContainer">
            <h2>Posts for you</h2>
            <?php displayPosts('isFollowing'); ?>
        </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
</div><!--'#homeboy'-->
