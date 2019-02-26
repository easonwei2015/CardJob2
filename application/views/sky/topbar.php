<!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $index_base_url;?>"> <img alt="Tzuchi Logo" src="<?php echo $base_url;?>assets/img/logo20.png" class="hidden-xs"/>
                <span>Tzuchi</span></a>

            <!-- user dropdown starts -->
            <div class="btn-group pull-right">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i><span class="hidden-sm hidden-xs">&emsp; <?php echo $chName;?></span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <!--<li><a href="#">Profile</a></li>-->
                    <li class="divider"></li>
                    <li><a href="<?php echo $index_base_url;?>logout">登出</a></li>
                </ul>
            </div>
            <!-- user dropdown ends -->
        </div>
    </div>