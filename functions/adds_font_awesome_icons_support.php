<?php
function adds_fontawesome_icons_support()
{
    wp_enqueue_style(
        $handle = 'font-awesome',
        $src = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
    );
}
