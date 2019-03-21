<?php
include 'scalp_db.php';

$connection = connectDatabase();

$addedNewProject = false;
$updatedProject = false;
$deletedProject = false;
if (isset($_POST['new'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $startDate = mysqli_real_escape_string($connection, $_POST['startDate']);
    $createQuery = sprintf("INSERT INTO Project(name, description, startDate) VALUES ('%s', '%s', '%s')",
        $name,
        $description,
        $startDate
    );
    mysqli_query($connection, $createQuery) or die(mysqli_error($connection));
    $addedNewProject = true;
}
else if(isset($_POST['update'])) {
    $uid = mysqli_real_escape_string($connection, $_POST['id']);
    $uname = mysqli_real_escape_string($connection, $_POST['name']);
    $udescription = mysqli_real_escape_string($connection, $_POST['description']);
    $ustartDate = mysqli_real_escape_string($connection, $_POST['startDate']);
    $updateQuery = sprintf("UPDATE Project SET name='%s', description='%s', startDate='%s' WHERE id='%s'",
        $uname,
        $udescription,
        $ustartDate,
        $uid
    );
    mysqli_query($connection, $updateQuery) or die(mysqli_error($connection));
    $updatedProject = true;
}
else if(isset($_POST['delete'])) {
    $deleteQueryMxdPeople = sprintf("DELETE FROM ProjectsContributors WHERE Project_id='%s'", 
    mysqli_real_escape_string($connection, $_POST['id']));
    $deleteQueryMxdCompanies = sprintf("DELETE FROM CompaniesProjects WHERE Project_id='%s'", 
    mysqli_real_escape_string($connection, $_POST['id']));
    $deleteQueryProjects = sprintf("DELETE FROM Project WHERE id='%s'", 
    mysqli_real_escape_string($connection, $_POST['id']));
    mysqli_query($connection, $deleteQueryMxdPeople) or die(mysqli_error($connection));
    mysqli_query($connection, $deleteQueryMxdCompanies) or die(mysqli_error($connection));
    mysqli_query($connection, $deleteQueryProjects) or die(mysqli_error($connection));
    $deletedProject = true;
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

        <div class="projects-content">
            <header class="title">
                <h1>Projects</h1>
            </header>
            <div>
                <?php
                    if(isset($_GET['projectid'])) {
                        $querySelect = sprintf("SELECT id, name, description, startDate FROM Project WHERE id='%s'",
                        mysqli_real_escape_string($connection, $_GET['projectid']));
                    }
                    else {
                        $querySelect = "SELECT id, name, description, startDate FROM Project ORDER BY name";
                    }
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
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
                <?php while ($row = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?=$row['name']?></td>
                        <td><?=$row['description']?></td>
                        <td><?=$row['startDate']?></td>
                        <td>
                            <a href="projects.php?projectid=<?=$row['id']?>">
                                <i class="material-icons">edit</i>
                            </a>
                            <a href="details.php?projectid=<?=$row['id']?>">
                                <i class="material-icons">info</i>
                            </a>
                        </td> 
                    </tr>                
                <?php endwhile; ?> 
                </tbody>
                </table>

                <!-- Create New -->
                <?php if(!isset($_GET['projectid'])): ?>
                <div class="form-container">
                    <form method="post" action="">
                        <h2>New project</h2>
                            <div class="form-field">
                                <label for="name">Name:</label>
                                <input required name="name" id="name" type="text" />
                            </div>
                            <div class="form-field">
                                <label for="description">Description:</label>
                                <input name="description" id="description" type="text"  />
                            </div>
                            <div class="form-field">
                                <label for="startDate">Project starts:</label>
                                <?php $currentDate = date('y-m-h', time()); ?>
                                <input required name="startDate" id="startDate" type="date" value=<?=$currentDate?>/>
                            </div>
                            <input class="form-submit" name="new" type="submit" value="Create" />
                    </form>
                </div>
                <?php endif; ?>
                
                <!-- Modify, Delete -->
                <?php if(isset($_GET['projectid'])): ?>
                <?php 
                    $result = mysqli_query($connection, $querySelect) or die(mysqli_error($connection));
                    $mdrow = mysqli_fetch_array($result)
                ?>
                <div class="form-container">
                    <form method="post" action="">
                        <h2>Modify/Delete project</h2>
                        <input type="hidden" name="id" id="mdid" value="<?=$mdrow['id']?>" />
                        <div class="form-field">
                            <label for="mdname">Name:</label>
                            <input required name="name" id="mdname" type="text" value="<?=$mdrow['name']?>" />
                        </div>
                        <div class="form-field">
                            <label for="mddescription">Description:</label>
                            <input name="description" id="mddescription" type="text" value="<?=$mdrow['description']?>" />
                        </div>
                        <div class="form-field">
                            <label for="mdstartDate">Project started:</label>
                            <input name="startDate" id="mdstartDate" type="date" value="<?=$mdrow['startDate']?>"/>
                        </div>
                        <input class="form-submit" name="update" type="submit" value="Save" />
                        <input class="form-submit" name="delete" type="submit" value="Delete" />
                    </form>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>