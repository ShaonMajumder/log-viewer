<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Files</title>
    <style>
        /* Sidebar styles */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #111;
            overflow-x: hidden;
            padding-top: 20px;
        }

        /* Sidebar links */
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #818181;
            display: block;
        }

        /* Change color on hover */
        .sidebar a:hover {
            color: #f1f1f1;
        }

        /* Main content */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .branding {
            position: fixed;
            bottom: 20px;
            left: 20px;
            color: #818181;
            font-size: 14px;
        }

        .branding a {
            color: #818181;
            text-decoration: none;
        }

        .branding a:hover {
            color: #f1f1f1;
        }

        .sidebar-item.active a {
            color: white;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>

    <!-- and it's easy to individually load additional languages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/go.min.js"></script>
    <script>
       



        $(document).ready(function () {
            
            $(".sidebar-item").click(function () {
                $(".sidebar-item").removeClass("active");
                $(this).addClass("active");

                var fileToLoad = $(this).data("file");
                var fileTitle = fileToLoad.split("=").pop();
                $("#fileTitle").text("Log Title: " + decodeURIComponent(fileTitle));
                $(".json").empty();

                $.ajax({
                    type: "GET",
                    url: fileToLoad,
                    dataType: "json",
                    success: function (response) {
                        var contentWithLineBreaks = response.content;
                        $(".json").html(contentWithLineBreaks);
                        hljs.highlightAll();
                        $('.json').each(function(i) {
                            // if (i % 2 === 0){
                            //     $(this).before("\n&nbsp;&nbsp;");
                            // }
                            if (i === $('.json span').length -1){
                                $(this).after("\n")
                            }
                        });
                    },
                    error: function (error) {
                        console.error(error.responseText);
                    }
                });


            });

            $(".sidebar-item[data-file='logs/get/?file=laravel.log']").find("a").click();
        });
    </script>
</head>
<body>
    <div class="sidebar">
        <h2 style="color: white">Log Viewer
         <a href="https://www.linkedin.com/in/shaonmajumder/" target="_blank"> by <span>Shaon Majumder</span></a>
        </h2>
        
        <ul>
            @foreach ($logFiles as $logFile)
                <li class="sidebar-item" data-file="{{ 'logs/get/?file='. urlencode($logFile) }}">
                    <a href="#">{{ $logFile }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="content">
        <h3 id="fileTitle"></h3> 
        <pre><code class="json"></code></pre>
    </div>
</body>
</html>
