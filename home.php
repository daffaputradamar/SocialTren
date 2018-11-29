<?php 
    include 'helper/connection.php'; 
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: index.php');
    }
?>

<!DOCTYPE html>
<html>
    <?php include 'layouts/header.php'; ?>

    <body>
        <?php include 'layouts/navbar.php'; ?>
        <div style="margin-top:12px"></div>
        <div class="container">
            <div class="row">
                <div class="col s3">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <?php 
                                    $query = "SELECT * from users WHERE kd_user = $user_id";
                                    $result = mysqli_query($con, $query);
                                    $row = mysqli_fetch_assoc($result);
                                ?>
                                <div class="col s3">
                                    <img src="assets/photo_profil/<?=$row['photo_profil']?>" class="circle" alt="photo profile" width="70">
                                </div>
                                <div class="col s9">
                                    <div style="margin-top: 20px; margin-left: 20px;">
                                        <div>
                                            <h6><?=$row['first_name']?> <?=$row['last_name']?></h6>
                                            <p><?=$row['username']?></p>
                                        </div>           
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="row">
                                <div class="col s6">
                                    <blockquote>
                                        <h6>Posts</h6>
                                        <?php 
                                            $query = "SELECT COUNT(kd_post) as post_sum FROM posts
                                                WHERE kd_user = 1";
                                            $result = mysqli_query($con, $query);
                                            $row = mysqli_fetch_array($result);
                                            if ($row) {
                                                echo "$row[0]";
                                            } else {
                                                echo '0';
                                            }
                                        ?>
                                    </blockquote>
                                </div>
                                <div class="col s6">
                                    <blockquote>
                                        <h6>Followers</h6>
                                        1
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s6">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                            <?php if (isset($_GET['error'])) { ?>
                                <div class="card">
                                    <div class="card-content">
                                        <p class="red-text"><?= $_GET['error'] ?></p>
                                    </div>
                                </div>
                            <?php } ?>
                                <form action="actions/add_post.php" method="post" enctype="multipart/form-data">
                                    <div class="file-field input-field">
                                    <div class="btn orange lighten-1">
                                        <span>File</span>
                                        <input type="file" name="post-photo">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Upload one file (Optional)">
                                    </div>
                                    </div>
                                    <div class="input-field col s12">
                                    <textarea id="tweet_textarea" name="body" class="materialize-textarea"></textarea>
                                    <label for="tweet_textarea">What's new today</label>
                                    <button class="submit-button right" name="submit-post" style="border-radius: 5px; margin-top:13px;">Post</button>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div>
                                <?php 
                                    $query = "SELECT * FROM posts p 
                                        INNER JOIN users u ON p.kd_user = u.kd_user
                                        ORDER BY created_at DESC";
                                    $result = mysqli_query($con, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <div class="row">
                                        <div class="col s2">
                                            <img src="assets/photo_profil/<?=$row['photo_profil']?>" class="circle" alt="photo profile" width="60">
                                        </div>
                                        <div class="col s10">
                                            <div style="display:flex; align-items: center; justify-content: space-between">
                                            <a class="black-text" href="profile.php?kd_user=<?=$row['kd_user']?>">
                                                <div>
                                                    <h6><?=$row['first_name']?> <?=$row['last_name']?></h6>
                                                    <p><?=$row['username']?></p>
                                                </div>
                                            </a>
                                                <div style="display:flex; align-items: center;">
                                                    <form action="actions/update_post.php" method="post" style="margin-right: 10px">
                                                        <input type="hidden" name="kd_post" value="<?=$row['kd_post']?>">
                                                        <input type="hidden" name="kd_user" value="<?=$row['kd_user']?>">
                                                        <button type="submit" class="submit-button" style="padding: 2px; margin:0;border-radius: 10px">
                                                            <i class="material-icons">edit</i> 
                                                        </button>
                                                    </form>
                                                    <form action="actions/report_post.php" method="post">
                                                        <input type="hidden" name="kd_post" value="<?=$row['kd_post']?>">
                                                        <button type="submit" class="report-button" style="padding: 5px 4px 2px 4px; margin:0;border-radius: 10px">
                                                            <i class="tiny material-icons">report</i> 
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="divider" style="margin-bottom:10px"></div>
                                            <?php if ($row['photo'] != NULL) { ?>
                                                <img src="assets/posts/<?=$row['photo']?>" alt="" class="responsive-img materialboxed">
                                            <?php } ?>
                                            <br>
                                            <p>
                                                <?= $row['body'] ?>
                                            </p>
                                            <div class="right-align">
                                                <a class="grey-text" href="post.php?kd_post=<?= $row['kd_post'] ?>"><small>Read More</small></a>
                                            </div>
                                            <div class="row">
                                                <div class="col s3">
                                                    <?php 
                                                        $user_id = $_SESSION['user'];
                                                        $post = $row['kd_post'];
                                                        $query = "SELECT * FROM likes WHERE kd_post = $post AND kd_user = $user_id LIMIT 1";
                                                        $res = mysqli_query($con, $query);
                                                        if (mysqli_fetch_assoc($res)) {
                                                    ?>
                                                        <form action="actions/add_like.php" method="post">
                                                            <input type="hidden" name="kd_post" value=<?=$post?>>
                                                            <button class="submit-button love-button love-button-active" type="submit" name="submit-like" id="fav-btn" style="border-radius: 5px; margin-top:13px; padding: 4px 8px;"><i class="material-icons">favorite</i><span style="padding-left: 5px">Like</span>
                                                            </button>
                                                        </form>
                                                    <?php } else { ?>
                                                        <form action="actions/add_like.php" method="post">
                                                            <input type="hidden" name="kd_post" value=<?=$post?>>
                                                            <button class="submit-button love-button" type="submit" name="submit-like" id="fav-btn" style="border-radius: 5px; margin-top:13px; padding: 4px 8px;"><i class="material-icons">favorite</i><span style="padding-left: 5px">Like</span>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="col s3">
                                                    <a href="post.php?kd_post=<?= $row['kd_post'] ?>"><button class="submit-button" style="border-radius: 5px; margin-top:13px; padding: 4px 8px;" > <i class="material-icons">comment</i><span style="padding-left: 5px">Comment</span> </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s3">
                    <div class="card">
                        <div class="card-content">
                            <div>
                                <h6 style="margin-bottom:30px">Discover new people</h6>
                                <?php
                                    $user_id = $_SESSION['user'];
                                    $query = "SELECT * FROM users WHERE kd_user != $user_id ORDER BY RAND() LIMIT 3";
                                    $result = mysqli_query($con, $query);
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <div class="row" style="margin-top: 15px">
                                        <div class="col s3" style="margin-top: 15px">
                                            <img src="assets/photo_profil/<?= $row['photo_profil'] ?>" class="circle" alt="photo profile" width="35">
                                        </div>
                                        <div class="col s6">
                                            <p><?=$row['username']?></p>
                                            <button class="submit-button" style="border-radius: 5px; margin-top:13px; padding: 4px 8px;" href="#">Follow</button>
                                        </div>
                                    </div>
                                    <div class="divider"></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <noscript>
            <input type="submit" value="Submit form!" />
        </noscript>
        <?php mysqli_close($con) ?>
        <?php include 'layouts/scripts.php'; ?>
    </body>
</html>