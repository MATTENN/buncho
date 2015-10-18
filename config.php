<?php
const WEBSITE_URL = 'http://buncho.azurewebsites.net/';
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
