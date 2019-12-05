<?php
  //session_start();
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/<?=$server_location?>/view/bulma.min.css">
  <link rel="stylesheet" href="/<?=$server_location?>/view/style.css">
</head>

<body>
<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <img src="/<?=$server_location?>/view/img/Logo_profile_lite.png" height="28">
    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">

      <?php if (isset($_SESSION['sess_uid']))
      {
      ?>
      <script src = "/<?=$server_location?>/controller/notify.js"></script>
      <a class="navbar-item" href="/<?=$server_location?>/view/home.php">
        Home
      </a>
      <a class="navbar-item" href="/<?=$server_location?>/view/gallery.php">
        My Gallery
      </a>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          My Editor
        </a>
        <div class="navbar-dropdown">
          <a class="navbar-item" href="/<?=$server_location?>/view/upload.php">
            Upload Photo
          </a>
          <a class="navbar-item" href="/<?=$server_location?>/view/camera.php">
            Take Photo
          </a>
        </div>
      </div>
      
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Settings
        </a>

        <div class="navbar-dropdown">
          <a class="navbar-item" href="/<?=$server_location?>/view/edit_profile.php">
            Edit Profile
          </a>
          <a class="navbar-item" href="/<?=$server_location?>/view/update_password.php">
            Update Password
          </a>
          <hr class="navbar-divider">
          <div class="navbar-item">
            Notifications 
            <div>
            <label class="switch">
              <?php
                $user_id = $_SESSION['sess_uid'];
                if (isset($user_id)) {
                  $sql = $conn->prepare("SELECT * FROM `users` WHERE `uid`=:user_id");
                  $sql->bindParam('user_id', $user_id);
                  $sql->execute();
                  $result = $sql->fetch(PDO::FETCH_ASSOC);
                  if ($result['notified'] == 1){
              ?>
                  <input type="checkbox" id="notify" checked>
              <?php
                  }
                  else {
              ?>
                    <input type="checkbox" id="notify">
              <?php
                  }
                }
              ?>
              <span class="slider round"></span>
            </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons" button onclick="logout()">
          <a class="button is-light" href="/<?=$server_location?>/model/logout.php">
            Log out
          </a>
        </div>
      </div>
    </div>
    <?php
      } else 
    {
    ?>
    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a class="button is-primary" href="/<?=$server_location?>/view/register.php">
            <strong>Sign up</strong>
          </a>
          <a class="button is-light" href="/<?=$server_location?>/view/login.php">
            Log in
          </a>
        </div>
      </div>
    </div>
    <?php
    }
    ?>
  </div>
</nav>
</body>

<script>
document.addEventListener('DOMContentLoaded', () => {

  // Get all "navbar-burger" elements
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Check if there are any navbar burgers
  if ($navbarBurgers.length > 0) {

    // Add a click event on each of them
    $navbarBurgers.forEach( el => {
      el.addEventListener('click', () => {

        // Get the target from the "data-target" attribute
        const target = el.dataset.target;
        const $target = document.getElementById(target);

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        el.classList.toggle('is-active');
        $target.classList.toggle('is-active');

      });
    });
  }

});
</script>