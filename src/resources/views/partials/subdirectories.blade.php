<ul>
    @foreach (File::files($log_directory) as $file)
        <li class="sidebar-item" data-file="{{ 'logs/get/?file='.  urlencode( basename($log_directory) . '/' . $file->getRelativePathname()) }}">
            <a href="#">{{ $file->getRelativePathname() }}</a>
        </li>
    @endforeach
    
    @foreach (File::directories($log_directory) as $subdirectory)
        <li class="subdirectory collapsible">
            <a href="#" class="subdirectory-link">{{ basename($subdirectory) }}</a>
            <ul class="collapsible-content">
                <?php
                    $files = File::files($subdirectory);
                ?>

                @if ( count($files) > 0 )
                    @include('log-viewer::partials.subdirectories', ['log_directory' => $subdirectory ])    
                @endif

            </ul>
        </li>
    @endforeach
    
</ul>