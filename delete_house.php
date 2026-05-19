<?php
// 1. Database connection saagi
include 'dbconnect.php'; 

// 2. ID URL irraa dhufe (delete_house.php?id=10) fudhu
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $house_id = $_GET['id'];

    // 3. Query mana sana haquuf fayyadu barreessi
    // 'houses' maqaa table keetii yoo ta'e:
    $sql = "DELETE FROM houses WHERE id = $house_id";

    if (mysqli_query($conn, $sql)) {
        // 4. Yoo milkiin haqame, gara galmee manneenitti (view_houses.php) deebisi
        echo "<script>alert('Mana milkiin haqziteetta!'); window.location.href='manage_houses.php';</script>";
    } else {
        // 5. Yoo dogoggorri uumame
        echo "Dogoggora: " . mysqli_error($conn);
    }
} else {
    // Yoo ID'n hin ergamne ta'e
    echo "ID'n mana haqamuuf barbaadamu hin argamne.";
}

mysqli_close($conn);
?>