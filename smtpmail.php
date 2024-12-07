<?php
/*
 * Plugin Name:       SMTPMail
 * Plugin URI:        https://iipec.net
 * Description:       Envio de correo via smtp.
 * Version:           0.1
 * Author:            Ramon A Linares Febles
 * Author URI:        https://iipec.net/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       smtpmail
 * Domain Path:       /languages
*/

## Activate 
function smtpmail_activate(){
    require_once("activate.php");
}

register_activation_hook(__FILE__, "smtpmail_activate");

## Desinstall
function smtpmail_desactivate(){
    require_once("deactivate.php");
}
register_deactivation_hook(__FILE__, "smtpmail_desactivate");

## Menu administrativo
function add_admin_smtpmail_menu() {
    add_menu_page(
        "SMTP Server",
        "SMTP Server",
        "manage_options",
        "smtpmail/admin/form.php",
        '',
        "dashicons-email-alt",
        '81'
    );
}

add_action("admin_menu", "add_admin_smtpmail_menu");

$formError = new WP_Error;

if( count( $_POST ) )
{
    if( array_key_exists( "tag", $_POST ) )
    {
        ## Required validate
        foreach(["host", "user", "port"] as $field) 
        {
            if( array_key_exists( $field, $_POST) )
            {
                if( empty($_POST[$field]) ) {
                   $formError->add($field, "Campo $field requerido");
                }
            }
        }

        if( empty(get_option("smtpmail_host")) && empty($_POST["password"])) {
            $formError->add($field, "Campo password requerido");
        }
        
        ## Validar Email 
        if( ((bool) filter_var($_POST["user"], FILTER_VALIDATE_EMAIL)) == false ) {
            $formError->add($field, "Campo usuario no valido, se esperava un Correo Electronico");
        }
       
        if( !$formError->has_errors() )
        {
            if( empty(get_option("smtpmail_host")) ) {
                add_option("smtpmail_host", $_POST["host"]);
                add_option("smtpmail_user", $_POST["user"]);
                add_option("smtpmail_password", base64_encode($_POST["password"]));
                add_option("smtpmail_port", $_POST["port"]);
            }
            else {
                update_option("smtpmail_host", $_POST["host"]);
                update_option("smtpmail_user", $_POST["user"]);
                if( !empty($_POST["password"])):
                update_option("smtpmail_password", base64_encode($_POST["password"]));
                endif;
                update_option("smtpmail_port", $_POST["port"]);
            }
        }
    }
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Mail
{
    private static $smtp;

    private static $started = false;

    public static function initialize()
    {
        if( !empty(get_option("smtpmail_host")) )
        {
            self::$started  = true;
            self::$smtp     = new PHPMailer( true );

            self::$smtp->isSMTP(); 

            self::$smtp->Host       = get_option("smtpmail_host");
            self::$smtp->SMTPAuth   = true;
            self::$smtp->Username   = get_option("smtpmail_user");
            self::$smtp->Password   = base64_decode(get_option("smtpmail_password"));
            self::$smtp->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            self::$smtp->Port       = get_option("smtpmail_port"); 
        }
    }

    public static function start() {
        return self::$started;
    }

    public static function from($email, $name) {
        self::$smtp->setFrom($email, $name);
    }

    public static function to($email) {
        self::$smtp->addAddress($email);
    }

    public static function subject($description) {
        self::$smtp->Subject = $description;
    }

    public function attache($path) {
        self::$smtp->addAttachment($path);
    }

    public static function sendHtml( $data="Empty" ) {
        self::$smtp->isHTML(true);  
        self::$smtp->Body = $data;
        return self::$smtp->send();
    }

    public static function text($data)  {
        self::$smtp->AltBody = $data;
        return self::$smtp->send();
    }

    // public static function tag($slug=null) {
        
    //     if( self::$started ) {            
    //         if( count($_POST) ) {
    //             if( array_key_exists("tag", $_POST) ) {
    //                 if( $_POST["tag"] == $slug ) {
    //                     global $wpdb;
    //                     $table  = $wpdb->prefix."mailers";

    //                     $wpdb->insert($table, [
    //                         "type"  => "mail",
    //                         "state" => "pendiente",
    //                         "ip"    => $_SERVER["REMOTE_ADDR"],
    //                         "agent" => $_SERVER["HTTP_USER_AGENT"],
    //                         "meta"  => json_encode($_POST)
    //                     ]);	
    //                 }
    //             }
    //         }
    //     }        
    // }

    public static function registerMail($meta) {
        global $wpdb;
        $table  = $wpdb->prefix."mailers";

        $wpdb->insert($table, [
            "type"  => "mail",
            "state" => "pendiente",
            "ip"    => $_SERVER["REMOTE_ADDR"],
            "agent" => $_SERVER["HTTP_USER_AGENT"],
            "meta"  => json_encode($meta)
        ]);	
    }
    public static function makePost($tag, $closure) {
        
        if( ($closure instanceof  \Closure) ) 
        {
            if(count($_POST) )
            {
                if( array_key_exists("tag", $_POST) ) { 

                    $obj = new self;

                    if( $_POST["tag"] == $tag ) {                    
                        return $closure($obj);
                    }
                }
            }
        }
    }
}

Mail::initialize();