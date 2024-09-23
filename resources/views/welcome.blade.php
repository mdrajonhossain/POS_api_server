<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pos api</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .progress {
            width: 100%;
            background-color: #e0e0e0;
        }
        .progress-bar {
            width: 0;
            height: 30px;
            background-color: #76c7c0;
            text-align: center;
            line-height: 30px;
            color: white;
        }
    </style>
</head>
<body>
    <div>
        <h1>POS Api Server Connecting...</h1>
        <div class="progress">
            <div class="progress-bar" id="progress-bar">0%</div>
        </div>
        <p id="status-message"></p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateProgressBar(progress, message) {
                $('#progress-bar').css('width', progress + '%');
                $('#progress-bar').text(progress + '%');
                $('#status-message').text(message);
            }

            updateProgressBar(25, 'Initializing...');

            setTimeout(function() {
                $.ajax({
                    url: '/connection',
                    method: 'GET',
                    success: function(response) {
                        if (response.status) {
                            updateProgressBar(100, response.message);
                            toastr.success(response.message);
                        } else {
                            updateProgressBar(100, response.message);
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        updateProgressBar(100, 'Database connection.');                        
                    }
                });
            }, 1000);
        });
    </script>
</body>
</html>
