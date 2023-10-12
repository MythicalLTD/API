<?php
use MythicalSystems\Database\Connect;
use MythicalSystems\Session\SessionManager;
use MythicalSystems\AppConfig;

$appConfig = new AppConfig();
$conn = new Connect();
$conn = $conn->connectToDatabase();
$session = new SessionManager;
$session->authenticateUser();
$sql = "SELECT * FROM problems";
$result = $conn->query($sql);
$tableData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tableData[] = [
            $row["id"],
            $row["project"],
            $row["type"],
            $row["title"],
            $row["date"]
        ];
    }
}

$tableDataJSON = json_encode($tableData);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $appConfig->get('app')['name'] ?> - Problems
    </title>
    <link rel="icon" type="image/png" href="<?= $appConfig->get('app')['logo'] ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #ffffff;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 1rem;
        }

        .container {
            background-color: #2d2d2d;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .chart-container {
            background-color: #363636;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
    <link rel="stylesheet" href="/assets/css/tablestyle.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <?= $appConfig->get('app')['name'] ?>
            </a>
            <br><br>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/ui/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ui/telemetry">Telemetry</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ui/problems">Problems</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ui/auth/logout">Exit</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Welcome to
            <?= $appConfig->get('app')['name'] ?>!
        </h3><br>
        <table id="problemsTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="container text-center">
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h2>Most Popular Projects</h2>
                    <canvas id="popularprojects" width="200" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h2>Most Popular Problems Type</h2>
                    <canvas id="popularproblemstype" width="200" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h2>Most Popular Problems Title</h2>
                    <canvas id="popularproblemstitle" width="200" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var tableData = <?= $tableDataJSON ?>;
            var chartData = {
                projectCounts: {},
                problemtype: {},
                problemtitle: {},
            };

            tableData.forEach(function (row) {
                chartData.projectCounts[row[1]] = (chartData.projectCounts[row[1]] || 0) + 1;
                chartData.problemtype[row[2]] = (chartData.problemtype[row[2]] || 0) + 1;
                chartData.problemtitle[row[3]] = (chartData.problemtitle[row[3]] || 0) + 1;
            });

            createPieChart("popularprojects", Object.keys(chartData.projectCounts), Object.values(chartData.projectCounts));
            createPieChart("popularproblemstype", Object.keys(chartData.problemtype), Object.values(chartData.problemtype));
            createPieChart("popularproblemstitle", Object.keys(chartData.problemtitle), Object.values(chartData.problemtitle));

            function createPieChart(chartId, labels, data) {
                var ctx = document.getElementById(chartId).getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                            ],
                            borderWidth: 1
                        }]
                    },
                });
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            var tableData = <?= $tableDataJSON ?>;

            var table = $('#problemsTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "columnDefs": [{
                    "targets": -1,
                    "data": null,
                    "defaultContent": '<button class="btn btn-primary">Download report</button>'
                }]
            });

            table.rows.add(tableData).draw();

            $('#problemsTable tbody').on('click', 'button', function () {
                var data = table.row($(this).parents('tr')).data();
                var problemId = data[0]; 
                window.location.href = '/ui/problems/show?id=' + problemId;
            });
        });

    </script>

</body>

</html>