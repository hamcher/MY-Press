<!DOCTYPE html>
<html>
    <head>
        <title>Visual Inspector Plugins</title>
        <?php
        //CSS Files
        wp_enqueue_style('Bootstrap-CSS', plugins_url('../assets/css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('Visual-Inspector-Plugin-Projects', plugins_url('../assets/css/viPlugin.css', __FILE__));

        //JS Files
        wp_enqueue_script('Bootstrap-JS', plugins_url('../assets/js/bootstrap.min.js', __FILE__), array('jquery'), TRUE);
        wp_enqueue_script('Visual-Inspector-Plugin-Login-JS', plugins_url('../assets/js/viPluginLogin.js', __FILE__), array('jquery'), TRUE);
        wp_enqueue_script('Visual-Inspector-Plugin-Projects-JS', plugins_url('../assets/js/viPluginProjects.js', __FILE__), array('jquery'), TRUE);
        wp_enqueue_script('Visual-Inspector-Plugin-Timeago-JS', plugins_url('../assets/js/jquery.timeago.js', __FILE__), array('jquery'), TRUE);

        $adminEmail = sanitize_email(get_option('admin_email'));
        ?>

        <script type="text/javascript">
            var viImageUrl = '<?= esc_url(plugins_url('../', __FILE__)); ?>';
            var viPluginUrl = '<?= esc_url(plugins_url('/', __FILE__)); ?>';
        </script>
    </head>
    <body onload="viLoadFunction()">
        <div id="viStudioIcon">
            <div>
                <div>
                    <div id="loader"></div>
                </div>
            </div>    
        </div>    

        <div id="viContainer" class="animate-bottom container">
            <div id="viLoginHeader" class="row vi-header">
                <div class="col-md-3 col-sm-4 hidden-xs vi-logo">
                    <img src= "<?php echo esc_url(plugins_url('../assets/images/vi-logo.svg', __FILE__)); ?>" alt="canvasflip visual inspector logo">
                    <span style="font-size:14px; font-weight: 700;">Visual</span> Inspector
                </div>
            </div>

            <div id="viProjectsHeader" class="row vi-header">
                <div class="col-md-3 col-sm-4 hidden-xs vi-logo">
                    <img src="<?php echo esc_url(plugins_url('../assets/images/vi-logo.svg', __FILE__)); ?>" alt="canvasflip visual inspector logo">
                    <span style="font-size:14px; font-weight: 700;">Visual</span> Inspector
                </div>

                <div class="col-md-9 col-sm-6 hidden-xs login-popup-btn">
                    <a id="viPluginLogout" class="viProjectLogout">Logout</a>
                </div> 
            </div>

            <div id="viLogin" class="container page">
                <h1> Get Started to VI Sync </h1>
                <div class="cf-vi-login-form">
                    <div id="viLoginForm" class="vi-form">
                        <div class="vi-plugin-email">
                            <input id="viEmail" type="email" placeholder="Enter Your Email Address">
                            <p id="vi-email-error-message" class="help-text text-danger"> <span class="text-red">* </span>Email is required.</p>
                        </div>
                        <div class="vi-plugin-email">
                            <input id="viPassword" type="password" placeholder="Enter Your Password">
                            <p id="vi-pwd-error-message" class="help-text text-danger"> <span class="text-red">* </span>Password is required</p>
                        </div>
                    </div>
                    <div class="cf-vi-forgot-pwd">
                        <a id="vi-forgot-pwd" href="https://www.canvasflip.com/#/forgotpwd" target="_blank">Forgot Password?</a>
                    </div>
                    <div class="cf-vi-login-btn">
                        <a id="viSignup">Login</a>
                    </div>
                </div>
                <div id="viSocialSignin" class="form-signin active">
                    <div class="social-option">
                        Or continue with
                    </div>
                    <div class="social-signup-cta">
                        <button id="cfGoogleLogin" class="loginBtn loginBtn-google" data-provider="google" data-onsuccess="signinCallback" data-loading-text="<div class='btn-loader-msg'>Login...</div><div class='btn-loader btn-loader-small btn-loader-google'></div>">
                            Login with Google
                        </button>
                        <button id="cfFbLogin" class="loginBtn loginBtn-facebook" data-loading-text="<div class='btn-loader-msg'>Login...</div><div class='btn-loader btn-loader-small btn-loader-facebook'></div>">
                            Login with Facebook
                        </button>
                    </div>
                </div>
                <div class="vi-error-text">
                    <span id="viErrorText"></span>
                </div>
                <div class="new-to-vi">
                    <a target="_blank" href="https://www.canvasflip.com/vi/">New to Visual Inspector? Click here to know more.</a>
                </div>
            </div>

            <div id="viProject" class="container page">
                <div class="vi-project-heading">
                    <span id="viWPProjectLabel"><img src="<?php echo esc_url(plugins_url('../assets/images/vi-back-arrow.svg', __FILE__)); ?>" alt="Back Arrow"></span>
                    <span class="vi-project-found-title">PROJECTS</span>
                </div>

                <div class="row">
                    <div class="vi-filer col-md-12">
                        <div class="vi-search pull-left">
                            <span class="vi-search-icon"><img src="<?php echo esc_url(plugins_url('../assets/images/vi-search.svg', __FILE__)); ?>" alt=" Search Box"></span>
                            <input type="text" id="viSearchProject" class="vi-search-text" placeholder="Search projects...">
                        </div>
                        <div class="vi-sort pull-right">
                            <span>Sort:</span>
                            <span class="dropdown">
                                <a class="dropdown-toggle" id="viProjectdropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span id="viProjectSortedBy"> Last Edited</span>
                                    <span><img src="<?php echo esc_url(plugins_url('../assets/images/vi-arrow-down.svg', __FILE__)); ?>" alt="Down Arrow"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="viProjectdropdownMenu">
                                    <li><a href="#" class="sort-by" data-id="last-edited"> Last Edited </a></li>
                                    <li><a href="#" class="sort-by" data-id="newsest-first"> Newest First </a></li>
                                    <li><a href="#" class="sort-by" data-id="a-z"> A - Z </a></li>
                                    <li><a href="#" class="sort-by" data-id="z-a"> Z - A </a></li>
                                </ul>
                            </span>
                        </div>
                    </div>
                    <div id="viProjectContainer" class="col-md-12 col-centered">
                        <form name="vi-form" id="vi-form" action="options.php" method="post" enctype="multipart/form-data" style="display: none;">
                            <textarea name="vi_settings[vi_css]" id="vi_settings[vi_css]"></textarea>
                            <?php settings_fields('vi_settings_group'); ?>
                            <input type="submit" name="submit" id="vi-submit">
                        </form>
                        <div id="viWPProjectContainer"></div>
                        <div id="viProjectsContainer"></div>
                    </div>
                </div>
            </div>

            <!--Project's Empty Screen-->
            <div id="viEmptyProjects" class="container page">
                <!--                                <div class="vi-project-heading">
                                                    <span class="vi-project-title">No Projects Found</span>
                                                </div>-->

                <div id="viProjectEmptyContainer" class="col-centered">
                    <div class="no-wpPlugin_project">
                        <div class="empty-state text-center">
                            <img src="<?php echo esc_url(plugins_url('../assets/images/vi-empty-state.svg', __FILE__)); ?>">
                            <p class="empty-heading-text">To continue,</p>
                            <div class="empty-subheading-text">
                                <p>1. <a href="https://chrome.google.com/webstore/detail/visual-inspector-by-canva/efaejpgmekdkcngpbghnpcmbpbngoclc" target="_blank">Download the Visual Inspector Chrome extension</a></p>
                                <p>2. Make changes to your live website,</p>
                                <p>3. Sync changes from Chrome extension.</p>
                            </div>
                            <a class="empty-vi-btn" href="https://www.canvasflip.com/visual-inspector/">Know more about Visual Inspector</a>
                        </div>
                    </div>
                </div>

            </div>

            <!--Delete Modal-->
            <div class="modal fade" id="deleteModal" role="dialog">
                <div class="vertical-alignment-helper">
                    <div class="modal-dialog vi-modal-dialog vertical-align-center">
                        <!-- Modal content-->
                        <div class="modal-content vi-modal-content">
                            <div class="modal-header reset-modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <img src="<?php echo esc_url(plugins_url('../assets/images/vi-close.svg', __FILE__)); ?>" alt="Close Button"/>
                                </button>
                                <h4 class="vi-modal-title">Are you sure want to reset to original page?</h4>
                            </div>
                            <div class="modal-body">
                                <p class="vi-modal-text">You can get all the changes by clicking Sync button.</p>
                                <div class="vi-modal-actions text-center">
                                    <a id="viDeleteYes" class="vi-modal-btn vi-modal-btn-yes">YES, RESET PROJECT</a>
                                    <a id="viDeleteCancel" class="vi-modal-btn vi-modal-btn-cancel">CANCEL, KEEP PROJECT</a>
                                    <p class="vi-reset-success-text">*Reset is successful.</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    

        <!----Mobile view----->
        <div id="viMobileView">
            <div class="vi-mobile-logo">
                <img src="<?php echo esc_url(plugins_url('../assets/images/vi-logo.svg', __FILE__)); ?>" alt="Visual Inspector Logo"/>
            </div>
            <div class="vi-mobile-image">
                <img src="<?php echo esc_url(plugins_url('../assets/images/vi-mobile-view.svg', __FILE__)); ?>" alt="Mobile Version" />
            </div>
            <div class="vi-mobile-title">
                Oops! Desktop Only
            </div>
            <div class="vi-mobile-sub-title">
                Visual Inspector dashboard can be viewed only from a Desktop.
            </div>
            <div class="vi-mobile-border">
            </div>
            <div class="vi-mobile-send-email">
                <a  href="mailto:<?php echo $adminEmail ?>?subject=Reminder: Checkout Visual Inspector dashboard from Desktop&body=​Don't forget to checkout Visual Inspector dashboard from desktop - https://www.canvasflip.com/vi/">
                    Remind me on email
                </a>
            </div>
        </div>
    </body>
</html>