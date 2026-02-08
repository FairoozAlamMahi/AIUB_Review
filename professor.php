<?php
include "db.php";

/* ================= GET ID ================= */

if (!isset($_GET['id'])) {
    die("Professor not found.");
}

$id = intval($_GET['id']);

/* ================= HANDLE NEW REVIEW ================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating >= 1 && $rating <= 5) {

        // insert review (comment can be empty)
        $stmt = $conn->prepare("INSERT INTO reviews (professor_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $id, $rating, $comment);
        $stmt->execute();

        // calculate new average & total
        $avg = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE professor_id = ?");
        $avg->bind_param("i", $id);
        $avg->execute();
        $data = $avg->get_result()->fetch_assoc();

        // update professors table
        $update = $conn->prepare("UPDATE professors SET average_rating = ?, total_reviews = ? WHERE id = ?");
        $update->bind_param("dii", $data['avg_rating'], $data['total'], $id);
        $update->execute();
    }

    // reload page
    header("Location: professor.php?id=" . $id);
    exit();
}

/* ================= GET PROFESSOR ================= */

$stmt = $conn->prepare("SELECT * FROM professors WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$prof = $stmt->get_result()->fetch_assoc();

if (!$prof) {
    die("Professor not found.");
}

/* ================= GET REVIEWS (ONLY COMMENTS) ================= */

$reviews = $conn->prepare("
    SELECT rating, comment, created_at 
    FROM reviews 
    WHERE professor_id = ?
    AND comment IS NOT NULL
    AND comment != ''
    ORDER BY created_at DESC
");
$reviews->bind_param("i", $id);
$reviews->execute();
$reviews_result = $reviews->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $prof['name']; ?> – Profile</title>
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

    <!-- PROFESSOR INFO -->
    <div class="prof-card" style="cursor:default;">
        <img src="images/professors/<?php echo $prof['image']; ?>" alt="">
        <h2><?php echo $prof['name']; ?></h2>
        <p><b>Position:</b> <?php echo $prof['position']; ?></p>
        <p><b>Faculty:</b> <?php echo $prof['faculty']; ?></p>
        <p><b>Department:</b> <?php echo $prof['department']; ?></p>
        <div class="rating">
            ⭐ <?php echo number_format($prof['average_rating'], 1); ?>
            (<?php echo $prof['total_reviews']; ?> ratings)
        </div>
    </div>

    <!-- ================= ADD REVIEW ================= -->
    <h2 style="margin-top:40px;">Add Your Rating</h2>

    <form method="POST" style="background:white; padding:15px; border-radius:6px; margin-bottom:30px;">

        <label>Rating:</label>

        <div class="star-rating">
            <span data-value="1">★</span>
            <span data-value="2">★</span>
            <span data-value="3">★</span>
            <span data-value="4">★</span>
            <span data-value="5">★</span>
        </div>

        <input type="hidden" name="rating" id="ratingValue" required>

        <br>

        <label>Comment (optional):</label>
        <textarea name="comment" style="width:100%; height:80px;"></textarea>

        <br><br>

        <button type="submit">Submit</button>
    </form>

    <!-- ================= COMMENTS ONLY ================= -->
    <h2>Student Comments</h2>

    <?php if ($reviews_result->num_rows == 0): ?>
        <p>No comments yet.</p>
    <?php else: ?>

        <?php while($r = $reviews_result->fetch_assoc()): ?>
            <div style="background:white; padding:15px; margin-bottom:10px; border-radius:6px;">
                <div class="rating">⭐ <?php echo $r['rating']; ?></div>
                <p style="margin-top:5px;"><?php echo htmlspecialchars($r['comment']); ?></p>
                <small style="color:#777;">
                    <?php echo $r['created_at']; ?>
                </small>
            </div>
        <?php endwhile; ?>

    <?php endif; ?>

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

<!-- ================= STAR SCRIPT ================= -->
<script>
const stars = document.querySelectorAll(".star-rating span");
const ratingInput = document.getElementById("ratingValue");

stars.forEach(star => {
    star.addEventListener("click", () => {
        const value = star.getAttribute("data-value");
        ratingInput.value = value;

        stars.forEach(s => {
            s.classList.remove("active");
            if (s.getAttribute("data-value") <= value) {
                s.classList.add("active");
            }
        });
    });
});
</script>

</body>
</html>
