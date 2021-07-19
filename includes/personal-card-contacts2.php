<?php require_once('../../session_setup.php'); if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; } ?> 
<?php include 'includes/head.php'; include 'includes/nav_logged_in.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div>
                    <h3>
                        <span class="glyphicon glyphicon-user"></span>
                        <span id="contact_count"></span>
                        <span>Contacts</span>
                    </h3>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">

            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="row" id="folders">
                    <!-- See handlebars tempalte -->
                </div>
                <div class="row" id="companies">
                    <!-- See handlebars tempalte -->
                </div>
                <div class="row" id="contacts">
                    <!-- See handlebars tempalte -->
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <div class="row" id="tags">
                </div>
                <div class="row temp-bar">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 shift-right">
                        <input type="checkbox" name="selectall" onchange="selectAll(this);" value="" class=" bar-height"/>  Select All
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <div class="dropdown pull-left" id="dropdown-folder">
                            <a class="dropdown-toggle btn btn-default" data-toggle="dropdown" href="#">Folder
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-form" role="menu" id="folder-dropdown">
                            </ul>
                        </div>
                        <button class="btn btn-default" id="btnApply">
                            Apply
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <button class="btn btn-success" id="btnAdd">
                            Create Folder
                        </button>
                    </div>
                    
                </div>
                <div class="row" id="cards">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="left-bc-view">
                        <!-- Just Pic of Box -->
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="right-bc-view">
                        <!-- Details/Mutual Contacts -->
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/x-handlebars" id="h_folders">
        <h5>Folder</h5>
        <ul>
            {{#each folders}}
            <li><input type="checkbox" id="{{folder_name}}" name="{{folder_name}}" onchange="folderChanged(this);" />{{folder_name}}</li>
            {{/each}}
        </ul>
    </script>

    <script type="text/x-handlebars" id="h_companies">
        <h5>Company</h5>
        <ul>
            {{#each companies}}
                <li><input type="checkbox" id="{{company_name}}" name="{{company_name}}" onchange="companyChanged(this);" />{{company_name}}</li>
            {{/each}}
        </ul>
    </script>

    <script type="text/x-handlebars" id="h_contacts">
        <h5>Contacts</h5>
        <ul>
            {{#each contacts}}
            <li><input type="checkbox" id="{{full_name}}" name="{{full_name}}" onchange="contactChanged(this);" />{{full_name}}</li>
            {{/each}}
        </ul>
    </script>

    <script type="text/x-handlebars" id="h_corporate">
        <form role="form">
          <div class="form-group">
            <label></label>
            <input type="text" value="{{full_name}}">
          </div>
          <div class="form-group">
            <label>Company</label>
            <input type="text" value="{{company_name}}">
          </div>
          <div class="form-group">
            <label>Department Name</label>
            <input type="text" value="{{department_name}}">
          </div>
          <div class="form-group">
            <label>Position</label>
            <input type="text" value="{{position}}">
          </div>
        </form>
    </script>

    <script type="text/x-handlebars" id="h_personal">
        <form role="form">
          <div class="form-group">
            <label></label>
            <input type="text" value="{{full_name}}">
          </div>
          <div class="form-group">
            <label>Company</label>
            <input type="text" value="{{phone}}">
          </div>
          <div class="form-group">
            <label>Department Name</label>
            <input type="text" value="{{mobile}}">
          </div>
          <div class="form-group">
            <label>Position</label>
            <input type="text" value="{{email_address}}">
          </div>
        </form>
    </script>

    <script type="text/x-handlebars" id="h_professional">
       <form role="form">
          <div class="form-group">
            <label>Card Name</label>
            <input type="text" value="{{card_name}}">
          </div>
          <div class="form-group">
            <label>Distributed brand</label>
            <input type="text" value="{{distributed_brand}}">
          </div>
          <div class="form-group">
            <label>Category</label>
            <input type="text" value="{{category}}">
          </div>
          <div class="form-group">
            <label>Sub Category</label>
            <input type="text" value="{{sub_category}}">
          </div>
          <div class="form-group">
            <label>Website</label>
            <input type="text" value="{{website_link}}">
          </div>
        </form>
    </script>

    <script type="text/x-handlebars" id="h_product">
        <form role="form">
          <div class="form-group">
            <label>Card Name</label>
            <input type="text" value="{{card_name}}">
          </div>
          <div class="form-group">
            <label>Distributed brand</label>
            <input type="text" value="{{distributed_brand}}">
          </div>
          <div class="form-group">
            <label>Category</label>
            <input type="text" value="{{category}}">
          </div>
          <div class="form-group">
            <label>Sub Category</label>
            <input type="text" value="{{sub_category}}">
          </div>
          <div class="form-group">
            <label>Website</label>
            <input type="text" value="{{website_link}}">
          </div>
        </form>
    </script>

    <script type="text/x-handlebars" id="h_navtabs">

        <div role="tabpanel">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#{{card_detail}}" aria-controls="home" role="tab" data-card-id="{{card_id}}" class="tab_details">Details</a>
            </li>
            <li role="presentation">
                <a href="#{{card_mutual_contacts}}" aria-controls="profile" role="tab" data-card-id="{{card_id}}" class="tab_mutual_contacts">Mutual Contacts</a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="{{card_detail}}">

            </div>
            <div role="tabpanel" class="tab-pane" id="{{card_mutual_contacts}}">

            </div>
          </div>
        </div>

    </script>

    <script type="text/x-handlebars" id="h_mutual_contacts">
        {{#each contacts}}
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <a href="#" class="thumbnail">
                  <img src="{{profile_image}}" alt="{{first_name}} {{last_name}}" class="profile-image">
                </a>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <h4>{{first_name}} {{last_name}}</h4>
            </div>
        </div>
        {{/each}}
    </script>

    <script type="text/x-handlebars" id="h_card_panel">
        <div class="panel panel-primary" id="{{src_id}}">
          <div class="panel-heading">
              <input type="checkbox" class="bc-select-chb" name="name" value="{{card_id}}" />
              {{card_name}}
              <button class="btn btn-default pull-right re-padded"><span class="glyphicon glyphicon-zoom-in"></span></button>
          </div>
          <div class="panel-body">
            <img src="{{card_image_url}}" class="bc-half-page copy-well">
          </div>
        </div>
    </script>

    <!-- Modal -->
    <div class="modal fade" id="modal-add-folder" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Folder</h4>
                </div>
                <div class="modal-body">
                    <input type="text" placeholder="Add a folder" id="modal-add-folder-text" class="col-sm-12" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="modal-add-folder-cancel"><span class="glyphicon glyphicon-remove" style="cursor: pointer"></span></button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="modal-add-folder-ok"><span class="glyphicon glyphicon-ok" style="cursor: pointer"></span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- Load dependency assets -->
    <script src="assets/js/lodash.compat.min.js"></script>
    <script src="assets/js/handlebars-v2.0.0.js" type="text/javascript"></script>

    <!-- Load page specific assets -->
    <link href="p-p-card-contacts.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="p-p-card-contacts.js" type="text/javascript"></script>

    <?php include 'includes/footer.php';?>

</body>
</html>

