<?php 
    global $wpdb;
    $table  = $wpdb->prefix."mailers";

   $listas = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
?>
<div id="wp-media-grid" class="wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-email-alt" style="margin-top:5px;"></span>
        <?php echo get_admin_page_title();?>
    </h1>

    <div style="padding: 15px 0;">
        <table class="wp-list-table widefat mixed striped page">
            <thead>
                <tr>
                    <th width=40 style="text-align:center;">ID</th>
                    <th width=60 style="text-align:center;">Tipo</th>
                    <th>Estado</th>
                    <th>Progamacion</th>
                    <th>Meta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listas as $row): ?>
                <tr>
                    <td width=40 style="text-align:center;">
                        <?php echo $row["ID"]; ?>
                    </td>
                    <td width=60 style="text-align:center;">
                        <?php echo $row["type"]; ?>
                    </td>
                    <td><?php echo $row["state"]; ?></td>
                    <td><?php echo $row["handler"]; ?></td>
                    <td><?php echo $row["meta"]; ?></td>
                    <td>Action</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>