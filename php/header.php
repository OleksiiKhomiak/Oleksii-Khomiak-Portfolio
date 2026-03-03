<?php
echo "<div>
<a href='projects.php'>Projects</a>
<a href='home.php'>Home</a>";

if (isset($_SESSION['userName']) && $_SESSION['userName'] == "UserName") {
    echo "<a href='addPage.php'>Projects</a>";
}

echo "</div>";
?>

