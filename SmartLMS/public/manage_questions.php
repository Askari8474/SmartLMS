<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<?php
$quiz_id = $_GET['quiz_id'];
?>

<div class="main-content">
    <div class="container-fluid">
        <h2 class="mb-4">Manage Questions for Quiz ID: <?php echo $quiz_id; ?></h2>

        <div class="mb-4">
            <a href="add_question.php?quiz_id=<?php echo $quiz_id; ?>" class="btn btn-success">Add New Question</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once '../data/db.php';
                $sql = "SELECT * FROM questions WHERE quiz_id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $quiz_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['question_text'] . '</td>';
                        echo '<td>';
                        echo '<a href="edit_question.php?id=' . $row['id'] . '&quiz_id=' . $quiz_id . '" class="btn btn-primary btn-sm">Edit</a> ';
                        echo '<a href="delete_question.php?id=' . $row['id'] . '&quiz_id=' . $quiz_id . '" class="btn btn-danger btn-sm">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    $stmt->close();
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>