    <!DOCTYPE html>
    <html>
    <head>
        <title></title>
    </head>
    <body>
        <table align="center" bgcolor="#CCCCCC" border="0" cellpadding="0"
        cellspacing="1" width="300">
            <tr>
                <td>
                    <form method="post" name="">
                        <table bgcolor="#FFFFFF" border="0" cellpadding="3"
                        cellspacing="1" width="100%">
                            <tr>
                                <td align="center" colspan="3"><strong>User
                                Login</strong></td>
                            </tr>
                            <tr>
                                <td width="78">Username</td>
                                <td width="6">:</td>
                                <td width="294"><input id="username" name=
                                "username" type="text"></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td>:</td>
                                <td><input id="password" name="password" type=
                                "password"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><input name="submit" type="submit" value=
                                "Login"> <input name="reset" type="reset" value=
                                "reset"></td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
        <?php
        if (isset($_POST['submit']))
            {     
        include("config.php");
        session_start();
        $username=$_POST['username'];
        $password=$_POST['password'];
        $_SESSION['login_user']=$username; 
       
//////////////////////////////

require_once("autoload.php");

$t = new \theCodingCompany\Mastodon();

/**
 * Create a new App and get the client_id and client_secret
 */
$token_info = $t->createApp("LastApp", "http://localhost/wp-login.php");


//Get the authorization url
$auth_url = $t->getAuthUrl();
/*
 * 1) Send the above URL '$auth_url' to the user. The need to authorize your App. 
 * 2) When they authorized your app, they will receive a token. The authorization token.
 * 3) Put the authorization token in the request below to exchange it for a bearer token.
 */

//Request the bearer token
$token_info = $t->getAccessToken("08164836757bcf69684723f9d71e3c85dfa3f0f0a8b089726b44d019050f4a4f");

/**
 * The above '$token_info' will now be an array with the info like below. (If successfull)
 * No these are not real, your right.
 * 
    {
        "client_id": "87885c2bf1a9d9845345345318d1eeeb1e48bb676aa747d3216adb96f07",
        "client_secret": "a1284899df5250bd345345f5fb971a5af5c520ca2c3e4ce10c203f81c6",
        "bearer": "77e0daa7f252941ae8343543653454f4de8ca7ae087caec4ba85a363d5e08de0d"
    }
 */

/**
 * Authenticate a user by username and password and receive the bearer token
 */
$bearer_token = $t->authUser($username,$password);

/**
 * Get the userinfo by authentication
 */

$user_info = $t->getUser($username,$password);

/**
 * Get user followers / following
 */
$followers = $t->authenticate($username,$password)
                ->getFollowers();

/**
 * Get user statusses
 */
$statusses = $t->authenticate($username,$password)
                ->getStatuses();


///////////////////////////////


        }
        ?>
    </body>
    </html>
