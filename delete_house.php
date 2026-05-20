<?php
// 1. Database connection saagi
include 'dbconnect.php'; 

// 2. ID URL irraa dhufe (delete_house.php?id=10) fudhu
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $house_id = $_GET['id'];

    // 3. Query mana sana haquuf fayyadu barreessi (Prepared Statement fayyadamnee)
    // 'houses' maqaa table keetii yoo ta'e:
    $sql = "DELETE FROM houses WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $house_id);

        if (mysqli_stmt_execute($stmt)) {
            // 4. Yoo milkiin haqame, gara galmee manneenitti (view_houses.php) deebisi
            echo "<script>alert('House deleted successfully!'); window.location.href='manage_houses.php';</script>";
            exit();
        } else {
            // 5. Yoo dogoggorri uumame
            echo "Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
} else {
    // Yoo ID'n hin ergamne ta'e
    echo "Error: Invalid or missing house ID.";
}

mysqli_close($conn);
?>