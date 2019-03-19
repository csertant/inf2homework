<?php
include 'scalp_db.php';

$connection = connectDatabase();

$search = null;
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Scalp - Search</title>
        <link rel="stylesheet" href="styles.css" />
        <link rel="icon" href="pics/favicon.png" />
        <meta charset="utf-8" />
    </head>
    <body>
        <?php include 'menu.html'; ?>

        <div class="people-content">
            <header class="title">
                <h1>Search</h1>
            </header>

            <div class="results">
                <?php
                    $peopleSearch = sprintf("SELECT id, name, address, email, workplace FROM People WHERE LOWER(name) LIKE '%%%s%%' ", mysqli_real_escape_string($connection, strtolower($search)));
                    $peopleResult = mysqli_query($connection, $peopleSearch) or die(mysqli_error($connection));

                    $companySearch = sprintf("SELECT id, name, address, ceo FROM Company WHERE LOWER(name) LIKE '%%%s%%' ", mysqli_real_escape_string($connection, strtolower($search)));
                    $companyResult = mysqli_query($connection, $companySearch) or die(mysqli_error($connection));

                    $projectSearch = sprintf("SELECT id, name, description, projectLeader FROM Project WHERE LOWER(name) LIKE '%%%s%%' ", mysqli_real_escape_string($connection, strtolower($search)));
                    $projectResult = mysqli_query($connection, $projectSearch) or die(mysqli_error($connection));
                ?>

                <!-- People -->
                <?php if (!($sg = mysqli_fetch_array($peopleResult))): ?>
                    <div>There are no people like that in our database.</div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Workplace</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_array($peopleResult)): ?>
                            <tr>
                                <td><?=$row['name']?></td>
                                <td><?=$row['address']?></td>
                                <td><?=$row['email']?></td>
                                <td><?=$row['workplace']?></td>
                                <td>
                                    <a class="" href="editPeople.php?personid=<?=$row['id']?>">
                                        <i class=""></i>
                                    </a>
                                </td> 
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Company -->
                <?php if (!($sg = mysqli_fetch_array($companyResult))): ?>
                    <div>There are no companies like that in our database.</div>
                <?php endif; ?>
                <table class="">
                    <thead class="">
                        <tr>
                            <th>Name</th>
                            <th>Address</th>      
                            <th>Ceo</th>      
                            <th></th>
                        </tr> 
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_array($companyResult)): ?>
                        <tr>
                            <td><?=$row['name']?></td>
                            <td><?=$row['address']?></td>
                            <td><?=$row['ceo']?></td>
                            <td>
                                <a class="" href="editCompany.php?companyid=<?=$row['id']?>">
                                    <i class=""></i>
                                </a>
                            </td> 
                        </tr>                
                    <?php endwhile; ?> 
                    </tbody>
                </table>
                <!-- Project -->
                <?php if (!($sg = mysqli_fetch_array($projectResult))): ?>
                    <div>There are no projects like that in our database.</div>
                <?php endif; ?>
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
                    <?php while ($row = mysqli_fetch_array($projectResult)): ?>
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
            </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>