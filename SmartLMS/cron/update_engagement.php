<?php
require_once __DIR__ . '/../data/db.php';

// Fetch all learners
$learners = [];
$sql_learners = "SELECT id FROM users WHERE role = 'learner'";
$result_learners = $conn->query($sql_learners);
while ($row = $result_learners->fetch_assoc()) {
    $learners[$row['id']] = [
        'login_frequency' => 0,
        'avg_time_spent' => 0,
        'avg_quiz_score' => 0,
        'course_completion_rate' => 0,
        'overall_score' => 0
    ];
}

if (empty($learners)) {
    die("No learners found.\n");
}

// 1. Login Frequency: number of logins in last 30 days / 4.33
$sql_logins = "SELECT user_id, COUNT(*) as login_count 
               FROM login_log 
               WHERE login_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
               GROUP BY user_id";
$result_logins = $conn->query($sql_logins);
while ($row = $result_logins->fetch_assoc()) {
    if (isset($learners[$row['user_id']])) {
        $learners[$row['user_id']]['login_frequency'] = $row['login_count'] / 4.33;
    }
}

// 2. Average time spent: total time spent on resources / number of sessions (logins)
// Let's get total time spent per student
$sql_time = "SELECT student_id, SUM(time_spent) as total_time FROM resource_views GROUP BY student_id";
$result_time = $conn->query($sql_time);
$student_time = [];
while ($row = $result_time->fetch_assoc()) {
    $student_time[$row['student_id']] = $row['total_time'];
}

// Get total login count for each student (for sessions)
$sql_all_logins = "SELECT user_id, COUNT(*) as total_logins FROM login_log GROUP BY user_id";
$result_all_logins = $conn->query($sql_all_logins);
while ($row = $result_all_logins->fetch_assoc()) {
    if (isset($learners[$row['user_id']])) {
        $time = isset($student_time[$row['user_id']]) ? $student_time[$row['user_id']] : 0;
        $learners[$row['user_id']]['avg_time_spent'] = $row['total_logins'] > 0 ? $time / $row['total_logins'] : 0;
    }
}

// 3. Average quiz score: average percentage across all quiz attempts
$sql_quiz = "SELECT qa.student_id, 
                    AVG(CASE WHEN (SELECT COUNT(*) FROM questions q WHERE q.quiz_id = qa.quiz_id) > 0 
                             THEN (CAST(qa.score AS DECIMAL(10,2)) / (SELECT COUNT(*) FROM questions q WHERE q.quiz_id = qa.quiz_id) * 100) 
                             ELSE 0 END) as avg_percentage
             FROM quiz_attempts qa
             GROUP BY qa.student_id";
$result_quiz = $conn->query($sql_quiz);
while ($row = $result_quiz->fetch_assoc()) {
    if (isset($learners[$row['student_id']])) {
        $learners[$row['student_id']]['avg_quiz_score'] = $row['avg_percentage'];
    }
}

// 4. Course completion rate: (number of courses completed) / (number of courses enrolled)
$sql_completion = "SELECT student_id, 
                          SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed_count,
                          COUNT(*) as enrolled_count
                   FROM enrollments
                   GROUP BY student_id";
$result_completion = $conn->query($sql_completion);
while ($row = $result_completion->fetch_assoc()) {
    if (isset($learners[$row['student_id']])) {
        $learners[$row['student_id']]['course_completion_rate'] = $row['enrolled_count'] > 0 ? $row['completed_count'] / $row['enrolled_count'] : 0;
    }
}

// Min-Max Scaling (Normalization)
$metrics = ['login_frequency', 'avg_time_spent', 'avg_quiz_score', 'course_completion_rate'];
$min_max = [];
foreach ($metrics as $metric) {
    $values = array_column($learners, $metric);
    $min_max[$metric] = [
        'min' => !empty($values) ? min($values) : 0,
        'max' => !empty($values) ? max($values) : 0
    ];
}

foreach ($learners as $student_id => &$data) {
    $norm = [];
    foreach ($metrics as $metric) {
        $min = $min_max[$metric]['min'];
        $max = $min_max[$metric]['max'];
        if ($max == $min) {
            $norm[$metric] = ($max > 0) ? 1 : 0; // If all have same non-zero value, norm is 1
        } else {
            $norm[$metric] = ($data[$metric] - $min) / ($max - $min);
        }
    }

    // overall_score = 0.2 * login_frequency_norm + 0.3 * avg_time_spent_norm + 0.3 * avg_quiz_score_norm + 0.2 * course_completion_rate
    // Wait, course_completion_rate is already 0-1, but the formula says course_completion_rate (not norm). 
    // Usually it's better to normalize it too if it's compared with others, but let's follow the formula strictly.
    // Prompt: overall_score = 0.2 * login_frequency_norm + 0.3 * avg_time_spent_norm + 0.3 * avg_quiz_score_norm + 0.2 * course_completion_rate
    
    $data['overall_score'] = 0.2 * $norm['login_frequency'] + 
                             0.3 * $norm['avg_time_spent'] + 
                             0.3 * $norm['avg_quiz_score'] + 
                             0.2 * $data['course_completion_rate'];
    
    // Convert to percentage (0-100) or keep 0-1? 
    // The user didn't specify, but score DECIMAL(5,2) can hold up to 999.99.
    // Let's store as a value between 0 and 100 for better readability.
    $score_to_store = $data['overall_score'] * 100;

    // Update or Insert in engagement_scores
    // Check if entry exists
    $stmt_check = $conn->prepare("SELECT id FROM engagement_scores WHERE student_id = ?");
    $stmt_check->bind_param("i", $student_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        $stmt_update = $conn->prepare("UPDATE engagement_scores SET score = ?, login_frequency = ?, avg_time_spent = ?, avg_quiz_score = ?, course_completion_rate = ?, calculated_at = NOW() WHERE student_id = ?");
        $stmt_update->bind_param("dddddi", $score_to_store, $data['login_frequency'], $data['avg_time_spent'], $data['avg_quiz_score'], $data['course_completion_rate'], $student_id);
        $stmt_update->execute();
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO engagement_scores (student_id, score, login_frequency, avg_time_spent, avg_quiz_score, course_completion_rate, calculated_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt_insert->bind_param("iddddd", $student_id, $score_to_store, $data['login_frequency'], $data['avg_time_spent'], $data['avg_quiz_score'], $data['course_completion_rate']);
        $stmt_insert->execute();
    }
}

echo "Engagement scores updated successfully for " . count($learners) . " learners.\n";
?>
