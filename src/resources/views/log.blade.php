<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Files</title>
    <style>
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

        .sidebar-item.active a {
            color: white;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0; /* Optional: Remove default padding */
            margin: 0; /* Optional: Remove default margin */
        }

        /* Main content */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .subdirectory {
            padding-left: 20px;
        }
        .subdirectory.collapsible {
            cursor: pointer;
        }
        .subdirectory.collapsible::before {
            content: "-";
            float: left;
            color: white;
        }
        .subdirectory.collapsible.collapsed::before {
            content: "+";
            color: white;
        }

        #fileSuggestions {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            width: 100%;
        }

        #fileSuggestions div {
            padding: 5px;
            cursor: pointer;
        }

        #fileSuggestions div:hover {
            background-color: #f0f0f0;
        }

    </style>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>



    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>

    <!-- and it's easy to individually load additional languages -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/languages/go.min.js"></script>
    <script>
       $(document).ready(function () {
            $('.subdirectory.collapsible .subdirectory-link').click(function () {
                $(this).parent().find('.collapsible-content').slideToggle();
                $(this).parent().toggleClass('collapsed');
            });

            $(".sidebar-item").click(function () {
                $(".sidebar-item").removeClass("active");
                $(this).addClass("active");

                var fileToLoad = $(this).data("file");
                var fileTitle = fileToLoad.split("=").pop();
                $("#fileTitle").text("Log Title: " + decodeURIComponent(fileTitle));
                $(".json").empty();

                fileLoad(fileToLoad)
            });

            $(".sidebar-item[data-file='logs/get/?file=laravel.log']").find("a").click();
            

            $('#searchInput').select2({
                minimumInputLength: 3,
                placeholder: 'Search for a log',
                ajax: {
                    url: "{{ route('logs.search') }}",
                    dataType: 'json',
                    
                    minimumInputLength: 3,

                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                }
            }).on('select2:select', function (e) {
                var selectedOption = e.params.data; // This contains the selected option's data
                var selectedText = selectedOption.text; // Get the selected option's text
            
                var fullFIleUrl = 'logs/get/?file=' +   encodeURIComponent( selectedText ) 
                fileLoad(fullFIleUrl)

                var $option = $("<option selected></option>").val('1').text(selectedText);
                $('#searchInput').append($option).trigger('change');
            });


            $("#searchInput").select2("val", 'FAD');

        });

        function fileLoad(fileToLoad){
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

           
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h2 style="color: white">Log Viewer
         <a href="https://www.linkedin.com/in/shaonmajumder/" target="_blank"> by <span>Shaon Majumder</span></a>
        </h2>

        
        <select id="searchInput" style="width: 100%"></select>
        

    
        
        <ul>
            @foreach (File::files($log_directory) as $file)
                <li class="sidebar-item" data-file="{{ 'logs/get/?file='.  urlencode( $file->getRelativePathname()) }}">
                    <a href="#">{{ $file->getRelativePathname() }}</a>
                </li>
            @endforeach
            
            @foreach (File::directories($log_directory) as $subdirectory)
                <li class="subdirectory collapsible">
                    <a href="#" class="subdirectory-link">{{ basename($subdirectory) }}</a>
                    <ul class="collapsible-content">
                        <?php $files = File::files($subdirectory); ?>
                        @if ( count($files) > 0 )
                            @include('log-viewer::partials.subdirectories', ['log_directory' => $subdirectory ])    
                        @endif
                    </ul>
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
