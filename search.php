<?php
include 'scalp_db.php';

$connection = connectDatabase();
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
                    $search = null;
                    if (isset($_POST['search'])) {
                        $search = $_POST['search'];
                    }
                    $peopleSearch = sprintf("SELECT id, name, address, email, cellphone FROM People WHERE LOWER(name) LIKE '%%%s%%' ", mysqli_real_escape_string($connection, strtolower($search)));
                    $peopleResult = mysqli_query($connection, $peopleSearch) or die(mysqli_error($connection));

                    $companySearch = sprintf("SELECT id, name, address, category FROM Company WHERE LOWER(name) LIKE '%%%s%%' ", mysqli_real_escape_string($connection, strtolower($search)));
                    $companyResult = mysqli_query($connection, $companySearch) or die(mysqli_error($connection));

                    $projectSearch = sprintf("SELECT id, name, description, startDate FROM Project WHERE LOWER(name) LIKE '%%%s%%' ", mysqli_real_escape_string($connection, strtolower($search)));
                    $projectResult = mysqli_query($connection, $projectSearch) or die(mysqli_error($connection));
                ?>

                <!-- People -->
                <h2>People</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Cellphone</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!mysqli_num_rows($peopleResult)): ?>
                            <tr><td colspan="4">There are no people like that in our database.</td></tr>
                        <?php endif; ?>
                        <?php while ($row = mysqli_fetch_array($peopleResult)): ?>
                            <tr>
                                <td><?=$row['name']?></td>
                                <td><?=$row['address']?></td>
                                <td><?=$row['email']?></td>
                                <td><?=$row['cellphone']?></td>
                                <td>
                                    <a class="" href="people.php?personid=<?=$row['id']?>">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </td> 
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- Company -->
                <h2>Startups</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>      
                            <th>Category</th>      
                            <th></th>
                        </tr> 
                    </thead>
                    <tbody>
                        <?php if (!mysqli_num_rows($companyResult)): ?>
                            <tr><td colspan="4">There are no companies like that in our database.</td></tr>
                        <?php endif; ?>
                        <?php while ($row = mysqli_fetch_array($companyResult)): ?>
                            <tr>
                                <td><?=$row['name']?></td>
                                <td><?=$row['address']?></td>
                                <td><?=$row['category']?></td>
                                <td>
                                    <a class="" href="companies.php?companyid=<?=$row['id']?>">
                                        <i class="material-icons">edit</i>
                                    </a>
                                </td> 
                            </tr>                
                        <?php endwhile; ?> 
                    </tbody>
                </table>
                <!-- Project -->
                <h2>Projects</h2>
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
                        <?php if (!mysqli_num_rows($projectResult)): ?>
                            <tr><td colspan="4">There are no projects like that in our database.</td><tr>
                        <?php endif; ?>
                        <?php while ($row = mysqli_fetch_array($projectResult)): ?>
                            <tr>
                                <td><?=$row['name']?></td>
                                <td><?=$row['description']?></td>
                                <td><?=$row['startDate']?></td>
                                <td>
                                    <a class="" href="projects.php?projectid=<?=$row['id']?>">
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
            </div>
        </div>

        <?php include 'footer.html'; ?>
        <?php disconnectDatabase($connection)?>
    </body>
</html>