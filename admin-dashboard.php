<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f7f8fa;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            color: white;
        }
        .navbar-brand:hover {
            color: #ccc;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding: 20px;
            position: fixed;
            width: 250px;
            transition: margin 0.2s; /* Smooth transition */
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .dashboard-header {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .search-input {
            margin-bottom: 20px;
        }
        .table {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }
        .table th, .table td {
            vertical-align: middle;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                height: auto; /* Allow sidebar to be scrollable */
                width: 100%; /* Full width on mobile */
                padding: 10px;
                transition: none; /* Disable transition */
            }
            .content {
                margin-left: 0; /* Reset margin on mobile */
                padding: 10px;
            }
            .dashboard-header {
                font-size: 24px; /* Adjust header size for mobile */
            }
            .table {
                font-size: 14px; /* Reduce font size for mobile */
            }
            .sidebar a {
                padding: 8px 0; /* Reduce padding on mobile */
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="http://localhost/Subscription%20System/index.php">Visit Website</a>
        <a href="#">Total Subscribers</a>
    </div>

    <!-- Content Section -->
    <div class="content">
        <div class="dashboard-header">Total Emails</div>

        <!-- Search Input -->
        <div class="search-input">
            <input type="text" id="search" class="form-control" placeholder="Search by name or email">
        </div>

        <!-- Responsive Table of Names and Emails -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Modified</th>
                        <th scope="col">Action</th> <!-- Added Action column -->
                    </tr>
                </thead>
                <tbody id="emailTable">
                    <!-- Rows will be dynamically added here via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Fetch subscribers from PHP script and populate table
        function loadSubscribers() {
            $.ajax({
                url: 'get_subscribers.php', // The PHP script URL
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let table = $('#emailTable');
                    table.empty(); // Clear table before adding new data

                    if (data.length > 0) {
                        data.forEach((subscriber, index) => {
                            table.append(`
                                <tr>
                                    <th scope="row">${index + 1}</th>
                                    <td>${subscriber.name}</td>
                                    <td>${subscriber.email}</td>
                                    <td>${subscriber.modified}</td>
                                    <td>
                                        <button onclick="deleteSubscriber('${subscriber.name}')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> <!-- Font Awesome trash icon -->
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        table.append('<tr><td colspan="5">No subscribers found.</td></tr>'); // Adjusted colspan
                    }
                },
                error: function(error) {
                    console.error('Error fetching subscribers:', error);
                }
            });
        }

        // Delete subscriber by name
        function deleteSubscriber(name) {
            if (confirm('Are you sure you want to delete this subscriber: ' + name + '?')) {
                $.ajax({
                    url: 'delete_subscriber.php',
                    type: 'POST',
                    data: { name: name }, // Send the name instead of the ID
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.success) {
                                loadSubscribers(); // Reload subscribers after deletion
                            } else {
                                alert('Error deleting subscriber: ' + data.message);
                            }
                        } catch (e) {
                            console.error('Failed to parse response:', e);
                            console.error('Response:', response);
                            alert('An unexpected error occurred. Please check the console for more details.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error deleting subscriber:', xhr.responseText);
                        alert('Error deleting subscriber: ' + xhr.responseText);
                    }
                });
            }
        }

        // Filter subscribers in table based on search input
        document.getElementById('search').addEventListener('input', function() {
            let searchValue = this.value.toLowerCase();
            let tableRows = document.querySelectorAll('#emailTable tr');
            
            tableRows.forEach(row => {
                let name = row.cells[1].innerText.toLowerCase();
                let email = row.cells[2].innerText.toLowerCase();
                if (name.includes(searchValue) || email.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Load subscribers on page load
        $(document).ready(function() {
            loadSubscribers();
        });
    </script>

</body>
</html>
