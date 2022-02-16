<?php
/*
 * Plugin Name: ag-impacts - Login Widget
 * Description: Plugin para el widget de login
 * Author: Camilo Rodriguez
 * Version: 1.0
 */

class PCLoginWidget extends WP_Widget {

  function __construct() {
    parent::__construct(
            'agloginwidget', 'AG Login Widget', array(
      'description' => 'Widget para hacer login'
            )
    );
  }

  function form($instance) {
    ?>
    <label>Titulo</label>
    <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" >

    <?php
  }

  function update($new, $old) {
    if ($new['title'] == '')
      return $old;

    return $new;
  }

  function widget($args, $instance) {
    $title = apply_filters('widgets_title', $instance['title']);

//    echo $args['before_widget'];
//    echo $args['before_title'] . $title . $args['after_title'];


    if (!is_user_logged_in()) :
      ?>
      <style>
        #dialog-form { font-size: 62.5%; }
        /*label, input { display:block; }*/
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        fieldset { padding:0; border:0; margin-top:25px; }
        h1 { font-size: 1.2em; margin: .6em 0; }
        div#users-contain { width: 350px; margin: 20px 0; }
        div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
      </style>
      <script>
        $(function() {
          var form,
                  // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
                  emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
                  user = $("#reguser"),
                  name = $("#regname"),
                  lastname = $("#reglastname"),
                  email = $("#regemail"),
                  password = $("#regpwd"),
                  repassword = $("#regrepwd"),
                  allFields = $([]).add(name).add(email).add(password).add(repassword).add(user).add(lastname),
                  tips = $(".validateTips");

          function updateTips(t) {
            tips
                    .text(t)
                    .addClass("ui-state-highlight");
            setTimeout(function() {
              tips.removeClass("ui-state-highlight", 1500);
            }, 500);
          }

          function checkLength(o, n, min, max) {
            if (o.val().length > max || o.val().length < min) {
              o.addClass("ui-state-error");
              updateTips("Length of " + n + " must be between " +
                      min + " and " + max + ".");
              return false;
            } else {
              return true;
            }
          }

          function checkRegexp(o, regexp, n) {
            if (!(regexp.test(o.val()))) {
              o.addClass("ui-state-error");
              updateTips(n);
              return false;
            } else {
              return true;
            }
          }

          function checkPass(o, l, n) {
            if (o.val() != l.val()) {
              o.addClass("ui-state-error");
              l.addClass("ui-state-error");
              updateTips(n);
              return false;
            } else {
              return true;
            }
          }

          function addUser() {
            var valid = true;
            allFields.removeClass("ui-state-error");

            valid = valid && checkLength(user, "Username", 3, 16);
            valid = valid && checkLength(name, "First Name", 3, 16);
            valid = valid && checkLength(lastname, "Last Name", 3, 16);
            valid = valid && checkLength(email, "Email", 6, 80);
            valid = valid && checkLength(password, "Password", 5, 16);
            valid = valid && checkPass(password, repassword, "Valid your password");
      //            valid = valid && checkRegexp(name, /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter.");
            valid = valid && checkRegexp(email, emailRegex, "eg. ui@jquery.com");
            valid = valid && checkRegexp(password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9");

            if (valid) {
              $.ajax({
                url: "./wp-content/themes/agimpacts/signup.php?" + $("#newuserform").serialize(),
                type: "POST",
                success: function(result) {
                  if (result != '') {
                    var n = noty({
                      layout: 'top',
                      type: 'error',
                      timeout: 6000,
                      text: result
                    });
                  } else {
                    location.reload();
                  }
                },
                complete: function() {

                }
              });
              //              document.getElementById("newuserform").submit();
              //              dialog.dialog("close");
            }
            return valid;
          }

          dialog = $("#dialog-form").dialog({
            autoOpen: false,
            height: 600,
            width: 350,
            modal: true,
            buttons: {
              "Create an account": addUser,
              Cancel: function() {
                dialog.dialog("close");
              }
            },
            close: function() {
              form[ 0 ].reset();
              tips.text("All form fields are required.");
              allFields.removeClass("ui-state-error");
            }
          });

          form = dialog.find("form").on("submit", function(event) {
            event.preventDefault();
            addUser();
          });
        });
      </script>
      <div id='log-pplug' >
        <form action="<?php echo bloginfo('url') . '/wp-login.php'; ?>" method="post" class="pure-form">
          <button type="button" id="btn-signup" class="pure-button button-small" onclick="dialog.dialog('open');$('#dialog-form').show()">Sign up</button>
          - 
          <input type="text" name="log" placeholder="Username" />
          <input type="password" name="pwd" placeholder="Password" />
          <button type="submit" id="btn-login" class="pure-button button-small"> Log in</button>
          <a style="font-weight: bold" href="<?php echo bloginfo('url') . '/wp-login.php'; ?>/wp-login.php?action=lostpassword">Lost your password?</a>
        </form>
        <div id="dialog-form" title="Create new user" style="display:none">
          <p class="validateTips">All form fields are required.</p>

          <form id="newuserform" name="newuserform" method="post">
            <fieldset>
              <label for="name">Username</label>
              <input type="text" name="reguser" id="reguser" value="" class="text ui-widget-content ui-corner-all">
              <label for="email">Email</label>
              <input type="text" name="regemail" id="regemail" value="" class="text ui-widget-content ui-corner-all">
              <label for="name">Institution</label>
              <input type="text" name="reginstitution" id="reginstitution" class="text ui-widget-content ui-corner-all">
              <label for="name">First Name</label>
              <input type="text" name="regname" id="regname" class="text ui-widget-content ui-corner-all">
              <label for="name">Last Name</label>
              <input type="text" name="reglastname" id="reglastname" class="text ui-widget-content ui-corner-all">
              <label for="password">Password</label>
              <input type="password" name="regpwd" id="regpwd" value="" class="text ui-widget-content ui-corner-all">
              <label for="password">Repeat Password</label>
              <input type="password" name="regrepwd" id="regrepwd" class="text ui-widget-content ui-corner-all">
              <!--<label><input type="checkbox" name="agree"> I agree terms of use and privacy </label>-->
              <input type="hidden" name="register" value="1">
              <!-- Allow form submission with keyboard without duplicating the dialog button -->
              <!--<input type="submit" value="Aceptar">-->
            </fieldset>
          </form>
        </div>
      </div>
      <?php
    else:
      $user = wp_get_current_user();
      ?>
      <div class="perfil">
        Welcome, <?php echo $user->first_name . " - "; ?> <a style="font-weight: bold" href="<?php echo wp_logout_url(get_bloginfo('url')); ?>">Sign out</a>
      </div>

    <?php
    endif;

//    echo $args['after_widget'];
  }

}

add_action('widgets_init', function() {
  register_widget('PCLoginWidget');
});
