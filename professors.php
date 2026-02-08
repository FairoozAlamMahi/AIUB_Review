<?php
include "db.php";

/* ================= FILTER LOGIC ================= */

$where = "";

if (isset($_GET['faculty'])) {
    $faculty = $conn->real_escape_string($_GET['faculty']);
    $where = "WHERE faculty = '$faculty'";
}

if (isset($_GET['department'])) {
    $department = $conn->real_escape_string($_GET['department']);
    $where = "WHERE department = '$department'";
}

/* ================= GET PROFESSORS ================= */

$sql = "SELECT * FROM professors $where ORDER BY name ASC";
$result = $conn->query($sql);

/* ================= GET FILTER DATA ================= */

$faculties = $conn->query("SELECT DISTINCT faculty FROM professors ORDER BY faculty ASC");
$departments = $conn->query("SELECT DISTINCT department FROM professors ORDER BY department ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>AIUB Faculty Review – Professors</title>
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

    <h2 class="welcome">Browse Professors</h2>

    <!-- SEARCH BAR (UI only for now) -->
    <div class="search-bar">
        <input type="text" placeholder="Search faculty by name...">
        <button>Search</button>
    </div>

    <!-- FILTERS -->
    <div style="margin-bottom:20px; display:flex; gap:10px; flex-wrap:wrap;">

        <!-- Faculty -->
        <select onchange="filterFaculty(this.value)">
            <option value="">Filter by Faculty</option>
            <?php while($f = $faculties->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($f['faculty']); ?>">
                    <?php echo $f['faculty']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Department -->
        <select onchange="filterDepartment(this.value)">
            <option value="">Filter by Department</option>
            <?php while($d = $departments->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($d['department']); ?>">
                    <?php echo $d['department']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Clear -->
        <button onclick="window.location='professors.php'">Clear</button>

    </div>

    <!-- PROFESSOR GRID -->
    <div class="prof-grid">

        <?php while($row = $result->fetch_assoc()): ?>
            <div class="prof-card" onclick="goProfile(<?php echo $row['id']; ?>)">
                <img src="images/professors/<?php echo $row['image']; ?>" alt="">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['department']; ?></p>
                <div class="rating">⭐ <?php echo number_format($row['average_rating'], 1); ?></div>
            </div>
        <?php endwhile; ?>

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
function filterFaculty(value) {
    if (value === "") {
        window.location = "professors.php";
    } else {
        window.location = "professors.php?faculty=" + encodeURIComponent(value);
    }
}

function filterDepartment(value) {
    if (value === "") {
        window.location = "professors.php";
    } else {
        window.location = "professors.php?department=" + encodeURIComponent(value);
    }
}

function goProfile(id) {
    window.location.href = "professor.php?id=" + id;
}
</script>

</body>
</html>
