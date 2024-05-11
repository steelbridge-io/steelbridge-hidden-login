# steelbridge Hidden Login
## A WordPress plugin that produces a custom login page.

1. Create a page in your wordpress site titled what ever you wish your login page to be. In this example we will create a page titled "hidden login". Save this page.
2. Add the following code to the new login page using the html block:

```
<form name="loginform" id="loginform" action="https://steelbridge.io/wp-login.php" method="post">
    <p>
        <label for="user_login">Username<br>
        <input name="log" id="user_login" class="input" type="text"></label>
    </p>
    <p>
        <label for="user_pass">Password<br>
        <input name="pwd" id="user_pass" class="input" type="password"></label>
    </p>
    <p class="submit">
        <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log In">
        <input type="hidden" name="redirect_to" value="https://steelbridge.io/wp-admin/">
    </p>
</form>
```

3. Save the page and now add the slug to the line 25 of steelbridge-hidden-login.php in this plugin where to see /hidden-login/ replace with /your-slug/. Do the same on line 68.

```
// Find the follwoing in the code found in steelbridge-hidden-login.php

function append_cookie() {
/* Line 25 */if ($_SERVER['REQUEST_URI'] === '/hidden-login' || $_SERVER['REQUEST_URI'] === '/your-slug/') {
setcookie( 'custom_login_page', 1, time() + 300, COOKIEPATH, COOKIE_DOMAIN );
}
}

function redirect_after_logout(){
/* Line 67 */ wp_redirect( home_url('/your-slug') );
	exit();
}
```
4. Make sure to noindex your new login page. You don't want this page showing up in search or becoming part of your site map.

I will add a subpage for settings soon. The subpage will allow you to add the slug in the settings dashboard ans additionally produce a page for logging in rather than having to create one within your WordPress environment. 