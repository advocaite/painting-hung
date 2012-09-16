<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <title>{slogan}</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <link rel="Shortcut Icon" href="../favicon.ico" type="image/x-icon" />
            <link rel="stylesheet" href="../css/default_front.css" type="text/css" />
            <script type="text/javascript" src="../js/jquery.min.js"></script>
            <script type="text/javascript" src="../js/kode_function.js"></script>
            <script type="text/javascript" src="../js/jquery.form.js"></script>
            <script type="text/javascript" src="../js/kode_front_function.js"></script>
        </head>
        <body>
            <div id="kode_front">
                <div class="content">
                    <div class="content_header"><h3>{Administrator}</h3></div>
                    <div class="content_body">
                        <form  method="post" action="login.php" id="LoginForm" name="LoginForm">
                            <div>
                                <label for="username">{Username}:</label>
                                <input class="full ipt" type="text" id="username" value="" name="username"/>
                            </div>
                            <div>
                                <label for="password">{Password}:</label>
                                <input class="full ipt" type="password" id="password" value="" name="password"/>
                            </div>
                            <div>
                                <label>Mã xác thực:</label>
                                <input name="validate_code" id="validate_code" type="text" value=""  class="ipt" size="10" />
                                <img src="../includes/_validate.php?r=0" alt="code" name="kode_img_sign" width="80" height="25" align="absbottom" class="kode_validate_img" id="kode_img_sign" />
                            </div>
                            <div id="kode_result"></div>
                            <p>
                                <input type="submit" name="submit_btn" id="submit_btn" class="btn btn_green big" value="{Sign On}"/>
                                <input name="action" type="hidden" value="Login" />
                                <input name="check" type="hidden" value="{code}" />
                            </p>                       
                        <p id='copyright'>Copyright 2010-2011, Ltd. All Rights Reserved.</p>
                    </form>
                </div>
            </div>
        </div>         
    </body>
</html>