<div id="wp-media-grid" class="wrap">

    <h1 class="wp-heading-inline">
        <?php echo get_admin_page_title();?>
    </h1>

    <?php if($formError->has_errors()):?>        
        <div class="notice notice-error is-dismissible">
            <?php foreach($formError->get_error_messages() as $error ): ?>
            <p><?php echo $error; ?></p>
            <?php endforeach; ?>   
        </div>             
    <?php endif; ?>
    
    <form method="POST">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="host">SMTP host:</label></th>
                    <td>
                        <input type="text" 
                                name="host"
                                id="host" 
                                value="<?php echo get_option('smtpmail_host');?>"
                                autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="user">SMTP user:</label></th>
                    <td>
                        <input type="text" 
                                name="user"
                                id="user" 
                                value="<?php echo get_option('smtpmail_user');?>"
                                autocomplete="off">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="pwd">SMTP password:</label></th>
                    <td>
                        <input type="password" 
                                name="password" 
                                id="pwd"
                                value="">
                    </td>
                </tr>                
                <tr>
                    <th scope="row"><label for="port">SMTP Port:</label></th>
                    <td>
                        <input type="text" 
                                name="port" 
                                id="port"
                                value="<?php echo get_option('smtpmail_port');?>"
                                autocomplete="off">
                    </td>
                </tr>
                
                <tr>
                    <td colspan=2>
                        <button type="submit" class="button button-hero">
                            Guardar
                        </button>
                    </td>
                </tr>
                
            </tbody>
        </table>

        <input type="hidden" name="tag" value="ziptag">
    </form>

</div>