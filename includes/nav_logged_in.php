<body class="profiles">
  <section id="navigation" class="">
      <div id="logo-cut" class="col-lg-1 col-md-1 col-sm-1 col-xs-2"><img src="assets/images/logo-cut.png" width="100%" height="auto" title="Cardition" /></div>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <form id="add-bcn" class="navbar-form navbar-left" role="search" style="margin-top:12px;">
            <div class="form-group">
              <input id="card-by-id-input" type="text" class="form-control" placeholder="Add Card With BCN">
                        </div>
            <button id="card-by-id-submit" type="submit" class="btn btn-primary">Go</button>
          </form>
          <button type="button" class="navbar-toggle collapsed pull-right" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!--<button type="button" id="contacts-filter-menu" class="navbar-toggle collapsed pull-right" style="padding:6px 14px !important;">
            <span class="sr-only">Toggle filters</span>
            <span class="glyphicon glyphicon-filter" style="cursor: pointer"></span>
          </button>-->
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li>
              <a href="home.php">Home</a>
            </li>
            <li>
              <a href="search-page.php">Card Search</a>
            </li>
            <!--<li><a href="view-all-cards.php">(Temp) View Global Card</a></li>-->
            <li class="dropdown">
              <a id="sub-menus-parent" href="search-page.php" class="dropdown-toggle" data-toggle="dropdown">
                View / Manage Folders<span class="caret"></span>
              </a>
              <ul class="dropdown-menu" id="dd_subfolders" role="menu">
                <li>
                  <a href="card-contacts.php?category=personal">Personal / Corporate Cards</a>
                </li>
                <li>
                  <a href="card-contacts.php?category=professional">Service / Product Cards</a>
                </li>
                <li class="divider"></li>
                <li class="dropdown-submenu">
                  <a id="sub-folders-parent" class="subfolders-menu" tabindex="-1" href="#">Subfolders</a>
                  <ul class="dropdown-menu pull-right" id="dd_folders">
                  </ul>
                  <a href="manage-folders.php">Create / Delete Subfolders</a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="holders-of-your-card.php">Holders of your Cards</a>
                </li>
                <li>
                  <a href="card-link-requests.php">Card Requests</a>
                </li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a id="account-parent" href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span id="test">
                </span>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu" id="right-dropdown-menu">
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </section>
