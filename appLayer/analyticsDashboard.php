<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../dbLayer/DB.php");

// Get total users
$total_users = $_DB->select("SELECT COUNT(*) AS count FROM users")[0]['count'] ?? 0;

// Get active users (last 30 days)
$active_users_result = $_DB->select("SELECT COUNT(*) AS count FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$active_users = $active_users_result[0]['count'] ?? 0;
$inactive_users = $total_users - $active_users;

// Time-based signups
function get_signup_data($interval) {
    global $_DB;
    $group_by = $interval === 'DAY' ? 'DATE(created_at)' :
                ($interval === 'WEEK' ? 'YEARWEEK(created_at)' :
                'DATE_FORMAT(created_at, "%Y-%m")');

    $query = "SELECT $group_by AS label, COUNT(*) AS count
              FROM users
              GROUP BY label
              ORDER BY label DESC
              LIMIT 7";

    $result = $_DB->select($query);
    return $result ?: [];
}

$daily = get_signup_data('DAY');
$weekly = get_signup_data('WEEK');
$monthly = get_signup_data('MONTH');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Analytics Dashboard</title>
    <link rel="stylesheet" href="../styling/analytics-dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h1>User Statistics</h1>

    <div class="card">
        <h2>Total Users: <?php echo $total_users; ?></h2>
    </div>

    <div class="card">
        <h3>Daily Signups (Last 7 Days)</h3>
        <canvas id="dailyChart"></canvas>
    </div>

    <div class="card">
        <h3>Weekly Signups (Last 7 Weeks)</h3>
        <canvas id="weeklyChart"></canvas>
    </div>

    <div class="card">
        <h3>Monthly Signups (Last 7 Months)</h3>
        <canvas id="monthlyChart"></canvas>
    </div>

    <div class="card">
        <h3>Active vs Inactive Users</h3>
        <canvas id="activeInactiveChart"></canvas>
    </div>
</div>

<script>
    const makeBarChart = (ctxId, labels, data) => {
        new Chart(document.getElementById(ctxId), {
            type: 'bar',
            data: {
                labels: labels.reverse(),
                datasets: [{
                    label: 'Users',
                    data: data.reverse(),
                    backgroundColor: '#4e91f9'
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    };

    makeBarChart("dailyChart",
        <?php echo json_encode(array_column($daily, 'label')); ?>,
        <?php echo json_encode(array_column($daily, 'count')); ?>);

    makeBarChart("weeklyChart",
        <?php echo json_encode(array_column($weekly, 'label')); ?>,
        <?php echo json_encode(array_column($weekly, 'count')); ?>);

    makeBarChart("monthlyChart",
        <?php echo json_encode(array_column($monthly, 'label')); ?>,
        <?php echo json_encode(array_column($monthly, 'count')); ?>);

    new Chart(document.getElementById("activeInactiveChart"), {
        type: 'pie',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [<?php echo $active_users; ?>, <?php echo $inactive_users; ?>],
                backgroundColor: ['#4CAF50', '#F44336']
            }]
        }
    });
</script>
</body>
</html>
