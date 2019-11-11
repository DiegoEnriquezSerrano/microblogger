

<div id="homeboy" class="container">
    <div id="homeModule">
    </div><!--'#homeModule'-->
    <div id="postsModule">
        <?php displayPostBox(); ?>
        <?php displayNavlist(); ?>
        
        <div id="postContainer">

        <?php if (isset($_GET['username'])) {?>

            <?php 
            $username = 'username=';
            $actualUsername = $_GET['username'];
            displayPosts('$username.$actualUsername'); ?>

        <?php } else { ?>

            <h2>Public profiles</h2>

            <?php displayUsers(); ?>
            
        <?php } ?>

        </div><!--'#postContainer'-->
    </div><!--'#postsModule'-->
</div><!--'#homeboy'-->