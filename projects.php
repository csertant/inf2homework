<?php
include 'scalp_db.php';

$connection = connectDatabase();

$addedNewProject = false;
if (isset($_POST['new'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $projectLeader = mysqli_real_escape_string($connection, $_POST['projectLeader']);
    $createQuery = sprintf("INSERT INTO Project(name, description, projectLeader) VALUES ('%s', '%s', '%s')",
        $name,
        $description,
        $projectLeader
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
    $addedNewProject = true;
}
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

        <div class="people-content">
            <header class="title">
                <h1>Projects</h1>
            </header>
            <div>
                <?php
                    $querySelect = "SELECT id, name, description, projectLeader FROM Project ORDER BY name";
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                ?>
                <table class="">
                <thead class="">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>      
                        <th>Leader</th>      
                        <th></th>
                    </tr> 
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['description']?></td>
                        <td><?=$row['projectLeader']?></td>
                        <td>
                            <a class="" href="editProject.php?projectid=<?=$row['id']?>">
                                <i class=""></i>
                            </a>
                        </td> 
                    </tr>                
                <?php endwhile; ?> 
                </tbody>
                </table>

                <form method="post" action="">
                    <div class="form-container">
                        <h2>New project</h2>
                            <div class="form-field">
                                <label for="name">Name</label>
                                <input required class="form-control" name="name" id="name" type="text" />
                            </div>
                            <div class="form-field">
                                <label for="description">Description</label>
                                <input required class="form-control" name="description" id="description" type="text"  />
                            </div>
                            <div class="form-field">
                                <label for="pojectLeader">Leader</label>
                                <!-- Select existing people-->
                                
                            </div>
                            <input class="form-submit" name="new" type="submit" value="Create" />
                    </div>
                </form>
            </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>