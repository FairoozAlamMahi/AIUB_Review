<?php
include "db.php";

/* total professors */
$p_result = $conn->query("SELECT COUNT(*) as total FROM professors");
$p_row = $p_result->fetch_assoc();
$total_professors = $p_row['total'];

/* total reviews */
$r_result = $conn->query("SELECT COUNT(*) as total FROM reviews");
$r_row = $r_result->fetch_assoc();
$total_reviews = $r_row['total'];

/* total departments */
$d_result = $conn->query("SELECT COUNT(DISTINCT department) as total FROM professors");
$d_row = $d_result->fetch_assoc();
$total_departments = $d_row['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>AIUB Faculty Review – Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<img src="bg-academic.png" alt="" id="bg">

<!-- TOP NAV -->
<div id="p1">

    <a href="dashboard.php">
        <h2 id="logo">AIUB Faculty Review</h2>
    </a>

    <a href="professors.php">Professors</a>
    <a href="#">Departments</a>
    <a href="#">Top Rated</a>
    <a href="#">Recent Reviews</a>

</div>

<!-- MAIN CONTENT -->
<div id="main_wrapper">

    <h2 class="welcome">Welcome to AIUB Faculty Review</h2>

    <!-- SEARCH BAR -->
    <div class="search-bar">
        <input type="text" placeholder="Search faculty by name...">
        <button>Search</button>
    </div>

    <!-- ANNOUNCEMENT -->
    <div id="announcement_panel">
        <h2>📢 Notice</h2>
        <div class="announcement_box">
            This platform allows students to anonymously review faculty members.
            Please be respectful and honest.
        </div>
    </div>

    <!-- STATS -->
    <div class="stats">

        <!-- Professors (clickable via JS) -->
        <div class="card" onclick="goProfessors()" style="cursor:pointer;">
            <h3>Professors</h3>
            <p><?php echo $total_professors; ?></p>
        </div>

        <!-- Reviews -->
        <div class="card">
            <h3>Total Reviews</h3>
            <p><?php echo $total_reviews; ?></p>
        </div>

        <!-- Departments -->
        <div class="card">
            <h3>Departments</h3>
            <p><?php echo $total_departments; ?></p>
        </div>

    </div>

</div>

<!-- USER ICON -->
<a href="#">
    <div id="usericon">👤</div>
</a>

<!-- FOOTER -->
<div id="footer">
    <p>© 2026 AIUB Faculty Review</p>
    <p>Student opinions only • Not an official university platform</p>
</div>

<!-- SCRIPT -->
<script>
function goProfessors() {
    window.location.href = "professors.php";
}
</script>

</body>
</html>
