<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'staff')) {
    header("Location: ../login.php");
    exit();
}

require_once "../Appointment.php";
require_once "../Service.php";
require_once "../User.php";

$appointmentObj = new Appointment();
$serviceObj = new Service();
$userObj = new User();

// Get statistics
$all_appointments = $appointmentObj->getAllAppointments();
$pending_appointments = $appointmentObj->getAllAppointments('pending');
$approved_appointments = $appointmentObj->getAllAppointments('approved');
$services = $serviceObj->viewServices();
$users = $userObj->getAllUsers();

$total_appointments = count($all_appointments);
$total_pending = count($pending_appointments);
$total_approved = count($approved_appointments);
$total_services = count($services);
$total_users = count($users);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #667eea;
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .stat-card .number {
            color: #333;
            font-size: 36px;
            font-weight: bold;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .quick-link {
            background: white;
            padding: 30px 20px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .quick-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .quick-link h3 {
            margin-top: 10px;
            color: #667eea;
        }

        .icon {
            font-size: 40px;
        }

        .section-title {
            margin: 40px 0 20px;
            color: #333;
            font-size: 24px;
        }

        .recent-appointments {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        table th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-container">
            <h1>👨‍💼 Admin Dashboard</h1>
            <div class="nav-links">
                <span>Welcome, <?= $_SESSION['full_name']; ?>!</span>
                <a href="../index.php">View Site</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Appointments</h3>
                <div class="number"><?= $total_appointments; ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending Approvals</h3>
                <div class="number"><?= $total_pending; ?></div>
            </div>
            <div class="stat-card">
                <h3>Approved Appointments</h3>
                <div class="number"><?= $total_approved; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Services</h3>
                <div class="number"><?= $total_services; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?= $total_users; ?></div>
            </div>
        </div>

        <h2 class="section-title">Quick Actions</h2>
        <div class="quick-links">
            <a href="manageAppointments.php" class="quick-link">
                <div class="icon">📅</div>
                <h3>Manage Appointments</h3>
            </a>
            <a href="manageServices.php" class="quick-link">
                <div class="icon">💇</div>
                <h3>Manage Services</h3>
            </a>
            <a href="addService.php" class="quick-link">
                <div class="icon">➕</div>
                <h3>Add New Service</h3>
            </a>
            <a href="manageAppointments.php?status=pending" class="quick-link">
                <div class="icon">⏰</div>
                <h3>Pending Approvals</h3>
            </a>
        </div>

        <h2 class="section-title">Recent Appointments</h2>
        <div class="recent-appointments">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
                <?php 
                $recent = array_slice($all_appointments, 0, 10);
                foreach ($recent as $apt): 
                ?>
                    <tr>
                        <td><?= $apt['id']; ?></td>
                        <td><?= htmlspecialchars($apt['full_name']); ?></td>
                        <td><?= htmlspecialchars($apt['service_name']); ?></td>
                        <td><?= date('M d, Y', strtotime($apt['appointment_date'])); ?></td>
                        <td><?= date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                        <td>
                            <span class="status status-<?= $apt['status']; ?>">
                                <?= ucfirst($apt['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>