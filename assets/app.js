/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

var $ = require('jquery');
require('bootstrap');

/**
 * Evénement sur le range
 */
$('#property_surface').after('<div id="result">'+$('#property_surface').val()+' m²</div>');

$('#property_surface').on('input', function () {
    console.log( $(this).val() );
    $('#result').remove(); // Supprime la div pour éviter les doublons
    $(this).after('<div id="result">'+$(this).val()+' m²</div>');
});
