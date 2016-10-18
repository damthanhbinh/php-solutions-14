<?php
require_once '../includes/connection.php';
// create database connection
$conn = dbConnect('write', 'pdo');
if (isset($_POST['insert'])) {
    // initialize flag
    $OK = false;
    // create SQL
    $sql = 'INSERT INTO blog (title, article)
                VALUES(:title, :article)';
    // prepare the statement
    $stmt = $conn->prepare($sql);
    // bind the parameters and execute the statement
    $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
    $stmt->bindParam(':article', $_POST['article'], PDO::PARAM_STR);
    // execute and get number of affected rows
    $stmt->execute();
    $OK = $stmt->rowCount();
    // redirect if successful or display error
    if ($OK) {
        header('Location: http://localhost/phpsols/admin/blog_list_pdo.php');
        exit;
    } else {
        $errorInfo = $stmt->errorInfo();
        if (isset($errorInfo[2])) {
            $error = $errorInfo[2];
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Insert Blog Entry</title>
    <link href="../styles/admin.css" rel="stylesheet" type="text/css">
</head>

<body>
<h1>Insert New Blog Entry</h1>
<?php if (isset($error)) {
    echo "<p>Error: $error</p>";
} ?>
<form method="post" action="" enctype="multipart/form-data">
    <p>
        <label for="title">Title:</label>
        <input name="title" type="text" id="title" value="<?php if (isset($error)) {
            echo htmlentities($_POST['title']);
        } ?>">
    </p>
    <p>
        <label for="article">Article:</label>
        <textarea name="article" id="article"><?php if (isset($error)) {
                echo htmlentities($_POST['article']);
            } ?></textarea>
    </p>
    <p>
        <label for="category">Categories:</label>
        <select name="category[]" size="5" multiple id="category">
            <?php
            // get categories
            $getCats = 'SELECT cat_id, category FROM categories ORDER BY category';
            // use foreach loop to submit query and display results
            foreach ($conn->query($getCats) as $row) {
                ?>
                <option value="<?= $row['cat_id']; ?>" <?php
                if (isset($_POST['category']) && in_array($row['cat_id'], $_POST['category'])) {
                    echo 'selected';
                } ?>><?php echo $row['category']; ?></option>
            <?php } ?>
        </select>
    </p>
    <p>
        <input type="submit" name="insert" value="Insert New Entry" id="insert">
    </p>
</form>
</body>
</html>