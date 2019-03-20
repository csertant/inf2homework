<?php
include 'scalp_db.php';

$connection = connectDatabase();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Scalp - Projects</title>
        <link rel="stylesheet" href="styles.css" />
        <link rel="icon" href="pics/favicon.png" />
        <meta charset="utf-8" />
    </head>
    <body>
        <?php include 'menu.html'; ?>

        <div class="project-details-content">
            <header class="title">
                <h1>Project details</h1>
            </header>
                <?php
                    $querySelect = sprintf("SELECT id, name, description, startDate FROM Project WHERE id='%s'",
                    mysqli_real_escape_string($connection, $_GET['projectid']));
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                    $row = mysqli_fetch_array($result);
                ?>
                <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>      
                        <th>Project started</th>      
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                    <?php if(!mysqli_num_rows($result)): ?>
                        <tr><td colspan="4">Table is empty.</td></tr>
                    <?php endif; ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['description']?></td>
                        <td><?=$row['startDate']?></td>
                        <td>
                            <a href="projects.php?projectid=<?=$row['id']?>">
                                <i class="material-icons">edit</i>
                            </a>
                        </td> 
                    </tr>
                </tbody>
                </table>
                <!-- Company and Contributors, new, modify, delete -->
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>