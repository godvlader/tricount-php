<div class="navbar">
    <div class="navbar-container">
      <div class="logo-container">
        <h1 class="logo">
          <i class="fa fa-credit-card" aria-hidden="true" onclick='window.location.reload(true);'></i> Tricount
        </h1>
      </div>
      <div class="menu-container">
        <ul class="menu-list" id="menu-list">
          <li class="menu-list-item"><a href="user/profile">Home</a></li>
          <li class="menu-list-item"><a class="logout-icon" href="user/logout"><i class="fa-solid fa-right-from-bracket"></i></a></li>
          <?php if (isset($backValue)) : ?>
            <a class="backBtn" href="<?= $backValue?>">BACK</a>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  