<?php

/*** Select Query ***/
global $wpdb, $table_prefix;
$table_name = $table_prefix . 'employee';

$select_query = "SELECT * FROM `$table_name`";

$results = $wpdb->get_results($select_query);

// echo "<pre>";
// print_r($results);
// echo "</pre>";
ob_start();
?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>MOBILE NO</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $data) : ?>
            <tr>
                <td><?php echo $data->id; ?></td>
                <td><?php echo $data->name; ?></td>
                <td><?php echo $data->email; ?></td>
                <td><?php echo $data->m_number; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
return ob_get_clean();
